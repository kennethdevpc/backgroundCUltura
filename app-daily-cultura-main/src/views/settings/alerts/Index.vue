<script setup lang="ts">
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import services from "../../../services/alert.service";
import ToCreate from "@/components/base/ToCreate.vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();

const service = services();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const createModule = () => {
  router.push({ name: `${routeName.value}.create` });
};

const editModule = (id: string | number) => {
	router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteModule(id: string | number) {
    await service.destroy(id);
    fetchData();
}

const headers: Header[] = [
	{ text: "#", value: "id" },
	{ text: "TITULO", value: "title" },
	{ text: "TYPE", value: "type" },
	{ text: "DESCRIPCION", value: "description" },
	{ text: "FECHA DE CREACION", value: "created_at" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
	const { id } = item

	return {
		id,
		sections: {
			general: {
				title: 'Datos Generales',
				fields: {
					"ID": item.id,
					"TITLE": item.title,
					"TYPE": item.type,
					"DESCRIPCION": item.description,
					"FECHA REGISTRO": item.created_at,
				}
			},
		}
	}
}

let page = ref(1);
let pageCount = ref(0);
async function fetchData() {
	await service.get(page.value);
	items.value = service.data.all;
	pageCount.value = service.data.count_page;
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
		<h2 class="text-lg font-medium mr-auto">Actividad de la plataforma</h2>
		<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
			<button class="btn btn-primary shadow-md mr-2" @click="createModule">
				Crear Alerta
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
				@update:modelValue="fetchData"
			/>
		</div>
		<BaseCrud
			@change_status="async () => await fetchData()"
			:headers="headers"
			:items="items"
			:item_see_fnc="item_map"
			label="Usuarios"
			:on-delete-fnc="deleteModule"
			:server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
