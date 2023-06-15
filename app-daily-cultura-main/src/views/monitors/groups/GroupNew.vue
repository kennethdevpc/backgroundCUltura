<script setup>
import { ref, onMounted, reactive, watch, computed } from "vue";
import services from "../../../services/dropDowns.service";

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';
import { required } from '../../../utils/validations'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";

// Store
import { useDropDowns } from '../../../stores/drop-downs';
import { storeToRefs } from "pinia";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const route = useRoute()

// Extracting Store Data
const drop_downs = useDropDowns()
const { form, form_rules } = storeToRefs(drop_downs)

// Extracting Select Data
const groups = services()

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true })


// Watch General Data for Validate in Store
watch(form.value, async () => {
	const valid = await v$.value.$validate()

	if (valid)
		drop_downs.toggleGeneralData(true)
	else
		drop_downs.toggleGeneralData(false)
})

const fetchOne = async () => {
	return await groups.getOne('groups', route.params.id)
}

const isEditing = computed(() => {
	if (route.params.id)
		return true
	else
		return false
})

onMounted(async () => {
	if (route.params.id) {
		await fetchOne().catch(() => {
			mixins.not_found_by_id()
		})
		const { id, created_at, updated_at, ...rest } = groups.dataOne.value
		form.value = { ...rest }
	} else {
		drop_downs.$reset()
	}
})

const onSubmit = async () => {
	const valid = v$.value.$validate()
	if (valid) {
		if (isEditing.value)
			await groups.update('groups', groups.dataOne.value.id, drop_downs.transpiledData, 'groups')
		else
			await groups.create('groups', drop_downs.transpiledData, 'groups')
	}
	else {
		alerts.validation()
	}
}
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
		<BaseBackButton />
		<h2 v-if="isEditing" class="text-lg font-medium mr-auto">
			Edición de grupo: <b>{{ groups.dataOne.value.id }}</b>
		</h2>
		<h2 v-else class="text-lg font-medium mr-auto">
			Ingresar grupo
		</h2>
	</div>
	<div class="intro-y box mt-5">
		<form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
			<section id="GeneralData"
				class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8">
				<div class="w-full">
					<BaseInput type="text" label="NOMBRE *" tooltip="Ingrese el nombre deL grupo" placeholder="Grupo..."
						name="name" v-model="form.name" :validator="v$" />
				</div>
			</section>
			<div class="flex justify-center">
				<button type="submit" class="btn btn-primary w-24 ml-2">
					{{ groups.dataOne.value.id ? 'Actualizar':'Ingresar' }}
				</button>
			</div>
		</form>
	</div>
</template>