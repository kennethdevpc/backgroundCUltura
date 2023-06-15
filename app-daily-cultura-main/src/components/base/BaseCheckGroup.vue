<script lang="ts" setup>
import { Validation } from "@vuelidate/core";
import BaseValidator from "@/components/base/Validator.vue";

// TypeScript => Defining Props
interface Props {
  name: string;
  label?: string;
  options: Array<any>;
  tooltip?: string | boolean;
  modelValue?: string | number | boolean | any[];
  validator: Validation;
}

const props = withDefaults(defineProps<Props>(), {
  tooltip: false,
});

// [TypeScript] => Defining Emits
interface Emits {
  (e: "update:modelValue", value: string | number | boolean): void;
}

const emit = defineEmits<Emits>();

const value = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    emit("update:modelValue", value);
  },
});
</script>

<template>
  <div class="BaseCheckGroup">
    <!-- Label -->
    <label v-if="label" class="form-label font-bold w-full text-left">{{
      label
    }}</label>
    <!-- Component -->
    <div class="form-check my-3">
      <div v-for="(option, index) in options" class="mr-3" key="index">
        <input
          type="checkbox"
          class="form-check-input w-5 h-5"
          :name="name"
          :id="name + index"
          :value="option.value"
          v-model="value"
        />
        <label class="form-check-label" :for="name + index">{{
          option.text || option.label
        }}</label>
      </div>
    </div>
    <!-- [Validator] => Default Component -->
    <BaseValidator :validator="validator" :name="name" :tooltip="tooltip" />
  </div>
</template>
