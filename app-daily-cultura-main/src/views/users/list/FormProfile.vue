<script setup lang="ts">
import FormHeader from "@/components/base/FormHeader.vue";
import useApi from "@/utils/useApi";
import Input from "@/components/base/Input.vue";
import Select from "@/components/base/Select.vue";
import Swal from "sweetalert2";
import { storeToRefs } from "pinia";
import usersService from "@/services/user.service";
import profileService from "@/services/profile.service";
import useVuelidate from "@vuelidate/core";
import { useProfile } from "@/stores/profile";
import { useSelectStore } from "@/stores/selects";
import alerts from "@/utils/alerts";

const user_profile_service = profileService();
const SelectStore = useSelectStore();
const { options } = storeToRefs(SelectStore);

const ProfileStore = useProfile();

const { form, form_rules } = storeToRefs(ProfileStore);

const v$ = useVuelidate(form_rules, form);

const router = useRouter();
const route = useRoute();

const { find, findOne, create, delete: deleteUser } = useApi();

const userByRol = (rol: string, data: Array<any>) => {
	return data
		.filter((user) => user.roles[0].slug === rol)
		.map((item) => ({ label: item.name, value: item.id }));
};

const { state: users } = useAsyncState(async () => {
	return await find<Array<{ name: string; id: string; roles: any[] }>>(
		"basicUsers"
	).then((response) => {
		const users = response.data;

		return {
			users,
			ambassadors: userByRol("lider_embajador", users),
			instructors: userByRol("lider_instructor", users),
			gestors: userByRol("gestores_culturales", users),
			psychosocials: userByRol("psicosocial", users),
			methodologicalSupports: userByRol("apoyo_metodologico", users),
			trackingMonitoringSupports: userByRol(
				"apoyo_seguimiento_monitoreo",
				users
			),
		};
	});
}, null);

const { state: user } = useAsyncState(async () => {
	if (route.params.id) {
		return await findOne<{ items: any }>(
			"users",
			route.params.id as string
		).then((response) => {
			const { email, profile, id, ...rest } = response.data.items;

			Object.assign(form.value, {
				email: email,
				user_id: id,
				contractor_full_name: profile.contractor_full_name,
				nac_id: profile.nac_id,
				document_number: profile.document_number,
				role_id: profile.role.slug,
				gestor_id: profile.gestor_id,
				support_tracing_monitoring_id:
					profile.support_tracing_monitoring_id,
				psychosocial_id: profile.psychosocial_id,
				ambassador_leader_id: profile.ambassador_leader_id,
				instructor_leader_id: profile.instructor_leader_id,
				methodological_support_id: profile.methodological_support_id,
			});

			return response.data.items;
		});
	}
}, null);

onUnmounted(() => {
	v$.value.$reset();
	ProfileStore.$reset();
});

function onDelete() {
	Swal.fire({
		icon: "question",
		html: "<p>Desea eliminar el perfil del usuario ?</p>",
		cancelButtonText: "No",
		confirmButtonText: "Si",
		showDenyButton: true,
	}).then(async (params) => {
		if (params.isConfirmed) {
			// await deleteUser("users", user.value.id);
			// router.push({ name: "users.index" });
			// alerts.custom("", "Perfil eliminado!", "info");
		}
	});
}

async function onSubmit() {
	const valid = await v$.value.$validate();

	if (valid) {
		try {
			// Si no hay perfil, crearlo
			if (!user.value.profile) {
				create("profiles", form.value);
				await user_profile_service.create(form.value);
			} else {
				const res = await user_profile_service.update(
					user.value.profile.id,
					form.value
				);

				if (res.status >= 200 && res.status <= 300) {
					alerts.custom("", "Perfil actualizado!", "success");
					router.push({ name: "users.index" });
				}

				if (res.status == 422) {
					let error = res.data.items;
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
			alerts.custom("", "Error al crear el perfil", "error");
		}
	} else {
		alerts.validation();
	}
}
</script>

<template>
	<FormHeader> Actualización de Usuario </FormHeader>
	<div class="intro-y box p-5 mt-5">
		<form
			@submit.prevent="onSubmit"
			class="space-y-8 divide-y divide-slate-200"
		>
			<div>
				<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
					<Input
						type="text"
						name="contractor_full_name"
						label="NOMBRE Y APELLIDO DEL CONTRATISTA *"
						v-model="form.contractor_full_name"
						:validator="v$"
					/>
					<Input
						type="number"
						name="document_number"
						label="NÚMERO DE IDENTIDAD"
						v-model="form.document_number"
						:validator="v$"
					/>
					<Input
						type="text"
						name="email"
						label="CORREO"
						v-model="form.email"
						:validator="v$"
					/>
					<Select
						name="nac_id"
						label="NAC"
						v-model="form.nac_id"
						:options="options.nacs"
						:validator="v$"
					/>

					<Select
						name="role_id"
						label="ROL"
						v-model="form.role_id"
						:options="options.roles"
						:validator="v$"
					/>

					<template v-if="form.role_id == 'gestores_culturales'">
						<Select
							name="psychosocial_id"
							label="PSICOSOCIAL"
							v-model="form.psychosocial_id"
							:options="users.psychosocials"
							:validator="v$"
						/>
						<Select
							label="APOYO METODOLÓGICO"
							name="methodological_support_id"
							v-model="form.methodological_support_id"
							:options="users.methodologicalSupports"
							:validator="v$"
						/>
					</template>

					<template v-if="form.role_id === 'embajador'">
						<Select
							name="ambassador_leader_id"
							label="LÍDER EMBAJADOR"
							v-model="form.ambassador_leader_id"
							:options="users.ambassadors"
							:validator="v$"
						/>
					</template>

					<template v-if="form.role_id === 'instructor'">
						<Select
							label="LÍDER INSTRUCTOR"
							name="instructor_leader_id"
							v-model="form.instructor_leader_id"
							:options="users.instructors"
							:validator="v$"
						/>
					</template>

					<template v-if="form.role_id === 'monitor_cultural'">
						<Select
							label="GESTOR"
							name="gestor_id"
							v-model="form.gestor_id"
							:options="users.gestors"
							:validator="v$"
						/>
						<Select
							label="PSICOSOCIAL"
							name="psychosocial_id"
							v-model="form.psychosocial_id"
							:options="users.psychosocials"
							:validator="v$"
						/>
					</template>
					<template
						v-if="
							form.role_id == 'monitor_cultural' ||
							form.role_id === 'embajador' ||
							form.role_id === 'instructor' ||
							form.role_id === 'gestores_culturales'
						"
					>
						<Select
							label="APOYO AL SEGUIMIENTO Y MONITOREO"
							name="support_tracing_monitoring_id"
							v-model="form.support_tracing_monitoring_id"
							:options="users.trackingMonitoringSupports"
							:validator="v$"
						/>
					</template>
				</div>
			</div>
			<div class="pt-5">
				<div class="flex justify-end gap-x-4">
					<button type="submit" class="btn btn-primary">
						Actualizar
					</button>
					<button
						@click="onDelete"
						type="button"
						class="btn btn-danger"
					>
						Eliminar
					</button>
				</div>
			</div>
		</form>
	</div>
</template>
