<script setup>
    // interface Item {
    //     [x: string | number | symbol]: unknown
    // }
    
    const props = defineProps({
        item: {
            type: Object,
            default: {}
        },
        label: {
            type: String,
            default: ''
        },
        onDelete: {
            type: Function,
            default() {
             
            }
        },
    })

    const modalActive = ref(false)
    const toggleModal = useToggle(modalActive)

    const deleteFnc = () => {
        props.onDelete(props.item.id)
        
        toggleModal(false)
    }
</script>

<template>
    <button @click="toggleModal(true)" class="btn btn-danger flex gap-1 items-center">
        <TrashIcon class="w-5 h-5" />
        <span class="text-sm">
            Eliminar
        </span>
    </button>
    <Modal :show="modalActive" backdrop>
        <ModalBody>
            <div class="p-5 text-center">
                <XCircleIcon class="w-16 h-16 mx-auto mt-3 text-danger" />
                <div class="mt-5 text-3xl">¿Estas seguro?</div>
                <div class="mt-2 text-slate-500">
                    Quieres realmente borrar {{ label ? label : 'el elemento' }} #{{ item.id }}? <br />
                    Este proceso no se puede deshacer.
                </div>
            </div>
            <div class="flex gap-2 justify-center px-5 pb-8 text-center">
                <button type="button" class="btn btn-primary" @click="toggleModal(false)">
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger w-24" @click="deleteFnc">
                    Borrar
                </button>
            </div>
        </ModalBody>
    </Modal>
</template>