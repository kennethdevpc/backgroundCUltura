<script lang="ts" setup>
import type { Select } from "@/utils/useSelect";
import { Validation } from "@vuelidate/core";
import VueMultiSelect from "vue-multiselect";
import Validator from "./Validator.vue";

// [TypeScript] => Defining Props
interface Props {
	allowEmpty?: boolean;
	disabled?: boolean;
	hideSelected?: boolean;
	label?: string;
	modelValue?: string | number | Array<any>;
	multiple?: boolean;
	name: string;
	onRemove?: Function;
	onSelect?: Function;
	options?: Select[] | null | undefined;
	placeholder?: string;
	tooltip?: string;
	validator?: Validation;
}

const props = withDefaults(defineProps<Props>(), {
	multiple: false,
	allowEmpty: true,
});

// [TypeScript] => Defining Emits
interface Emits {
	(e: "update:modelValue", value: string): void;
	(e: "searchValue", value: string): void;
}

const emit = defineEmits<Emits>();

// onMounted(() => {
// 	console.log(props.options, props.label)
// })

const noOpt = ["Sin Opción"];

function compareOptions(a, b) {
	if (typeof a.value === "number" && typeof b.value === "number") {
		return a.value - b.value;
	} else {
		return a.label.toString().localeCompare(b.label.toString());
	}
}

const options_handle = computed(() => {
	const { options } = props;
	const sortedOptions = (options ?? []).sort(compareOptions);
	return sortedOptions.map((item) => item.value);
});

const label_handle = (opt: string) => {
	const { options } = props;
	if (options != null) {
		return options.find((x) => x.value == opt)?.label || "Sin opción";
	} else {
		return "Sin opción";
	}
};

const searchByText = (value) => {
	emit("searchValue", value);
};

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
	components: { Validator },
};
</script>

<template>
	<div class="BaseSelect">
		<!-- Label -->
		<template v-if="label">
			<label :for="name" class="form-label font-bold">
				{{ label }}
			</label>
		</template>
		<!-- Component -->
		<VueMultiSelect
			v-model="value"
			:placeholder="placeholder ? placeholder : 'Selecciona'"
			class="box-border border rounded"
			:class="[
				{
					'border-danger': validator && validator[name].$error,
				},
			]"
			:options="options_handle"
			:max-height="600"
			:close-on-select="true"
			:clear-on-select="false"
			:custom-label="(opt: string) => label_handle(opt)"
			:allowEmpty="allowEmpty"
			:disabled="disabled"
			:hideSelected="hideSelected"
			:multiple="multiple"
			openDirection="bottom"
			selectedLabel=""
			deselectLabel=""
			selectLabel=""
			@select="onSelect"
			@remove="onRemove"
			@search-change="searchByText"
			v-bind="$attrs"
		>
			<template #noResult> No se encontraron elementos. </template>
			<template #noOptions> La lista esta vacia. </template>
			<template #tag="{ option, remove }">
				<span
					@click="() => remove(option)"
					class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary cursor-pointer"
				>
					<p>
						{{ label_handle(option) }}
					</p>
					<svg
						class="-mr-0.5 ml-1.5 h-3 w-3 text-primary/50"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 24 24"
					>
						<path
							fill="none"
							stroke="currentColor"
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M18 6L6 18M6 6l12 12"
						/>
					</svg>
				</span>
			</template>
		</VueMultiSelect>
		<!-- Validator -->
		<template v-if="validator">
			<Validator v-bind="{ name, validator, tooltip }" />
		</template>
	</div>
</template>

<style>
.BaseSelect {
}
</style>
