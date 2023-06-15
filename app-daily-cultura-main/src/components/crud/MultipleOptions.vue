<script setup lang="ts">
import mixins, { SelectKey } from '@/mixins'

defineProps<{
    type: SelectKey,
    options: Array<any>
}>()
</script>

<template>
    <div class="flex flex-col gap-y-1">
        <template v-for="option in options">
            <span
                class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-stone-900 whitespace-nowrap">
                {{ mixins.get_option_label(type, option) }}
            </span>
        </template>
    </div>
</template>