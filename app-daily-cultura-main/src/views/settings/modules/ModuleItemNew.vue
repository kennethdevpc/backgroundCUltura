<script setup>
	import { ref, onMounted, reactive, watch, computed } from "vue";
    import services from '../../../services/module_item.service'
    //router
    import { useRouter } from 'vue-router';

	// Importing Vuelidate & Rules
	import { useVuelidate } from '@vuelidate/core';

	// Importing Components
	import BaseInput from '@/components/base/Input.vue'
	import BaseRadio from '@/components/base/Radio.vue'
	import BaseTextarea from '@/components/base/Textarea.vue'
    import BaseBackButton from "../../../components/base/BaseBackButton.vue";
    import BaseSelect from '@/components/base/Select.vue'
	// Store
    import { useModuleItems } from '../../../stores/module_item'
	import { storeToRefs } from "pinia";

	// Extracting Store Data

    // Importing Options Store
    import { useSelectStore } from '@/stores/selects'
import mixins from "@/mixins";
    const select_store = useSelectStore()

    const { options } = storeToRefs(select_store)

    const moduleItems = useModuleItems()

    const services_item =  services()
	const { form, form_rules, form_options } = storeToRefs(moduleItems)
    //router
    const router = useRouter()
    const route = useRoute()
	// Using Vuelidate (with Store form & form)
	const v$ = useVuelidate(form_rules, form)
    const props = defineProps(['id'])
	// Watch General Data for Validate in Store
	watch(form.value, async () => {
		const valid = await v$.value.$validate()

		if (valid)
            moduleItems.toggleGeneralData(true)
		else
            moduleItems.toggleGeneralData(false)
	})

	const fetchOne = async () => {
		return await services_item.getOne(route.params.id)
	}
	const isEditing = computed(() => {
		if (route.params.id)
			return true
		else
			return false
	})

    onMounted(async () => {
        if(route.params.id){
            await fetchOne().catch(() => {
                mixins.not_found_by_id()
            })
            const { id, created_at, updated_at, ...rest } = services_item.dataOne.value
            form.value = { ...rest }
        }else{
            moduleItems.$reset()
        }   
    })

	const onSubmit = async () => {
        if (isEditing.value)
			await services_item.update(services_item.dataOne.value.id, form.value)
		else
			await services_item.create(form.value)
	}
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
        <BaseBackButton />
		<h2 class="text-lg font-medium mr-auto">
            {{props.id ? 'Editar':'Crear'}} modulo item
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
                        label="Ruta *"
                        tooltip="Ingrese el ruta"
                        placeholder="Ruta"
                        name="route"
                        v-model="form.route"
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
                    <BaseSelect
                    label="Modulo"
                    tooltip="Selecciona un modulo"
                    placeholder="Seleccione"
                    name="module_id"
                    v-model="form.module_id"
                    :options="options.modules || null"
                    :validator="v$"
                />
                </div>
			</section>
			<div class="flex justify-center">
                {{moduleItems.isGeneralData}} - {{isEditing}}
				<button :disabled="(!moduleItems.isGeneralData && !isEditing  && moduleItems.loading)"   type="submit" class="btn btn-primary w-24 ml-2">
					Ingresar
				</button>
			</div>
		</form>
	</div>
</template>