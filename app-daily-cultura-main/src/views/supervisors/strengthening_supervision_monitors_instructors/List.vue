<script lang="ts" setup>
import axios from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import mixins from "@/mixins";
import permissions from "@/permissions";
import binnacleTerritoriesService from "@/services/strengtheningSuperMonIns.service";
import type { Header, Item } from "vue3-easy-data-table";
import { useOnboardingStore } from "@/stores/onboarding";
import ToCreate from "@/components/base/ToCreate.vue";

const services = binnacleTerritoriesService();

const { hasPermission } = usePermissions();

const { get_user, is_role } = useOnboardingStore();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
	await services.destroy(id);
	fetchData();
}

const headers: Header[] = [
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "CREADO POR", value: "created_by.name" },
	{ text: "FECHA REGISTRO PLATAFORMA", value: "platform_registration_date" },
	{ text: "FECHA REVISIÓN", value: "revision_date", width: 120 },
	{ text: "NAC", value: "nac.name", width: 120 },
	{ text: "ROL", value: "role.name", width: 120 },
	{ text: "USUARIO", value: "user.name", width: 120 },
	{ text: "CREACIÓN", value: "created_at" },
	{ text: "ESTADO", value: "status" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
	const { id, consecutive } = item;
	return {
		id,
		consecutive,
		sections: {
			general: {
				title: "Datos Generales",
				fields: {
					"FECHA DE REVISIÓN": item.revision_date,
					NAC: item.nac.name,
					ROL: item.role.name,
					USUARIO: item.user.name,
				},
			},
			evaluations: {
				title: "Criterios de evaluación",
				fields: {
					"FECHA DE REGISTRO DEL PEC A PLATAFORMA": item.pec_date,
					DIRECCIÓN: item.address,
					"¿CUMPLIO?": item.pec_reached_target ? "SI" : "NO",
					"FICHA PEDAGOGICA REGISTRADA EN PLATAFORMA":
						item.pedagogicals_reached_target ? "SI" : "NO",
					"LISTADO DE ASISTENCIA": item.attendance_list ? "SI" : "NO",
					"EL MONITOR INICIO LA JORNADA A LA HORA REGISTRADA EN EL PEC? ":
						item.validated_pec_time ? "SI" : "NO",
					"DESCRIPCIÓN DE LA JORNADA": item.description,
					OBSERVACIONES: item.comments,
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
  await services.get(page.value, searchParams);
  items.value = services.data.all;
  pageCount.value = services.data.count_page;
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
	await services.get().then(() => {
		items.value = services.data.all;
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
			Fortalecimiento a la supervisión monitores e instructores
		</h2>
		<div
			v-if="permissions.strengtheningSuperMonIns.create()"
			class="w-full sm:w-auto flex mt-4 sm:mt-0"
		>
			<ToCreate :to="{ name: 'strengtheningSuperMonIns.create' }">
				Crear registro
			</ToCreate>
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
				permissions.strengtheningSuperMonIns.crud_management()
			"
			label="la bitácora"
			:on-delete-fnc="destroy"
            @change_status="fetchData()"
            @change_filter="updateData"
            :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
