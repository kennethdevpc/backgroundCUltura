<script setup>
import dayjs from 'dayjs'
import 'dayjs/locale/es'
import BaseInput from "@/components/base/Input.vue";
import Textarea from "@/components/base/Textarea.vue";
import BaseSelect from "@/components/base/Select.vue";

import { storeToRefs } from "pinia";
import useVuelidate from "@vuelidate/core";

import { useMethodologicalSheetsOne } from "@/stores/methodologicalSheetsOne";
import { useSelectStore } from "@/stores/selects";

import service from "@/services/methodologicalSheetsOne.service";
import servicesGeneral from "@/services/generals.service";

import alerts from "@/utils/alerts";
import { scroll_top } from "@/utils/scroll";
import permissions from '@/permissions'
import Management from "@/components/base/Management.vue";
import mixins from "@/mixins";
import FormHeader from '@/components/base/FormHeader.vue';

const selectStore = useSelectStore();
const methodologicalSheetsOneStore = useMethodologicalSheetsOne();
const { getConsecutive, getGroups, getGroupsCreatedBy, getDataSheet } = servicesGeneral();
const { computed: { is_role } } = mixins;
const monthCurrent = ref('');

const { options } = storeToRefs(selectStore);
const { form, form_rules } = storeToRefs(methodologicalSheetsOneStore);
const { create, getCountLimit, getOne, update } = service();
const router = useRouter();
const { params: { id } } = useRoute();

const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true });
const consecutive = ref("");
const limit = ref(0);
const count = ref(0);
const hasRegistrer = ref(false);
const groups = ref([]);
const instance = ref({
  reject_message: '',
  status: ""
});

const showLimit = () => {
  alerts.custom_validation('Límite superado, no se puede registrar mas Ficha Metodológica de Planeación ');
}

const buildConsecutive = async () => {
  const { data } = await getConsecutive("methodological_sheets_one", "FMP");
  const { data: { items } } = await getCountLimit();
  limit.value = items.limit;
  count.value = items.count;
  hasRegistrer.value = items.continue;
  consecutive.value = data;
  if (!items.continue) {
    showLimit();
  }
};

onBeforeMount(async () => {
  methodologicalSheetsOneStore.clear();
  v$.value.$reset();
  if (id) {
    const response = await getOne(id);
    const { data } = await getGroupsCreatedBy(response.data.items.created_by);
    groups.value = data;
    instance.value.reject_message = response.data.items.reject_message;
    instance.value.status = response.data.items.status;
    methodologicalSheetsOneStore.fillData(response.data.items);
    consecutive.value = response.data.items.consecutive;
  } else {
    const { data } = await getGroups();
    groups.value = data;
    buildConsecutive();
  }
});

watch(computed(() => form.value.date_ini), async () => {
  if (form.value.date_ini) {
    const { data } = await getDataSheet('methodological_sheets_one', form.value.date_ini);
    const month = dayjs(form.value.date_ini).locale('es').format("MMMM");
    monthCurrent.value = `Ficha ${data} - ${month}`;
  }
})

const onSubmit = async () => {
  const valid = await v$.value.$validate();

  if (valid) {
    try {
      if (id) {
        const response = await update(id, { ...form.value, status: instance.value.status });
        alerts.custom('', "Ficha metodológica de Planeación ha sido actualizada!", "success")
        router.push({ name: 'methodologicalsheetsone.index' })
      } else {
        if (!hasRegistrer.value) {
          showLimit();
          return;
        }
        await getConsecutive("methodological_sheets_one", "FMP").finally(() => {
          create({ ...form.value, consecutive: consecutive.value });
          methodologicalSheetsOneStore.clear();
          v$.value.$reset();
          scroll_top();
          buildConsecutive();
          monthCurrent.value = "";
          alerts.custom('', "Ficha metodológica de Planeación ha sido creada!", "success")

        });
      }
    } catch (error) {
      alerts.custom('', "Hubo un error al crear la ficha metodológica de Planeación",
        "error")
    }
  } else {
    alerts.validation();
  }
};
</script>

