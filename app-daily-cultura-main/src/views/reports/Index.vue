<script setup lang="ts">
import { Header } from "vue3-easy-data-table";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { useSelectStore } from "@/stores/selects";
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import mixins from "@/mixins";
import dayjs from "dayjs";
import reportServices from "@/services/report.services";
import useVuelidate from "@vuelidate/core";

interface ReportItem {
	id: number;
	name: string;
	type: string;
	pdf: boolean;
	excel: boolean;
	zip: boolean;
	download: boolean;
}

const router = useRouter();
const services = reportServices();
const { options } = useSelectStore();

/**
 * 1 = Monitors
 * 2 = Managers
 * 3 = Psychosocial's
 * 4 = Others
 */
const tab = ref(1);

const tabOptions = [
	{
		name: "Monitores",
		value: 1,
	},
	{
		name: "Gestores",
		value: 2,
	},
	{
		name: "Embajadores",
		value: 3,
	},
	{
		name: "Instructores",
		value: 4,
	},
	{
		name: "Psicosocial",
		value: 5,
	},
	{
		name: "Metodólogo",
		value: 6,
	},
	{
		name: "Supervisión",
		value: 7,
	},
	{
		name: "Apoyo al seguimiento",
		value: 8,
	},
	{
		name: "Coordinadores",
		value: 9,
	},
	{
		name: "Otros",
		value: 10,
	},
	{
		name: "Cortes",
		value: 11,
	},
];

const headers: Header[] = [
	{ text: "#", value: "id" },
	{ text: "NOMBRE", value: "name" },
	{ text: "ACCIONES", value: "actions" },
];

