
<script setup>
import servicesConsecutive from '@/services/generals.service'
import services from "@/services/pecs.service"
import servicesBeneficiaries from "@/services/generals.service"
import permissions from '@/permissions'
import { scroll_top } from "@/utils/scroll";

// Importing service select form
import selectsForms from '@/services/selectsForms.service'

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from "@/components/base/Aggregates.vue"
import Management from '@/components/base/Management.vue'

// Store
import { usePEC } from '@/stores/pec';
import { storeToRefs } from "pinia";
import mixins from '@/mixins';
import { loading } from '@/utils/loading';
import alerts from '@/utils/alerts';
import FormHeader from '@/components/base/FormHeader.vue';
// Extracting Store Data
const pec_store = usePEC()
const { form, form_rules_computed: form_rules } = storeToRefs(pec_store)

// Servicio de selects
const selectForm_service = selectsForms();

// Extracting Services
const pec_services = services()
const consecutive_services = servicesConsecutive()
const general = servicesBeneficiaries()

// Importing Options Store
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
	form.value.place_image1 = {
		name: 'photo1',
		file,
		filename,
	}
}
const handleRemoveFirstFile = () => {
	form.value.place_image1 = null
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
	if (err) return
	const { file, filename } = val
	form.value.place_image2 = {
		name: 'photo2',
		file,
		filename,
	}
}
const handleRemoveSecondFile = () => {
	form.value.place_image2 = null
}

const route = useRoute()

const instance = reactive({
	status: '',
	reject_message: '',
	neighborhoods: [],
	placeTypes: [],
	nacs: [],
	beneficiariesTable: [],
	groupBeneficiaries: [],
	// groups:[]
})

const editing = computed(() => {
	return (route.params.id) ? true : false
})

const fetch_consecutive = async () => {
	return await consecutive_services.getConsecutive('pecs', 'PC').then(() => {
		form.value.consecutive = consecutive_services.data.value
	})
}

const fetch_pec = async () => {
	return await pec_services.getOne(route.params.id)
}

const beneficiaries_by_group = ref([])

const get_beneficiaries = async (id) => {
	if (!editing.value) {
		await general.getGroupBeneficiaries(id).then((response) => {
		if (response != undefined) {
			let beneficiaries = response.data.items[0].beneficiaries
			beneficiaries_by_group.value = beneficiaries

			pec_store.$patch((state) => {
				state.form.aggregates.beneficiaries = beneficiaries
				// Object.assign(state.form, {
				// 	aggregates: {
				// 		beneficiaries: beneficiaries
				// 	}
				// })
			})
		}
	})
	}
}

const get_nac = computed(() => {
	return form.value.nac_id
})

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

watch(() => form.value.nac_id, async () => {
	if (form.value.nac_id) {
		await selectForm_service.getOne('get_neighborhoods', form.value.nac_id).then(() => {
			instance.neighborhoods = selectForm_service.dataOne.all
		})
	}
})

onBeforeMount(async () => {

	await selectForm_service.get('get_place_types').then(() => {
        instance.placeTypes = selectForm_service.data.all
    })

	await selectForm_service.get('get_nacs').then(() => {
        instance.nacs = selectForm_service.data.all
    })

	await selectForm_service.get('get_group_beneficiaries').then(() => {
        instance.groupBeneficiaries = selectForm_service.data.all
    })

	await selectForm_service.get('get_beneficiaries_table').then(() => {
        instance.beneficiariesTable = selectForm_service.data.all
    })

	if (editing.value) {
		pec_store.$reset()
		v$.value.$reset()
		await fetch_pec().then(() => {
			const { created_at, id, nac, neighborhood, beneficiaries, status, reject_message, ...rest } = pec_services.data.one

			pec_store.$patch((state) => {
				Object.assign(state.form, {
					...rest,
					aggregates: {
						beneficiaries: beneficiaries.map(({ pivot, ...rest }) => {
							return {
								...rest
							}
						})
					}
				})
			})

			instance.status = status
			instance.reject_message = reject_message
		})
			.catch(() => {
				mixins.not_found_by_id()
			})
	}
	else {
		pec_store.$reset()
		v$.value.$reset()
		await fetch_consecutive()
		await get_beneficiaries(0)
	}
})

onUnmounted(() => {
	pec_store.$reset()
	v$.value.$reset()
})

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
]

// Submiting Form
const onSubmit = async () => {
	const valid = await v$.value.$validate()

	if (valid) {
		if (editing.value) {
			await pec_services.update(pec_services.data.one.id, pec_store.transpiled_data)
		}
		else {
			await fetch_consecutive().finally(() => {
				pec_services.create(pec_store.transpiled_data).then(async (response) => {
					if (response.data.success) {
						files.I = []
						files.II = []
						pec_store.$reset()
						v$.value.$reset()
						scroll_top()
						await fetch_consecutive()
					}
				})
			})
		}
	}
	else {
		alerts.validation()
	}
}

