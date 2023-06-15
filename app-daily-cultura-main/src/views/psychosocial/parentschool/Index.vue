<script lang="ts" setup>
import type { Header, Item } from "vue3-easy-data-table";
import services from "@/services/parentschool.service";
import permissions from "@/permissions";
import BaseCrudPDF from "@/components/base/BaseCrudPDF.vue";

const parentschools_services = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
  await parentschools_services.destroy(id);
  fetchData();
}

const headers: Header[] = [
 // { text: "ID", value: "id" },
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "CREADO POR", value: "user.name" },
  { text: "HORA INICIO", value: "start_time" },
  { text: "HORA FINAL", value: "final_time" },
  { text: "LUGAR DE ATENCIÓN", value: "place_attention" },
  { text: "CONTACTO", value: "contact" },
  { text: "MONITOR", value: "monitor.name" },
  // { text: "OBJETIVO", value: 'objective' },
  // { text: "DESARROLLO", value: 'development' },
  // { text: "CONCLUSIONES", value: 'conclusions' },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await parentschools_services.get(page.value, searchParams);
  items.value = parentschools_services.data.all;
  pageCount.value = parentschools_services.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}
function updatePage() {
  fetchData();
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
          FECHA: item.date,
          "MONITOR CULTURAL": item.monitor.name,
          "HORA INICIO": item.start_time,
          "HORA FINAL": item.final_time,
          "LUGAR DE ATENCIÓN": item.place_attention,
          CONTACTO: item.contact,
          OBJETIVO: item.objective,
          DESARROLLO: item.development,
          CONCLUSIONES: item.conclusions,
        },
      },
      multimedia: {
        title: "Multimedia",
        fields: {
          "DESARROLLO DE LA ACTIVIDAD ARCHIVO": item.development_activity_image,
          "EVIDENCIA DE PARTICIPACIÓN ARCHIVO":
            item.evidence_participation_image,
        },
      },
    },
  };
};

onMounted(async () => {
  await fetchData();
  items.value.map((item) => {
    return {
      ...item,
      actions: "Acciones",
    };
  });
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Escuela de Padres</h2>
    <div
      v-if="permissions.parentschools.create()"
      class="w-full sm:w-auto flex mt-4 sm:mt-0"
    >
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Crear escuela de padres
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination
        v-model="page"
        :pages="pageCount"
        :range-size="1"
        active-color="#DCEDFF"
        @update:modelValue="updatePage"
      />
    </div>
    <BaseCrudPDF
      module="parentschools"
      :headers="headers"
      :items="items"
      label="la escuela de padre"
      :management_permissions="permissions.parentschools.crud_management()"
      :item_see_fnc="item_map"
      :on-delete-fnc="destroy"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
    />
  </div>
</template>
