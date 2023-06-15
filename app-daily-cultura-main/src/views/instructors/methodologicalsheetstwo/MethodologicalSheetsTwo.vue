<script lang="ts" setup>
import axios, { AxiosResponse } from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import methodologicalSheetsTwoService from "@/services/methodologicalSheetsTwo.service";
import GeneralService from "@/services/generals.service";
import type { Header, Item } from "vue3-easy-data-table";
import mixins from "@/mixins";
import dayjs from "dayjs";

const service = methodologicalSheetsTwoService();
const serviceGeneral = GeneralService();

const router = useRouter();
const route = useRoute();
const {
  computed: { is_role },
} = mixins;

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
  await service.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "FICHA", value: "datasheet", width: 140 },
  { text: "CREADO POR", value: "created_by.name" },
  { text: "FECHA", value: "date", width: 200 },
  { text: "TIPO ACTIVIDAD", value: "activity_type" },
  { text: "OBJETIVO ALCANZADO", value: "reached_target" },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}

function updatePage() {
  fetchData();
}

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await service.get(page.value, searchParams);
  items.value = service.data.all;
  pageCount.value = service.data.count_page;
}

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item;
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: "Datos Generales",
        fields: {
          "TIPO ACTIVIDAD": item.activity_type,
          FECHA: item.date_range,
          "ACTORES CLAVES DE LA COMUNIDAD PARTICIPANTE":
            item.keyactors_participating_community,
          "OBJETIVO DEL PROCESO": item.objective_process,
          "¿SE ALCANZÓ EL OBJETIVO?": item.reached_target,
          SUSTENTACIÓN: item.sustein,
          "CAPACIDAD (NUMERO DE ASISTENTES)": item.number_attendees,
          GRUPO: item.group_beneficiaries,
          // "ESTADO": item.status,
          // "MENSAJE DE RECHAZO": item.reject_message,
        },
      },
      multimedia: {
        title: "Multimedia",
        fields: {
          "IMAGEN (DESARROLLO DE LA JORNADA DE PACTO)":
            item.development_activity_image,
          "EVIDENCIA DE PARTICIPACIÓN": item.evidence_participation_image,
          "FOTO DEL DESARROLLO": item.aforo_pdf,
        },
      },
    },
  };
};

onMounted(async () => {
  const data = await fetchData();
  items.value = data.map((item) => {
    const reached_target = item?.reached_target ? "SI" : "NO";
    const activity_type = mixins.get_option_label(
      "activity_type",
      item.activity_type
    );
    const date = `${dayjs(item.date_ini).format("MMM-D")} a ${dayjs(
      item.date_fin
    ).format("MMM-D")} de ${dayjs(item.date_fin).format("YYYY")}`;

    return {
      ...item,
      reached_target,
      activity_type,
      date,
      actions: "Acciones",
    };
  });
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-5">
    <h2 class="text-lg font-medium mr-auto">
      Ficha Metodológica de Evaluación
    </h2>
    <div v-if="permissions.sheetsOne.create()" class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Crear ficha
      </button>
    </div>
  </div>
  <div class="intro-y box mt-5 p-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
        @update:modelValue="updatePage" />
    </div>
    <BaseCrud :item_see_fnc="item_map" :headers="headers" :items="items"
      :management_permissions="permissions.sheetsOne.crud_management()" label="la ficha metodológica"
      :on-delete-fnc="destroy" @change_status="fetchData()" @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }" />
  </div>
</template>
