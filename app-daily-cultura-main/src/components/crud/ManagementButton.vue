<script setup lang="ts">
import { CheckSquare } from 'lucide-vue-next'

const route = useRoute()

const moduleSlug = computed(() => {
    return String(route.name).split('.')[0]
})

defineProps<{
    id: string | number
}>()
</script>

<template>
    <button @click="() => $router.push({ name: `${moduleSlug}.edit`, params: { id } })" class="btn btn-secondary flex gap-1 items-center" v-bind="$attrs">
        <CheckSquare class="w-5 h-5" />
        <span class="text-sm">
            Revisión
        </span>
    </button>
</template>