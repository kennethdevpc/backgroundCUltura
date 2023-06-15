<script lang="ts" setup>
import axios from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import mixins from "@/mixins";
import permissions from "@/permissions";
import monthlyMonitoringServices from "@/services/monthly_monitoring_reports.service";
import type { Header, Item } from "vue3-easy-data-table";

const services = monthlyMonitoringServices();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
  await services.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "FECHA CARGA DE DOCUMENTO", value: "date" },
  { text: "CREADO POR", value: "user.name" },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item;
  //console.log('item.date',item)
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: "Datos Generales",
        fields: {
          "FECHA CARGA": item.date,
          DOCUMENTO: item.file,
          // "ESTADO": item.status,
          DESCRIPCIÓN: item.description,
        },
      },
    },
  };
};

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await services.get(page.value, searchParams);
  items.value = services.data.all;
  pageCount.value = services.data.count_page;
}

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
    <h2 class="text-lg font-medium mr-auto">Informe mensual de supervisión</h2>
    <div
      v-if="permissions.monthlyMonitoring.create()"
      class="w-full sm:w-auto flex mt-4 sm:mt-0"
    >
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Crear un Informe
      </button>
    </div>
  </div>
  <div class="intro-y box mt-5 p-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination
        v-model="page"
        :pages="pageCount"
        :range-size="1"
        active-color="#DCEDFF"
        @update:modelValue="updatePage"
      />
    </div>
    <BaseCrud
      :headers="headers"
      :items="items"
      :item_see_fnc="item_map"
      :management_permissions="permissions.monthlyMonitoring.crud_management()"
      label="el informe"
      :on-delete-fnc="destroy"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
    />
  </div>
</template>
