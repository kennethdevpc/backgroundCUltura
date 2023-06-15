<script lang="ts" setup>
import axios from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import mixins from "@/mixins";
import permissions from "@/permissions";
import culturalseedbedsService from "@/services/culturalSeedbeds.service";
import type { Header, Item } from "vue3-easy-data-table";

const router = useRouter();
const route = useRoute();

const service = culturalseedbedsService();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
	await culturalseedbedsService().destroy(id);
	fetchData();
}

const headers: Header[] = [
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "FECHA", value: "date", width: 120 },
	{ text: "SEMILLERO", value: "datasheet", width: 180 },
	{ text: "MIEMBROS", value: "quantity_members" },
	{ text: "CREACIÓN", value: "created_at" },
	{ text: "CREADO POR", value: "created_by.name" },
	{ text: "ESTADO", value: "status" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
	const { pec_id, consecutive, users } = item;
	const user = computed(() => {
		return user[0].name;
	});
	return {
		pec_id,
		consecutive,
		sections: {
			general: {
				title: "Datos Generales",
				fields: {
					FECHA: item.date,
					PEC: mixins.get_option_label("pecs", item.pec_id),
					"FICHAS DE PLANEACION": item.datasheet_planning.datasheet,
					"NIVEL DE DOMINIO DEL SEMILLERO": mixins.get_option_label(
						"filter_level",
						item.filter_level
					),
					"CANTIDAD DE INTEGRANTES DEL SEMILLERO":
						item.quantity_members,
					DOMINIO: item.domain_level,
					"OBJETIVO VIVENCIAL DE LA JORNADA": item.objective_process,
				},
			},
			component: {
				title: "COMPONENTE METADOLOGICO",
				fields: {
					"DERECHO CULTURAL": mixins.get_option_label(
						"cultural_rights",
						item.cultural_right_id
					),
					ORIENTACIONES: mixins.get_option_label(
						"orientations",
						item.orientation_id
					),
					LINEAMIENTO: mixins.get_option_label(
						"lineaments",
						item.lineament_id
					),
					VALOR: mixins.get_option_label("values", item.values),
					"EXPERTICIA ARTÍSTICA A TRABAJAR": item.artistic_expertise,
					"OBSERVACIONES DE LA REALIZACIÓN": item.observations,
				},
			},
			multimedia: {
				title: "Multimedia",
				fields: {
					"IMAGEN DESARROLLO": item.development_activity_image,
					"IMAGEN EVIDENCIA DE PARTICIPACIÓN":
						item.evidence_participation_image,
				},
			},
		},
	};
};

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

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

/* async function fetchData() {
	culturalseedbedsService()
		.get()
		.then((response) => {
			items.value = response.data.items;
		});
} */

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
		<h2 class="text-lg font-medium mr-auto">
			Bitácora de semillero cultural
		</h2>
		<div
			v-if="permissions.culturalSeddbeds.create()"
			class="w-full sm:w-auto flex mt-4 sm:mt-0"
		>
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Crear una Bitácora semillero cultural
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
			:management_permissions="
				permissions.culturalSeddbeds.crud_management()
			"
			label="la bitácora semillero cultural"
			:on-delete-fnc="destroy"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
