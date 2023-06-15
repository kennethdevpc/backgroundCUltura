<script lang="ts" setup>
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import ToCreate from "@/components/base/ToCreate.vue";
import type { Header, Item } from "vue3-easy-data-table";

//  [Service] => Importing Cultural Ensemble Service
import methodologicalAccompaniments from "@/services/methodologicalAccompaniments.service";

// [Const, Let, Var] => Init Variables
const items: any = ref([]);
const { hasPermission } = usePermissions();
const methodologicalAccompanimentsServices = methodologicalAccompaniments();

// [Method] => Fetching Initial Data
let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await methodologicalAccompanimentsServices.get(page.value, searchParams);
  items.value = methodologicalAccompanimentsServices.data.all;
  pageCount.value = methodologicalAccompanimentsServices.data.count_page;
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
  await methodologicalAccompanimentsServices.destroy(id);
  fetchData();
}

const headers: Header[] = [
//  { text: "ID", value: "id" },
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "NAC", value: "nac.name" },
  { text: "ASPECTOS", value: "aspects" },
  { text: "ROLES", value: "roles_associate" },
  { text: "CREADOR POR", value: "user.name" },
  { text: "FECHA CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item;
   var label_aspects ="";

   item.aspects.forEach(element => 
   label_aspects += mixins.get_option_label('aspects', element) +','
   
   );
   var label_roles = "";
   item.roles_associate.forEach(element => 
   label_roles += mixins.get_option_label('roles_display', element) +','
   
   );

  return {
    id,
    consecutive,
    sections: {
      general: {
        title: "Datos Generalesx",
        fields: {
          CONSECUTIVO: item.consecutive,
          FECHA: item.date,
          OTROS: item.others,
          OBJETIVO_VISITA: item.objective_visit,
          ASPECTOS: label_aspects,
          ROLES:label_roles,
          ASPECTOS_PREVIOS : item.aspects_comments,
          OBSERVACIÓN: item.comments,

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
    <h2 class="text-lg font-medium mr-auto">Acompañamiento metodológico</h2>
    <div v-if="hasPermission('methodologicalAccompaniments.create')" class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <ToCreate :to="{ name: 'methodologicalAccompaniments.create' }">
        Crear un acompañamiento metodológico
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
      :management_permissions="permissions.methodologicalAccompaniments.crud_management()"
      label="Acompañamiento metodológico" :on-delete-fnc="destroy"
      @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }" />
  </div>
</template>