<template>
  <FormHeader :state="{ consecutive: consecutive, status: instance.status, reject_message: instance.reject_message }">
    <div class="flex">
      <span>
        Ficha Metodológica de Planeación
      </span>
      <p v-if="is_role('instructor') && !id" class="text-lg font-italic mr-auto ml-4">{{
        monthCurrent
      }}
      </p>
    </div>
  </FormHeader>
  <Management class="mt-5" v-if="id ? permissions.sheetsOne.management() : false"
    @send="(payload) => mixins.methods.send_management('methodological_sheets_one', id, payload)" />

  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.sheetsOne.no_edit()">

      <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60">
        <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
          <BaseInput type="text" label="NOMBRE DEL SEMILLERO CULTURAL *"
            tooltip="Ingrese el nombre del semillero cultural" placeholder="Nombre del semillero cultural"
            name="semillero_name" v-model="form.semillero_name" :validator="v$" />

          <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
            <BaseInput type="date" label="FECHA INICIAL *" tooltip="Ingrese la fecha inicial" placeholder="Fecha"
              name="date_ini" v-model="form.date_ini" :validator="v$" />

            <BaseInput type="date" label="FECHA FINAL *" tooltip="Ingrese la fecha final" placeholder="Fecha"
              name="date_fin" v-model="form.date_fin" :validator="v$" />
          </div>
        </div>

        <div class="intro-y flex flex-col gap-6">
          <h2 class="font-bold text-lg mr-auto intro-y">
            NIVEL DE DOMINIO DEL SEMILLERO
          </h2>
          <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
            <BaseSelect label="GRUPO *" tooltip="Ingrese el grupo" name="group_id" v-model="form.group_id"
              :options="groups" :validator="v$" />
            <BaseSelect label="FILTRO *" tooltip="Ingrese el filtro" name="filter_level" v-model="form.filter_level"
              :options="options.filter_level" :validator="v$" />
          </div>
        </div>

        <div class="intro-y flex flex-col gap-6">
          <h2 class="font-bold text-lg mr-auto intro-y">
            COMPONENTE METODOLÓGICO
          </h2>

          <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
            <BaseSelect label="DERECHO CULTURAL *" tooltip="Ingrese derecho cultural" name="cultural_right_id"
              v-model="form.cultural_right_id" :options="options.cultural_rights" :validator="v$" />

            <BaseSelect label="LINEAMIENTOS *" tooltip="Ingrese el lineamiento" name="lineament_id"
              v-model="form.lineament_id" :options="options.lineaments" :validator="v$" />

            <BaseSelect label="ORIENTACIONES *" tooltip="Ingrese la orientación" name="orientation_id"
              v-model="form.orientation_id" :options="options.orientations" :validator="v$" />

            <BaseSelect label="VALOR *" tooltip="Ingrese el valor" name="value" v-model="form.value"
              :options="options.values" :validator="v$" />
          </div>

          <Textarea rows="5" label="EXPERTICIA ARTÍSTICA A TRABAJAR *" tooltip=""
            placeholder="Experticia artística a trabajar" name="worked_expertise" v-model="form.worked_expertise"
            :validator="v$" />

          <Textarea rows="5" label="CARACTERÍSTICAS DEL PROCESO A TRABAJAR *" tooltip=""
            placeholder="Características del proceso a trabajar" name="characteristics_process"
            v-model="form.characteristics_process" :validator="v$" />

          <Textarea rows="5" label="OBJETIVO DEL PROCESO *" tooltip="" placeholder="Objetivo del proceso"
            name="objective_process" v-model="form.objective_process" :validator="v$" />

          <Textarea rows="5" label="OBSERVACIONES *" tooltip="" placeholder="Observaciones" name="comments"
            v-model="form.comments" :validator="v$" />
        </div>

        <div class="flex justify-center" v-if="!permissions.sheetsOne.no_edit()">
          <button type="submit" class="btn btn-primary w-24 mr-1 mb-2">
            {{ id ? "Actualizar" : "Ingresar" }}
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
