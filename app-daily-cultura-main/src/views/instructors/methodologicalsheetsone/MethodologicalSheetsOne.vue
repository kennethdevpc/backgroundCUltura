<script lang="ts" setup>
import axios, { AxiosResponse } from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import permissions from "@/permissions";
import services from "@/services/methodologicalSheetsOne.service";
import type { Header, Item } from "vue3-easy-data-table";
import GeneralService from "@/services/generals.service";
import dayjs from "dayjs";
import mixins from "@/mixins";

const service = services();

const router = useRouter();
const route = useRoute();
const serviceGeneral = GeneralService();

const {
	computed: { is_role, is_admin },
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

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

const headers: Header[] = [
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "FICHA", value: "datasheet", width: 140 },
	{ text: "CREADO POR", value: "created_by.name" },
	{ text: "FECHA", value: "date", width: 200 },
	{ text: "SEMILLERO", value: "semillero_name" },
	{ text: "GRUPO", value: "group.name" },
	{ text: "LINEAMIENTO", value: "lineament_id" },
	{ text: "ORIENTACIÓN", value: "orientation_id" },
	{ text: "CREACIÓN", value: "created_at" },
	{ text: "ESTADO", value: "status" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

async function fetchData() {
	const searchParams = Object.keys(filter.value).length
		? new URLSearchParams(filter.value)
		: null;
	let response: AxiosResponse;
	if (is_role("lider_instructor") || is_admin()) {
		response = await service.get(page.value, searchParams);
	} else {
		response = await serviceGeneral.getAllDataCreatedBy(
			"methodological_sheets_one", page.value, searchParams
		);
	}
	pageCount.value = response.data.count_page;
	return response.data.items;
}

async function updateData(values)  {
	page.value = 1;
	filter.value = values;
	const data = await fetchData();
	items.value = data.map((item) => {
		const date = `${dayjs(item.date_ini).format("MMM-D")} a ${dayjs(
			item.date_fin
		).format("MMM-D")} de ${dayjs(item.date_fin).format("YYYY")}`;
		const ficha = `Ficha ${item.id}`;
		return {
			...item,
			date,
			ficha,
			actions: "Acciones",
		};
	});
}

async function updatePage() {
	const data = await fetchData();
	items.value = data.map((item) => {
		const date = `${dayjs(item.date_ini).format("MMM-D")} a ${dayjs(
			item.date_fin
		).format("MMM-D")} de ${dayjs(item.date_fin).format("YYYY")}`;
		const ficha = `Ficha ${item.id}`;
		return {
			...item,
			date,
			ficha,
			actions: "Acciones",
		};
	});
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
					"NOMBRE DEL SEMILLERO CULTURAL": item.semillero_name,
					FECHA: item.date,
					GRUPO: item.group.name,
					FILTRO: mixins.get_option_label(
						"filter_level",
						item.filter_level
					),
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
					VALOR: mixins.get_option_label("values", item.value),
					"EXPERTICIA ARTÍSTICA A TRABAJAR": item.worked_expertise,
					"CARACTERÍSTICAS DEL PROCESO A TRABAJAR":
						item.characteristics_process,
					"OBJETIVO DEL PROCESO": item.objective_process,
					OBSERVACIONES: item.comments,
				},
			},
		},
	};
};
onMounted(async () => {
	const data = await fetchData();
	items.value = data.map((item) => {
		const date = `${dayjs(item.date_ini).format("MMM-D")} a ${dayjs(
			item.date_fin
		).format("MMM-D")} de ${dayjs(item.date_fin).format("YYYY")}`;
		const ficha = `Ficha ${item.id}`;
		return {
			...item,
			date,
			ficha,
			actions: "Acciones",
		};
	});
});
</script>

<template>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-5">
		<h2 class="text-lg font-medium mr-auto">
			Ficha Metodológica de Planeación
		</h2>
		<div
			v-if="permissions.sheetsOne.create()"
			class="w-full sm:w-auto flex mt-4 sm:mt-0"
		>
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Crear ficha
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
			:item_see_fnc="item_map"
			:items="items"
			:management_permissions="permissions.sheetsOne.crud_management()"
			label="la ficha metodológica"
			:on-delete-fnc="destroy"
			@change_status="fetchData()"
			@change_filter="updateData"
			:server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
