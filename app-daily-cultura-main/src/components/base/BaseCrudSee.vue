<script setup lang="ts">
import SlideOver from '../SlideOver.vue';
import ImageWrapper from '../see/ImageWrapper.vue';
import DocWrapper from '../see/DocWrapper.vue';

interface Item {
    [key: string]: any
}

withDefaults(defineProps<{
    item: {
        [key: string]: any,
        consecutive: string
    },
    full_view?: boolean
}>(), {
    full_view: false
})
</script>

<template>
    <SlideOver :full_view="full_view">
        <template #button="scope">
            <button @click="scope.open(true)" class="btn btn-secondary flex gap-1 items-center">
                <EyeIcon class="w-5 h-5" />
                <span class="text-sm">
                    Ver
                </span>
            </button>
        </template>
        <template #header="scope">
            <div class="px-4 pb-0 pt-5 sm:pb-5 sm:pt-0 sm:px-6 flex flex-row items-center justify-between">
                <h1 class="text-base font-semibold leading-6 text-gray-900">
                    {{ item.consecutive }}
                </h1>
                <button @click="scope.close(false)"
                    class="flex sm:hidden bg-secondary hover:bg-secondary/70 p-2.5 rounded-full transition">
                    <XIcon />
                </button>
            </div>
        </template>
        <template #body>
            <div class="px-4 py-5 sm:p-0 space-y-4">
                <template v-for="section in item.sections">
                    <div class="border border-gray-200 rounded-md shadow-sm sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h2 class="text-base font-semibold leading-6 text-gray-900">
                                {{ section.title }}
                            </h2>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <template v-for="key in Object.keys(section.fields)">
                                    <template v-if="section.fields[key] == '' || section.fields[key] == null" />
                                    <template v-else>
                                        <template v-if="key.includes('IMAGEN')">
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 sm:col-span-3">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 sm:col-span-3 sm:mt-0">
                                                    <ImageWrapper :path="section.fields[key]" />
                                                </dd>
                                            </div>
                                        </template>
                                        <template v-else-if="key.includes('ARCHIVO')">
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 sm:col-span-3">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 sm:col-span-3 sm:mt-0">
                                                    <template v-if="
                                                        section.fields[key].includes('pdf')
                                                        || section.fields[key].includes('doc')
                                                        || section.fields[key].includes('docx')
                                                    ">
                                                        <DocWrapper :path="section.fields[key]" />
                                                    </template>
                                                    <template v-else>
                                                        <ImageWrapper :path="section.fields[key]" />
                                                    </template>
                                                </dd>
                                            </div>
                                        </template>
                                        <template v-else-if="key.includes('DOCUMENTO')">
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 sm:col-span-3">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 sm:col-span-3 sm:mt-0">
                                                    <DocWrapper :path="section.fields[key]" />
                                                </dd>
                                            </div>
                                        </template>
                                        <template
                                            v-else-if="typeof section.fields[key] == 'object' && section.fields[key].length">
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 sm:col-span-3">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 sm:col-span-3 sm:mt-0">
                                                    <div class="overflow-x-auto">
                                                        <table class="border-collapse border border-secondary">
                                                            <thead class="border-b border-secondary">
                                                                <th class="px-2 py-1 font-normal text-xs opacity-80 text-center even:bg-white odd:bg-slate-50"
                                                                    v-for="header_key in Object.keys(section.fields[key][0])">
                                                                    {{ header_key }}
                                                                </th>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="border-b border-secondary"
                                                                    v-for="assistant in section.fields[key]">
                                                                    <td class="px-2 py-1 font-semibold text-sm text-left whitespace-nowrap even:bg-white odd:bg-slate-50"
                                                                        v-for="body_key in Object.keys(section.fields[key][0])">
                                                                        {{ assistant[body_key] }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </dd>
                                            </div>
                                        </template>
                                        <template v-else-if="key.includes('RANGO')">
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 sm:col-span-3">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 sm:col-span-3 sm:mt-0">
                                                    <div class="relative w-full">
                                                        <div class="w-full bg-slate-200 rounded h-4">
                                                            <div class="bg-primary h-full rounded text-xs text-white flex justify-center items-center"
                                                                :style="`width:${(section.fields[key].value + 10) / 5}%`"
                                                                role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                aria-valuemax="5">
                                                                {{ section.fields[key].value }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </dd>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">
                                                    {{ key }}
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                    {{ section.fields[key] }}
                                                </dd>
                                            </div>
                                        </template>
                                    </template>
                                </template>
                            </dl>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </SlideOver>
</template>

<style scoped>
#range::-webkit-progress-bar {
    @apply bg-secondary rounded-full w-full;
}

#range::-webkit-progress-value {
    @apply bg-primary rounded-full w-full;
}
</style>