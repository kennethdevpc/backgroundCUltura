<script setup>
// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'

// Importing Validations
import { required } from '@/utils/validations'
import { email } from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'

// Importing Stores
import { storeToRefs } from 'pinia'
import { useSelectStore } from "@/stores/selects"
import { useAssistants } from '@/stores/assistant'
import { usePsychosocialInstruction } from '@/stores/psychosocialinstruction'
import alerts from '@/utils/alerts'

// Importing Services and Extracting

// Extracting Asistants
const assistant_store = useAssistants()
const { form, form_rules } = storeToRefs(assistant_store)

// Extracting Select Data
const select_store = useSelectStore()
// const { options } = storeToRefs(select_store)
const lists = options.value

// Extracting psychosocialinstruction
const psychosocialinstruction = usePsychosocialInstruction()

const v$ = useVuelidate(form_rules, form)

const modalActive = ref(false)
const toggleModal = useToggle(modalActive)
const loading = ref(false)

onMounted(() => {
    assistant_store.$reset()
})

const onSubmit = async () => {
    const valid = await v$.value.$validate()
    loading.value = true
    if (valid){
        psychosocialinstruction.push_aggregate(form.value, 'a')
        loading.value = false
        assistant_store.$reset()
        toggleModal(false)
    }
    else {
        alerts.validation()
    }
}
</script>

<template>
    <button type="button" @click="toggleModal(true)" class="btn btn-primary mb-2 mr-1">
        Agregar Asistente
    </button>
    <Modal :show="modalActive" backdrop>
        <ModalHeader>
            <h1 class="font-medium">
                Crear Asistente
            </h1>
        </ModalHeader>
        <ModalBody>
            <form id="assistant_form" @submit.prevent="onSubmit" class="grid grid-cols-2 gap-6 justify-evenly">
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
                    <BaseInput type="text" label="CEDULA *" tooltip="Ingrese el numero de cedula"
                        placeholder="Numero de cedula" name="assistant_document_number" v-model="form.assistant_document_number"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="TELEFONO *" tooltip="Ingrese el numero de telefono"
                        placeholder="Numero de telefono" name="assistant_phone" v-model="form.assistant_phone"
                        :validator="v$" />
                </div>
                <div class="w-full">
                    <BaseInput type="text" label="EMAIL *" tooltip="Ingrese el correo electronico"
                        placeholder="Correo electronico" name="assistant_email" v-model="form.assistant_email"
                        :validator="v$" />
                </div>
            </form>
        </ModalBody>
        <ModalFooter>
            <div class="flex gap-2 justify-end">
                <button type="button" class="btn btn-secondary" @click="toggleModal(false)">
                    Cancelar
                </button>
                <button type="submit" form="assistant_form" class="btn btn-primary w-24">
                    Confirmar
                </button>
            </div>
        </ModalFooter>
    </Modal>
</template>