<script setup>
import { required } from "@/utils/validations";

// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";
import { useSelectStore } from "@/stores/selects";

// Importing Store
import { storeToRefs } from "pinia";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import { useProfile } from "@/stores/profile";
import usersService from "@/services/user.service";
import profileService from "@/services/profile.service";
import BaseBackButton from "@/components/base/BaseBackButton.vue";
import Swal from "sweetalert2";
import alerts from "@/utils/alerts";

const user_service = usersService();
const user_profile_service = profileService();
const router = useRouter();

// Extracting Store Data
const ProfileSotre = useProfile();
const { params } = useRoute();
const SelectStore = useSelectStore();
const { options } = storeToRefs(SelectStore);
const { form, form_rules } = storeToRefs(ProfileSotre);

const { id } = params;
const findUser = ref({});

const users = ref([]);
const userAmbassador = ref([]);
const userInstrunctors = ref([]);

const usersGestor = ref([]);
const usersPsychosocial = ref([]);
const usersMethodologicalSupport = ref([]);
const userSupportTracingMonitoring = ref([]);

const getUserByRol = (rol, data) =>
	data
		?.filter((user) => user?.roles?.[0]?.slug === rol)
		?.map((item) => ({ label: item.name, value: item.id }));
const fetchOne = async () => {
	await user_service.getOne(id);
};

const getAll = async () => {
	await user_service.get();
};

onUnmounted(() => {
	v$.value.$reset();
	ProfileSotre.clearInputs();
});

onMounted(async () => {
	// Find user, fill inputs
	await fetchOne();
	let items = user_service.data.one;
	findUser.value = items;
	form.value.user_id = id;
	// Get all roles
	// const { data: { items: { data: dataRoles } } } = await getAllRoles();
	/* const { data: dataSelects } = await getSelectAll();
    roles.value = dataSelects?.roles; //data.map((item) => ({ label: item.name, value: item.slug })); */
	//Get all user, to add

	await user_service.usersNoPaginate();

	const users_data = user_service.data.no_paginate;

	users.value = users_data?.map((user) => ({
		label: user.name,
		value: user.id,
	}));
	usersGestor.value = getUserByRol("gestores_culturales", users_data);
	usersPsychosocial.value = getUserByRol("psicosocial", users_data);
	usersMethodologicalSupport.value = getUserByRol(
		"apoyo_metodologico",
		users_data
	);
	userSupportTracingMonitoring.value = getUserByRol(
		"apoyo_seguimiento_monitoreo",
		users_data
	);
	userAmbassador.value = getUserByRol("lider_embajador", users_data);
	userInstrunctors.value = getUserByRol("lider_instructor", users_data);

	ProfileSotre.fillInputs(items);
});

const get_form_rules = computed(() => {
	if (form.value.role_id === "gestores_culturales") {
		return {
			...form_rules.value,
			psychosocial_id: { required },
			methodological_support_id: { required },
		};
	} else if (
		form.value.role_id === "monitor_cultural" ||
		form.value.role_id === "embajador" ||
		form.value.role_id === "instructor"
	) {
		return {
			...form_rules.value,
			...(form.value.role_id === "embajador" && {
				ambassador_leader_id: { required },
			}),
			...(form.value.role_id === "instructor" && {
				instructor_leader_id: { required },
			}),
			psychosocial_id: { required },
			gestor_id: { required },
			support_tracing_monitoring_id: { required },
		};
	} else {
		return {
			...form_rules.value,
		};
	}
});

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(get_form_rules, form);

const onSubmit = async () => {
	const valid = await v$.value.$validate();
	if (valid) {
		try {
			// Si no hay perfil, crearlo
			if (!findUser.value?.profile) {
				await user_profile_service.create(form.value);
				alerts.custom("", "Perfil añadido!", "success");
				// findUser.value.profile = items;
			} else {
				const res = await user_profile_service.update(
					findUser.value?.profile?.id,
					form.value
				);
				if (res.status == 201) {
					alerts.custom("", "Perfil actualizado!", "success");
					router.push({ name: "users.index" });
				}
				if (res.status == 422) {
					let error = res.response.data.items;
					if (error.email) {
						alerts.custom(
							"Error al Crear Usuario",
							error.email.at(0),
							"error"
						);
					} else if (error.document_number) {
						alerts.custom(
							"Error al Crear Usuario",
							error.document_number.at(0),
							"error"
						);
					} else {
						alerts.custom(
							"Error al Crear Usuario",
							"Desconocido?",
							"error"
						);
					}
				}
			}
		} catch (error) {
			const {
				response: { response },
			} = error;
			alerts.custom("", "Error al crear el perfil", "error");
		}
	}
};

