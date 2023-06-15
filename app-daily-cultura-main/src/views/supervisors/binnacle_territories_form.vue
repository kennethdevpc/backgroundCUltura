<script setup lang="ts">
import { loading } from '@/utils/loading';
import { scroll_top } from "@/utils/scroll";
import mixins from '@/mixins';
import permissions from '@/permissions'
import servicesConsecutive from '@/services/generals.service'
import servicesGeneralSelects from '@/services/general_selects.service'
import binnacleTerritoriesService from "@/services/binnacle_territories.service"

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Management from '@/components/base/Management.vue'
import alerts from '@/utils/alerts';

// Store
import { binnacleTerritories } from '@/stores/binnacle_territories';
import { useOnboardingStore } from '@/stores/onboarding';
import { storeToRefs } from "pinia";
import { useSelectStore } from "@/stores/selects"
import { Select } from '@/utils/useSelect';
import FormHeader from '@/components/base/FormHeader.vue';

// Extracting Store Data
const binnacle_store = binnacleTerritories()
const { form, form_rules } = storeToRefs(binnacle_store)
const { get_user } = useOnboardingStore();
const { is_role } = mixins.computed

// Extracting Select Data
const select_store = useSelectStore()
const { options: lists } = storeToRefs(select_store)

// Extracting Services
const services = binnacleTerritoriesService()
const consecutive_services = servicesConsecutive()
const disabledButton = ref(false);
// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

// Images
const files = reactive({
	I: [],
	II: [],
})

// Images - First Input
const handleAddFirstFile = (err, val) => {
	if (err) return
	const { file, filename } = val
	form.value.file_1 = file
}
const handleRemoveFirstFile = () => {
	form.value.file_1 = null
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
	if (err) return
	const { file, filename } = val
	form.value.file_2 = file
}
const handleRemoveSecondFile = () => {
	form.value.file_2 = null
}

const route = useRoute()

const instance = reactive({
	status: '',
	consecutive: '',
	reject_message: ''
})

const editing = computed(() => {
	return (route.params.id) ? true : false
})

const fetch_consecutive = async () => {
	return await consecutive_services.getConsecutive('binnacle_territories', 'BV').then(() => {
		form.value.consecutive = consecutive_services.data.value
		instance.consecutive = consecutive_services.data.value
	})
}

const fetch_binnacle = async () => {
	return await services.getOne(route.params.id)
}

const { data: other_selects, getRoles, getUsers } = servicesGeneralSelects

const { nac_id, role_id } = toRefs(form.value)

watch(nac_id, async (new_val, old_val) => {
	if (new_val != old_val) {
		await getRoles(new_val)
	}
})

watch(role_id, async (new_val, old_val) => {
	if (new_val != old_val) {
		await getUsers(new_val)
	}
})

onBeforeMount(async () => {
	if (editing.value) {
		await fetch_binnacle().catch(() => {
			mixins.not_found_by_id()
		})
		const { created_at, deleted_at, updated_at, status, reviewed_id, reject_message, nac_id, role_id, user_id, consecutive, development_activity_image, evidence_participation_image, ...rest_binnacle } = services.data.one

		await getRoles(nac_id)
		await getUsers(role_id)

		const isSubdirector = is_role('subdireccion');
		//console.log(isSubdirector)
		binnacle_store.$patch((state) => {
			Object.assign(state.form, {
				...rest_binnacle,
				file_1: development_activity_image,
				file_2: evidence_participation_image,
				nac_id,
				role_id,
				user_id,
				consecutive,
				status,
			})
		})

		Object.assign(instance, {
			status,
			reject_message,
			consecutive
		})
	}
	else {
		await fetch_consecutive()
	}
})

//console.log(form.value)

// Submiting Form
const onSubmit = async () => {
	const valid = await v$.value.$validate()

	if (valid) {
		disabledButton.value = true;
		if (editing.value) {
			await services.update(services.data.one.id, binnacle_store.form).finally(() => {
					disabledButton.value = false;
				});
		}
		else {
			await fetch_consecutive().finally(() => {
				services.create(binnacle_store.form).then(async (response) => {
					if (response.data.success) {
						files.I = []
						files.II = []
						binnacle_store.$reset()
						v$.value.$reset()
						scroll_top()
						await fetch_consecutive()
					}
				}).finally(() => {
					disabledButton.value = false;
				});
			})
		}
	}
	else {
		alerts.validation()
	}
}

const management = is_role("subdireccion") ? permissions.subdireccion.management() : permissions.direccion.management()
const no_edit = is_role("subdireccion") ? permissions.subdireccion.no_edit() : permissions.direccion.no_edit()
</script>

