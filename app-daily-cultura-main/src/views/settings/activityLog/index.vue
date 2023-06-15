<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import services from "../../../services/activityLog.service";
import { useRouter } from 'vue-router';
import mixins from "@/mixins";
const router = useRouter();
const route = useRoute();

const activityLog = services()

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const editModule = (id: string | number) => {
	router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteModule(id: string | number) {
    await activityLog.destroy(id);
    fetchData();
}

const headers: Header[] = [
	{ text: "#", value: "id" },
	{ text: "NAME", value: "log_name" },
	{ text: "DESCRIPCION", value: "description" },
	{ text: "LUGAR", value: "subject_type" },
	{ text: "EVENTO", value: "event" },
	{ text: "FECHA REGISTRO", value: "created_at" },
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
					"NOMBRE": item.log_name,
					"DESCRIPCION": item.description,
					"LUGAR": item.subject_type,
					"EVENTO": item.event,
					"FECHA REGISTRO": item.created_at,
				}
			},
			relation: {
				title: 'Relación',
				fields: {
					"ID": item.subject.id,
					"NOMBRE": item.subject.name,
					"FECHA CREACIÓN": item.subject.created_at
				}
			}
		}
	}
}
let page = ref(1)
let pageCount = ref(0)
async function fetchData() {

	await activityLog.get(page.value)
	items.value = activityLog.data.all
	pageCount.value = activityLog.data.count_page

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
	</div>
	<div class="intro-y box p-5 mt-5">
		<div class="flex items-center justify-center p-2 text-base">
			<v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
				@update:modelValue="fetchData" />
		</div>
		<BaseCrud @change_status="async () => await fetchData()" :headers="headers" :items="items" :item_see_fnc="item_map"
			label="Usuarios" :on-delete-fnc="deleteModule" :hiddenButton="true" :server_options="{ page: 1, rowsPerPage: 15 }" />

	</div>
</template>