const items = computed((): ReportItem[] => {
	switch (tab.value) {
		case 1:
			return [
				{
					id: 1,
					name: "Informe de Beneficiarios",
					type: "beneficiariesMonitor",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe de PECS",
					type: "pecs",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe de Fichas Pedagógicas",
					type: "pedagogicals",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				// NO VA PORQUE SE SEPARO BENEFICIARIOS Y ACUDIENTES EN DOS REPORTES APARTE
				// { id: 3, name: 'Informe de Incripciones', type: 'inscriptions', pdf: false, excel: true, zip: true },
				{
					id: 4,
					name: "Informe de Encuestas de Deserción",
					type: "pollDesertions",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 5,
					name: "Informe de Bitácoras Pacto",
					type: "binnacles_monitor",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 2:
			return [
				{
					id: 1,
					name: "Informe Mesa de Dialogo",
					type: "dialogueTables",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe Instrucción Metodológica",
					type: "methodologicalInstructionModels",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe Seguimiento de Gestión Cultural",
					type: "managerMonitorings",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 4,
					name: "Informe Activación cultural",
					type: "binnacleManagers",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 3:
			return [
				{
					id: 1,
					name: "Informe de Bitácora show cultural",
					type: "ambassador",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 4:
			return [
				{
					id: 1,
					name: "Informe de Beneficiarios",
					type: "beneficiariesInstructor",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe de ficha metodólogica de planeación",
					type: "methodologicalSheetsOne",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe de ficha metodologica de evaluación",
					type: "methodologicalsheetstwo",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 4,
					name: "Informe de Bitácora Ensamble Cultural",
					type: "culturalEnsembles",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 5,
					name: "Informe de Bitácora Circulación Cultural",
					type: "culturalCirculations",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 6,
					name: "Informe de Bitácora Semilleros Cultural",
					type: "culturalSeedbeds",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 5:
			return [
				{
					id: 1,
					name: "Informe Escuela de Padres",
					type: "parentschools",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe Bitácoras Psicopedagógicas",
					type: "psychopedagogicallogs",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe Instrucción Psicosocial",
					type: "psychosocialInstructions",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 6:
			return [
				{
					id: 1,
					name: "Informe Seguimiento metodológico",
					type: "methodologicalMonitorings",
					pdf: true,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe Acompañamiento metodológico",
					type: "methodologicalAccompaniments",
					pdf: true,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe de Fortalecimiento metodologico",
					type: "methodologicalStrengthening",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 7:
			return [
				{
					id: 1,
					name: "Informe Fortalecimiento a la supervisión monitores e instructores",
					type: "strengtheningSuperMonIns",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe Visita de supervisión de gestor",
					type: "managerSupervisionvisit",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 8:
			return [
				{
					id: 1,
					name: "Informe Fortalecimiento seguimiento",
					type: "strengtheningOfMonitorings",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe de seguimiento",
					type: "monitoringReport",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 9:
			return [
				{
					id: 1,
					name: "Informe de Visita territorio",
					type: "binnacleTerritorie",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
		case 10:
			return [
				{
					id: 1,
					name: "Informe de Encuestas",
					type: "polls",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 2,
					name: "Informe de Usuarios",
					type: "users",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 3,
					name: "Informe Variables",
					type: "variables",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 4,
					name: "Informe de Sesión",
					type: "sesion",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 5,
					name: "Historial de Entradas",
					type: "input_history",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 6,
					name: "Informe de Acudientes",
					type: "attendats",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 7,
					name: "Bitácora Impacto",
					type: "binnacleImpacts",
					pdf: false,
					excel: false,
					zip: true,
					download: false,
				},
				{
					id: 8,
					name: "Informe Grupos",
					type: "groups",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 6,
					name: "Informe de Revisiones",
					type: "revisions",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
				{
					id: 7,
					name: "Informe de Permisos",
					type: "permissions",
					pdf: false,
					excel: true,
					zip: false,
					download: false,
				},
			];
			break;
		case 11:
			return [
				{
					id: 1,
					name: "Generar corte de informe beneficiarios monitor",
					type: "beneficiariesMoni",
					pdf: false,
					excel: false,
					zip: false,
					download: true,
				},
				{
					id: 2,
					name: "Generar corte de informe impacto",
					type: "binnacleImpact",
					pdf: false,
					excel: false,
					zip: false,
					download: true,
				},
			];
			break;
		default:
			break;
	}
});

const form = reactive({
	nac_id: null,
	date_start: null,
	date_end: null,
	status: null,
	type: null,
	user_id: null,
	rol_id: null,
	data: false,
});

const filters_rules = computed(() => ({
	nac_id: {},
	date_start: {},
	date_end: {},
	status: {},
	type: {},
	user_id: {},
	rol_id: {},
	data: {},
}));

const exportExcel = async (type: string) => {
	await services.exportExcel(type, form);
};

const exportPdf = async (type: string) => {
	await services.exportPdf(type, form);
};

const exportZip = async (type: string) => {
	await services.exportZIP(type, form);
};

const searchInfoReport = async () => {
	form.data = true;
	return await services.searchInfoReport(form);
};

const v$ = useVuelidate(filters_rules, form, { $autoDirty: true });

const cleanFilter = () => {
	form.nac_id = null;
	form.date_start = null;
	form.date_end = null;
	form.status = null;
	form.type = null;
	form.user_id = null;
	form.rol_id = null;
	form.data = false;
	services.count.value = 0;
	v$.value.$reset();
};

const typeOptions = [
	{
		label: "Pecs",
		value: "pecs",
	},
	{
		label: "Ficha pedagógicas",
		value: "pedagogicals",
	},
	{
		label: "Encuestas de deserción",
		value: "pollDesertions",
	},

	{
		label: "Mesa de dialogo",
		value: "dialogueTables",
	},

	{
		label: "Usuarios",
		value: "users",
	},
	{
		label: "Variables",
		value: "variables",
	},
	{
		label: "Sesión",
		value: "view",
	},
	{
		label: "Acudientes",
		value: "attendats",
	},
	{
		label: "Beneficiarios",
		value: "beneficiaries",
	},

	{
		label: "Escuela de Padres",
		value: "parentschools",
	},
	{
		label: "Bitácora Psicopedagógica",
		value: "psychopedagogicallogs",
	},
];

const maxDate = computed(() => dayjs().format("YYYY-MM-DD"));
const { is_role } = mixins.computed;

const onExport = (type: string, format: string) => {
	const { data, type: formType, ...rest } = form;

	let routeData = router.resolve({
		name: "reports.download",
		query: {
			format,
			type,
			...rest,
		},
	});

	window.open(routeData.href, "_blank", "menubar=no");
};
</script>

<template>
	<div class="intro-y flex items-center mt-8">
		<h2 class="text-lg font-medium mr-auto">Reportes</h2>
	</div>
	<div class="intro-y box p-5 mt-5">
		<section
			class="flex flex-col lg:grid lg:grid-cols-6 xl:grid xl:grid-cols-6 gap-6 justify-evenly"
		>
			<div
				class="flex flex-col justify-start h-ful lg:col-span-2 xl:col-span-2"
			>
				<label
					for="regular-form-2"
					class="form-label font-bold min-w-max mr-4"
					>FECHA RANGO</label
				>
				<div class="grid grid-cols-2 gap-6 w-full intro-x">
					<BaseInput
						class=""
						type="date"
						tooltip="Desde"
						name="date_start"
						:max="maxDate"
						v-model="form.date_start"
						:validator="v$"
					/>
					<BaseInput
						class=""
						type="date"
						tooltip="Hasta"
						name="date_end"
						:max="maxDate"
						v-model="form.date_end"
						:validator="v$"
					/>
				</div>
			</div>
			<div class="flex flex-col justify-start h-full">
				<label
					for="regular-form-2"
					class="form-label font-bold min-w-max"
					>ACCIONES</label
				>
				<div class="grid grid-cols-2 gap-6 w-full intro-x">
					<button
						class="btn w-full"
						type="button"
						@click="cleanFilter"
					>
						Limpiar
					</button>
				</div>
			</div>
		</section>
	</div>
	<div class="intro-y box p-5 mt-5">
		<div class="flex justify-center">
			<RadioGroup v-model="tab" class="mt-2">
				<RadioGroupLabel class="sr-only">
					Elige un tipo de Reporte
				</RadioGroupLabel>
				<div class="grid grid-cols-1 gap-3 sm:grid-cols-6">
					<RadioGroupOption
						as="template"
						v-for="option in tabOptions"
						:key="option.name"
						:value="option.value"
						v-slot="{ checked }"
					>
						<div
							:class="[
								checked
									? 'bg-primary border-transparent text-white hover:bg-primary/90'
									: 'border-slate-200 hover:bg-slate-50',
								'btn py-2 px-2 flex items-center justify-center text-sm font-medium uppercase sm:flex-1',
							]"
						>
							<RadioGroupLabel as="span">
								{{ option.name }}
							</RadioGroupLabel>
						</div>
					</RadioGroupOption>
				</div>
			</RadioGroup>
		</div>
		<div class="mt-6">
			<DataTable :headers="headers" :items="items" hide-footer>
				<template #header-actions="{ text }">
					<div class="flex justify-end" v-html="text" />
				</template>
				<template #item-actions="item">
					<div class="flex justify-end gap-x-4">
						<template
							v-if="item.pdf && is_role('direccion') == false"
						>
							<button
								v-if="is_role('direccion')"
								class="btn"
								@click="(_$event) => onExport(item.type, 'pdf')"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="32"
									height="32"
									viewBox="0 0 256 256"
								>
									<path
										fill="currentColor"
										d="M48 136a8 8 0 0 0 8-8V40h88v48a8 8 0 0 0 8 8h48v32a8 8 0 0 0 16 0V88a7.7 7.7 0 0 0-2.4-5.7l-55.9-56A8.1 8.1 0 0 0 152 24H56a16 16 0 0 0-16 16v88a8 8 0 0 0 8 8Zm112-84.7L188.7 80H160ZM64 160H48a8 8 0 0 0-8 8v48a8 8 0 0 0 16 0v-8h8a24 24 0 0 0 0-48Zm0 32h-8v-16h8a8 8 0 0 1 0 16Zm132-16v12h16a8 8 0 0 1 0 16h-16v12a8 8 0 0 1-16 0v-48a8 8 0 0 1 8-8h28a8 8 0 0 1 0 16Zm-68-16h-14a8 8 0 0 0-8 8v48a8 8 0 0 0 8 8h14a32 32 0 0 0 0-64Zm0 48h-6v-32h6a16 16 0 0 1 0 32Z"
									/>
								</svg>
								<span class="ml-2"> Pdf </span>
							</button>
						</template>
						<template v-if="item.zip">
							<button
								class="btn"
								@click="(_$event) => onExport(item.type, 'zip')"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="32"
									height="32"
									viewBox="0 0 100 100"
								>
									<path
										fill="currentColor"
										d="M89.148 32.927c.001-.037.011-.07.011-.107a3.972 3.972 0 0 0-1.016-2.642l.02-.011l-7.87-13.627a2.53 2.53 0 0 0-2.468-1.914c-.083 0-.161.016-.242.024v-.024H22.219v.004c-.013 0-.026-.004-.039-.004a2.42 2.42 0 0 0-2.17 1.315l-.008-.005l-8.212 14.231l.015.008a4.068 4.068 0 0 0-.963 2.642c0 .047.012.091.014.138v48.211c-.002.048-.014.093-.014.142c0 2.284 1.817 4.069 4.095 4.066c.043 0 .083-.011.125-.012h69.87c.043.001.083.012.126.012c2.283 0 4.1-1.782 4.1-4.062c0-.036-.01-.068-.011-.104V32.927zM63.413 57.492l-12.391 17.43c-.226.318-.59.505-.98.507h-.004c-.386 0-.751-.187-.977-.503L36.59 57.494a1.201 1.201 0 0 1-.091-1.251c.208-.401.62-.654 1.071-.654h5.833l.001-15.654c0-.667.538-1.205 1.203-1.205h10.789c.665 0 1.204.539 1.204 1.204v15.655h5.83a1.206 1.206 0 0 1 .983 1.903zM18.376 28.733l5.263-9.119h52.67l5.266 9.119H18.376z"
									/>
								</svg>
								<span class="ml-2"> Zip </span>
							</button>
						</template>
						<template v-if="item.excel">
							<button
								class="btn"
								@click="
									(_$event) => onExport(item.type, 'excel')
								"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="32"
									height="32"
									viewBox="0 0 256 256"
								>
									<path
										fill="currentColor"
										d="M200 24H72a16 16 0 0 0-16 16v24H40a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h16v24a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16V40a16 16 0 0 0-16-16Zm-40 80h40v48h-40Zm40-16h-40v-8a16 16 0 0 0-16-16V40h56ZM72 40h56v24H72ZM40 80h104v96H40Zm32 112h56v24H72Zm72 24v-24a16 16 0 0 0 16-16v-8h40v48Zm-76.4-68.8L82 128l-14.4-19.2a8 8 0 1 1 12.8-9.6L92 114.7l11.6-15.5a8 8 0 0 1 12.8 9.6L102 128l14.4 19.2a8 8 0 0 1-1.6 11.2a7.7 7.7 0 0 1-4.8 1.6a8 8 0 0 1-6.4-3.2L92 141.3l-11.6 15.5A8 8 0 0 1 74 160a7.7 7.7 0 0 1-4.8-1.6a8 8 0 0 1-1.6-11.2Z"
									/>
								</svg>
								<span class="ml-2"> Excel </span>
							</button>
						</template>
						<template v-if="item.download">
							<button
								class="btn"
								@click="
									(_$event) => onExport(item.type, 'generator')
								"
							>
							<svg width="32" height="32" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15.037 38.3705C15.3808 38.0266 15.8471 37.8335 16.3333 37.8335H25.6667C26.1529 37.8335 26.6192 38.0266 26.963 38.3705C27.3068 38.7143 27.5 39.1806 27.5 39.6668C27.5 40.1531 27.3068 40.6194 26.963 40.9632C26.6192 41.307 26.1529 41.5002 25.6667 41.5002H16.3333C15.8471 41.5002 15.3808 41.307 15.037 40.9632C14.6932 40.6194 14.5 40.1531 14.5 39.6668C14.5 39.1806 14.6932 38.7143 15.037 38.3705Z" fill="#363B64" stroke="#363B64"/>
								<path d="M31.5216 9.82736L31.7576 10.095L32.0874 9.95886C32.2737 9.88197 32.4724 9.83945 32.6738 9.83333H46.6667C47.1529 9.83333 47.6192 10.0265 47.963 10.3703C48.3068 10.7141 48.5 11.1804 48.5 11.6667V46.6667C48.5 47.1529 48.3068 47.6192 47.963 47.963C47.6192 48.3068 47.1529 48.5 46.6667 48.5H39.6667C39.1804 48.5 38.7141 48.3068 38.3703 47.963C38.0265 47.6192 37.8333 47.1529 37.8333 46.6667C37.8333 46.1804 38.0265 45.7141 38.3703 45.3703C38.7141 45.0265 39.1804 44.8333 39.6667 44.8333H44.3333H44.8333V44.3333V21V20.5H44.3333H11.6667H11.1667V21V44.3333V44.8333H11.6667H30.3333C30.8196 44.8333 31.2859 45.0265 31.6297 45.3703C31.9735 45.7141 32.1667 46.1804 32.1667 46.6667C32.1667 47.1529 31.9735 47.6192 31.6297 47.963C31.2859 48.3068 30.8196 48.5 30.3333 48.5H9.33333C8.8471 48.5 8.38079 48.3068 8.03697 47.963C7.69315 47.6192 7.5 47.1529 7.5 46.6667V9.33333C7.5 8.8471 7.69315 8.38079 8.03697 8.03697C8.38079 7.69315 8.8471 7.5 9.33333 7.5H26.3666C27.3418 7.50004 28.3058 7.70759 29.1946 8.10886C30.0834 8.51013 30.8767 9.09594 31.5216 9.82736ZM11.1667 16.3333V16.8333H11.6667H29.68H30.3218L30.1648 16.211L29.5115 13.621L29.5113 13.6205C29.3339 12.9207 28.9288 12.2998 28.3598 11.8556C27.7908 11.4114 27.0902 11.169 26.3683 11.1667H26.3667H11.6667H11.1667V11.6667V16.3333ZM34.0016 16.4546L34.0963 16.8333H34.4867H44.3333H44.8333V16.3333V14V13.5H44.3333H33.9033H33.2629L33.4183 14.1213L34.0016 16.4546Z" fill="#363B64" stroke="#363B64"/>
							</svg>
							</button>
						</template>
					</div>
				</template>
			</DataTable>
		</div>
	</div>
</template>
