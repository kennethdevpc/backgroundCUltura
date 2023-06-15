<script setup lang="ts">
import { loading } from "@/utils/loading";
import { scroll_top } from "@/utils/scroll";
import mixins from "@/mixins";
import permissions from "@/permissions";
import servicesConsecutive from "@/services/generals.service";
import servicesGeneralSelects from "@/services/general_selects.service";
import strengtheningSuperMonInsService from "@/services/strengtheningSuperMonIns.service";

// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import Management from "@/components/base/Management.vue";
import alerts from "@/utils/alerts";

// Store
import { useStrengtheningSuperMonIns } from "@/stores/strengtheningSuperMonIns";
import { storeToRefs } from "pinia";
import { useSelectStore } from "@/stores/selects";
import { Select } from "@/utils/useSelect";
import useApi from "@/utils/useApi";
import FormHeader from "@/components/base/FormHeader.vue";
const disabledButton = ref(false);
// Extracting Store Data
const store = useStrengtheningSuperMonIns();
const { form, form_rules, form_options } = storeToRefs(store);

// Extracting Select Data
const select_store = useSelectStore();
const { options: lists } = storeToRefs(select_store);

// Extracting Services
const services = strengtheningSuperMonInsService();
const consecutive_services = servicesConsecutive();

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true });

// Images
const files = reactive({
	I: [],
	II: [],
});

// Images - First Input
const handleAddFile = (err, val, field) => {
	if (err) return;
	const { file } = val;
	form.value[field] = file;
};

const handleRemoveFile = (field) => {
	form.value[field] = null;
};

const route = useRoute();

const instance = reactive({
	status: "",
	consecutive: "",
	reject_message: "",
});

const editing = computed(() => {
	return route.params.id ? true : false;
});

const fetch_consecutive = async () => {
	return await consecutive_services
		.getConsecutive("strengthening_super_mons_insts", "FSMI")
		.then(() => {
			form.value.consecutive = consecutive_services.data.value;
			instance.consecutive = consecutive_services.data.value;
		});
};

const fetchOne = async () => {
	return await services.getOne(route.params.id);
};

const {
	data: other_selects,
	getRolesCustom,
	getRoles,
	getUsers,
	getPecs,
} = servicesGeneralSelects;

// watch(computed(() => form.value.nac_id), async (new_val, old_val) => {
// 	if (new_val != old_val) {
// 		if (new_val != services.data.one.nac_id) {
// 			form.value.role_id = ''
// 			form.value.supervised_user_full_name = ''
// 		}
// 		await getRolesCustom(form.value.nac_id);
// 	}
// });

watch(computed(() => form.value.role_id), async () => {
	if (form.value.role_id) {
		await getUsers(form.value.role_id);
	}
});

watch(computed(() => form.value.supervised_user_full_name), async () => {
	if (form.value.supervised_user_full_name) {
		await getPecs(form.value.supervised_user_full_name);
	}
});

onBeforeMount(async () => {
	store.$reset();
	v$.value.$reset();
	if (editing.value) {
		await fetchOne().catch(() => {
			mixins.not_found_by_id();
		});
		const {
			created_at,
			deleted_at,
			updated_at,
			status,
			reviewed_id,
			reject_message,
			nac_id,
			role_id,
			supervised_user_full_name,
			consecutive,
			...rest_binnacle
		} = services.data.one;

		// await getRoles(nac_id);
		await getUsers(role_id);

		store.$patch((state) => {
			Object.assign(state.form, {
				...rest_binnacle,
				nac_id,
				role_id,
				supervised_user_full_name,
				consecutive,
				status,
			});
		});

		Object.assign(instance, {
			status,
			reject_message,
			consecutive,
		});
	} else {
		await fetch_consecutive();
	}
});


// Submiting Form
const onSubmit = async () => {
	const valid = await v$.value.$validate();
	if (valid) {
		disabledButton.value = true;
		if (editing.value) {
			await services.update(services.data.one.id, store.form).finally(() => {
				disabledButton.value = false;
			});
		} else {
			await fetch_consecutive().finally(() => {
				services.create(store.form).then(async (response) => {
					if (response.data.success) {
						store.$reset();
						await store.clear();
						v$.value.$reset();
						scroll_top();
						await fetch_consecutive();
						files.I = [];
						files.II = [];
					}
				}).finally(() => {
					disabledButton.value = false;
				});
			});
		}
	} else {
		alerts.validation();
	}
};
</script>

