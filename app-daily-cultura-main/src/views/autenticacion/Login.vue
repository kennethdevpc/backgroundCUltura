<script setup lang="ts">
import { required, helpers, numeric } from "@vuelidate/validators";
import { useOnboardingStore } from "@/stores/onboarding";
import { useTopMenuStore } from "@/stores/top-menu";
import { useSelectStore } from "@/stores/selects";
import { useVuelidate } from "@vuelidate/core";
import BaseInput from "@/components/base/Input.vue";
import dom from "@left4code/tw-starter/dist/js/dom";
import Swal from "sweetalert2";
import { useAccessStore } from "@/stores/access";
import Alert from "@/components/Alert.vue";
import useApi from "@/utils/useApi";

const onboarding_store = useOnboardingStore();
const top_menu_store = useTopMenuStore();
const access_store = useAccessStore();
const select_store = useSelectStore();

const route = useRoute();
const router = useRouter();
const something = ref();

const form = reactive({
	email: "",
	password: "",

	// email: "5676797567",
	// password: "root",
});

const form_rules = computed(() => {
	return {
		email: {
			required: helpers.withMessage("Se requiere el usuario", required),
			numeric: helpers.withMessage("El usuario es número", numeric),
		},
		password: {
			required: helpers.withMessage(
				"Se requiere la contraseña",
				required
			),
		},
	};
});

const v$ = useVuelidate(form_rules, form);

async function on_submit() {
	let valid = v$.value.$validate();

	if (valid) {
		const response = await onboarding_store.login(form);
		if (response.status == 200) {
			// Swal.fire('Autenticación', 'Ha iniciado sesión con éxito, redireccionando...', 'success')
			router.push({ name: "dashboard" });
		} else {
			Swal.fire("Oops!", `${response.data.message}`, "warning");
			form.password = "";
		}
	} else {
		Swal.fire(
			"Validación",
			"Por favor valide los campos solicitados.",
			"error"
		);
	}
}

onMounted(() => {
	top_menu_store.$reset();
	onboarding_store.$reset();
	access_store.$reset();
	dom("body").removeClass("main").removeClass("error-page").addClass("login");
});

const { find } = useApi();

const { state: alerts, execute: refreshAlerts } = useAsyncState(
	() =>
		find<{ items: Array<any> }>("alerts").then(
			(response) => response.data.items
		),
	null,
	{ resetOnExecute: false }
);

router.beforeEach(() => {
	refreshAlerts();
});
</script>

<template>
	<div>
		<div class="container sm:px-10">
			<div class="block grid-cols-2 gap-4 xl:grid">
				<!-- BEGIN: Login Info -->
				<div
					class="hidden min-h-screen xl:grid xl:grid-cols-1 xl:content-between py-12"
				>
					<a href="#" class="flex items-center -intro-x">
						<img
							alt="Logo"
							class="w-16"
							src="@/assets/img/logo-white.svg"
						/>
						<p class="text-lg font-bold tracking-wide text-white">
							SSISET - Cultura
						</p>
					</a>
					<div class="mb-12">
						<img
							alt="Logo"
							class="w-1/2 -intro-x"
							src="@/assets/img/government_banner-white.webp"
						/>
					</div>
				</div>
				<!-- END: Login Info -->
				<!-- BEGIN: Login Form -->
				<div class="flex h-screen py-5 my-10 xl:h-auto xl:py-0 xl:my-0">
					<div
						class="w-full px-5 py-8 mx-auto my-auto bg-white rounded-md shadow-md xl:ml-20 dark:bg-darkmode-600 xl:bg-transparent sm:px-8 xl:p-0 xl:shadow-none sm:w-3/4 lg:w-2/4 xl:w-auto"
					>
						<a
							href="#"
							class="xl:hidden flex flex-col items-center -intro-x"
						>
							<img
								alt="Logo"
								class="w-16"
								src="@/assets/img/logo.svg"
							/>
						</a>
						<h2
							class="text-2xl font-bold text-center intro-x xl:text-3xl xl:text-left"
						>
							Iniciar sesión
						</h2>
						<template v-if="alerts != null">
							<div
								:class="{
									'h-28 overflow-y-auto': alerts.length > 1,
								}"
								class="mt-5 flex flex-col gap-y-2 snap-y"
							>
								<template v-for="alert in alerts">
									<Alert :alert="alert" />
								</template>
							</div>
						</template>
						<form @submit.prevent="on_submit">
							<div
								class="mt-8 intro-x flex flex-col gap-6 justify-evenly"
							>
								<div class="w-full">
									<BaseInput
										type="text"
										label="Usuario"
										tooltip=""
										placeholder="Número de documento"
										name="email"
										v-model="form.email"
										:validator="v$"
									/>
								</div>
								<div class="w-full">
									<BaseInput
										type="password"
										label="Contraseña"
										tooltip=""
										placeholder="*********"
										name="password"
										v-model="form.password"
										:validator="v$"
									/>
								</div>
							</div>
							<div
								class="mt-5 text-center intro-x xl:mt-8 xl:text-left"
							>
								<button
									type="submit"
									class="w-full px-4 py-3 align-top btn btn-primary xl:w-32 xl:mr-3"
								>
									Ingresar
								</button>
							</div>
						</form>
					</div>
				</div>
				<!-- END: Login Form -->
			</div>
		</div>
	</div>
</template>
