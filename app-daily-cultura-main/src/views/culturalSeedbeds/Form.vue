<script setup lang="ts">
import { loading } from '@/utils/loading';
import { scroll_top } from "@/utils/scroll";
import mixins from '@/mixins';
import permissions from '@/permissions'
import ServiceGlobal from '@/services/generals.service'
import Aggregates from "@/components/base/Aggregates.vue"
import servicesSeedbeds from "@/services/culturalSeedbeds.service";

import pecsService from "@/services/pecs.service";

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import BaseManagement from "@/components/base/Management.vue";

import Swal from "sweetalert2";

// Store
import { seedBeds } from '@/stores/seed_beds';
import { storeToRefs } from "pinia";
import { useSelectStore } from "@/stores/selects"
import dayjs from 'dayjs';
import es from "dayjs/locale/es";
import localeData from "dayjs/plugin/localeData";
import useMethodologicalSheetsOne from "@/services/methodologicalSheetsOne.service";
import router from '@/router';
import FormHeader from '@/components/base/FormHeader.vue';
import useApi from '@/utils/useApi'
const { find } = useApi()

dayjs.locale(es);
dayjs.extend(localeData);
// Extracting Store Data
const store = seedBeds();
const { form, form_rules, filesOne } = storeToRefs(store)
const { is_role } = mixins.computed
const methodologicalSheetsOne = useMethodologicalSheetsOne();
// Extracting Select Data
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);

// Extracting Services
const services = servicesSeedbeds()
const serviceGlobal = ServiceGlobal()
const route = useRoute()

const listOfMethologicalSheetOne = ref([]);
const id = route.params.id;
const groups = ref([]);
const disabledButton = ref(false);
const files = reactive({
	I: [],
	II: [],
});

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

const instance = ref({
	status: '',
	reject_message: ''
})
// Images - First Input
const handleAddFirstFile = (err, val) => {
	if (err) return
	const { file, filename } = val
	form.value.development_activity_image = file
}
const handleRemoveFirstFile = () => {
	form.value.development_activity_image = null
	// files.value = [];
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
	if (err) return
	const { file, filename } = val
	form.value.evidence_participation_image = file
}
const handleRemoveSecondFile = () => {
	form.value.evidence_participation_image = null
}

const getallMethologicalSheetOne = async () => {
	let { data } = await methodologicalSheetsOne.get()

	data.items.map((i: any) => {
		listOfMethologicalSheetOne.value.push({ label: `${i.consecutive}${i.id} | ${i.date_ini} al ${i.date_fin}`, value: i.id })
	});
}
const createdBy = ref('');
onBeforeMount(async () => {
	store.$reset();
	await getallMethologicalSheetOne();
	groups.value = options.value.group_beneficiaries;
	if (id) {
		const { data: { items: data } } = await services.getOne(id);
		const { datasheet_planning, beneficiaries, created_by, ...rest } = data;
		createdBy.value = created_by?.id;
		const buildBene = data?.beneficiaries?.map((item) => ({ full_name: item.full_name, id: item.id, nuip: item.document_number })) || [];
		filesOne.value.development_activity_image = rest.development_activity_image;
		filesOne.value.evidence_participation_image = rest.evidence_participation_image;

		form.value = {
			...rest,
			datasheet_planning_id: datasheet_planning?.id
		};
		if (is_role('lider_instructor') || is_role('apoyo_seguimiento_monitoreo')) {
			const response = await serviceGlobal.getGroupsCreatedBy(data?.created_by?.id);
			groups.value = response.data;
		}
		instance.value.reject_message = data?.reject_message;
		instance.value.status = data?.status;
		form.value.aggregates = {
			beneficiaries: buildBene
		};

	} else {
		await getConsecutivo()
	}
});
//Funcionalidad de consecutivo a implementar---

const getConsecutivo = async () => {
	let { data } = await serviceGlobal.getConsecutive("cultural_seedbeds", "BCC");
	form.value.consecutive = data
}

// Submiting Form
const onSubmit = async () => {
	const valid = await v$.value.$validate()
	if (valid) {
		disabledButton.value = true;
		const { ...rest } = form.value;
		const data = {
			...rest,
			aggregates: JSON.stringify(form.value.aggregates.beneficiaries.map((item) => item.id)),
		};
		if (id) {
			await services.update(id, data).then(async (response) => {
				router.push({ name: "culturalSeedbeds.index" });
			}).finally(() => {
                    disabledButton.value = false
                })
		} else {
			await getConsecutivo().finally(() => {
				data.datasheet_planning_id = form.value.datasheet_planning_id;
				services.create(data).then(async (response) => {
					await store.clear();
					store.$reset();
					form.value.aggregates.beneficiaries = [];
					v$.value.$reset()
					await getConsecutivo();
					scroll_top();
					files.I = [];
					files.II = [];
				}).finally(() => {
                    disabledButton.value = false
                })
			})
		}
	} else {
		Swal.fire('Validación', 'Por favor valide los campos solicitados.', 'error')
	}
}


