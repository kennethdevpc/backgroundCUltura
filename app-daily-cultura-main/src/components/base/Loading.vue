<script setup lang="ts">
import { loading } from '@/utils/loading'
</script>

<template>
    <div v-if="loading" class="fixed top-0 left-0 z-[1000] bg-black/40 backdrop-blur-sm w-full h-full">
        <div class="grid place-content-center place-items-center h-full">
            <loading-icon icon="three-dots" class="w-16 text-white" color="rgb(255 255 255)"  />
        </div>
    </div>
</template>