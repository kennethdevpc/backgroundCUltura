<script setup>
	import { ref, onMounted, reactive, watch, computed } from "vue";
    import services from '../../../services/module.service'
    import { useRouter } from 'vue-router';
	// Importing Vuelidate & Rules
	import { useVuelidate } from '@vuelidate/core';
   
	// Importing Components
	import BaseInput from '@/components/base/Input.vue'
	import BaseRadio from '@/components/base/Radio.vue'
	import BaseTextarea from '@/components/base/Textarea.vue'
    import BaseBackButton from "../../../components/base/BaseBackButton.vue";

	// Store
    import { useModules } from '../../../stores/module'
	import { storeToRefs } from "pinia";
    import mixins from "@/mixins";
    const module = services()
    const props = defineProps(['id'])
	// Extracting Store Data
    const modules = useModules()
	const { form, form_rules, form_options } = storeToRefs(modules)


	// Using Vuelidate (with Store form & form)
	const v$ = useVuelidate(form_rules, form)
    const router = useRouter()
    // Routes
	const route = useRoute()
	// Watch General Data for Validate in Store
	watch(form.value, async () => {
		const valid = await v$.value.$validate()
		if (valid)
            modules.toggleGeneralData(true)
		else
            modules.toggleGeneralData(false)
	})
	const fetchOne = async () => {
		return await module.getOne(props.id)
	}
	const isEditing = computed(() => {
		if (route.params.id)
			return true
		else
			return false
	})
    onMounted(async () => {
        const { id, created_at, updated_at, ...rest } = module.dataOne.value
        if(route.params.id){
            await fetchOne().catch(() => {
                mixins.not_found_by_id()
            })
            const { id, created_at, updated_at, ...rest } = module.dataOne.value
			form.value = { ...rest }
        }else{
            modules.$reset()
        }    
    })
	const onSubmit = async () => {

        if (isEditing.value)
			await module.update(module.dataOne.value.id, form.value)
		else
			await module.create(form.value)
	}
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
        <BaseBackButton />
		<h2 class="text-lg font-medium mr-auto">
			{{props.id ? 'Editar':'Crear'}} modulo
		</h2>
	</div>

	<div class="intro-y box mt-5">
		<form @submit.prevent="onSubmit" class="px-5 sm:px-10 py-10 border-t border-slate-200/60 dark:border-darkmode-400">
			<section id="generalData" class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
				<div class="w-full">
					<BaseInput
                        type="text"
                        label="Nombre *"
                        tooltip="Ingrese el nombre"
                        placeholder="Nombre"
                        name="name"
                        v-model="form.name"
                        :validator="v$"
                    />
				</div>
                <div class="w-full">
					<BaseInput
                        type="text"
                        label="Slug *"
                        tooltip="Ingrese el slug"
                        placeholder="Slug"
                        name="slug"
                        v-model="form.slug"
                        :validator="v$"
                    />
				</div>
                <div class="w-full lg:col-span-2 xl:col-span-2">
                    <BaseTextarea
                        label="Descripcion *"
                        tooltip="Ingrese la descripcion"
                        placeholder="Descripcion"
                        name="description"
                        v-model="form.description"
                        :validator="v$"
                        rows="3"
                    />
                </div>
                <div class="w-full">
					<BaseInput
                        type="text"
                        label="Icon"
                        tooltip="Ingrese el icon"
                        placeholder="icon"
                        name="icon"
                        v-model="form.icon"
                        :validator="v$"
                    />
				</div>
                <div class="w-full">
                    <BaseRadio
                        label="Disponible?"
                        tooltip="Disponible?"
                        name="available"
                        v-model="form.available"
                        :options="form_options.decisions"
                        :validator="v$"
                    />
                </div>
                <div class="w-full">
                    <BaseRadio
                    label="Tiene artículos?"
                    tooltip="Tiene artículos?"
                    name="hasItems"
                    v-model="form.hasItems"
                    :options="form_options.decisions"
                    :validator="v$"
                />
                </div>
                <div class="w-full">
                    <BaseInput
                    type="number"
                    label="Posición"
                    tooltip="Posición"
                    placeholder="1"
                    name="position"
                    v-model="form.position"
                    :validator="v$"
                />
                </div>
			</section>
			<div class="flex justify-center">
				<button :disabled="(!modules.isGeneralData && !isEditing  && modules.loading)" type="submit" class="btn btn-primary w-24 ml-2">
					Ingresar
				</button>
			</div>
		</form>
	</div>
</template>