const onDelete = () => {
	Swal.fire({
		icon: "question",
		html: "<p>Desea eliminar el perfil del usuario ?</p>",
		cancelButtonText: "No",
		confirmButtonText: "Si",
		showDenyButton: true,
	}).then(async (params) => {
		if (params.isConfirmed) {
			await deleted(findUser.value?.profile?.id);
			router.push({ name: "users.index" });
			alerts.custom("", "Perfil eliminado!", "info");
		}
	});
};
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
		<BaseBackButton />
		<h2 class="intro-y text-lg font-medium mr-auto mt-5">
			Actualización del Usuario ({{ findUser?.name }})
		</h2>
	</div>
	<div
		class="intro-y box py-4 sm:py-5 mt-5 px-1 sm:px-10 pt-4 border-t border-slate-200/60 dark:border-darkmode-400"
	>
		<form @submit.prevent="onSubmit" class="my-5">
			<div
				class="intro-y grid grid-cols-1 flex-col items-start gap-1 mt-2"
			>
				<!-- NOMBRES , NUMBER DOC -->
				<div
					class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4"
				>
					<BaseInput
						:value="form.contractor_full_name"
						type="text"
						label="Nombres y apellidos contratista"
						tooltip="Ingrese los nombres y apellidos del contratista"
						placeholder="Nombres y apellidos contratista"
						name="contractor_full_name"
						v-model="form.contractor_full_name"
						:validator="v$"
					/>

					<BaseInput
						:value="form.document_number"
						type="number"
						label="Número de identidad"
						tooltip="Ingrese el número de identidad"
						placeholder="Ingrese el número de identidad"
						name="document_number"
						v-model="form.document_number"
						:validator="v$"
					/>

					<BaseInput
						:value="form.email"
						type="text"
						label="Correo"
						tooltip="Ingrese correo"
						placeholder="Ingrese el correo"
						name="email"
						v-model="form.email"
						:validator="v$"
					/>
				</div>

				<!-- NAC,  ROL -->
				<div
					class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4"
				>
					<BaseSelect
						label="NAC"
						tooltip="Ingrese el NAC"
						placeholder="Seleccione"
						name="nac_id"
						v-model="form.nac_id"
						:options="options.nacs"
						:validator="v$"
					/>

					<BaseSelect
						label="Rol"
						tooltip="Seleccione el Rol"
						placeholder="Seleccione"
						name="role_id"
						v-model="form.role_id"
						:options="options.roles"
						:validator="v$"
					/>
				</div>
				<div
					v-if="form.role_id == 'gestores_culturales'"
					class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4 w-full intro-x"
				>
					<BaseSelect
						label="Psicosocial"
						tooltip="Psicosocial"
						placeholder="Seleccione"
						name="psychosocial_id"
						v-model="form.psychosocial_id"
						:options="usersPsychosocial"
						:validator="v$"
					/>

					<BaseSelect
						label="Apoyo metodológico"
						tooltip="Apoyo metodológico"
						placeholder="Seleccione"
						name="methodological_support_id"
						v-model="form.methodological_support_id"
						:options="usersMethodologicalSupport"
						:validator="v$"
					/>

					<BaseSelect
						label="Apoyo al seguimiento y monitoreo"
						tooltip="Apoyo al seguimiento  y monitoreo"
						placeholder="Seleccione"
						name="support_tracing_monitoring_id"
						v-model="form.support_tracing_monitoring_id"
						:options="userSupportTracingMonitoring"
						:validator="v$"
					/>
				</div>

				<div
					v-if="
						form.role_id == 'monitor_cultural' ||
						form.role_id === 'embajador' ||
						form.role_id === 'instructor'
					"
					class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4 w-full intro-x"
				>
					<BaseSelect
						v-if="form.role_id === 'embajador'"
						label="Líder Embajador"
						tooltip="Líder Embajador"
						placeholder="Seleccione"
						name="ambassador_leader_id"
						v-model="form.ambassador_leader_id"
						:options="userAmbassador"
						:validator="v$"
					/>

					<BaseSelect
						v-if="form.role_id === 'instructor'"
						label="Líder Instructor"
						tooltip="Líder tructor"
						placeholder="Seleccione"
						name="instructor_leader_id"
						v-model="form.instructor_leader_id"
						:options="userInstrunctors"
						:validator="v$"
					/>

					<BaseSelect
						label="Gestor"
						tooltip="Gestor"
						placeholder="Seleccione"
						name="gestor_id"
						v-model="form.gestor_id"
						:options="usersGestor"
						:validator="v$"
						v-if="form.role_id === 'monitor_cultural'"
					/>

					<BaseSelect
						label="Psicosocial"
						tooltip="Psicosocial"
						placeholder="Seleccione"
						name="psychosocial_id"
						v-model="form.psychosocial_id"
						:options="usersPsychosocial"
						:validator="v$"
						v-if="form.role_id == 'monitor_cultural'"
					/>

					<BaseSelect
						label="Apoyo al seguimiento y al monitoreo"
						tooltip="Apoyo al seguimiento y al monitoreo"
						placeholder="Seleccione"
						name="support_tracing_monitoring_id"
						v-model="form.support_tracing_monitoring_id"
						:options="userSupportTracingMonitoring"
						:validator="v$"
					/>
				</div>
			</div>

			<button class="btn btn-primary mt-5" type="submit">
				Actualizar
			</button>
			<button
				v-if="findUser?.profile"
				class="btn bg-red-600 text-white mt-5 ml-5"
				type="button"
				@click="onDelete"
			>
				Eliminar
			</button>
		</form>
	</div>
</template>
