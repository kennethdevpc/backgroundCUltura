<script setup>
import permissions from "@/permissions";

// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";

// Importing Store
import { storeToRefs } from "pinia";
import { binnacleStore } from "@/stores/binnacle";

// Importing Options Store
import { useSelectStore } from "@/stores/selects";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import Management from "@/components/base/Management.vue";
import Aggregates from "@/components/base/Aggregates.vue";

import services from "@/services/binnacles.service";
import servicesConsecutive from "@/services/generals.service";

import pecsService from "@/services/pecs.service";
import pedagogicalService from "@/services/pedagogical.service";
import servicesBeneficiaries from "@/services/generals.service";
import Textarea from "@/components/base/Textarea.vue";
import { scroll_top } from "@/utils/scroll";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import alerts from "@/utils/alerts";
import dayjs from "dayjs";
import FormHeader from "@/components/base/FormHeader.vue";

// Extracting Store Data
const binnacle_store = binnacleStore();
const {
  form,
  get_form_rules_computed: form_rules,
  form_options,
} = storeToRefs(binnacle_store);

// Extracting Services
const binnacle_services = services();
const consecutive_services = servicesConsecutive();

const { byActivityDate } = pecsService();
const { byRangeActivityDate: byActivityDatePedagocial } = pedagogicalService();
const disabledButton = ref(false);


const router = useRouter();
const route = useRoute();

const files = reactive({
  I: [],
  II: [],
  III: [],
});

const beneficiariesForo = [
  {
    value: "aforo",
    label: "Aforo",
  },
  {
    value: "beneficiaries",
    label: "Beneficiarios",
  },
];

// Extracting Store Data
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);

const binnacles_options = computed(() => {
  const { is_role, is_admin } = mixins.computed;
  const { binnacles } = options.value;

  if (
    is_admin() ||
    is_role("gestores_culturales") ||
    is_role("apoyo_seguimiento_monitoreo") ||
    is_role("lider_instructor") ||
    is_role("lider_embajador")
  ) {
    return binnacles.filter(
      ({ value }) =>
        value == "JP" ||
        value == "SC" ||
        value == "TP" ||
        value == "EC" ||
        value == "AC"
    );
  } else if (is_role("monitor_cultural")) {
    return binnacles.filter(({ value }) => value == "JP");
  } else if (is_role("instructor")) {
    return binnacles.filter(
      ({ value }) => value == "SC" || value == "TP" || value == "EC"
    );
  } else if (is_role("embajador")) {
    return binnacles.filter(({ value }) => value == "AC");
  }
});

const aggregates_conditions = computed(() => {
  const { is_role } = mixins.computed;
  const binnacle = form.value.binnacle_id;
  const capacity = form.value.beneficiaries_or_capacity;

  if (is_role("super.root")) {
    return true;
  } else if (is_role("instructor")) {
    if (binnacle == "SC") {
      return true;
    } else if (binnacle == "TP") {
      return false;
    } else if (binnacle == "EC" && capacity == "beneficiaries") {
      return true;
    }
  } else if (is_role("monitor_cultural") || is_role("apoyo_seguimiento_monitoreo") || is_role("gestores_culturales") || is_role("direccion") || is_role("subdireccion")) {
    if (binnacle == "JP") {
      return true;
    }
  }
});

const editing = computed(() => {
  return route.params.id ? true : false;
});

const fetch_consecutive = async () => {
  return await consecutive_services
    .getConsecutive("binnacles", "JP")
    .then(() => {
      instance.consecutive = consecutive_services.data.value;
      form.value.consecutive = consecutive_services.data.value;
    });
};

const fetch_binnacle = async () => {
  return await binnacle_services.getOne(route.params.id);
};

const instance = reactive({
  status: "",
  reject_message: "",
});

function formatTime(time) {
  const [hours, minutes] = time.split(':');
  const suffix = hours >= 12 ? 'PM' : 'AM';
  const formattedHours = hours % 12 || 12;
  return `${formattedHours}:${minutes} ${suffix}`;
}
const listPecs = ref([])
const listPedagogicals = ref([])

const searchPecs = async (value) => {
  if (value.length >= 3) {
    listPecs.value.splice(0, listPecs.value.length)

    const response = await byActivityDate(value)
    const items = response?.data?.items || []

    if (!items.length) return

    listPecs.value.push(...items.map((pec) => ({
      value: pec.id,
      label: `${pec.consecutive} - ${dayjs(pec.activity_date).format("DD/MM/YYYY")} - ${formatTime(pec.start_time)} - ${formatTime(pec.final_hour)}`,
    })))
  }
}