function formatTime(time) {
	const [hours, minutes] = time.split(':');
	const suffix = hours >= 12 ? 'PM' : 'AM';
	const formattedHours = hours % 12 || 12;
	return `${formattedHours}:${minutes} ${suffix}`;
}

const pecs_options = asyncComputed(async () => {
	const response = await pecsService().byActivityDate()
	const { items } = response.data

	return items.map((pec) => ({
		value: pec.id,
		label: `${pec.consecutive} - ${dayjs(pec.activity_date).format("DD/MM/YYYY")} - ${formatTime(pec.start_time)} - ${formatTime(pec.final_hour)}`,
	}));

}, null)

const beneficiaries_headers = [
	{
		text: 'ID',
		value: 'id',
	},
	{
		text: 'NOMBRE',
		value: 'full_name',
	},
	{
		text: 'CÉDULA',
		value: 'nuip',
	}
];

const beneficiaries_by_group = ref([])
const get_beneficiaries = async (id) => {

	await serviceGlobal.getGroupBeneficiaries(id, createdBy.value).then((response) => {
		if (response != undefined) {
			let beneficiaries = response.data.items[0].beneficiaries
			beneficiaries_by_group.value = beneficiaries

			store.$patch((state) => {
				state.form.aggregates.beneficiaries = beneficiaries;
			})
		}
	})
}

const get_group = computed(() => {
	return form.value.group_id
})

watch(get_group, async (new_val, old_val) => {
	if (new_val != '' && new_val != null) {
		if (new_val != old_val) {
			await get_beneficiaries(new_val)
		}
	}
})

/**
 * Watch the changes form date_range input and obtains his month in long format
 * --> put in the token value Ficha ${split of consecutive obtaining her number} - the month
 */
if (is_role('instructor') || is_role('super.root') || is_role('root')) {
	watch(computed(() => form.value.date), async (newQuestion, oldQuestion) => {


		if (newQuestion != null && oldQuestion != "") {
			if (form.value.date) {
				form.value.datasheet = 'Cargando...'
				const count = await find(`getDataSheet/cultural_seedbeds/${form.value.date}/date`).then((response) => response.data)
				const month = dayjs(form.value.date).format("MMMM");
				const dataSheet = computed(() => (`Circulación ${count} - ${month}`))
				form.value.datasheet = dataSheet.value
			}
		}
		if (!id) {
			form.value.datasheet = 'Cargando...'
			const count = await find(`getDataSheet/cultural_seedbeds/${form.value.date}/date`).then((response) => response.data)
			const month = dayjs(form.value.date).format("MMMM");
			const dataSheet = computed(() => (`Circulación ${count} - ${month}`))
			form.value.datasheet = dataSheet.value
		}

		/**	if (newQuestion != null && oldQuestion != null) {
				if (form.value.date) {
					form.value.datasheet = 'Cargando...'
					const count = await find(`getDataSheet/cultural_seedbeds/${form.value.date}/date`).then((response) => response.data)
					const month = dayjs(form.value.date).format("MMMM");
					const dataSheet = computed(() => (`Semillero ${count} - ${month}`))
					form.value.datasheet = dataSheet.value
				}
			}
			if ((newQuestion != oldQuestion) && oldQuestion != null) {
				if (form.value.date) {
					form.value.datasheet = 'Cargando...'
					const count = await find(`getDataSheet/cultural_seedbeds/${form.value.date}/date`).then((response) => response.data)
					const month = dayjs(form.value.date).format("MMMM");
					const dataSheet = computed(() => (`Semillero ${count} - ${month}`))
					form.value.datasheet = dataSheet.value
				}
			}
		*/
	})
}

</script>

