<script lang="ts" setup>
// Importing Overall librearies / Dependencies
import alert from "@/utils/alerts";
import { storeToRefs } from "pinia";
import { useVuelidate } from "@vuelidate/core";
import { loading } from "@/utils/loading";
import dayjs from "dayjs";
import es from "dayjs/locale/es";
import localeData from "dayjs/plugin/localeData";
// Testing both imports
import permissions from "@/permissions";
import mixins from "@/mixins";

// [Importing Stores] => ['cultural_ensembles']
import { useCulturalEnsembles } from "@/stores/cultural_ensembles";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import BaseManagement from "@/components/base/Management.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseCheckGroup from "@/components/base/BaseCheckGroup.vue";
import FormHeader from "@/components/base/FormHeader.vue";

import userserviceMethologicalSheetOne from "@/services/methodologicalSheetsOne.service";
import pecsService from "@/services/pecs.service";
import { scroll_top } from "@/utils/scroll";
import alerts from "@/utils/alerts";
import useApi from '@/utils/useApi'
const { find } = useApi()
dayjs.locale(es);
dayjs.extend(localeData);
// [Using Store] => ['cultural_ensembles']
const store = useCulturalEnsembles();
const methodologicalSheetsOne = userserviceMethologicalSheetOne();
const { byActivityDate } = pecsService();
const router = useRouter();
const pecs_options = ref([]);

const { is_role } = mixins.computed;
// [Extracting Store] => ['cultural_ensembles']
const { form, form_rules, form_options, files, instance } = storeToRefs(store);

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, {
  $autoDirty: true,
  $lazy: true,
});

// [Computed] => Getting Edit Status
const route = useRoute();
const isEditing = computed(() => (route.params.id ? true : false));

// [Ref] => Declaring Edit Image
const editImage = ref({
  development_activity_image: null,
  evidence_participation_image: null,
  aforo_pdf: null,
});

const listOfMethologicalSheetOne: any = ref([]);
const getallMethologicalSheetOne = async () => {
  let { data } = await methodologicalSheetsOne.get();
  data.items.map((i: any) => {
    listOfMethologicalSheetOne.value.push({
      label: `${i.consecutive}${i.id} | ${i.date_ini} al ${i.date_fin}`,
      value: i.id,
    });
  });
};

// [onBeforeMount] => Getting fetched data before loading template
onBeforeMount(async () => {
  store.clearForm();
  store.$reset();

  await store.getMethodologicalOptions();
  await getallMethologicalSheetOne();
  await fecthPecs();
  if (isEditing.value) {
    await store.getCulturalEnsemble(route.params.id);
    editImage.value = {
      development_activity_image: form.value.development_activity_image,
      evidence_participation_image: form.value.evidence_participation_image,
      aforo_pdf: form.value.aforo_pdf,
    };
  } else {
    store.getCulturalConsecutive();
  }
});

// [Submit] => Checking Payload before adding or editing
const onSubmit = async () => {
  const valid = await v$.value.$validate();
  //console.log(v$.value.$errors);
  if (valid) {
    const FORM_DATA = {
      ...form.value,
    };

    // If not editing, add the form, otherwise, edit it.
    if (!isEditing.value) {

      await store.getCulturalConsecutive().finally(() => {
        store
          .addCulturalEnsemble(FORM_DATA)
          .then(async (response) => {
            alert.general("¡Ficha creada exitosamente!");
            store.$reset();
            await store.clearForm();
            v$.value.$reset();
            await store.getCulturalConsecutive();
            scroll_top();
          })
          .catch((error) => {
            alert.error("Verifica todos los campos");
          });

      });

    } else {
      await store
        .editCulturalEnsemble(route.params.id, FORM_DATA)
        .then((response) => {
          alert.general("¡Ficha editada exitosamente!");
          router.push({ name: "culturalEnsembles.index" });
        })
        .catch((error) => {
          alert.error("Verifica todos los campos" + error);
        });
    }
  } else {
    alerts.validation();
  }
};

const addFile = (err, val, name) => {
  if (err) return;
  const { file } = val;
  form.value[name] = file;
};

const removeFile = (name) => {
  form.value[name] = null;
};

// Creating Overall composables
const getSingleLabel = (currOption, allOptions) => {
  if (allOptions.length > 0) {
    return allOptions.find((opt) => opt.value == currOption).label;
  } else {
    return "Sin Datos";
  }
};

const getSingleOption = (options) => {
  if (options.length > 0) {
    return options.map((opt) => opt.value);
  } else {
    return [];
  }
};

