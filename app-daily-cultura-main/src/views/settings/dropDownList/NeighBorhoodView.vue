<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import neighborhoodService from "../../../services/neighborhood.service";

const router = useRouter();
const route = useRoute();

const neighborhood = neighborhoodService();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

const edit = (id: string | number) => {
	router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteNeighborhoods(id: string | number) {
	await neighborhood.destroy(id);
    fetchData();
}

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}
function updatePage() {
  fetchData();
}

const headers: Header[] = [
	{ text: "#", value: "id" },
	{ text: "NAC", value: "nac.name" },
	{ text: "NOMBRE", value: "name" },
	{ text: "FECHA DE CREACIÓN", value: "created_at" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

async function fetchData() {
    const searchParams = Object.keys(filter.value).length
        ? new URLSearchParams(filter.value)
        : null;
	await neighborhood.get(page.value, searchParams);
    items.value = neighborhood.data.all;
    pageCount.value = neighborhood.data.count_page;
}
var success = neighborhood.success;

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
		<h2 class="text-lg font-medium mr-auto">Barrios</h2>
		<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Crear un barrio
			</button>
		</div>
	</div>
	<Alert variant="success" class="mb-2" v-if="success">
		<div class="flex items-center">
			<div class="text-lg font-medium">
				Awesome alert with additional info
			</div>
			<div
				class="px-1 ml-auto text-xs bg-white rounded-md text-slate-700"
			>
				New
			</div>
		</div>
		<div class="mt-3">
			Lorem Ipsum is simply dummy text of the printing and typesetting
			industry. Lorem Ipsum has been the industry's standard dummy text
			ever since the 1500s.
		</div>
	</Alert>
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
		<div class="intro-y box p-5 mt-5">
			<BaseCrud
				:headers="headers"
				:items="items"
				label="El barrio"
				:on-delete-fnc="deleteNeighborhoods"
                @change_status="fetchData()"
                @change_filter="updateData"
                :server_options="{ page: 1, rowsPerPage: 15 }"
			/>
		</div>
	</div>
</template>
