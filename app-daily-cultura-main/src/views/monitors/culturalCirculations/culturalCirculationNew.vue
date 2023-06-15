<script setup lang="ts">
import servicesConsecutive from "@/services/generals.service";
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import cultuiralCirculationsService from "@/services/cultural_circulations.service";
import Management from "@/components/base/Management.vue";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
import permissions from "@/permissions";
import { onBeforeMount, computed, ref } from "vue";
import { useCulturalCirculations } from "@/stores/culturalCirculations";
import userserviceMethologicalSheetOne from "@/services/methodologicalSheetsOne.service";
import { useSelectStore } from "@/stores/selects";
import { useVuelidate } from "@vuelidate/core";
import { storeToRefs } from "pinia";
import dayjs from "dayjs";
import es from "dayjs/locale/es";
import localeData from "dayjs/plugin/localeData";

import pecsService from "@/services/pecs.service";
import { scroll_top } from "@/utils/scroll";
import FormHeader from "@/components/base/FormHeader.vue";
import useApi from '@/utils/useApi'

dayjs.locale(es);
dayjs.extend(localeData);
const { find } = useApi()
const { byActivityDate } = pecsService();

const services = cultuiralCirculationsService();
const consecutive_services = servicesConsecutive();
const select_store = useSelectStore();
const store = useCulturalCirculations();

const { form, getFulesComputed, filesOne } = storeToRefs(store);
const v$ = useVuelidate(getFulesComputed, form, {
  $autoDirty: true,
  $lazy: true,
});
const { options: lists } = storeToRefs(select_store);
const methodologicalSheetsOne = userserviceMethologicalSheetOne();
const router = useRouter();
const { is_role } = mixins.computed;
const listOfMethologicalSheetOne: any = ref([]);
const route = useRoute();
const files = reactive({
  I: [],
  II: [],
  III: [],
});
const reject_message = ref("");

const editing = computed(() => {
  return route.params.id ? true : false;
});

// TODO: obtener el "estado: status" del formulario
const fetchConsecutive = async () => {
  let { data } = await consecutive_services.getConsecutive(
    "cultural_circulations",
    "BCC"
  );
  form.value.consecutive = data;
};

const getallMethologicalSheetOne = async () => {
  let { data } = await methodologicalSheetsOne.get();

  data.items.map((i: any) => {
    listOfMethologicalSheetOne.value.push({
      label: `${i.consecutive}${i.id} | ${i.date_ini} al ${i.date_fin}`,
      value: i.id,
    });
  });
};

const onSubmit = async () => {
  try {
    const valid = await v$.value.$validate();
    if (valid) {
      if (editing.value) {
        const response = await services.update(route.params.id, form.value);
        if (response.data.success) {
          router.push({ name: "culturalCirculations.index" });
        }
      } else {
        await fetchConsecutive().finally(() => {
          services.create(form.value).then(async (response) => {
            if (response.data.success) {
              store.$reset();
              v$.value.$reset();
              scroll_top();
              await fetchConsecutive();
              files.I = [];
              files.II = [];
              files.III = [];
            }
          });
        });
      }
    } else {
      alerts.validation();
    }
  } catch (error) {
    //console.log({ error });
  }
};

onBeforeMount(async () => {
  store.$reset();
  v$.value.$reset();
  files.I = [];
  files.II = [];
  files.III = [];
  await getallMethologicalSheetOne();
  if (editing.value) {
    const {
      data: { items: data },
    } = await services.getOne(route.params?.id);
    reject_message.value = data.reject_message;
    const { ...rest } = data;
    form.value = rest;
    filesOne.value.aforo_pdf = data.aforo_pdf;
    filesOne.value.development_activity_image = data.development_activity_image;
    filesOne.value.evidence_participation_image =
      data.evidence_participation_image;
  } else {
    fetchConsecutive();
  }
});

function formatTime(time) {
  return dayjs(`1999-01-01 ${time}`).format("hh:mm A");
}

