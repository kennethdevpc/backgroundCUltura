<script setup lang="ts">
const props = defineProps<{
    path: string
}>()

const doc_preview = props.path.includes('http') ? props.path : `${import.meta.env.VITE_BASE_IMAGE_URL}${!props.path.includes('storage') ? '/storage/' : ''}${props.path}`
</script>

<template>
    <!-- <div class="relative intro-x flex justify-center w-full h-24 rounded select-none">
        <div class="absolute z-0 bg-stripes rounded w-full h-full opacity-50" />
        <img :src="img_preview" class="object-center object-contain w-full h-24 relative rounded-lg z-10" alt="">
    </div> -->
    <div class="grid place-content-center w-full h-24 relative rounded-lg z-10">
        <a :href="doc_preview" target="_blank"
            class="flex flex-col items-center w-full p-5 bg-slate-200 fill-primary active:bg-primary active:fill-slate-200 rounded-lg transition">
            <svg width="32" height="32" viewBox="0 0 256 256">
                <path
                    d="m213.7 82.3l-56-56A8.1 8.1 0 0 0 152 24H56a16 16 0 0 0-16 16v176a16 16 0 0 0 16 16h144a16 16 0 0 0 16-16V88a8.1 8.1 0 0 0-2.3-5.7Zm-53.7-31L188.7 80H160ZM200 216H56V40h88v48a8 8 0 0 0 8 8h48v120Z" />
            </svg>
        </a>
    </div>
</template>