function formatTime(time) {
  const [hours, minutes] = time.split(":");
  const suffix = hours >= 12 ? "PM" : "AM";
  const formattedHours = hours % 12 || 12;
  return `${formattedHours}:${minutes} ${suffix}`;
}

const fecthPecs = async () => {
  const response = await byActivityDate();
  const { items } = response.data;
  pecs_options.value = items.map((pec) => ({
    value: pec.id,
    label: `${pec.consecutive} - ${dayjs(pec.activity_date).format(
      "DD/MM/YYYY"
    )} - ${formatTime(pec.start_time)} - ${formatTime(pec.final_hour)}`,
  }));
};



/**
 * Watch the changes form date_range input and obtains his month in long format
 * --> put in the token value Ficha ${split of consecutive obtaining her number} - the month
 */

if (is_role('instructor') || is_role('super.root') || is_role('root')) {

  watch(computed(() => form.value.date), async (newQuestion, oldQuestion) => {

    if (newQuestion != null && oldQuestion != null) {

      if (form.value.date) {
        form.value.datasheet = 'Cargando...'
        const count = await find(`getDataSheet/cultural_ensembles/${form.value.date}/date`).then((response) => response.data)
        const month = dayjs(form.value.date).format("MMMM");
        const dataSheet = computed(() => (`Ensamble ${count} - ${month}`))
        form.value.datasheet = dataSheet.value
      }
    }
    if (!isEditing.value){
			form.value.datasheet = 'Cargando...'
			const count = await find(`getDataSheet/cultural_ensembles/${form.value.date}/date`).then((response) => response.data)
			const month = dayjs(form.value.date).format("MMMM");
			const dataSheet = computed(() => (`Ensamble ${count} - ${month}`))
			form.value.datasheet = dataSheet.value
		}
    // if ((newQuestion != oldQuestion) && oldQuestion != null) {
    //   if (form.value.date) {
    //     form.value.datasheet = 'Cargando...'
    //     const count = await find(`getDataSheet/cultural_ensembles/${form.value.date}/date`).then((response) => response.data)
    //     const month = dayjs(form.value.date).format("MMMM");
    //     const dataSheet = computed(() => (`Ensamble ${count} - ${month}`))
    //     form.value.datasheet = dataSheet.value
    //   }

    // }

  }

  )
}
</script>

