<script lang="ts" setup>
import VueMultiselect from "vue-multiselect";
import { Validation } from "@vuelidate/core";
import BaseValidator from "@/components/base/Validator.vue";

interface Emits {
	(e: "update:modelValue", value: string | Array<any>): void;
	(e: "searchChange", value: Event): void;
}

interface Props {
	validator: Validation;
	modelValue?: any;
	labelName?: string;
	label?: string;
	tooltip?: string;
	placeholder?: string;
	disabled?: boolean;
	name: string;
	trackBy?: string;
	options: Array<any>;
	searchable?: boolean;
	allowEmpty?: boolean;
	closeOnSelect?: boolean;
	showLabels?: boolean;
	preselectFirst?: boolean;
	taggable?: boolean;
	tagPlaceholder?: string;
	multiple?: boolean;
	loading?: boolean;
	tag?: any;
	maxHeight?: number;
	hideTooltips?: boolean;
	customLabel?: any;
}

const emit = defineEmits<Emits>();

const props = withDefaults(defineProps<Props>(), {
	placeholder: "Seleccione...",
	maxHeight: 200,
	multiple: false,
	closeOnSelect: true,
	searchable: true,
	allowEmpty: false,
	preselectFirst: false,
	showLabels: false,
	taggable: false,
	tagPlaceholder: "",
	tag: () => {},
	loading: false,
	tooltip: "",
	disabled: false,
	hideTooltips: false,
});

const handleTag = (newVal) => {
	const result = props.tag(newVal);
	emit("update:modelValue", result);
};

const handleSearch = (query) => emit("searchChange", query);

const updateSelected = (event) => {
	emit("update:modelValue", event);
};

const checkInputValidity = computed<string | boolean>(() => {
	return props.validator[props.name].$errors.length ? "border-danger" : false;
});
</script>

<template>
	<div class="BaseMultiselect">
		<label v-if="labelName" :for="name" class="form-label font-bold">
			{{ labelName }}
			<span
				class="BaseMultiselect-required"
				v-if="validator[name].required"
				>*</span
			>
		</label>
		<!-- [Multiselect] => Component -->
		<VueMultiselect
			class="box-border border rounded"
			:class="[checkInputValidity]"
			:name="name"
			:model-value="modelValue"
			:options="options"
			:searchable="searchable"
			:close-on-select="closeOnSelect"
			:allow-empty="allowEmpty"
			:label="label"
			:track-by="trackBy"
			:multiple="multiple"
			:max-height="maxHeight"
			:placeholder="placeholder"
			:preselect-first="preselectFirst"
			:show-labels="showLabels"
			:disabled="disabled"
			:loading="loading"
			:taggable="taggable"
			:tag-placeholder="tagPlaceholder"
			:custom-label="customLabel"
			@tag="handleTag"
			@search-change="handleSearch"
			@update:model-value="updateSelected"
		>
			<!-- <template v-slot:selection="{ values, search, isOpen }">
        <span class="multiselect__single" v-if="values.length && !true">
          {{ values.length }} {{ values.length > 1 ? "opciónes" : "opción" }}
        </span>
      </template> -->
			<template #noResult> Sin resultados </template>
			<template #noOptions> Cargando resultados... </template>
		</VueMultiselect>
		<!-- [Validator] => Default Component + If statement -->
		<BaseValidator
			v-if="!hideTooltips"
			v-bind="{ name, validator, tooltip }"
		/>
	</div>
</template>

<style lang="css" scoped>
.BaseMultiselect-required {
	color: red;
}
</style>
