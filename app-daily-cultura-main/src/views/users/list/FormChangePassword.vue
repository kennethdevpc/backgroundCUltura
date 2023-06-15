<script setup>
// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";
import { useUser } from "../../../stores/user";
// Importing Store
import { storeToRefs } from "pinia";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import usersService from "../../../services/user.service";
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import alerts from "@/utils/alerts";
const user_store = useUser();
const { form_change_password, form_change_password_rules } =
	storeToRefs(user_store);
const user_service = usersService();
const router = useRouter();

// Extracting Store Data

const { params } = useRoute();

const { id } = params;
const findUser = ref({});

const fetchOne = async () => {
	await user_service.getOne(id);
};

onMounted(async () => {
	// Find user, fill inputs
	await fetchOne();
	let items = user_service.data.one;
	findUser.value = items;
	form_change_password.value.id = id;
});

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_change_password_rules, form_change_password);

const onSubmit = async () => {
	const valid = await v$.value.$validate();
	if (valid) {
		try {
			await user_service.changePassword(form_change_password.value);
			alerts.custom("", "Contraseña actualizada", "success");
			user_store.$reset();
			v$.value.$reset();
			router.push({ name: "users.index" });
		} catch (error) {
			alerts.custom("", "Error al cambiar contraseña", "error");
		}
	}
};
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
		<BaseBackButton />
		<h2 class="intro-y text-lg font-medium mr-auto mt-5">
			Cambio de contraseña del usuario ({{ findUser?.name }})
		</h2>
	</div>
	<div
		class="intro-y box py-4 sm:py-5 mt-5 px-1 sm:px-10 pt-4 border-t border-slate-200/60 dark:border-darkmode-400"
	>
		<form @submit.prevent="onSubmit" class="my-5" autocomplete="nope">
			<div
				class="intro-y grid grid-cols-1 flex-col items-start gap-1 mt-2"
			>
				<!-- NOMBRES , NUMBER DOC -->
				<div
					class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4"
				>
					<BaseInput
						:value="form_change_password.password"
						type="password"
						label="Ingresa tu nueva contraseña"
						tooltip="Ingresa tu nueva contraseña"
						placeholder="*******"
						name="password"
						v-model="form_change_password.password"
						:validator="v$"
					/>

					<BaseInput
						:value="form_change_password.password_confirmation"
						type="password"
						label="Confirma tu nueva contraseña"
						tooltip="Confirma tu nueva contraseña"
						placeholder="*******"
						name="password_confirmation"
						v-model="form_change_password.password_confirmation"
						:validator="v$"
					/>
				</div>
			</div>

			<button class="btn btn-primary mt-5" type="submit">
				Actualizar
			</button>
		</form>
	</div>
</template>
