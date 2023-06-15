<script lang="ts" setup>
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import ToCreate from "@/components/base/ToCreate.vue";
import type { Header, Item } from "vue3-easy-data-table";

//  [Service] => Importing Cultural Ensemble Service
import methodologicalStrengthening from "@/services/methodologicalStrengthenings.services";

// [Const, Let, Var] => Init Variables
const items: any = ref([]);
const { hasPermission } = usePermissions();
const methodologicalStrengtheningServices = methodologicalStrengthening();

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

// [Method] => Fetching Initial Data
async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await methodologicalStrengtheningServices.get(page.value, searchParams);
  items.value = methodologicalStrengtheningServices.data.all;
  pageCount.value = methodologicalStrengtheningServices.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}

function updatePage() {
  fetchData();
}
// [Vue Hooks] => Getting Table Items & Appending Actions
onMounted(async () => {
  await fetchData();
  items.value.map((item) => {
    return {
      ...item,
      actions: "Acciones",
    };
  });
});

// [Destroy] => Deleting an element from the Table
async function destroy(id: string | number) {
  await methodologicalStrengtheningServices.destroy(id);
  fetchData();
}

const headers: Header[] = [
// { text: "ID", value: "id" },
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "NAC", value: "nac.name" },
  { text: "DERECHO CULTURAL", value: "cultural_right.name" },
  { text: "ORIENTACIÓN", value: "orientation.name" },
  { text: "LINEAMIENTO", value: "lineament" },
  { text: "VALOR", value: "value_name" },
  { text: "CREADOR POR", value: "user.name" },
  { text: "FECHA CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item;
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: "Datos Generales",
        fields: {
          CONSECUTIVO: item.consecutive,
          FECHA: item.date,
          "DERECHO CULTURAL": mixins.get_option_label(
            "cultural_rights",
            item.cultural_right_id
          ),
          LINEAMIENTOS: mixins.get_option_label(
            "lineaments",
            item.lineament_id
          ),
          ORIENTACIONES: mixins.get_option_label(
            "orientations",
            item.orientation_id
          ),
          VALOR: item.value_name,
        },
      },
      multimedia: {
        title: "Multimedia",
        fields: {
          "IMAGEN I": item.development_activity_image,
          "IMAGEN II": item.evidence_participation_image,
        },
      },
      aggregates: {
        title: 'Agregados',
        fields: {
          "BENEFICIARIOS (TABLA)": item.assistants.map((beneficiary) => ({
            "NOMBRE COMPLETO": beneficiary.monitor_fullname,
            "CÉDULA": beneficiary.document_number
          }))
        }
      }
    },
  };
};
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Fortalecimiento metodológico</h2>
    <div v-if="hasPermission('methodologicalStrengthenings.create')" class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <ToCreate :to="{ name: 'methodologicalStrengthenings.create' }">
        Crear un fortalecimiento metodológico
      </ToCreate>
    </div>
  </div>
  <div class="intro-y box mt-5 p-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
        @update:modelValue="updatePage" />
    </div>
    <!-- <pre>{{ items }}</pre> -->
    <BaseCrud :items="items" :headers="headers" :item_see_fnc="item_map"
      :management_permissions="permissions.methodologicalStrengthenings.crud_management()"
      label="Fortalecimiento metodológico" :on-delete-fnc="destroy" 
      @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }"/>
  </div>
</template>
