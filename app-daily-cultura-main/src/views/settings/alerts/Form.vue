<script setup lang="ts">
// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";
import { required } from "@/utils/validations";

// Importing Components
import Input from "@/components/base/Input.vue";
import Select from "@/components/base/Select.vue";
import Textarea from "@/components/base/Textarea.vue";
import FormHeader from "@/components/base/FormHeader.vue";

// Composables
import alerts from "@/utils/alerts";
import useApi from "@/utils/useApi";

const { create } = useApi();

const form = reactive({
	type: "",
	title: "",
	description: "",
});

const form_rules = computed(() => ({
	type: { required },
	title: { required },
	description: { required },
}));

const v$ = useVuelidate(form_rules, form);

const types = [
	{
		label: "Informacion",
		value: "info",
	},
	{
		label: "Critico",
		value: "critical",
	},
	{
		label: "Advertencia",
		value: "warn",
	},
	{
		label: "Completado",
		value: "achievement",
	},
];

// Submiting Form
const onSubmit = async () => {
	const valid = await v$.value.$validate();

	if (valid) {
		await create("alerts", form).then((response) => {
			if (response.status >= 200 && response.status <= 300) {
				alerts.create();
			}
		});
	} else {
		alerts.validation();
	}
};
</script>

<template>
	<FormHeader> Creacion de Alerta </FormHeader>
	<div class="intro-y box mt-5 p-5">
		<form
			@submit.prevent="onSubmit"
			class="space-y-8 divide-y divide-slate-200"
		>
			<div>
				<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
					<Input
						type="text"
						name="title"
						label="TITULO *"
						v-model="form.title"
						:validator="v$"
					/>
					<Select
						name="type"
						label="TIPO"
						v-model="form.type"
						:options="types"
						:validator="v$"
					/>
					<Textarea
						name="description"
						label="DESCRIPCION"
						v-model="form.description"
						:validator="v$"
					/>
				</div>
			</div>
			<div class="pt-5">
				<div class="flex justify-end gap-x-4">
					<button type="submit" class="btn btn-primary">
						Guardar
					</button>
				</div>
			</div>
		</form>
	</div>
</template>
