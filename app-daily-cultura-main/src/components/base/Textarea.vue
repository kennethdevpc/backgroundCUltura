<script lang="ts" setup>
import { Validation } from "@vuelidate/core";
import BaseValidator from "@/components/base/Validator.vue";
import { computed } from "vue";

// [TypeScript] => Defining Props
interface Props {
	modelValue?: string | number;
	label?: string;
	placeholder?: string;
	minlength?: string | number;
	maxlength?: string | number;
	disabled?: boolean;
	tooltip?: string | boolean;
	name: string;
	rows?: string;
	cols?: string;
	validator?: Validation;
}

const props = withDefaults(defineProps<Props>(), {
	tooltip: false,
});

// [TypeScript] => Defining Emits
interface Emits {
	(e: "update:modelValue", value: string): void;
}

const emit = defineEmits<Emits>();

const value = computed({
	get() {
		return props.modelValue;
	},
	set(value) {
		emit("update:modelValue", value as string);
	},
});
</script>

<script lang="ts">
export default {
	inheritAttrs: false,
};
</script>

<template>
	<div>
		<!-- Label -->
		<label v-show="label" v-bind:for="name" class="form-label font-bold">{{
			label
		}}</label>
		<!-- Component -->
		<textarea
			v-bind="$attrs"
			v-model="value"
			:class="[
				'form-control',
				{ 'border border-danger': validator && validator[name].$error },
			]"
		></textarea>
		<!-- [Validator] => Default Component -->
		<template v-if="validator">
			<BaseValidator v-bind="{ name, validator, tooltip }" />
		</template>
	</div>
</template>
