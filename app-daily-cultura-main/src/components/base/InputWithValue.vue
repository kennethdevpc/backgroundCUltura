<script lang="ts" setup>
import { computed } from 'vue'

// [TypeScript] => Defining Props
interface Props {
    type:
        | 'text'
        | 'number'
        | 'email'
        | 'password'
        | 'search'
        | 'url'
        | 'tel'
        | 'date'
        | 'time'
        | 'range'
        | 'color'
        | 'hidden'
    modelValue?: string | number | object | boolean
    label?: string
    placeholder?: string
    min?: string | number
    max?: string | number
    disabled?: boolean
    tooltip?: string
    name: string
    validator: object
    value?: any
}

const props = withDefaults(defineProps<Props>(), {})

// [TypeScript] => Defining Emits
interface Emits {
    (e: 'update:modelValue', value: string): void
}

const emit = defineEmits<Emits>()

// Detecting v-model changes.
const onInput = (e) => {
    emit('update:modelValue', e.target.value)
}

const checkInputValidity = computed<string | boolean>(() => {
    return props.validator[props.name].$errors.length ? 'border-danger' : false
})
</script>

<template>
    <div class="BaseInput w-full">
        <!-- Label -->
        <label v-if="label" :for="name" class="form-label font-bold">{{ label }}</label>
        <!-- Component -->
        <input
            :value="value"
            autocomplete="nope"
            :name="name"
            :type="type"
            :min="min"
            :max="max"
            :placeholder="placeholder"
            :disabled="disabled"
            :model-value="modelValue"
            :onfocus="(type === 'time') ? 'this.showPicker()' : ''"
            :class="['form-control py-[9px]', checkInputValidity]"
            @input="onInput"
        />
        <!-- Validator -->
        <div class="mt-2">
            <template v-if="validator[name].$errors.length">
                <template v-for="error of validator[name].$errors" :key="error.$uid">
                    <small class="block text-danger">{{ error.$message }}</small>
                </template>
            </template>
            <template v-else-if="tooltip">
                <small class="block text-gray-400">{{ tooltip }}</small>
            </template>
        </div>
    </div>
</template>

<style scoped>
.BaseInput {
}
</style>
