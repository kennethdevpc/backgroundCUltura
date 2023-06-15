<script setup>
import { required } from "@/utils/validations";
import { storeToRefs } from "pinia";
import { useSelectStore } from "@/stores/selects";
import { useUser } from "../../../stores/user";
import { useVuelidate } from "@vuelidate/core";
import alerts from "@/utils/alerts";
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "../../../components/base/Select.vue";
import profileService from "../../../services/profile.service";
import usersService from "../../../services/user.service";

const user_profile_services = profileService();
const user_services = usersService();

const user_store = useUser();
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);

// { getAll: getAllUsers, usersNoPaginate }
const { form, form_rules } = storeToRefs(user_store);

const roles = ref([]);
const users = ref([]);

const usersGestor = ref([]);
const usersInstrunctors = ref([]);
const usersAmbassador = ref([]);
const usersPsychosocial = ref([]);
const usersMethodologicalSupport = ref([]);
const userSupportTracingMonitoring = ref([]);

const getUserByRol = (rol, data) =>
	data
		?.filter((user) => user?.roles?.[0]?.slug === rol)
		?.map((item) => ({ label: item.name, value: item.id }));

const getAllNoPaginate = async () => {
	await user_services.usersNoPaginate();
};

const fetchtTypeUsers = async () => {
	//Get all user, to add
	await getAllNoPaginate();
	const users_data = user_services.data.no_paginate;

	users.value = users_data?.map((user) => ({
		label: user.name,
		value: user.id,
	}));

	// Build user select depending to rol
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
	usersAmbassador.value = getUserByRol("lider_embajador", users_data);
	usersInstrunctors.value = getUserByRol("lider_instructor", users_data);
};

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
			// gestor_id: { required },
			...(form.value.role_id === "monitor_cultural" && {
				gestor_id: { required },
			}),
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
onUnmounted(() => {
	user_store.$reset();
	v$.value.$reset();
});

onMounted(async () => {
	user_store.$reset();
	v$.value.$reset();
	// Get all roles
	// const { data: { items: { data: dataRoles } } } = await getAllRoles();

	roles.value = options.value.roles; //data.map((item) => ({ label: item.name, value: item.slug }));
	fetchtTypeUsers();
});

