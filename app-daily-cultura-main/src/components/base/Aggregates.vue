<script setup lang="ts">
import { Validation } from '@vuelidate/core'
import { useSelectStore } from '@/stores/selects'
import DynamicForm from './DynamicForm.vue'
import { schemaT } from './DynamicForm.vue'
import VueMultiSelect from 'vue-multiselect'
import mixins from '@/mixins'
import { Header } from 'vue3-easy-data-table'

interface optionsT {
    [key: string]: any
}

interface aggregatesT {
    [key: string]: any
}

const props = defineProps<{
    headers: Header[]
    aggregates: aggregatesT[]
    options?: optionsT[]
    add_schema?: schemaT
    name?: string
    validator?: Validation
}>()

const emits = defineEmits<{
    (e: 'pop', id: string | number): void
    (e: 'push', value: any): void
}>()

const select = ref({})

const active_placeholder = ref(true)

const verify_prop = (property) => {
    return props.options.at(0)[property]
}

const filtered_options = computed(() => {
    if (props.options) {
        if (props.aggregates) {
            const options = props.options.filter((item) => {
                const property = Object.keys(item)[0]

                return !props.aggregates.some((f) => {
                    return f[property] === item[property]
                })
            })

            return options
        }
        return props.options
    }
    else {
        return []
    }
    // const options = props.options.filter((item) => !props.aggregates.includes(item))

})

watch(filtered_options, () => {
    if (filtered_options.value.length == 0) {
        active_placeholder.value = false
    } else {
        active_placeholder.value = true
    }
})

const check_validation = computed<string | boolean>(() => {
    return props.validator[props.name].$errors.length ? 'text-danger' : false
})

</script>

<template>
    <div class="grid grid-cols-1 gap-2">
        <div v-if="options" class="relative border border-slate-200 rounded">
            <span v-if="active_placeholder" class="pointer-events-none absolute top-2.5 left-[8px] text-sm z-10 opacity-100">
                Selecciona o busca...
            </span>
            <VueMultiSelect v-model="select" placeholder="Selecciona o busca..." openDirection="below"
                :disabled="filtered_options.length === 0" :label="
                    options.length == 0 ? 'No hay' :
                        verify_prop('monitor_fullname') ? 'monitor_fullname' :
                            verify_prop('full_name') ? 'full_name' :
                                verify_prop('name') ? 'name' :
                                    verify_prop('assistant_name') ? 'assistant_name' :
                verify_prop('names') ? 'names' : 'No hay'" :showPointer="false" :options="filtered_options"
                :option-height="104" :searchable="true" :close-on-select="true" @open="active_placeholder = false"
                @close="active_placeholder = true" @select="(payload) => {
                    $emit('push', payload)
                }" resetAfter>
                <template #noResult>
                    Sin resultados.
                </template>
                <template #noOptions>
                    Lista vacia.
                </template>
            </VueMultiSelect>
        </div>
        <div v-if="add_schema" class="p-1">
            <DynamicForm :schema="add_schema" @submit="(values) => $emit('push', values)" />
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap" v-for="header in headers">
                            {{ header.text }}
                        </th>
                        <th class="whitespace-nowrap">
                            <div class="w-full flex justify-end">
                                ACCIONES
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="aggregates.length">
                        <tr v-for="aggregate in aggregates">
                            <td v-for="header in headers">
                                <template v-if="header.value == 'nac_id'">
                                    {{ mixins.get_option_label('nacs', aggregate[header.value]) }}
                                </template>
                                <template v-else-if="header.value == 'role_id'">
                                    {{ mixins.get_option_label('roles_display', aggregate[header.value]) }}
                                </template>
                                <template v-else>
                                    {{ aggregate[header.value as unknown as string] }}
                                </template>
                            </td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="btn btn-danger" @click="$emit('pop', aggregate['id'])">
                                        <Trash2Icon class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr class="text-center">
                            <td :colspan="headers.length + 1">
                                <div class="flex flex-col gap-2">
                                    <span :class="check_validation">
                                        No hay agregados
                                    </span>
                                    <!-- <span v-if="validator[name].$errors.length">
                                        <template v-for="error of validator[name].$errors"
                                            :key="error.$uid">
                                            <small class="block text-danger">{{ error.$message }}</small>
                                        </template>
                                    </span> -->
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>