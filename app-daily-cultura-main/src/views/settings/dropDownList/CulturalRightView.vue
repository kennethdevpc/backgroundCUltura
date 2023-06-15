<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import axios from "axios";
import dropDowns from "../../../services/dropDowns.service";

const router = useRouter();
const route = useRoute();
const services = dropDowns();
const itemsSelected = ref<String[]>();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

const edit = (id: string | number) => {
	router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteCultural(id: string | number) {
	await dropDowns()
		.destroy("cultural-rights", id, "culturals")
		.then(() => {});
}

const headers: Header[] = [
	{ text: "#", value: "id" },
	{ text: "NOMBRE", value: "name" },
	{ text: "FECHA DE CREACIÓN", value: "created_at" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);
let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
	const searchParams = Object.keys(filter.value).length
		? new URLSearchParams(filter.value)
		: null;
	await services.get(page.value, "cultural-rights", searchParams);
	items.value = services.dataPaginate.all;
	pageCount.value = services.dataPaginate.count_page;
}

function updateData(values) {
    page.value = 1;
    filter.value = values;
    fetchData();
}
function updatePage() {
    fetchData();
}

var success = dropDowns().success;

onMounted(async () => {
	await fetchData();
	items.value.map((item) => {
		return {
			...item,
			actions: "Acciones",
		};
	});
});

const onSelectItem = (data) => {
	itemsSelected.value = data;
};
</script>

<template>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
		<h2 class="text-lg font-medium mr-auto">Derecho Cultural</h2>
		<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Crear un derecho cultural
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
                @update:modelValue="updatePage"
            />
        </div>
		<BaseCrud
			:onSelectItem="onSelectItem"
			:headers="headers"
			:items="items"
			label="el derecho cultural"
			:on-delete-fnc="deleteCultural"
            @change_status="fetchData()"
            @change_filter="updateData"
            :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