const pecs_options = asyncComputed(async () => {
  const response = await byActivityDate();
  const { items } = response.data;
  // dayjs('2018-04-13 19:18')
  return items.map((pec) => ({
    value: pec.id,
    label: `${pec.consecutive} - ${dayjs(pec.activity_date).format(
      "DD/MM/YYYY"
    )} - ${formatTime(pec.start_time)} - ${formatTime(pec.final_hour)}`,
  }));
}, null);

/**
 * Watch the changes form date_range input and obtains his month in long format
 * --> put in the token value Ficha ${split of consecutive obtaining her number} - the month
 */
if (is_role('instructor') || is_role('super.root') || is_role('root')) {

  watch(computed(() => form.value.date), async (newQuestion, oldQuestion) => {

    if (newQuestion != null && oldQuestion != "") {
      if (form.value.date) {
        form.value.datasheet = 'Cargando...'
        const count = await find(`getDataSheet/cultural_circulations/${form.value.date}/date`).then((response) => response.data)
        const month = dayjs(form.value.date).format("MMMM");
        const dataSheet = computed(() => (`Circulación ${count} - ${month}`))
        form.value.datasheet = dataSheet.value
      }
    }
    if (!editing.value) { 
        form.value.datasheet = 'Cargando...'
        const count = await find(`getDataSheet/cultural_circulations/${form.value.date}/date`).then((response) => response.data)
        const month = dayjs(form.value.date).format("MMMM");
        const dataSheet = computed(() => (`Circulación ${count} - ${month}`))
        form.value.datasheet = dataSheet.value
      }
    
  })
}
</script>
<template>
  <FormHeader :state="{
    consecutive: form.consecutive,
    status: form.status,
    reject_message: reject_message,
  }">
    {{
      editing
      ? `Bitácora circulación cultural #${route?.params.id}`
      : `Bitácora circulación cultural`
    }}
  </FormHeader>
  <Management class="mt-5" v-if="editing ? permissions.cultural_circulation.management() : false" @send="
    (payload) =>
      mixins.methods.send_management(
        'cultural_circulations',
        route?.params.id as string,
        payload
      )
  " />
  <div class="p-5 mt-5 intro-y box">
    <fieldset :disabled="permissions.cultural_circulation.no_edit()">
      <form @submit.prevent="onSubmit" class="space-y-8 divide-y divide-slate-200">
        <div>
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <BaseInput type="date" label="FECHA *" name="date" v-model="form.date" :validator="v$" />
            <BaseInput name="datasheet" type="text" label="FICHA NO. *" disabled :validator="v$"
              v-model="form.datasheet" />
            <BaseInput type="number" label="CANTIDAD DE INTEGRANTES DE SEMILLERO *" placeholder="0, 10, 20..."
              name="quantity_members" v-model="form.quantity_members" :validator="v$" />
            <div class="col-span-1 sm:col-span-2">
              <BaseTextarea label="ACTORES CLAVES DE ALIANZA PARA LA CIRCULACIÓN *" name="keyactors_circulation_alliance"
                v-model="form.keyactors_circulation_alliance" :validator="v$" rows="3" />
            </div>
            <BaseSelect label="FILTRO PEC *" placeholder="PEC Relacionado" name="pec_id" v-model="form.pec_id"
              :value="form.pec_id" :options="pecs_options || null" :validator="v$" />
            <BaseSelect label="FICHA METODOLÓGICA PLANEACIÓN *" placeholder="Ficha relacionada"
              name="datasheet_planning_id" v-model="form.datasheet_planning_id" :value="form.datasheet_planning_id"
              :options="listOfMethologicalSheetOne || null" :validator="v$" />
            <div class="col-span-1 sm:col-span-2">
              <BaseTextarea label="NOMBRE DEL EVENTO *" name="event_name" v-model="form.event_name" :validator="v$"
                rows="3" />
            </div>
            <div class="col-span-1 sm:col-span-2">
              <BaseSelect label="NIVEL DE DOMINIO DEL SEMILLERO PARTICIPANTE *" placeholder="Dominio" name="filter_level"
                v-model="form.filter_level" :value="form.filter_level" :options="lists.filter_level || []"
                :validator="v$" />
            </div>
            <div :class="{ 'grid grid-cols-2 gap-x-4': form.nac_id == '67' }" class="col-span-1 sm:col-span-2">
              <BaseSelect label="TERRITORIO DE CIRCULACIÓN *" placeholder="Territorio" name="nac_id" v-model="form.nac_id"
                :value="form.nac_id" :options="lists.nacs_with_other || null" :validator="v$" />
              <template v-if="form.nac_id == '67'">
                <BaseInput class="intro-y" type="text" label="¿OTRO CUAL?" placeholder="Otro territorio" name="other_nac"
                  v-model="form.other_nac" :validator="v$" />
              </template>
            </div>
            <BaseTextarea label="DESCRIPCIÓN" name="description" v-model="form.description" :validator="v$" rows="3" />
            <BaseTextarea label="CARACTERÍSTICAS DEL PÚBLICO ASISTENTE" name="public_characteristics"
              v-model="form.public_characteristics" :validator="v$" rows="3" />
          </div>
        </div>
        <div class="pt-8">
          <h3 class="text-lg font-medium leading-6 text-gray-900">
            Componente Metodológico
          </h3>
          <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <BaseSelect label="DERECHO CULTURAL *" placeholder="Seleccione" name="cultural_right_id"
              v-model="form.cultural_right_id" :value="form.cultural_right_id" :options="lists.cultural_rights || null"
              :validator="v$" />
            <BaseSelect label="LINEAMIENTOS *" placeholder="Seleccione" name="lineament_id" v-model="form.lineament_id"
              :value="form.lineament_id" :options="lists.lineaments || null" :validator="v$" />
            <BaseSelect label="ORIENTACIONES *" placeholder="Seleccione" name="orientation_id"
              v-model="form.orientation_id" :value="form.orientation_id" :options="lists.orientations || null"
              :validator="v$" />
            <BaseSelect label="VALOR *" placeholder="Seleccione" name="values" v-model="form.values" :value="form.values"
              :options="lists.values || null" :validator="v$" />
            <BaseTextarea label="EXPERTICIA ARTÍSTICA A TRABAJAR" name="artistic_expertise"
              v-model="form.artistic_expertise" :validator="v$" rows="3" />
            <BaseTextarea label="OBSERVACIONES DE TU PARTICIPACIÓN" name="participation_observations"
              v-model="form.participation_observations" :validator="v$" rows="3" />
          </div>
        </div>
        <div class="pt-8">
          <h3 class="text-lg font-medium leading-6 text-gray-900">Otros</h3>
          <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <BaseFilepond label="FOTO DEL DESARROLLO *" name="development_activity_image" ref="development_activity_image"
              v-model="form.development_activity_image" @addfile="
                (err, val) => (form.development_activity_image = val.file)
              " @removefile="
  (_$event) => (form.development_activity_image = null)
