<script lang="ts" setup>
import type { Header, Item } from "vue3-easy-data-table";
import services from "@/services/psychopedagogicalog.service";
import permissions from "@/permissions";
import mixins from "@/mixins";
import BaseCrudPDF from "@/components/base/BaseCrudPDF.vue";
const psychopedagogicalog_services = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
	await psychopedagogicalog_services.destroy(id).then(() => {
		fetchData();
	});
}

const headers: Header[] = [
	//{ text: "#", value: "id" },
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "CREADO POR", value: "user.name" },
	{ text: "FECHA", value: "date" },
	{ text: "NAC", value: "nac.name" },
	{ text: "HORA INICIO", value: "start_time" },
	{ text: "HORA FINAL", value: "final_time" },
	{ text: "ESTADO", value: "status" },
	{ text: "ACCIONES", value: "actions" },
];
const item_map = (item: { [key: string]: any }) => {
	const { id, consecutive } = item;
	const assistants = item.assistants.map((asis) => {
		return {
			NOMBRE: asis.assistant_name,
			CEDULA: asis.assistant_document_number,
			CARGO: asis.assistant_position,
			TELEFONO: asis.assistant_phone,
			EMAIL: asis.assistant_email,
			NAC: mixins.get_option_label("nacs", asis.nac_id),
		};
	});

	const assistance_monitors = item.monitors.map((mon) => {
		const monitor = mixins.get_monitor_by_id(mon.monitor_id);

		if (monitor != "No encontrad@") {
			return {
				ID: monitor.id,
				NOMBRE: monitor.monitor_fullname,
				CEDULA: monitor.document_number,
			};
		}
	});

	return {
		id,
		consecutive,
		sections: {
			general: {
				title: "Datos Generales",
				fields: {
					FECHA: item.date,
					NAC: item.nac.name,
					"HORA INICIO": item.start_time,
					"HORA FINAL": item.final_time,
					"NOMBRE DE LA PERSONA ATENDIDA": item.person_served_name,
					"NOMBRE DEL MONITOR CULTURAL": mixins.get_option_label(
						"nacs",
						item.monitor_id
					),
					OBJETIVO: item.objective,
					DESARROLLO: item.development,
					"CONCLUCIONES, REFLEXIONES Y COMPROMISOS DE LA JORNADA":
						item.conclusions_reflections_commitments,
					"REPORTE DE ALERTA PARA HACER SEGUIMIENTO":
						item.alert_reporting_tracking,
					"IMAGEN PRUEBA DEL DESARROLLO":
						item.development_activity_image,
					"IMAGEN PRUEBA DE PARTICIPACIÓN":
						item.evidence_participation_image,
					//"ASISTENTES":assistants,
					//"MONITORES ASISTENTES":assistance_monitors,
					//"RECHAZO":item.reject_message,
				},
			},
			asistances: {
				title: "Datos de asistencia",
				fields: {
					ASISTENTES: assistants,
					"MONITORES ASISTENTES": assistance_monitors,
				},
			},
		},
	};
};

const items = ref<Item[]>([]);

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await psychopedagogicalog_services.get(page.value, searchParams);
  items.value = psychopedagogicalog_services.data.all;
  pageCount.value = psychopedagogicalog_services.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}
function updatePage() {
  fetchData();
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
		<h2 class="text-lg font-medium mr-auto">Bitácora Psicopedagogica</h2>
		<div
			v-if="permissions.psychopedagogicallogs.create()"
			class="w-full sm:w-auto flex mt-4 sm:mt-0"
		>
			<button class="btn btn-primary shadow-md mr-2" @click="create">
				Crear una bitácora psicopedagogica
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
		<BaseCrudPDF
			module="psychopedagogicallogs"
			:headers="headers"
			:items="items"
			label="la bitácora psicopedagógica"
			:item_see_fnc="item_map"
			:management_permissions="
				permissions.psychopedagogicallogs.crud_management()
			"
			:on-delete-fnc="destroy"
            @change_status="fetchData()"
            @change_filter="updateData"
            :server_options="{ page: 1, rowsPerPage: 15 }"
		/>
	</div>
</template>
