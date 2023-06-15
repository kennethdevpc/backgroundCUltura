<script lang="ts" setup>
import { Validation } from "@vuelidate/core";

interface Props {
  validator: Validation;
  name: string;
  tooltip: string | boolean;
}

defineProps<Props>();
</script>

<template>
  <div class="BaseValidator">
    <template v-if="validator[name].$error">
      <div
        v-for="(error, index) in validator[name].$errors"
        :key="index"
        class="text-xs text-danger"
      >
        {{ error.$message }}
      </div>
    </template>
    <template v-else-if="tooltip">
      <small class="text-gray-400">
        {{ tooltip }}
      </small>
    </template>
  </div>
</template>

<style lang="css" scoped>
.BaseValidator {
  margin-top: 0.5rem;
}
</style>
