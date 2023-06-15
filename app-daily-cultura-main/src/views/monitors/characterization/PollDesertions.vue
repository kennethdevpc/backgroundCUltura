<script lang="ts" setup>
import BaseCrud from "@/components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import services from "@/services/pollDesertions.service";
import mixins from "@/mixins";

const poll_desertions_services = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
	await poll_desertions_services.destroy(id);
	fetchData();
}

const headers: Header[] = [
	//  { text: "#", value: "id" },
	{ text: "BENEFICIARIO", value: "beneficiary.full_name" },
	{ text: "FECHA", value: "date" },
	{ text: "NAC", value: "nac.name" },
	{ text: "FACTOR DE DESERCIÓN", value: "beneficiary_attrition_factors" },
	{ text: "OTRO FACTOR", value: "beneficiary_attrition_factor_other" },
	{ text: "DESINTERÉS Y APATÍA", value: "disinterest_apathy" },
	{ text: "REINTEGRACIÓN", value: "reintegration" },
	{ text: "CREACIÓN", value: "created_at" },
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
  await poll_desertions_services.get(page.value, searchParams);
  items.value = poll_desertions_services.data.all;
  pageCount.value = poll_desertions_services.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}
function updatePage() {
  fetchData();
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
					BENEFICIARIO: item.beneficiary.full_name,
					FECHA: item.date,
					NAC: item.nac.name,
					"FACTOR DE DESERCIÓN": mixins.get_option_label(
						"beneficiary_attrition_factors",
						item.beneficiary_attrition_factors
					),
					"OTRO FACTOR": item.beneficiary_attrition_factor_other,
					"A. DESINTERÉS Y APATÍA":
						item.disinterest_apathy == 1 ? "SI" : "NO",
					"A. EXPLICACIÓN": item.disinterest_apathy_explanation,
					"B. REINTEGRACIÓN": item.reintegration == 1 ? "SI" : "NO",
					"B. EXPLICACIÓN": item.reintegration_explanation,
					// "ESTADO": item.status,
					// "MENSAJE DE RECHAZO": item.reject_message,
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
		<h2 class="text-lg font-medium mr-auto">Encuesta de Deserción</h2>
		<div class="w-full sm:w-auto flex mt-4 sm:mt-0">
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Hacer encuesta de deserción
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
			label="la Encuesta de Deserción"
			:on-delete-fnc="destroy"
            @change_status="fetchData()"
            @change_filter="updateData"
            :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
