<script setup>
import { ref, onMounted, reactive, watch, computed } from "vue";
import services from "../../../services/neighborhood.service";
import servicesSelects from "@/services/selects.service"

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';
import { required } from '../../../utils/validations'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import BaseSelect from '@/components/base/Select.vue'

// Store
import { neighborhoodsStore } from '../../../stores/neighborhoods.ts';
import { storeToRefs } from "pinia";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const route = useRoute()

// Extracting Store Data
const neighborhoodStore = neighborhoodsStore()
const { form, form_rules } = storeToRefs(neighborhoodStore)

// Extracting Select Data
const neighborhoods = services()
const select_service = servicesSelects()

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true })

// Watch General Data for Validate in Store
watch(form.value, async () => {
	const valid = await v$.value.$validate()

	if (valid)
		neighborhoodStore.toggleGeneralData(true)
	else
		neighborhoodStore.toggleGeneralData(false)
})

const instance = reactive({
	nacs: []
})

const fetchOne = async () => {
	return await neighborhoods.getOne(route.params.id)
}

const editing = computed(() => {
	return (route.params.id) ? true : false
})

onMounted(async () => {

	await select_service.getNacs().then(() => {
        instance.nacs = select_service.nacs.all
    })

	if (editing.value) {
		await fetchOne().catch(() => {
			mixins.not_found_by_id()
		})
		const { id, created_at, ...rest } = neighborhoods.data.one
		form.value = { ...rest }
	} else {
		neighborhoodStore.$reset()
	}
})

const onSubmit = async () => {
	const valid = v$.value.$validate()
	if (valid){
		if (editing.value)
			await neighborhoods.update(neighborhoods.data.one.id, neighborhoodStore.transpiledData)
		else
			await neighborhoods.create(neighborhoodStore.transpiledData).then(async (response) => {
				if (response.data.success) {
					neighborhoodStore.$reset()
					v$.value.$reset()
				}
			})
	}
	else {
		alerts.validation()
	}

}
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
		<BaseBackButton />
		<h2 v-if="editing" class="text-lg font-medium mr-auto">
			Edición de barrio: <b>{{ neighborhoods.data.one.id }}</b>
		</h2>
		<h2 v-else class="text-lg font-medium mr-auto">
			Ingresar barrio
		</h2>
	</div>
	<div class="intro-y box mt-5">
		<form @submit.prevent="onSubmit"
			class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
			<section id="GeneralData"
				class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8">
				<div class="w-full">
					<BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
						v-model="form.nac_id" :options="instance.nacs" :validator="v$" />
				</div>
				<div class="w-full">
					<BaseInput type="text" label="NOMBRE *" tooltip="Ingrese el nombre del barrio"
						placeholder="Barrio..." name="name" v-model="form.name" :validator="v$" />
				</div>
			</section>
			<div class="flex justify-center">
				<button type="submit" class="btn btn-primary w-24 ml-2">
					Ingresar
				</button>
			</div>
		</form>
	</div>
</template>