<template>
	<FormHeader
		:state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
		{{ id ? `EDICIÓN BITÁCORA SEMILLERO CULTURAL #${id}` : "BITÁCORA SEMILLERO CULTURAL" }}
	</FormHeader>

	<BaseManagement v-if="id ? permissions.culturalSeddbeds.management() : false" @send="
		(payload) =>
			mixins.methods.send_management(
				'cultural_seedbeds',
				id,
				payload
			)
	" class="mt-5" />

	<div class="intro-y box mt-5">
		<fieldset :disabled="permissions.culturalSeddbeds.no_edit()">
			<form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 space-y-8">
				<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly">
					<!-- <div class="w-full"> -->
					<BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha" name="date"
						v-model="form.date" :validator="v$" />
					<BaseInput name="datasheet" type="text" label="FICHA NO. *" disabled :validator="v$"
						v-model="form.datasheet" />
					<!-- </div> -->
				</section>
				<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly">
					<div class="w-full">
						<BaseSelect label="FILTRO PEC *" tooltip="Filtro pec *" placeholder="Seleccione" name="pec_id"
							v-model="form.pec_id" :options="pecs_options" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect label="FICHAS DE PLANEACION *" tooltip="Fichas de planeacion *" placeholder="Seleccione"
							name="datasheet_planning_id" v-model="form.datasheet_planning_id"
							:options="listOfMethologicalSheetOne" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect label="NIVEL DE DOMINIO DEL SEMILLERO *" tooltip="Nivel de dominio *"
							placeholder="Seleccione" name="filter_level" v-model="form.filter_level"
							:options="options.filter_level" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="number" label="CANTIDAD DE INTEGRANTES DEL SEMILLERO *"
							tooltip="Cantidad de integrantes " placeholder="Ingrese" name="quantity_members"
							v-model="form.quantity_members" :validator="v$" />
					</div>
				</section>
				<section class="grid grid-cols-1 gap-6 justify-evenly">
					<div class="w-full">
						<BaseTextarea label="DESCRIPCION NIVEL DE DOMINIO *" tooltip="Ingrese el nivel de dominio"
							placeholder="Ingrese" name="level_domain_description" v-model="form.level_domain_description"
							:validator="v$" rows="5" />
					</div>

					<div class="w-full">
						<BaseTextarea label="OBJETIVO VIVENCIAL DE LA JORNADA *" tooltip="Ingrese el objetivo"
							placeholder="Ingrese" name="objective_process" v-model="form.objective_process" :validator="v$"
							rows="5" />
					</div>
					<div class="intro-y flex justify-between items-start md:items-end mt-5">
						<div class="intro-y flex flex-col items-start gap-1">
							<h2 class="text-lg font-medium mr-auto">
								COMPONENTE METADOLOGICO
							</h2>
						</div>
					</div>
					<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly">
						<div class="w-full mt-5">
							<BaseSelect label="DERECHO CULTURAL *" tooltip="Selecciona el derecho cultural"
								placeholder="Seleccione" name="cultural_right_id" v-model="form.cultural_right_id"
								:options="options.cultural_rights" :validator="v$" />
						</div>
						<div class="w-full mt-5">
							<BaseSelect label="LINEAMIENTO *" tooltip="Selecciona eL lineamiento" placeholder="Seleccione"
								name="lineament_id" v-model="form.lineament_id" :options="options.lineaments"
								:validator="v$" />
						</div>
						<div class="w-full mt-5">
							<BaseSelect label="ORIENTACIONES *" tooltip="Seleccione la orientacion" placeholder="Seleccione"
								name="orientation_id" v-model="form.orientation_id" :options="options.orientations"
								:validator="v$" />
						</div>
						<div class="w-full mt-5">
							<BaseSelect label="VALOR *" tooltip="Selecciona el valor" placeholder="Seleccione" name="values"
								v-model="form.values" :options="options.values" :validator="v$" />
						</div>
						<div class="w-full">
							<BaseTextarea label="EXPERTICIA ARTÍSTICA A TRABAJAR:*" tooltip="Ingrese la experticia"
								placeholder="Ingrese" name="artistic_expertise" v-model="form.artistic_expertise"
								:validator="v$" rows="5" />
						</div>

						<div class="w-full">
							<BaseTextarea label="OBSERVACIONES DE LA REALIZACIÓN:*" tooltip="Ingrese la observación"
								placeholder="Ingrese" name="observations" v-model="form.observations" :validator="v$"
								rows="5" />
						</div>
					</section>

				</section>
				<section class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
					<BaseFilepond label="FOTO DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen"
						name="development_activity_image" ref="development_activity_image_ref"
						:to_edit="filesOne.development_activity_image" v-model="form.development_activity_image"
						@addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="files.I"
						:validator="v$" />

					<BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen"
						name="evidence_participation_image" ref="evidence_participation_image_ref"
						:to_edit="filesOne.evidence_participation_image" v-model="form.evidence_participation_image"
						@addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile" :files="files.II"
						:validator="v$" />
				</section>
				<div class="w-full">
					<BaseSelect label="GRUPO *" tooltip="Selecciona un grupo" placeholder="Seleccione" name="group_id"
						v-model="form.group_id" :options="groups" :validator="v$" />
					<!-- @select="get_beneficiaries(form.group_id)" -->
				</div>
				<section class="flex flex-col justify-start mb-8">
					<div>
						<h3 class="intro-y form-label font-bold uppercase">
							<span>
								Asistentes Agregados
							</span>
							<span>
								# {{ form.aggregates.beneficiaries.length }}
							</span>
						</h3>
					</div>
					<Aggregates @pop="(id) => store.pop_aggregate(id)"
						@push="(aggregate) => store.push_aggregate(aggregate)"
						:options="options.beneficiaries_table || null" :headers="beneficiaries_headers"
						:aggregates="form.aggregates.beneficiaries" :validator="v$" name="aggregates">
					</Aggregates>
				</section>
				<div class="flex justify-center" v-if="!permissions.culturalSeddbeds.no_edit()">
					<button :disabled="disabledButton" type="submit" class="btn btn-primary w-24 ml-2">
						{{ id ? "Actualizar" : "Ingresar" }}
					</button>
				</div>
			</form>
		</fieldset>
	</div>
</template>