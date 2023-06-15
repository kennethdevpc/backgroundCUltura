<script setup>
// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";

// Store
import { usePermissions } from '../../../stores/permissions'
import { storeToRefs } from "pinia";

// Services
import services from '../../../services/permission.service'
import mixins from "@/mixins";
import alerts from '@/utils/alerts';

// Extracting Services
const permission_services = services()

// Extracting Store Data
const permission_store = usePermissions()
const { form, form_rules } = storeToRefs(permission_store)

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form)

// Routes
const route = useRoute()

const fetchOne = async () => {
	return await permission_services.getOne(route.params.id)
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
		const { id, created_at, updated_at, ...rest } = permission_services.dataOne.value
		form.value = { ...rest }
	} else {
		permission_store.$reset()
	}
})

const onSubmit = async () => {
	const valid = await v$.value.$validate()

	if (valid) {
		if (isEditing.value)
			await permission_services.update(permission_services.dataOne.value.id, form.value)
		else
			await permission_services.create(form.value)
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
			Edicion de Permiso: <b>{{ permission_services.dataOne.value.name }}</b>
		</h2>
		<h2 v-else class="text-lg font-medium mr-auto">
			Creacion de Permiso
		</h2>
	</div>
	<div class="intro-y box mt-5">
		<form @submit.prevent="onSubmit"
			class="px-5 sm:px-10 py-10 border-t border-slate-200/60 dark:border-darkmode-400">
			<section id="generalData"
				class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
				<div class="w-full">
					<BaseInput type="text" label="Nombre *" tooltip="Ingrese el nombre" placeholder="Nombre" name="name"
						v-model="form.name" :validator="v$" />
				</div>
				<div class="w-full">
					<BaseInput type="text" label="Slug *" tooltip="Ingrese el slug" placeholder="Slug" name="slug"
						v-model="form.slug" :validator="v$" />
				</div>
				<div class="w-full">
					<BaseInput type="text" label="Controlador *" tooltip="Ingrese el controlador"
						placeholder="Controlador" name="controller" v-model="form.controller" :validator="v$" />
				</div>
				<div class="w-full lg:col-span-2 xl:col-span-3">
					<BaseTextarea label="Descripcion *" tooltip="Ingrese la descripcion" placeholder="Descripcion"
						name="description" v-model="form.description" :validator="v$" rows="3" />
				</div>
			</section>
			<div class="flex justify-center">
				<button :disabled="permission_services.loading.value" type="submit" class="btn btn-primary w-24 ml-2">
					Ingresar
				</button>
			</div>
		</form>
	</div>
</template>