" :validator="v$" :to_edit="filesOne.development_activity_image" :files="files.I" />
            <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" name="evidence_participation_image"
              ref="evidence_participation_image" v-model="form.evidence_participation_image" @addfile="
                (err, val) => (form.evidence_participation_image = val.file)
              " @removefile="
  (_$event) => (form.evidence_participation_image = null)
" :validator="v$" :to_edit="filesOne.evidence_participation_image" :files="files.II" />
            <BaseFilepond label="DOCUMENTO DEL AFORO *" :accept_only_pdf="true" name="aforo_pdf" ref="aforo_pdf"
              v-model="form.aforo_pdf" @addfile="(err, val) => (form.aforo_pdf = val.file)"
              @removefile="(_$event) => (form.aforo_pdf = null)" :validator="v$" :to_edit="filesOne.aforo_pdf"
              :files="files.III" />
            <BaseInput type="number" label="CAPACIDAD (NUMERO DE ASISTENTES) *" placeholder="0, 10, 20..."
              name="number_attendees" v-model="form.number_attendees" :validator="v$" />
          </div>
        </div>
        <div class="pt-5">
          <div class="flex justify-end gap-x-4">
            <button type="submit" class="btn btn-primary">
              {{ editing ? "Actualizar" : "Guardar" }}
            </button>
          </div>
        </div>
      </form>
    </fieldset>
  </div>
</template>