const onSubmit = async () => {
	const valid = await v$.value.$validate();
	if (valid) {
		const res = await user_services.create({
			user: {
				name: form.value.contractor_full_name,
				password: form.value.password,
				email: form.value.email,
			},
			profile: {
				contractor_full_name: form.value.contractor_full_name,
				nac_id: form.value.nac_id,
				document_number: form.value.document_number,
				role_id: form.value.role_id,
				methodological_support_id: form.value.methodological_support_id,
				psychosocial_id: form.value.psychosocial_id,
				support_tracing_monitoring_id:
					form.value.support_tracing_monitoring_id,
				ambassador_leader_id: form.value.ambassador_leader_id,
				instructor_leader_id: form.value.instructor_leader_id,
				gestor_id: form.value.gestor_id,
			},
		});

		if (res.status == 201) {
			alerts.custom("", "Usuario creado exitosamente!", "success");

			user_store.$reset();
			v$.value.$reset();
			fetchtTypeUsers();
		}

		// if (res.status == 200) {
		//     const user_id = user_services.data.user_id
		//     await user_profile_services.create({
		//         contractor_full_name: form.value.contractor_full_name,
		//         nac_id: form.value.nac_id,
		//         document_number: form.value.document_number,
		//         role_id: form.value.role_id,
		//         methodological_support_id: form.value.methodological_support_id,
		//         psychosocial_id: form.value.psychosocial_id,
		//         support_tracing_monitoring_id: form.value.support_tracing_monitoring_id,
		//         ambassador_leader_id: form.value.ambassador_leader_id,
		//         instructor_leader_id: form.value.instructor_leader_id,
		//         user_id: user_id,
		//         gestor_id:form.value.gestor_id
		//     }).then((response) => {
		//         if (response.status == 200) {
		//             alerts.custom('', 'Usuario creado exitosamente!', 'success')

		//             user_store.$reset()
		//             v$.value.$reset();
		//             fetchtTypeUsers();
		//         }
		//     })
		// }

		if (res.status == 400) {
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
	} else {
		alerts.validation();
	}
};
</script>

<template>
	<div class="intro-y flex flex-col items-start gap-1 mt-8">
		<BaseBackButton />
		<h2 class="intro-y text-lg font-medium mr-auto mt-5">
			Creación de Usuario
		</h2>
	</div>
	<div
		class="intro-y box py-4 sm:py-5 px-1 sm:px-10 mt-5 pt-4 border-t border-slate-200/60 dark:border-darkmode-400"
	>
		<form @submit.prevent="onSubmit" class="my-5">
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
				<BaseInput
					:value="form.contractor_full_name"
					type="text"
					label="Nombres y apellidos"
					tooltip="Ingrese nombres y apellidos"
					placeholder="Nombres y apellidos"
					name="contractor_full_name"
					v-model="form.contractor_full_name"
					:validator="v$"
				/>

				<BaseInput
					:value="form.document_number"
					type="number"
					label="Número de identidad"
					tooltip="Ingrese el número de identidad"
					placeholder="Número de identidad"
					name="document_number"
					v-model="form.document_number"
					:validator="v$"
				/>
			</div>

			<div
				class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4"
			>
				<BaseInput
					:value="form.password"
					type="password"
					label="Contraseña"
					tooltip="Ingrese la contraseña"
					placeholder="Contraseña"
					name="password"
					v-model="form.password"
					:validator="v$"
				/>

				<BaseInput
					:value="form.password_confirm"
					type="password"
					label="Reescribe contraseña"
					tooltip="Ingrese de nuevo la contraseña"
					placeholder="Reescribe la contraseña"
					name="password_confirm"
					v-model="form.password_confirm"
					:validator="v$"
				/>
			</div>

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
					:options="roles"
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
				v-if="form.role_id == 'monitor_cultural'"
				class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4 w-full intro-x"
			>
				<BaseSelect
					v-if="form.role_id === 'embajador'"
					label="Líder Embajador"
					tooltip="Líder Embajador"
					placeholder="Seleccione"
					name="ambassador_leader_id"
					v-model="form.ambassador_leader_id"
					:options="usersAmbassador"
					:validator="v$"
				/>

				<BaseSelect
					v-if="form.role_id === 'instructor'"
					label="Líder Instructor"
					tooltip="Líder tructor"
					placeholder="Seleccione"
					name="instructor_leader_id"
					v-model="form.instructor_leader_id"
					:options="usersInstrunctors"
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
				/>

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
					label="Apoyo al seguimiento y al monitoreo"
					tooltip="Apoyo al seguimiento y al monitoreo"
					placeholder="Seleccione"
					name="support_tracing_monitoring_id"
					v-model="form.support_tracing_monitoring_id"
					:options="userSupportTracingMonitoring"
					:validator="v$"
				/>
			</div>

			<div
				v-if="
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
					:options="usersAmbassador"
					:validator="v$"
				/>

				<BaseSelect
					v-if="form.role_id === 'instructor'"
					label="Líder Instructor"
					tooltip="Líder tructor"
					placeholder="Seleccione"
					name="instructor_leader_id"
					v-model="form.instructor_leader_id"
					:options="usersInstrunctors"
					:validator="v$"
				/>

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
					label="Apoyo al seguimiento y al monitoreo"
					tooltip="Apoyo al seguimiento y al monitoreo"
					placeholder="Seleccione"
					name="support_tracing_monitoring_id"
					v-model="form.support_tracing_monitoring_id"
					:options="userSupportTracingMonitoring"
					:validator="v$"
				/>
			</div>

			<div
				class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mt-4"
			>
				<BaseInput
					:value="form.email"
					type="email"
					label="Correo"
					tooltip="Ingrese el correo"
					placeholder="Correo"
					name="email"
					v-model="form.email"
					:validator="v$"
				/>
			</div>
			<button class="btn btn-primary mt-5" type="submit">Ingresar</button>
		</form>
	</div>
</template>
