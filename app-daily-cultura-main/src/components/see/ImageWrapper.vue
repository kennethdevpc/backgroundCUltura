<script setup lang="ts">
const props = defineProps<{
    path: string
}>()
//VITE_BASE_URL
const img_preview = props.path.includes('http') ? props.path : `${import.meta.env.VITE_BASE_IMAGE_URL}${!props.path.includes('storage')?'/storage/':''}${props.path}`
</script>

<template>
    <div class="relative intro-x flex justify-center w-full h-24 rounded select-none">
        <div class="absolute z-0 bg-stripes rounded w-full h-full opacity-50" />
        <img :src="img_preview"
            class="object-center object-contain w-full h-24 relative rounded-lg z-10" alt="">
    </div>
</template>