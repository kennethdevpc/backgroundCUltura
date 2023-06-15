<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { VNodeRef } from 'vue'

const open = ref(false)
const router = useRouter()

const toggleModal = useToggle(open)

watch(open, (new_val) => {
    if (new_val == false){
        router.push({ hash: '' })
    }
})

withDefaults(defineProps<{
    full_view: boolean
}>(), {
    full_view: false
})

</script>

<template>
    <slot name="button" :open="toggleModal" :close="toggleModal" />
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-[10000]" @close="open = false">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div :class="full_view ? 'pl-0' : 'sm:pl-10'" class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full">
                        <TransitionChild as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel :class="full_view ? 'max-w-full' : 'sm:max-w-md md:max-w-lg'" class="pointer-events-auto relative w-screen">
                                <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                                    enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100"
                                    leave-to="opacity-0">
                                    <div :class="full_view ? 'hidden' : 'flex pt-4 pr-2 sm:-ml-16 sm:pr-4 -ml-8'" class="absolute top-0 left-0">
                                        <button type="button"
                                            class="hidden sm:flex text-slate-300 hover:text-white p-2.5 rounded-full transition"
                                            @click="open = false">
                                            <span class="sr-only">Close panel</span>
                                            <XIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>
                                </TransitionChild>
                                <div class="flex h-full flex-col overflow-y-auto bg-white p-0 sm:p-5 shadow-xl">
                                    <div class="grid grid-cols-[1fr_auto] items-center">
                                        <slot name="header" :close="toggleModal"/>
                                        <button v-if="full_view" type="button"
                                            class="hidden sm:flex bg-secondary hover:bg-secondary/70 p-2.5 rounded-full transition"
                                            @click="open = false">
                                            <span class="sr-only">Close panel</span>
                                            <XIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>
                                    <slot name="body" />
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>