<template>
	<FormHeader
		:state="{ consecutive: instance.consecutive, status: instance.status, reject_message: instance.reject_message }">
		{{ (editing) ? `Edición de visita a territorio #${services.data.one.id}` : `Visita a territorio` }}
	</FormHeader>
	<Management class="mt-5" v-if="(editing) ? management : false"
		@send="(payload) => mixins.methods.send_management('binnacle_territories', services.data.one.id, payload)" />
	<div class="intro-y box mt-5">
		<fieldset :disabled="no_edit">
			<form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 space-y-8">
				<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly">
					<div class="w-full">
						<BaseSelect label="NAC *" tooltip="Seleccionar NAC" placeholder="Seleccione" name="nac_id"
							v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect :disabled="form.nac_id == ''" label="ROL *" tooltip="Seleccionar ROL"
							placeholder="Seleccione" name="role_id" v-model="form.role_id"
							:options="(other_selects.roles as Select[]) || []" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect :disabled="form.role_id == ''" label="NOMBRE *" tooltip="Seleccionar nombre"
							placeholder="Seleccione" name="user_id" v-model="form.user_id"
							:options="(other_selects.users as Select[]) || []" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha"
							name="activity_date" v-model="form.activity_date" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="time" label="HORA INICIO *" tooltip="Ingrese la hora de inicio"
							placeholder="Hora inicio" name="start_time" v-model="form.start_time" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="time" label="HORA FINAL *" tooltip="Ingrese la hora final" placeholder="Hora final"
							name="final_hour" :min="form.start_time" v-model="form.final_hour" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="text" label="LUGAR *" tooltip="Ingrese el nombre del lugar"
							placeholder="Nombre del lugar" name="place" v-model="form.place" :validator="v$" />
					</div>
					<!-- <div :class="form.place_type === 'O' ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'"
									class="w-full grid gap-6 lg:col-span-1 xl:col-span-2">
									<div class="w-full">
										<BaseSelect label="TIPO DE LUGAR *" tooltip="Ingrese el tipo de lugar"
											placeholder="Seleccione" name="place_type" v-model="form.place_type"
											:options="lists.place_types" :validator="v$" />
									</div>
									<div class="w-full intro-x" v-if="form.place_type === 'O'">
										<BaseInput type="text" label="ESCRIBA EL TIPO *" tooltip="Ingrese el tipo de lugar"
											placeholder="Tipo de lugar" name="other_place_type" v-model="form.other_place_type"
											:validator="v$" />
									</div>
								</div> -->
				</section>
				<section class="grid grid-cols-1 gap-6 justify-evenly">
					<h2 class="text-xl font-bold uppercase">
						Desarrollo de la Visita
					</h2>
					<BaseTextarea label="OBJETIVOS ESTRATÉGICOS DEL ÁREA *" tooltip="Ingrese los objetivos"
						placeholder="Objetivos" name="strategic_objectives_area" v-model="form.strategic_objectives_area"
						:validator="v$" rows="5" />
					<BaseTextarea label="PROPÓSITO DE LA VISITA *" tooltip="Ingrese el propósito" placeholder="Propósito"
						name="purpose_visit" v-model="form.purpose_visit" :validator="v$" rows="5" />
				</section>
				<section class="grid grid-cols-1 gap-6 justify-evenly">
					<h2 class="text-xl font-bold uppercase">
						Diagnóstico Inicial
					</h2>
					<BaseTextarea label="TEMÁTICAS ABORDADAS *" tooltip="Ingrese las temáticas abordadas"
						placeholder="Temáticas" name="topics_covered" v-model="form.topics_covered" :validator="v$"
						rows="5" />
					<BaseTextarea
						label="PERCEPCIÓN DE LOS PARTICIPANTES FRENTE A LAS ACTIVIDADES DESARROLLADAS POR EL ÁREA *"
						tooltip="Ingrese la percepción" placeholder="Percepción" name="participants_perception"
						v-model="form.participants_perception" :validator="v$" rows="5" />
					<BaseTextarea label="DIFICULTADES O PROBLEMÁTICAS IDENTIFICADAS *"
						tooltip="Ingrese las dificultades o problemáticas" placeholder="Dificultades o problemáticas"
						name="problems_identified" v-model="form.problems_identified" :validator="v$" rows="5" />
				</section>
				<section class="grid grid-cols-1 gap-6 justify-evenly">
					<BaseTextarea label="RECOMENDACIONES Y ACCIONES DE MEJORA PROPUESTAS POR LOS PARTICIPANTES *"
						tooltip="Ingrese las recomendaciones y acciones" placeholder="recomendaciones y acciones"
						name="recommendations_actions" v-model="form.recommendations_actions" :validator="v$" rows="5" />
					<BaseTextarea label="PERCEPCIONES/COMENTARIOS/ANÁLISIS FRENTE AL AVANCE DEL PROCESO *"
						tooltip="Ingrese percepciones/comentarios/análisis" placeholder="Percepciones/comentarios/análisis"
						name="comments_analysis" v-model="form.comments_analysis" :validator="v$" rows="5" />
				</section>
				<section class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
					<BaseFilepond label="DESARROLLO *" tooltip="Arrastra o selecciona una Imagen" name="file_1"
						ref="file_1ref" :to_edit="services.data.one.development_activity_image" v-model="form.file_1"
						@addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="files.I"
						:validator="v$" />
					<BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen"
						name="file_2" ref="file_2ref" :to_edit="services.data.one.evidence_participation_image"
						v-model="form.file_2" @addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile"
						:files="files.II" :validator="v$" />
				</section>
				<div class="flex justify-center">
					<button :disabled="disabledButton" type="submit" class="btn btn-primary w-24 ml-2">
						Ingresar
					</button>
				</div>
			</form>
		</fieldset>
	</div>
</template>