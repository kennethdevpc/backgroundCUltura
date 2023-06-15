<script lang="ts" setup>
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import ToCreate from "@/components/base/ToCreate.vue";
import type { Header, Item } from "vue3-easy-data-table";

//  [Service] => Importing Cultural Ensemble Service
import strengtheningSpervisionManager from "@/services/strengthening_supervision_manager.service";

// [Const, Let, Var] => Init Variables
const items: any = ref([]);
const { hasPermission } = usePermissions();
const service = strengtheningSpervisionManager();

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

// [Method] => Fetching Initial Data
async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await service.get(page.value, searchParams);
  items.value = service.data.all;
  pageCount.value = service.data.count_page;
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
	await service.destroy(id);
	fetchData();
}

const headers: Header[] = [
	// { text: "ID", value: "id" },
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "NAC", value: "nac.name" },
	{ text: "TRANSFERENCIA", value: "methodological_instruction.name" },
	{ text: "DIRECCIÓN", value: "address" },
	{ text: "FECHA DE REVISIÓN", value: "revision_date" },
	{ text: "HORA INICIO", value: "start_time" },
	{ text: "HORA FINAL", value: "final_time" },
	{ text: "USUARIO SUPERVISADO", value: "user_manager.name" },
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
				title: "Datos Generalesx",
				fields: {
					CONSECUTIVO: item.consecutive,
					"FECHA DE REVISIÓN": item.revision_date,
					"HORA INICIO": item.start_time,
					"HORA FINAL": item.final_time,
					DIRECCIÓN: item.address,
					TRANSFERENCIA: item.methodological_instruction.name,
					FRECUENCIA: item.frequency,
					"BITACORAS REGISTRADAS EN PLATAFORMA":
						item.binnacle_registered_plataform,
					"DESCRIPCIÓN DE LA JORNADA": item.description,
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
		},
	};
};
</script>

<template>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
		<h2 class="text-lg font-medium mr-auto">
			Visita de supervisión de gestor
		</h2>
		<div
			v-if="hasPermission('strengtheningSupervisionMans.create')"
			class="w-full sm:w-auto flex mt-4 sm:mt-0"
		>
			<ToCreate :to="{ name: 'strengtheningSupervisionMans.create' }">
				Crear un visita de supervisión de gestor
			</ToCreate>
		</div>
	</div>
	<div class="intro-y box mt-5 p-5">
		<!-- <pre>{{ items }}</pre> -->
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
			:items="items"
			:headers="headers"
			:item_see_fnc="item_map"
			:management_permissions="
				permissions.strengtheningSupervisionManager.crud_management()
			"
			label="Visita de supervisión de gestor"
			:on-delete-fnc="destroy"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