const searchPedagogicals = async (value) => {
  if (value.length >= 3) {
    listPedagogicals.value.splice(0, listPedagogicals.value.length)

    const response = await byActivityDatePedagocial(value);
    const items = response?.data?.items || []
    if (!items.length) return

    listPedagogicals.value.push(...items.map((pedagogical) => ({
      value: pedagogical.id,
      label: `${pedagogical.consecutive} - ${dayjs(pedagogical.activity_date).format("DD/MM/YYYY")}`,
    })))
  }
}
const pedagogical_options = asyncComputed(async () => {
  const response = await byActivityDatePedagocial();
  const { items } = response.data;

  return items.map((pedagogical) => ({
    value: pedagogical.id,
    label: `${pedagogical.consecutive} - ${dayjs(
      pedagogical.activity_date
    ).format("DD/MM/YYYY")}`,
  }));
});


const pecs_options = asyncComputed(async () => {
  const response = await byActivityDate();
  const { items } = response.data;

  return items.map((pec) => ({
    value: pec.id,
    label: `${pec.consecutive} - ${dayjs(pec.activity_date).format("DD/MM/YYYY")} - ${formatTime(pec.start_time)} - ${formatTime(pec.final_hour)}`,
  }));

}, null);


onBeforeMount(async () => {
  if (editing.value) {
    binnacle_store.$reset();
    v$.value.$reset();
    await fetch_binnacle().catch(() => {
      mixins.not_found_by_id();
    });

    //console.log(binnacle_services.data.one)

    const {
      activation_mode,
      assistants,
      beneficiaries,
      created_at,
      created_by,
      deleted_at,
      id,
      reject_message,
      updated_at,
      status,
      user_method_support_id,
      user_review_instructor_leader_id,
      user_review_manager_cultural_id,
      user_review_support_follow_id,
      ...rest_binnacle
    } = binnacle_services.data.one;

    binnacle_store.$patch((state) => {
      Object.assign(state.form, {
        ...rest_binnacle,
        aggregates: {
          beneficiaries: beneficiaries.map(
            ({ id, document_number, full_name }) => ({
              id,
              full_name,
              nuip: document_number,
            })
          ),
        },
      });
    });

    instance.status = status;
    instance.reject_message = reject_message;
  } else {
    binnacle_store.$reset();
    v$.value.$reset();
    await fetch_consecutive();
    await get_beneficiaries(0);
  }
});

onUnmounted(() => {
  binnacle_store.$reset();
  v$.value.$reset();
});

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true });

const onSubmit = async () => {
  const valid = await v$.value.$validate();
  if (valid) {
    disabledButton.value = true;
    if (editing.value) {
      await binnacle_services.update(
        binnacle_services.data.one.id,
        binnacle_store.get_transpiled_data,
        "monitor"
      ).finally(() => {
        disabledButton.value = false
      })

    } else {
      await fetch_consecutive().finally(() => {
        binnacle_services
          .create(binnacle_store.get_transpiled_data, "monitor")
          .then(async (response) => {
            if (response.data.success) {
              binnacle_store.$reset();
              v$.value.$reset();
              await fetch_consecutive();
              scroll_top();
              files.I = [];
              files.II = [];
              files.III = [];
            }
          }).finally(() => {
            disabledButton.value = false
          })
      });
    }
  } else {
    alerts.validation();
  }
};

const get_binnacle = computed(() => {
  return form.value.binnacle_id;
});

watch(get_binnacle, () => {
  if (get_binnacle.value == "JP") {
    form.value.beneficiaries_or_capacity = "beneficiaries";
  } else if (get_binnacle.value == "SC") {
    form.value.beneficiaries_or_capacity = "beneficiaries";
  } else if (get_binnacle.value == "TP") {
    form.value.beneficiaries_or_capacity = "aforo";
  } else if (get_binnacle.value == "AC") {
    form.value.beneficiaries_or_capacity = "aforo";
  }
});

const addDevActivityImg = (err, val) => {
  if (err) return;
  const { file } = val;
  form.value.development_activity_image = file;
};

const addParticipationImg = (err, val) => {
  if (err) return;
  const { file } = val;
  form.value.evidence_participation_image = file;
};

const addAforoFile = (err, val) => {
  if (err) return;
  const { file } = val;
  form.value.aforo_file = file;
};

const removeDevActivityImg = () => {
  form.value.development_activity_image = null;
};

const removeParticipationImg = () => {
  form.value.evidence_participation_image = null;
};

const removeAforoFile = () => {
  form.value.aforo_file = null;
  files.value = [];
};

const beneficiaries_headers = [
  {
    text: "ID",
    value: "id",
  },
  {
    text: "NOMBRE",
    value: "full_name",
  },
  {
    text: "CÉDULA",
    value: "nuip",
  },
];

const general = servicesBeneficiaries();
const beneficiaries_by_group = ref([]);