<template>
	<FormHeader
		:state="{ consecutive: instance.consecutive, status: instance.status, reject_message: instance.reject_message }">
		{{
			editing
			? `Edición Fortalecimiento a la supervisión monitores e instructores #${services.data.one.id}`
			: `Fortalecimiento a la supervisión monitores e instructores`
		}}
	</FormHeader>
	<Management class="mt-5" v-if="editing ? permissions.strengtheningSuperMonIns.management() : false" @send="
		(payload) =>
			mixins.methods.send_management(
				'strengthening_super_mons_insts',
				services.data.one.id,
				payload
			)
	" />
	<div class="intro-y box mt-5">
		<fieldset :disabled="permissions.strengtheningSuperMonIns.no_edit()">
			<form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 space-y-8">
				<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly">
					<div class="w-full">
						<BaseInput type="date" label="FECHA DE REVISIÓN *" tooltip="Ingrese la fecha de revisión"
							placeholder="Fecha" name="revision_date" v-model="form.revision_date" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect label="NAC *" tooltip="Seleccionar NAC" placeholder="Seleccione" name="nac_id"
							v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect label="ROL *" tooltip="Seleccionar ROL" placeholder="Seleccione" name="role_id"
							v-model="form.role_id" :options="(form_options.roles as Select[]) || []" :validator="v$" />
					</div>
					<div class="w-full">
						<BaseSelect :disabled="form.role_id == ''" label="USUARIO SUPERVISADO *"
							tooltip="Seleccionar nombre" placeholder="Seleccione" name="supervised_user_full_name"
							v-model="form.supervised_user_full_name" :options="(other_selects.users as Select[]) || []"
							:validator="v$" />
					</div>
				</section>
				<h2 class="text-xl font-bold uppercase">Criterios de evaluación</h2>
				<section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly">

					<BaseInput type="date" label="FECHA DE REGISTRO EN PLATAFORMA *" tooltip="Ingresar fecha del PEC"
						name="platform_registration_date" v-model="form.platform_registration_date" :validator="v$" />

					<BaseInput name="address" type="text" label="Dirección" v-model="form.address" :validator="v$" />

					<BaseRadio label="¿CUMPLIO? *" tooltip="" name="pec_reached_target" v-model="form.pec_reached_target"
						:options="form_options.radio" :validator="v$" required />

					<BaseRadio label="FICHA PEDAGOGICA REGISTRADA EN PLATAFORMA *" tooltip=""
						name="pedagogicals_reached_target" v-model="form.pedagogicals_reached_target"
						:options="form_options.radio" :validator="v$" required />

					<BaseRadio label="LISTADO DE ASISTENCIA *" tooltip="" name="attendance_list"
						v-model="form.attendance_list" :options="form_options.radio" :validator="v$" required />

					<BaseRadio label="EL MONITOR INICIO LA JORNADA A LA HORA REGISTRADA EN EL PEC? *" tooltip=""
						name="validated_pec_time" v-model="form.validated_pec_time" :options="form_options.radio"
						:validator="v$" required />
				</section>

				<section class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mt-5">
					<BaseTextarea label="DESCRIPCIÓN DE LA JORNADA *" tooltip="" placeholder="Descripción de la jornada"
						name="description" v-model="form.description" :validator="v$" rows="3" required />

					<BaseTextarea label="OBSERVACIONES *" tooltip="" placeholder="Observaciones" name="comments"
						v-model="form.comments" :validator="v$" rows="3" required />
				</section>

				<section class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
					<BaseFilepond label="EVIDENCIA DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen"
						name="development_activity_image" ref="development_activity_image"
						:to_edit="editing && services.data.one != null ? services.data.one.development_activity_image : null"
						v-model="form.development_activity_image" @addfile="
							(error, event) =>
								handleAddFile(error, event, 'development_activity_image')
						" @removefile="() => handleRemoveFile('development_activity_image')" :files="files.I" :validator="v$" />
					<BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen"
						name="evidence_participation_image" ref="evidence_participation_image"
						:to_edit="editing && services.data.one != null ? services.data.one.evidence_participation_image : null"
						v-model="form.evidence_participation_image" @addfile="
							(error, event) =>
								handleAddFile(error, event, 'evidence_participation_image')
						" @removefile="() => handleRemoveFile('evidence_participation_image')" :files="files.II" :validator="v$" />
				</section>
				<div v-if="!permissions.strengtheningSuperMonIns.no_edit()" class="flex justify-center">
					<button :disabled="disabledButton" type="submit" class="btn btn-primary w-24 ml-2">
						{{ editing ? "Actualizar" : "Ingresar" }}
					</button>
				</div>
			</form>
		</fieldset>
	</div>
</template>
