<script setup>
import { ref, onMounted, reactive, watch, computed } from "vue"
import services from '@/services/assistants.service'

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';
import { required } from '../../../utils/validations'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseBackButton from "@/components/base/BaseBackButton.vue"
import BaseSelect from '@/components/base/Select.vue'

// Store
import { useAssistants } from "@/stores/assistant"
import { useSelectStore } from "@/stores/selects"
import { storeToRefs } from "pinia";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";

const route = useRoute()
const router = useRouter()

// Extracting Store Data
const assistants_store = useAssistants()
const { form, form_rules } = storeToRefs(assistants_store)

// Extracting Select Data
const select_store = useSelectStore()
const { options } = storeToRefs(select_store)
const lists = options.value

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true })

// Extracting services
const assistants_services = services()

const fetchOne = async () => {
    await assistants_services.getOne(route.params.id)
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

        const { id, created_at, updated_at, deleted_at, nac_id, ...rest } = assistants_services.dataOne.value
        form.value = { ...rest, nac_id: String(nac_id) }
    } else {
        assistants_store.$reset()
    }
})

const onSubmit = async () => {
    const valid = v$.value.$validate()
	if (valid){
        if (isEditing.value){
            const response = await assistants_services.update(assistants_services.dataOne.value.id, form.value).then(() => {
                assistants_store.$reset
                select_store.dataSelects(true)
                router.push({ name: 'assistants.index' })
            })
        }
        else {
            const response = await assistants_services.create(form.value).then(() => {
                assistants_store.$reset
                select_store.dataSelects(true)
                router.push({ name: 'assistants.index' })
            })
        }
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
            Edición de Asistente: <b>{{ assistants_services.dataOne.value.id }}</b>
        </h2>
        <h2 v-else class="text-lg font-medium mr-auto">
            Crear Asistente
        </h2>
    </div>
    <div class="intro-y box mt-5">
        <form id="assistant_form" @submit.prevent="onSubmit" class="px-5 sm:px-10 py-10 border-t border-slate-200/60">
            <div class="grid grid-cols-2 gap-6 justify-evenly mb-8">
                <div class="w-full">
                    <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                        v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="NOMBRE *" tooltip="Ingrese el nombre"
                        placeholder="Nombre" name="assistant_name" v-model="form.assistant_name"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="CARGO *" tooltip="Ingrese el cargo"
                        placeholder="Cargo" name="assistant_position" v-model="form.assistant_position"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="CÉDULA *" tooltip="Ingrese el numero de cédula"
                        placeholder="Numero de cédula" name="assistant_document_number" v-model="form.assistant_document_number"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="tel" label="TELÉFONO *" tooltip="Ingrese el numero de teléfono"
                        placeholder="Numero de teléfono" name="assistant_phone" v-model="form.assistant_phone"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="EMAIL *" tooltip="Ingrese el correo electrónico"
                        placeholder="Correo electrónico" name="assistant_email" v-model="form.assistant_email"
                        :validator="v$" />
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" form="assistant_form" class="btn btn-primary w-24">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</template>