<script setup lang="ts">
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import useApi from "@/utils/useApi";
import type { Header, Item } from "vue3-easy-data-table";
import { setLoading } from "@/utils/loading";
import mixins from "@/mixins";
import services from "../../../services/methodologicalMonitorings";

const { find, delete: deleteOne } = useApi();
const { hasPermission } = usePermissions();

const methodologicalMonitoringService = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

const edit = (id: string | number) => {
  router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

const items = ref<Item[]>([]);

let page = ref(1)
let pageCount = ref(0)
const filter = ref({})

async function fetchData() {
  const searchParams = Object.keys(filter.value).length 
  ? new URLSearchParams(filter.value)
  : null
  await methodologicalMonitoringService.get(page.value, searchParams).then(() => {
    items.value = methodologicalMonitoringService.data.all;
    pageCount.value = methodologicalMonitoringService.data.count_page
  });
}
function updateData(values) {
  page.value = 1
  filter.value = values
  fetchData()
}
function updatePage()  {
  fetchData()
}

async function destroy(id: string | number) {
  methodologicalMonitoringService.destroy(id);
  await fetchData();
}

const headers: Header[] = [
	// { text: "ID", value: "id" },
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "SEGUIMIENTO", value: "datasheet" },
	{ text: "NAC", value: "nac.name" },
	{ text: "ROLES", value: "roles_mm" },
	// { text: "ORIENTACIÓN", value: "orientation.name" },
	{ text: "FECHA REALIZACIÓN", value: "date_realization" },
	{ text: "FECHA INICIAL RANGO", value: "date_planning_ini" },
	{ text: "FECHA FINAL RANGO", value: "date_planning_fin" },
	{ text: "CREADO POR", value: "user.name" },
	{ text: "CREACIÓN", value: "created_at" },
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
					// "ID": item.id,
					// "CONSECUTIVO": item.consecutive,
					// "ROL": item.role.name,
					NAC: item.nac.name,
					ORIENTACIÓN: item.orientation.name,
					"FECHA REALIZACIÓN": item.date_realization,
					"FECHA INICIAL": item.date_planning_ini,
					"FECHA FINAL": item.date_planning_fin,
					"OBJETIVO DEL PROCESO": item.objective_process,
					OBSERVACIONES: item.comments,
					"TIPO DE FORTALECIMIENTO": item.strengthening_type_name,
					"COMENTARIOS DEL FORTALECIMIENTO":
						item.strengthening_comments,
					"TEMÁTICAS A FORTALECER SEGÚN ROL":
						item.topics_to_strengthened,
				},
			},
			multimedia: {
				title: "Multimedia",
				fields: {
					"IMAGEN DEL LUGAR": item.development_activity_image,
					"IMAGEN DEL LUGAR 2": item.evidence_participation_image,
				},
			},
			aggregates: {
				title: "Agregados",
				fields: {
					"Asistentes (TABLA)": item.aggregates.map((item) => ({
						NOMBRE: mixins.get_option_label(
							"users_table",
							item.aggregate_id
						),
						// "NOMBRE COMPLETO": beneficiary.full_name,
						// "CÉDULA": beneficiary.nuip
					})),
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
    <h2 class="text-lg font-medium mr-auto">Seguimiento metodológico</h2>
    <div
      v-if="hasPermission('methodologicalMonitorings.create')"
      class="w-full sm:w-auto flex mt-4 sm:mt-0"
    >
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Crear un Seguimiento
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-center p-2 text-base">
			<v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
				@update:modelValue="updatePage" />
		</div>
    <BaseCrud
      :headers="headers"
      :items="items"
      :item_see_fnc="item_map"
      label="la inscripción"
      :management_permissions="permissions.inscriptions.crud_management()"
      :on-delete-fnc="destroy"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
    />
  </div>
</template>