const get_beneficiaries = async (id) => {
  if (!editing.value) {
    await general.getGroupBeneficiaries(id).then((response) => {
      if (response != undefined) {
        let beneficiaries = response.data.items[0].beneficiaries;

        beneficiaries_by_group.value = beneficiaries;

        binnacle_store.$patch((state) => {
          Object.assign(state.form, {
            aggregates: {
              beneficiaries: beneficiaries,
            },
          });
        });
      }
    });
  }
};

const get_group = computed(() => {
  return form.value.group_id;
});

watch(get_group, async (new_val, old_val) => {
  if (new_val != "" && new_val != null) {
    if (new_val != old_val) {
      await get_beneficiaries(new_val);
    }
  }
});
</script>

<template>
  <FormHeader
    :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
    {{ (editing) ? `Edición de Bitácora Jornada Pacto #${binnacle_services.data.one.id}` : `Bitácora Jornada
        Pacto` }}
  </FormHeader>
  <Management class="mt-5" v-if="editing ? permissions.binnacles.management() : false" @send="
    (payload) =>
      mixins.methods.send_management(
        'binnacles',
        binnacle_services.data.one.id,
        payload
      )
  " />
  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.binnacles.no_edit()">
      <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
        <section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
          <!-- First Part -->
          <template :class="
            form.binnacle_id === 'EC' || form.binnacle_id === 'TP'
              ? 'grid-cols-2'
              : 'grid-cols-1'
          " class="grid gap-3">
            <div class="w-full">
              <BaseSelect label="BITÁCORA" tooltip="Ingrese la bitácora" name="binnacle_id" v-model="form.binnacle_id"
                :options="binnacles_options" :validator="v$" />
            </div>
            <div class="w-full intro-x">
              <BaseSelect v-if="form.binnacle_id === 'EC' || form.binnacle_id === 'TP'" label="BENEFICIARIOS O AFORO"
                tooltip="Beneficiarios o Aforo" name="beneficiaries_or_capacity" v-model="form.beneficiaries_or_capacity"
                :options="beneficiariesForo" :validator="v$" />
            </div>
          </template>

          <BaseInput type="text" label="NOMBRE DE ACTIVIDAD" tooltip="Ingrese el nombre de la actividad"
            placeholder="Nombre de la actividad" name="activity_name" v-model="form.activity_name" :validator="v$" />

          <BaseInput type="date" label="FECHA DE ACTIVIDAD" tooltip="Ingrese la fecha de la actividad"
            placeholder="Fecha de la actividad" name="activity_date" v-model="form.activity_date" :validator="v$" />

          <BaseInput type="time" label="HORA INICIO" tooltip="Ingrese la hora de inicio" placeholder="Hora inicio"
            name="start_time" v-model="form.start_time" :validator="v$" />

          <BaseInput type="time" label="HORA FINAL" tooltip="Ingrese la hora final" placeholder="Hora final"
            name="final_hour" v-model="form.final_hour" :validator="v$" />

          <BaseInput type="text" label="LUGAR" tooltip="Ingrese el lugar" placeholder="Lugar" name="place"
            v-model="form.place" :validator="v$" />
        </section>

        <!-- PECS, PEDAGOGICALS -->
        <section v-if="!mixins.computed.is_role('embajador')"
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6 mb-8">
          <BaseSelect :value="form.pec_id" label="PEC" tooltip="Seleccionar PEC" placeholder="PEC" name="pec_id"
            v-model="form.pec_id" :validator="v$" :options="pecs_options || null" />
          <BaseSelect :value="form.pedagogical_id" label="FICHA PEDAGÓGICA" tooltip="Seleccionar ficha pedagógica"
            placeholder="Ficha pedagógica" name="pedagogical_id" v-model="form.pedagogical_id" :validator="v$"
            :options="pedagogical_options || null" />
        </section>

        <section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
          <!-- Options Data Overload -->
          <BaseSelect label="NAC" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id" v-model="form.nac_id"
            :options="options.nacs || null" :validator="v$" />

          <!-- Second Part -->
          <!-- <BaseRadio label="Modalidad de activación" tooltip="Modalidad de activación" name="activation_mode"
                                        v-model="form.activation_mode" :options="form_options.activation_mode" :validator="v$" /> -->

          <BaseSelect label="EXPERTICIA" tooltip="Ingrese la Experticia" placeholder="Seleccione" name="expertise_id"
            v-model="form.expertise_id" :options="options.expertises || null" :validator="v$" />

          <BaseSelect label="DERECHO CULTURAL" tooltip="Ingrese el Derecho Cultural" placeholder="Seleccione"
            name="cultural_right_id" v-model="form.cultural_right_id" :options="options.cultural_rights || null"
            :validator="v$" />

          <BaseInput type="text" label="OBJETIVO VIVENCIAL" tooltip="Ingrese objetivo vivencial"
            placeholder="Objetivo vivencial..." name="experiential_objective" v-model="form.experiential_objective"
            :validator="v$" />

          <BaseSelect label="LINEAMIENTOS" tooltip="Ingrese los lineamientos" placeholder="Seleccione" name="lineament_id"
            v-model="form.lineament_id" :options="options.lineaments || null" :validator="v$" />

          <BaseSelect label="ORIENTACIONES" tooltip="Ingrese las orientaciones" placeholder="Seleccione"
            name="orientation_id" v-model="form.orientation_id" :options="options.orientations || null" :validator="v$" />

          <!-- Third Part -->
          <BaseRadio label="¿SE CUMPLIÓ EL OBJETIVO VIVENCIAL DURANTE LA JORNADA DE ACTIVACIÓN CULTURAL?"
            tooltip="Objetivo" name="goals_met" v-model="form.goals_met" :options="form_options.goals_met"
            :validator="v$" />
        </section>

        <section class="grid grid-cols-1 gap-6 mb-8">
          <Textarea rows="5" label="EXPLIQUE EL ¿POR QUE?" tooltip="" placeholder="Porque..." name="explain_goals_met"
            v-model="form.explain_goals_met" :validator="v$" />

          <Textarea rows="5" label="INICIO" tooltip="" placeholder="Inicio..." name="start_activity"
            v-model="form.start_activity" :validator="v$" />

          <Textarea rows="5" label="DESARROLLO" tooltip="" placeholder="Desarrollo..." name="activity_development"
            v-model="form.activity_development" :validator="v$" />

          <Textarea rows="5" label="FINAL (REFLEXIÓN DE LA JORNADA DE EXPERIMENTACIÓN)" tooltip=""
            placeholder="Reflexión de la jornada de experimentación..." name="end_of_activity"
            v-model="form.end_of_activity" :validator="v$" />

          <Textarea rows="5" label="OBSERVACIONES" tooltip="" placeholder="Observaciones..." name="observations_activity"
            v-model="form.observations_activity" :validator="v$" />
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <BaseFilepond label="DESARROLLO DE LA JORNADA DE PACTO *" tooltip="Arrastra una imagen"
            name="development_activity_image" ref="development_activity_image_ref"
            :to_edit="binnacle_services.data.one.development_activity_image" v-model="form.development_activity_image"
            @addfile="addDevActivityImg" @removefile="removeDevActivityImg" :files="files.I" :validator="v$" />

          <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra una imagen"
            name="evidence_participation_image" ref="evidence_participation_image"
            :to_edit="binnacle_services.data.one.evidence_participation_image" v-model="form.evidence_participation_image"
            @addfile="addParticipationImg" @removefile="removeParticipationImg" :files="files.II" :validator="v$" />
        </section>

        <!-- AFORO -->
        <div v-if="form.beneficiaries_or_capacity == 'aforo' || form.binnacle_id == 'AC'"
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6 mb-8">
          <BaseFilepond label="DOCUMENTO DEL AFORO *" tooltip="Arrastra un documento PDF" name="aforo_file"
            ref="aforo_file" v-model="form.aforo_file" @addfile="addAforoFile" @removefile="removeAforoFile"
            accept_only_pdf :files="files.III" :validator="v$" />

          <BaseInput type="number" label="CAPACIDAD (NÚMERO DE ASISTENTES) *" tooltip="Ingrese la capacidad"
            placeholder="0..." name="beneficiaries_capacity" v-model="form.beneficiaries_capacity" :validator="v$" />
        </div>
        <div class="w-full" v-if="aggregates_conditions">
          <BaseSelect label="GRUPO *" tooltip="Selecciona un grupo" placeholder="Seleccione" name="group_id"
            v-model="form.group_id" :options="options.group_beneficiaries" :validator="v$" />
        </div>
        <section v-if="aggregates_conditions" class="flex flex-col justify-start mb-8">
          <div>
            <h3 class="intro-y form-label font-bold uppercase">
              <span> Asistentes Agregados </span>
              <span> # {{ form.aggregates.beneficiaries.length }} </span>
            </h3>
          </div>
          <div class="overflow-x-auto overflow-y-hidden">
            <Aggregates @pop="(id) => binnacle_store.pop_aggregate(id)"
              @push="(aggregate) => binnacle_store.push_aggregate(aggregate)"
              :options="options.beneficiaries_table || null" :headers="beneficiaries_headers"
              :aggregates="form.aggregates.beneficiaries" :validator="v$" name="aggregates">
            </Aggregates>
          </div>
        </section>

        <div class="flex justify-end">
          <button :disabled="disabledButton" class="btn btn-primary" type="submit">
            {{ editing ? "Actualizar" : "Ingresar" }}
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
