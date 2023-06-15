<script lang="ts" setup>
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import ToCreate from "@/components/base/ToCreate.vue";
import type { Header, Item } from "vue3-easy-data-table";

//  [Service] => Importing Cultural Ensemble Service
import culturalEnsembles from "@/services/cultural_ensembles.service";

// [Const, Let, Var] => Init Variables
const items: any = ref([]);
const { hasPermission } = usePermissions();
const culturalEnsemblesServices = culturalEnsembles();

// [Method] => Fetching Initial Data
let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await culturalEnsemblesServices.get(page.value, searchParams);
  items.value = culturalEnsemblesServices.data.all;
  pageCount.value = culturalEnsemblesServices.data.count_page;
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
  await culturalEnsemblesServices.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "FECHA", value: "date", width: 120 },
  { text: "ENSAMBLE CULTURAL", value: "datasheet", width: 150 },
  { text: "DERECHO CULTURAL", value: "cultural_right_id" },
  { text: "LINEAMIENTOS", value: "lineament_id" },
  { text: "ORIENTACIONES", value: "orientation_id" },
  { text: "CREADO POR", value: "created_by" },
  { text: "FECHA DE CREACIÓN", value: "created_at" },
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
          PEC: mixins.get_option_label("pecs", item.pec_id),
          "FICHA METODOLOGICA PLANEACION": item.datasheet_planning.datasheet,
          "NIVEL DE DOMINIO DEL SEMILLERO PARTICIPANTE":
            mixins.get_option_label("filter_level", item.filter_level),
          "DERECHO CULTURAL": mixins.get_option_label(
            "cultural_rights",
            item.cultural_right_id
          ),
          DESCRIPCION: item.description,
          "ARACTERÍSTICAS DEL ENSAMBLE": item.assembly_characteristics,
          "OBJETIVO DEL PROCESO": item.objective_process,
          "CARACTERISTICAS PÚBLICO ASISTENTE": item.public_characteristics,
          LINEAMIENTOS: mixins.get_option_label(
            "lineaments",
            item.lineament_id
          ),
          ORIENTACIONES: mixins.get_option_label(
            "orientations",
            item.orientation_id
          ),
          VALOR: mixins.get_option_label("values", item.value),
          // 'ASPECTOS A EVALUAR': mixins.get_option_label(
          //   "evaluate_aspects",
          //   item.evaluate_aspects
          // ),
        },
      },
      multimedia: {
        title: "Multimedia",
        fields: {
          "IMAGEN I": item.development_activity_image,
          "IMAGEN II": item.evidence_participation_image,
          "AFORO PDF": item.aforo_pdf,
        },
      },
    },
  };
};
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Bitácora Ensamble Cultural</h2>
    <div v-if="hasPermission('culturalEnsembles.create')" class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <ToCreate :to="{ name: 'culturalEnsembles.create' }">
        Crear una Bitácora
      </ToCreate>
    </div>
  </div>
  <div class="intro-y box mt-5 p-5">
    <!-- <pre>{{ items }}</pre> -->
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
        @update:modelValue="updatePage" />
    </div>
    <BaseCrud :items="items" :headers="headers" :item_see_fnc="item_map"
      :management_permissions="permissions.culturalEnsembles.crud_management()" label="Emsamble Cultural Label"
      :on-delete-fnc="destroy" @change_status="fetchData()" @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }" />
  </div>
</template>