<template>
  <FormHeader :state="{
    consecutive: form.consecutive,
    status: instance.status,
    reject_message: instance.reject_message,
  }">
    {{
      isEditing
      ? `Edición de Ensamble de Cultural #${route.params.id}`
      : `Ensamble de Cultural`
    }}
  </FormHeader>
  <BaseManagement v-if="isEditing ? permissions.culturalEnsembles.management() : false" @send="
    (payload) =>
      mixins.methods.send_management(
        'cultural_ensembles',
        route.params.id,
        payload
      )
  " class="mt-5" />
  <!-- Form Data -->
  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.culturalEnsembles.no_edit()">
      <!-- <pre>{{ form }}</pre> -->
      <form @submit.prevent="onSubmit" class="grid gap-3 grid-cols-1 p-5 border-t border-slate-200/60">
        <!-- #1 -->
        <section class="grid gap-5 grid-cols-2 md:grid-cols-2">
          <BaseInput type="date" label="FECHA *" name="date" placeholder="Fecha" v-model="form.date" :validator="v$" />
          <BaseInput name="datasheet" type="text" label="FICHA NO. *" disabled :validator="v$" v-model="form.datasheet" />
        </section>

        <!-- #2 -->
        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <BaseSelect label="PEC" :validator="v$" name="pec_id" v-model="form.pec_id" :options="pecs_options" />

          <BaseSelect :validator="v$" name="datasheet_planning" v-model="form.datasheet_planning"
            :options="listOfMethologicalSheetOne" track-by="id" label="FICHAS METODOLOGICA PLANEACION *"
            placeholder="Seleccione..." />
        </section>

        <!-- #3 -->
        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <BaseSelect :validator="v$" name="filter_level" v-model="form.filter_level" :options="form_options.filter_level"
            label="NIVEL DE DOMINIO DEL SEMILLERO PARTICIPANTE" />
        </section>

        <!-- #4 -->
        <section class="grid gap-5 grid-cols-1">
          <BaseTextarea label="DESCRIPCION *" placeholder="Descripción" name="description" v-model="form.description"
            :validator="v$" rows="3" />
          <BaseTextarea label="CARACTERÍSTICAS DEL ENSAMBLE *" placeholder="Características del ensamble"
            name="assembly_characteristics" v-model="form.assembly_characteristics" :validator="v$" rows="3" />
          <BaseTextarea label="OBJETIVO DEL PROCESO *" placeholder="Objetivo del proceso" name="objective_process"
            v-model="form.objective_process" :validator="v$" rows="3" />
          <BaseTextarea label="CARACTERISTICAS PÚBLICO ASISTENTE *" placeholder="Caracteristicas del público asistente"
            name="public_characteristics" v-model="form.public_characteristics" :validator="v$" rows="3" />
        </section>

        <!-- #5 -->
        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <BaseSelect :validator="v$" name="cultural_right_id" v-model="form.cultural_right_id"
            :options="form_options.cultural_rights" label="DERECHO CULTURAL" />
          <BaseSelect :validator="v$" name="lineament_id" v-model="form.lineament_id" :options="form_options.lineaments"
            label="LINEAMIENTOS" />

          <BaseSelect :validator="v$" name="orientation_id" v-model="form.orientation_id"
            :options="form_options.orientations" label="ORIENTACIONES" />

          <BaseSelect :validator="v$" name="value" v-model="form.value" :options="form_options.values" label="VALOR" />

          <BaseTextarea class="col-span-full" label="EXPERTICIA ARTÍSTICA A TRABAJAR *"
            placeholder="Experticia artística a trabajar" name="artistic_expertise" v-model="form.artistic_expertise"
            :validator="v$" rows="3" />
        </section>

        <!-- #6 -->
        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <!-- <BaseSelect class="col-span-full" :validator="v$" name="evaluate_aspects" v-model="form.evaluate_aspects"
                                                                                        :options="form_options.evaluate_aspects" label="ASPECTOS A EVALUAR" /> -->

          <!-- <div class="flex flex-col">
                                                                      <label class="form-label font-bold w-full text-left">ASPECTOS A EVALUAR</label>
                                                                      <div class="flex gap-6">
                                                                        <div>
                                                                          <label class="pr-2 form-label font-bold" for="projection">Proyección artistica</label>
                                                                          <input type="checkbox" id="projection" value="P" v-model="form.evaluate_aspects">
                                                                        </div>
                                                                        <div>
                                                                          <label class="pr-2 form-label font-bold" for="tenc">Técnica</label>
                                                                          <input type="checkbox" id="tenc" value="T" v-model="form.evaluate_aspects">
                                                                        </div>
                                                                        <div>
                                                                          <label class="pr-2 form-label font-bold" for="nehavior">Comportamiento</label>
                                                                          <input type="checkbox" id="nehavior" value="C" v-model="form.evaluate_aspects" :vali>
                                                                        </div>
                                                                      </div>
                                                                    </div> -->
          <BaseCheckGroup :options="form_options.evaluate_aspects" name="evaluate_aspects"
            :validate="form.evaluate_aspects" label="ASPECTOS A EVALUAR" :validator="v$"
            v-model="form.evaluate_aspects" />

          <BaseTextarea class="col-span-full" label="COMENTARIOS *" placeholder="Comentarios"
            name="evaluate_aspects_comments" v-model="form.evaluate_aspects_comments" close-on-select="false"
            :validator="v$" rows="3" />

          <BaseFilepond label="FOTO DEL DESARROLLO *" tooltip="Arrastra una imagen JPG/PNG"
            name="development_activity_image" ref="development_activity_image" :files="files.development_activity_image"
            :validator="v$" @addfile="
              (err, val) => addFile(err, val, 'development_activity_image')
            " @removefile="removeFile('development_activity_image')" :to_edit="editImage.development_activity_image" />

          <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra una imagen JPG/PNG"
            name="evidence_participation_image" ref="evidence_participation_image"
            :files="files.evidence_participation_image" :validator="v$" @addfile="
              (err, val) => addFile(err, val, 'evidence_participation_image')
            " @removefile="removeFile('evidence_participation_image')"
            :to_edit="editImage.evidence_participation_image" />

          <BaseFilepond label="DOCUMENTO DEL AFORO *" tooltip="Arrastra un documento PDF" name="aforo_pdf" ref="aforo_pdf"
            :files="files.aforo_pdf" :validator="v$" @addfile="(err, val) => addFile(err, val, 'aforo_pdf')"
            @removefile="removeFile('aforo_pdf')" :to_edit="editImage.aforo_pdf" accept_only_pdf />

          <BaseInput type="text" label="CAPACIDAD (NUMERO DE ASISTENTES) *" placeholder="Ingrese cantidad"
            name="number_attendees" v-model="form.number_attendees" :validator="v$" />
        </section>
        <div v-if="!permissions.culturalEnsembles.no_edit()" class="flex justify-center">
          <button type="submit" class="btn btn-primary w-24 mr-1 mb-2" :disabled="loading">
            {{ isEditing ? "Actualizar" : "Ingresar" }}
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
