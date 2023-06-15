<script lang="ts" setup>
// [TypeScript] => Defining Props
interface Props {
  modelValue?: string | number | object | boolean;
  label?: string;
  placeholder?: string;
  multiple?: boolean;
  disabled?: boolean;
  tooltip?: string;
  name: string;
  options: Array<any> | null;
  validator: object;
}

const props = withDefaults(defineProps<Props>(), {
  multiple: false,
});

// [TypeScript] => Defining Emits
interface Emits {
  (e: "update:modelValue", value: string): void;
}

const emit = defineEmits<Emits>();

// Detecting v-model changes.
// const onSelect = (event) => {
//     emit('update:modelValue', event)
// }

const value = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    emit("update:modelValue", value as string);
  },
});
</script>

<template>
  <div class="BaseSelect">
    <!-- Label -->
    <label v-if="label" :for="name" class="form-label font-bold">{{
      label
    }}</label>
    <!-- Component -->
    <TomSelect
      v-model="value"
      :options="{
        placeholder,
      }"
      :multiple="multiple"
      :disabled="disabled"
    >
      <option value="0">Seleccionar</option>
      <option v-for="option in options" :value="option.value">
        {{ option.label }}
      </option>
      <slot />
    </TomSelect>
    <!-- Validator -->
    <div class="mt-2">
      <template v-if="validator[name].$errors.length">
        <div v-for="error of validator[name].$errors" :key="error.$uid">
          <small class="block text-danger">{{ error.$message }}</small>
        </div>
      </template>
      <template v-else-if="tooltip">
        <small class="block text-gray-400">{{ tooltip }}</small>
      </template>
    </div>
  </div>
</template>

<style scoped>
.BaseSelect {
}
</style>
