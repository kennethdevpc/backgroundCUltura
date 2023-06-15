<script setup>
import dayjs from 'dayjs'
import 'dayjs/locale/es'
import { scroll_top } from "@/utils/scroll";
import { useVuelidate } from "@vuelidate/core";
import ServiceGlobal from "@/services/generals.service";
import Service from "@/services/methodologicalSheetsTwo.service";

// Importing Store
import { storeToRefs } from "pinia";
import { useMethodologicalSheetsTwo } from "@/stores/methodologicalSheetsTwo";

// Importing Options Store
import { useSelectStore } from "@/stores/selects";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import Management from "@/components/base/Management.vue";
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from "@/components/base/Aggregates.vue"


import Swal from "sweetalert2";
import permissions from "@/permissions";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import { useOnboardingStore } from "@/stores/onboarding";
import FormHeader from '@/components/base/FormHeader.vue';

// Extracting Store Data
const store = useMethodologicalSheetsTwo()
const { form, form_options, get_form_rules_computed, filesOne } = storeToRefs(store)
const { computed: { is_role } } = mixins;
// Extracting Store Data
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);
const router = useRouter();

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(get_form_rules_computed, form, { $autoDirty: true, $lazy: true });

// Extracting Services
const service = Service();
const { getConsecutive, getDataSheet, getGroupBeneficiaries } = ServiceGlobal();

const route = useRoute();
const monthCurrent = ref('');
const files = reactive({
  I: [],
  II: [],
  aforo_pdf: []
});

const beneficiaries_headers = [
  {
    text: 'ID',
    value: 'id',
  },
  {
    text: 'NOMBRE',
    value: 'full_name',
  },
  {
    text: 'CÉDULA',
    value: 'nuip',
  }
]

const editing = computed(() => {
  return route.params.id ? true : false;
});

const fetch_consecutive = async () => {
  return await getConsecutive("methodological_sheets_two", "FP")
    .then((response) => {
      form.value.consecutive = response.data;
    });
};

const fetchOne = async () => {
  return await service.getOne(route.params.id);
};

const aggregates_conditions = computed(() => {
  const type = form.value.activity_type;
  if (!type) {
    return 0;
  }

  if (type === 'E' || type === 'C') {
    return 1
  }

  return 2;

})

onMounted(async () => {
  if (editing.value) {
    store.$reset();
    await fetchOne()
      .then(() => {
        const {
          created_at,
          id,
          monitor,
          nac,
          orientation,
          status,
          reject_message,
          user_id,
          ...rest
        } = service.data.one;

        store.$patch((state) => {
          Object.assign(state.form, { ...rest, status });
          state.update_instance.consecutive = rest.consecutive;
          state.update_instance.status = status;
          state.update_instance.reject_message = reject_message;
          state.filesOne.development_activity_image = rest.development_activity_image;
          state.filesOne.aforo_pdf = rest.aforo_pdf
          state.filesOne.evidence_participation_image = rest.evidence_participation_image
        });
      })
      .catch(() => {
        mixins.not_found_by_id();
      });
  } else {
    store.$reset();
    v$.value.$reset();
    await fetch_consecutive();
  }
});

const onSubmit = async () => {
  const valid = await v$.value.$validate();
  if (valid) {
    if (editing.value) {
      const response = await service.update(
        service.data.one.id,
        form.value
      );
      if (response.data.items) {
        router.push({ name: "methodologicalsheetstwo.index" });
      }
    } else {
      const buildData = {
        ...form.value,
        beneficiaries: JSON.stringify(form.value.beneficiaries.map((item) => (item.id)))
      }
      await fetch_consecutive().finally(() => {

        service
          .create({ ...buildData, user_id: useOnboardingStore().get_user.id })
          .then(async (response) => {
            if (response.data.items) {
              files.I = [];
              files.II = [];
              files.aforo_pdf = [];
              monthCurrent.value = "";
              store.clear()
              store.$reset();
              v$.value.$reset();
              await fetch_consecutive();
              scroll_top();
            }
          });


      });

    }
  } else {
    Swal.fire(
      "Validación",
      "Por favor valide los campos solicitados.",
      "error"
    );
  }
};

const addFile = (err, val, number) => {
  if (err) {
    return
  }
  else {
    const { file } = val
    if (number == 1) {
      form.value.development_activity_image = file
    }
    if (number == 2) {
      form.value.evidence_participation_image = file
    }
    if (number == 3) {
      form.value.aforo_pdf = file
    }
  }
}

const removeFile = (number) => {
  if (number == 1) {
    form.value.development_activity_image = null
    files.I = []
  }
  if (number == 2) {
    form.value.evidence_participation_image = null
    files.II = []
  }
  if (number == 3) {
    form.value.aforo_pdf = null
    files.aforo_pdf = []
  }

}

const beneficiaries_by_group = ref([])

watch(computed(() => form.value.date_ini), async () => {
  if (form.value.date_ini) {
    const { data } = await getDataSheet('methodological_sheets_two', form.value.date_ini);
    const month = dayjs(form.value.date_ini).locale('es').format("MMMM");
    monthCurrent.value = `Ficha ${data} - ${month}`;
  }
})


const get_beneficiaries = async (id) => {
  if (!editing.value) {
    await getGroupBeneficiaries(id).then((response) => {
      if (response != undefined) {
        let beneficiaries = response.data.items[0].beneficiaries
        beneficiaries_by_group.value = beneficiaries

        store.$patch((state) => {
          state.form.beneficiaries = beneficiaries;
        })
      }
    })
  }
  
}

const get_group = computed(() => {
  return form.value.group_id
})

