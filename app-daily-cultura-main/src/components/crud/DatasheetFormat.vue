<script setup lang="ts">
import useSlug from '@/utils/composables/useSlug'

const { isSlug } = useSlug()

const props = defineProps<{
    datasheet: string
}>()

const datasheetFormat = computed(() => {
    const splitting = props.datasheet.split('Ficha')
    
    if (isSlug('culturalEnsembles')) {
        return `Ensamble ${splitting[1]}`
    }
    else if (isSlug('culturalCirculations')) {
        return `Circulación ${splitting[1]}`
    }
    else {
        return `Semillero ${splitting[1]}`
    }
})
</script>

<template>
    <span
        class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-stone-900 whitespace-nowrap">
        {{ datasheetFormat }}
    </span>
</template>