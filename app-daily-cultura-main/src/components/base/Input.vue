<script lang="ts" setup>
import { Validation } from "@vuelidate/core";
import BaseValidator from "@/components/base/Validator.vue";

// [TypeScript] => Defining Props
interface Props {
	type:
		| "text"
		| "number"
		| "email"
		| "password"
		| "search"
		| "url"
		| "tel"
		| "date"
		| "time"
		| "range"
		| "color"
		| "hidden"
		| "file";
	modelValue?: string | number | Object | Boolean;
	label?: string;
	placeholder?: string;
	min?: string | number;
	max?: string | number;
	disabled?: boolean;
	tooltip?: string | boolean;
	name: string;
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

// Detecting v-model changes.
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
	<div class="BaseInput w-full relative">
		<!-- Label -->
		<label v-show="label" v-bind:for="name" class="form-label font-bold">{{
			label
		}}</label>
		<!-- If is Date -->
		<div
			v-if="type === 'date'"
			class="absolute right-0 box-border rounded-r w-10 h-[42px] pointer-events-none flex items-center justify-center bg-slate-100 border text-slate-500"
			v-bind:class="[
				{ 'border-danger': validator && validator[name].$error },
				label ? 'top-7' : 'top-0',
			]"
		>
			<CalendarIcon class="w-4 h-4" />
		</div>
		<!-- Component -->
		<input
			autocomplete="off"
			:name="name"
			:type="type"
			:min="min"
			:max="max"
			:placeholder="placeholder"
			:disabled="disabled"
			v-model="value"
			v-bind:onfocus="
				type === 'time' || type === 'date' ? 'this.showPicker()' : ''
			"
			v-bind:class="[
				'form-control py-[10px]',
				{ 'border-danger': validator && validator[name].$error },
			]"
		/>
		<!-- [Validator] => Default Component -->
		<template v-if="validator">
			<BaseValidator v-bind="{ validator, name, tooltip }" />
		</template>
	</div>
</template>