watch(get_group, async (new_val, old_val) => {
  if (new_val != '' && new_val != null) {
    if (new_val != old_val) {
      await get_beneficiaries(new_val)
    }
  }
})

</script>

<template>
  <FormHeader
    :state="{ consecutive: form.consecutive, status: store.update_instance.status, reject_message: store.update_instance.reject_message }">
    <div class="flex">
      <span>
        {{
          editing
          ? `Edición de Ficha Metodológica de Evaluación`
          : `Ficha Metodológica de Evaluación`
        }}
      </span>
      <p v-if="is_role('instructor') && !id" class="text-lg font-italic mr-auto ml-4">{{
        monthCurrent
      }}
      </p>
    </div>
  </FormHeader>

  <Management v-if="editing ? permissions.pedagogicals.management() : false" @send="
    (payload) =>
      mixins.methods.send_management(
        'methodological_sheets_two',
        route.params.id,
        payload
      )
  " class="mt-5" />
  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.pedagogicals.no_edit()">
      <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60">
        <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5">
          <BaseSelect label="TIPO ACTIVIDAD *" tooltip="" placeholder="Tipo de la actividad"
            :options="options.activity_type" name="activity_type" v-model="form.activity_type" :validator="v$" />

          <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
            <BaseInput type="date" label="FECHA INICIAL *" tooltip="Ingrese la fecha inicial" placeholder="Fecha"
              name="date_ini" v-model="form.date_ini" :validator="v$" />

            <BaseInput type="date" label="FECHA FINAL *" tooltip="Ingrese la fecha final" placeholder="Fecha"
              name="date_fin" v-model="form.date_fin" :validator="v$" />
          </div>
        </div>

        <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5">
          <BaseTextarea label="ACTORES CLAVES DE LA COMUNIDAD PARTICIPANTE *" tooltip=""
            name="keyactors_participating_community" v-model="form.keyactors_participating_community" :validator="v$" />
          <BaseTextarea label="OBJETIVO DEL PROCESO *" tooltip="" placeholder="Objetivo del proceso"
            name="objective_process" v-model="form.objective_process" :validator="v$" required />
        </div>

        <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5">
          <BaseRadio label="¿SE ALCANZÓ EL OBJETIVO? *" tooltip="" name="reached_target" v-model="form.reached_target"
            :options="form_options.reached_target" :validator="v$" required />
          <!--    <BaseInput type="number" label="CANTIDAD DE PARTICIPANTES *" tooltip="" name="participants_number"
                                          v-model="form.participants_number" :validator="v$" /> -->
        </div>

        <div class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mt-5">
          <BaseTextarea label="SUSTENTE *" tooltip="" placeholder="Sustentación" name="sustein" v-model="form.sustein"
            :validator="v$" required />
        </div>

        <div class="w-full intro-x grid grid-cols-1 md:grid-cols-2 gap-6 justify-evenly mt-5">
          <BaseFilepond label="FOTO DEL DESARROLLO *" tooltip="Arrastra un archivo valido"
            name="development_activity_image" ref="development_activity_image"
            :to_edit="filesOne.development_activity_image" v-model="form.development_activity_image"
            @addfile="(err, val) => addFile(err, val, 1)" @removefile="removeFile(1)" :files="files.I" :validator="v$" />

          <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra un archivo valido"
            name="evidence_participation_image" :to_edit="filesOne.evidence_participation_image"
            ref="evidence_participation_image" v-model="form.evidence_participation_image"
            @addfile="(err, val) => addFile(err, val, 2)" @removefile="removeFile(2)" :files="files.II" :validator="v$" />
        </div>


        <div class="w-full intro-x grid grid-cols-2 md:grid-cols-2 gap-6 justify-evenly mt-5"
          v-if="aggregates_conditions === 1">
          <BaseFilepond label="DOCUMENTO DEL AFORO *" tooltip="Arrastra un archivo valido" name="aforo_pdf"
            ref="aforo_pdf" v-model="form.aforo_pdf" :to_edit="filesOne.aforo_pdf"
            @addfile="(err, val) => addFile(err, val, 3)" @removefile="removeFile(3)" :files="files.aforo_pdf"
            :validator="v$" accept_only_pdf />

          <BaseInput type="number" label="CAPACIDAD (NUMERO DE ASISTENTES) *" tooltip="" name="number_attendees"
            v-model="form.number_attendees" :validator="v$" />
        </div>

        <div class="w-full" v-if="aggregates_conditions === 2">
          <BaseSelect label="GRUPO *" tooltip="Selecciona un grupo" placeholder="Seleccione" name="group_id"
            v-model="form.group_id" :options="options.group_beneficiaries" :validator="v$" />
        </div>
        <section v-if="aggregates_conditions === 2" class="flex flex-col justify-start nt-5">
          <div>
            <h3 class="intro-y form-label font-bold uppercase">
              <span>
                Asistentes Agregados
              </span>
              <span>
                # {{ form.beneficiaries.length }}
              </span>
            </h3>
          </div>
          <div class="overflow-x-auto overflow-y-hidden">
            <Aggregates @pop="(id) => store.pop_aggregate(id)" @push="(aggregate) => store.push_aggregate(aggregate)"
              :options="options.beneficiaries_table || null" :headers="beneficiaries_headers"
              :aggregates="form.beneficiaries" :validator="v$" name="beneficiaries">
            </Aggregates>
          </div>
        </section>

        <div v-if="permissions.sheetsOne.create()" class="flex justify-center mt-6">
          <button :disabled="loading" type="submit" class="btn btn-primary w-24 mr-1 mb-2">
            {{ editing ? "Actualizar" : "Ingresar" }}
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