</script>

<template>
	<FormHeader
		:state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
		{{ (editing) ? `Edición de PEC #${pec_services.data.one.id}` : 'PEC' }}
	</FormHeader>
	<Management class="mt-5" v-if="(editing) ? permissions.pecs.management() : false"
		@send="(payload) => mixins.methods.send_management('pecs', pec_services.data.one.id, payload)" />
	<div class="intro-y box mt-5">
		<fieldset :disabled="permissions.pecs.no_edit()">
			<form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60">
				<section id="GeneralData"
					class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
					<div class="w-full">
						<BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
							v-model="form.nac_id" :options="instance.nacs" :validator="v$" />
					</div>
					<div :class="(form.neighborhood_id == 357) ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'"
						class="w-full grid gap-6">
						<div class="w-full">
							<BaseSelect label="BARRIO *" tooltip="Ingrese el barrio" placeholder="Seleccione"
								name="neighborhood_id" v-model="form.neighborhood_id" :options="instance.neighborhoods"
								:validator="v$" />
						</div>
						<div class="w-full intro-x" v-if="form.neighborhood_id == 357">
							<BaseInput type="text" label="ESCRIBA EL BARRIO *" tooltip="Ingrese el nombre del barrio"
								placeholder="Nombre del barrio" name="other_neighborhoods"
								v-model="form.other_neighborhoods" :validator="v$" />
						</div>
					</div>
					<div class="w-full">
						<BaseInput type="text" label="LUGAR *" tooltip="Ingrese el nombre del lugar"
							placeholder="Nombre del lugar" name="place" v-model="form.place" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseInput type="text" label="DIRECCIÓN DEL LUGAR *" tooltip="Ingrese la dirección del lugar"
							placeholder="Dirección del lugar" name="place_address" v-model="form.place_address"
							:validator="v$" />
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
					<div :class="form.place_type === 'O' ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'"
						class="w-full grid gap-6 lg:col-span-1 xl:col-span-2">
						<div class="w-full">
							<BaseSelect label="TIPO DE LUGAR *" tooltip="Ingrese el tipo de lugar" placeholder="Seleccione"
								name="place_type" v-model="form.place_type" :options="instance.placeTypes" :validator="v$" />
						</div>
						<div class="w-full intro-x" v-if="form.place_type === 'O'">
							<BaseInput type="text" label="ESCRIBA EL TIPO *" tooltip="Ingrese el tipo de lugar"
								placeholder="Tipo de lugar" name="other_place_type" v-model="form.other_place_type"
								:validator="v$" />
						</div>
					</div>
					<div class="col-span-2 xl:col-span-3">
						<BaseTextarea label="BREVE RESEÑA DEL LUGAR *" tooltip="Ingrese la descripción"
							placeholder="Descripción" name="place_description" v-model="form.place_description"
							:validator="v$" rows="5" />
					</div>
				</section>
				<section id="Images" class="mb-8">
					<div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
						<BaseFilepond label="FOTOGRAFÍA DEL LUGAR *" tooltip="Arrastra o selecciona una Imagen"
							name="place_image1" ref="place_image1_ref" :to_edit="pec_services.data.one.place_image1"
							v-model="form.place_image1" @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile"
							:files="files.I" :validator="v$" />
						<BaseFilepond label="FOTOGRAFÍA DEL LUGAR *" tooltip="Arrastra o selecciona una Imagen"
							name="place_image2" ref="place_image2_ref" :to_edit="pec_services.data.one.place_image2"
							v-model="form.place_image2" @addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile"
							:files="files.II" :validator="v$" />
					</div>
				</section>
				<div class="w-full">
					<BaseSelect label="GRUPO *" tooltip="Selecciona un grupo" placeholder="Seleccione" name="group_id"
						v-model="form.group_id" :options="instance.groupBeneficiaries" :validator="v$" />
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
					<Aggregates @pop="(id) => pec_store.pop_aggregate(id)"
						@push="(aggregate) => pec_store.push_aggregate(aggregate)"
						:options="instance.beneficiariesTable || null" :headers="beneficiaries_headers"
						:aggregates="form.aggregates.beneficiaries" :validator="v$" name="aggregates">
					</Aggregates>
				</section>
				<div class="flex justify-center">
					<button :disabled="loading" type="submit" class="btn btn-primary w-24 ml-2">
						Ingresar
					</button>
				</div>
			</form>
		</fieldset>
	</div>
</template>

<style scoped>
.table th {
	border-bottom: 0;
}

.dropdown-menu .dropdown-content {
	@apply pt-14 pb-1.5 px-1.5;
}
</style>