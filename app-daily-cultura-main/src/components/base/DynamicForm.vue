<script setup lang="ts">
import { Form, Field, ErrorMessage } from 'vee-validate'

export interface childrenT {
    tag: string
    label: string
}

export interface fieldT {
    name: string
    label?: string
    as: string
    placeholder?: string
    children: childrenT[]
}

export interface schemaT {
    fields: fieldT[]
}

const props = defineProps<{
    schema: schemaT
    initial?: Object
}>()

const emits = defineEmits(['submit'])

function onSubmit(values, { resetForm }){
    emits('submit', values)
    resetForm()
}

</script>

<template>
    <Form @submit="onSubmit" class="grid grid-cols-1 gap-2 lg:grid lg:grid-cols-[1fr_auto] lg:gap-4 lg:justify-between md:items-start"
    v-slot="{}">
        <div class="flex flex-col gap-2 lg:grid lg:gap-4 lg:justify-evenly lg:items-start" :style="`grid-template-columns: repeat(${props.schema.fields.length}, minmax(0, 1fr))`">
            <div
                v-for="{ as, name, label, children, ...attrs } in schema.fields" :key="name">
                <label v-if="label" :for="name">{{ label }}</label>
                <Field :as="as" :id="name" :name="name" v-bind="attrs" validateOnInput class="form-control" autocomplete="off">
                    <template v-if="children && children.length">
                        <option v-if="as.includes('select')" value="" disabled selected hidden>
                            <span v-if="attrs?.placeholder">
                                {{ attrs?.placeholder }}
                            </span>
                        </option>
                        <component v-for="({ tag, label, ...childAttrs }, idx) in children"
                            :key="idx"
                            :is="tag"
                            v-bind="childAttrs"
                        >
                            {{ label }}
                        </component>
                    </template>
                </Field>
                <ErrorMessage :name="name" v-slot="{ message }">
                    <p class="text-left text-danger text-xs">
                        {{message}}
                    </p>
                </ErrorMessage>
            </div>
        </div>
        <button type="submit" class="btn btn-primary flex">
            <PlusIcon class="w-5 h-5" />
        </button>
    </Form>
</template>