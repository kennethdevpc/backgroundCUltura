<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import dropDowns from "../../../services/dropDowns.service";
import service from "@/services/cultural_circulations.service";
import permissions from "@/permissions";
import mixins from "@/mixins";

const router = useRouter();
const route = useRoute();
const { hasPermission } = usePermissions();
const { get, destroy: destroyOne } = service();
const culturalCirculation = service();
const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

const edit = (id: string | number) => {
  router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteGroup(id: string | number) {
  await dropDowns()
    .destroy("groups", id, "groups")
    .then(() => { });
}

async function destroy(id: string | number) {
  await destroyOne(id);
  fetchData();
}

const headers: Header[] = [
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "FECHA", value: "date", width: 120 },
  { text: "CIRCULACIÓN", value: "datasheet", width: 150 },
  { text: "DERECHO CULTURAL", value: "cultural_right_id" },
  { text: "LINEAMIENTOS", value: "lineament_id" },
  { text: "ORIENTACIONES", value: "orientation_id" },
  { text: "CREADO POR", value: "created_by.name" },
  { text: "FECHA DE CREACIÓN", value: "created_at" },
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
  await culturalCirculation.get(page.value, searchParams);
  items.value = culturalCirculation.data.all;
  pageCount.value = culturalCirculation.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}

function updatePage() {
  fetchData();
}

onMounted(async () => {
  const response = await fetchData();
  items.value = culturalCirculation.data.all?.map((item) => {
    return {
      ...item,
      actions: "Actions"
    }
  })

});

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: 'Datos Generales',
        fields: {
          "ESTADO": item.status,
          "CONSECUTIVE": item.consecutive,
          "NOMBRE EVENTO": item.event_name,
          "NIVEL": mixins.get_option_label('filter_level', item.filter_level),
          //  "CANTIDAD DE INTEGRANTES": item.quantity_members,
          "DERECHO CULTURAL": mixins.get_option_label('cultural_rights', item.cultural_right_id),
          "LINEAMIENTOS": mixins.get_option_label('lineaments', item.lineament_id),
          "VALOR": mixins.get_option_label('values', item.values),
          "ORIENTACIONES": mixins.get_option_label('orientations', item.orientation_id),
          "DESCRIPCIÓN": item.description,
          "CARACTERISTICAS PÚBLICO ASISTENTE": item.public_characteristics,
          "EXPERTICIA ARTÍSTICA A TRABAJAR": item.artistic_expertise,
          "OBSERVACIONES DE TU PARTICIPACIÓN": item.participation_observations,
          "CREADO POR": item.created_by.name,
          "FECHA DE CREACIÓN": item.created_at,
          // "ESTADO": item.status,
          // "MENSAJE DE RECHAZO": item.reject_message,
        }
      },
      multimedia: {
        title: 'Multimedia',
        fields: {
          "IMAGEN (DESARROLLO DE LA JORNADA DE PACTO)": item.development_activity_image,
          "EVIDENCIA DE PARTICIPACIÓN": item.evidence_participation_image,
          "FOTO DEL DESARROLLO": item.aforo_pdf,
        }
      },
    }
  }
}

</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Bitácora circulación cultural</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0" v-if="permissions.cultural_circulation.create()">
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Crear una bitácora circulación cultural
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
    <BaseCrud :item_see_fnc="item_map" :headers="headers" :items="items"
      :management_permissions="permissions.cultural_circulation.crud_management()" label="circulación cultural"
      :on-delete-fnc="destroy"
      @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }" />
  </div>
</template>
