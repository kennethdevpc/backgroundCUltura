<script lang="ts" setup>
// Importing Overall librearies / Dependencies
import { storeToRefs } from "pinia";
import { useVuelidate } from "@vuelidate/core";
import { loading } from "@/utils/loading";
import services from "@/services/methodologicalAccompaniments.service"
// Testing both imports
import permissions from "@/permissions";
import mixins from "@/mixins";
import alerts from '@/utils/alerts';
import { methodologicalAccompaniments } from "@/stores/methodologicalAccompaniments";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import BaseMultiselect from "@/components/base/Multiselect.vue";
import BaseManagement from "@/components/base/Management.vue";
import Aggregates from '@/components/base/Aggregates.vue'
import BaseSelect from '@/components/base/Select.vue'
import { scroll_top } from "@/utils/scroll";

import servicesConsecutive from '@/services/generals.service'
import FormHeader from "@/components/base/FormHeader.vue";
const consecutive_services = servicesConsecutive()
const methodological_accompaniment_services = services()
const methodologicalAccompaniments_store = methodologicalAccompaniments();
const { form, form_rules, form_options, instance } = storeToRefs(methodologicalAccompaniments_store);

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, {
	$autoDirty: true,
	$lazy: true,
});

// [Computed] => Getting Edit Status
const route = useRoute();
const isEditing = computed(() => (route.params.id ? true : false));
const roles = ref([]);
const assistants = ref([]);

const files = ref({
	development_activity_image: [],
	evidence_participation_image: [],
});
const editing = computed(() => {
	return (route.params.id) ? true : false
})


const fetch_methodological_accompaniment = async () => {
	return await methodological_accompaniment_services.getOne(route.params.id)
}
const users_headers = [
	{
		text: 'ID',
		value: 'id',
	},
	{
		text: 'NOMBRE COMPLETO',
		value: 'monitor_fullname',
	},
	{
		text: 'NUMERO DE CÉDULA',
		value: 'document_number',
	},
]

// [onBeforeMount] => Getting fetched data before loading template
onBeforeMount(async () => {
	await fetch_consecutive()
	// await select_store.dataSelects()

	if (editing.value) {
		methodologicalAccompaniments_store.$reset()
		v$.value.$reset()
		await fetch_methodological_accompaniment().then(() => {
			const { created_at, status, assistants, roles_associate, reject_message, ...rest } = methodological_accompaniment_services.data.one

			methodologicalAccompaniments_store.$patch((state) => {
				Object.assign(state.form, {
					...rest,
					aggregates: {
						assistants: assistants.map(({ pivot, ...rest }) => {
							return {
								...rest
							}
						})
					},
					roles: roles_associate
				})
			})

			instance.value.status = status
			instance.value.reject_message = reject_message
		})
			.catch(() => {
				mixins.not_found_by_id()
			})
	}
	else {
		methodologicalAccompaniments_store.$reset()
		v$.value.$reset()
		await fetch_consecutive()

	}
});
const disabledButton = ref(false);
// [Submit] => Checking Payload before adding or editing
const onSubmit = async () => {
	const valid = await v$.value.$validate()
	if (valid) {
		disabledButton.value = true;
		if (editing.value) {
			await methodological_accompaniment_services.update(methodological_accompaniment_services.data.one.id, methodologicalAccompaniments_store.transpiled_data).finally(() => {
				disabledButton.value = false
			});
		}
		else {
			await fetch_consecutive().finally(() => {
				methodological_accompaniment_services.create(methodologicalAccompaniments_store.transpiled_data).then(async (response) => {
					if (response.data.success) {
						files.value.development_activity_image = []
						files.value.evidence_participation_image = []
						methodologicalAccompaniments_store.$reset()
						v$.value.$reset()
						scroll_top()
						await fetch_consecutive()
					}
				}).finally(() => {
					disabledButton.value = false
				});
			})
		}
	}
	else {
		alerts.validation()
	}
};

const addFile = (err, val, name) => {
	if (err) return;
	const { file, filename } = val;
	form.value[name] = {
		name: "photo1",
		file,
		filename,
	};
};

const removeFile = (name) => {
	form.value[name] = null;
};

// Creating Overall composables
const getSingleLabel = (currOption, allOptions) => {
	if (allOptions.length > 0) {
		return allOptions.find((opt) => opt.value == currOption).label;
	} else {
		return "Sin Datos";
	}
};

const getSingleOption = (options) => {
	if (options.length > 0) {
		return options.map((opt) => opt.value);
	} else {
		return [];
	}
};

const fetch_consecutive = async () => {
	return await consecutive_services.getConsecutive('methodological_accompaniments', 'MA').then(() => {
		form.value.consecutive = consecutive_services.data.value
	})
}


const get_nac = computed(() => {
	return form.value.nac_id
})

watch(get_nac, async (new_val, old_val) => {
	if (new_val != '' && new_val != null) {
		if (new_val != old_val) {
			await get_profile_role_user_nac(new_val)
		}
	}
})


const get_profile_role_user_nac = async (id) => {
	await consecutive_services.getProfileRoleUserNac(id).then((response) => {
		if (response != undefined) {
			let assistants_data = response.data.items.original.assistants
			let roles_data = response.data.items.original.roles


			if (roles_data.length > 0) {
				roles.value = roles_data
			} else {
				alerts.personalized('El nac seleccionado no poseé roles.', 'info')
				roles.value = [];
			}

			if (assistants_data.length > 0) {
				assistants.value = assistants_data
			} else {
				assistants.value = [];
				alerts.personalized('El nac seleccionado no poseé asistentes.', 'info')
				form.value.aggregates.assistants = [];
			}

		}
	})
}



</script>

<template>
	<FormHeader
		:state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
		{{
			isEditing
			? `Edición de Acompañamiento Metodologico (visitas) #${route.params.id}`
			: `Acompañamiento Metodologico (visitas)`
		}}
	</FormHeader>

	<BaseManagement v-if="isEditing ? permissions.methodologicalAccompaniments.management() : false" @send="
		(payload) =>
			mixins.methods.send_management(
				'methodological_accompaniments',
				route.params.id,
				payload
			)
	" class="mt-5" />
	<!-- Form Data -->
	<div class="intro-y box mt-5">
		<fieldset :disabled="permissions.methodologicalAccompaniments.no_edit()">
			<!-- <pre>{{ form }}</pre> -->
			<form @submit.prevent="onSubmit" class="grid gap-3 grid-cols-1 p-5 border-t border-slate-200/60">

				<section class="grid gap-5 grid-cols-1 md:grid-cols-2">
					<BaseInput type="date" label="FECHA *" name="date" placeholder="Fecha" v-model="form.date"
						:validator="v$" />

					<BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
						v-model="form.nac_id" :options="form_options.nacs" :validator="v$" />

				</section>
				<section class="grid gap-5 grid-cols-1 md:grid-cols-2">
					<!-- <BaseSelect name="roles" label="ROL *" placeholder="Roles relacionados al NAC" multiple hideSelected
					                        :options="roles" :validator="v$" v-model="form.roles" /> -->
					<BaseMultiselect :validator="v$" name="roles" v-model="form.roles" :options="getSingleOption(roles)"
						:custom-label="
							(opt) => getSingleLabel(opt, roles)
						" label-name="ROLES " multiple />
					<!-- 
												<BaseSelect name="aspects" label="ASPECTOS  *" placeholder="ASPECTOS PREVIOS A TENER EN CUENTA" multiple hideSelected
					                        :options="form_options.aspects" :validator="v$" v-model="form.aspects" /> -->
					<BaseMultiselect :validator="v$" name="aspects" v-model="form.aspects"
						:options="getSingleOption(form_options.aspects)" :custom-label="
							(opt) => getSingleLabel(opt, form_options.aspects)
						" label-name="ASPECTOS PREVIOS A TENER EN CUENTA" multiple />
				</section>
				<!-- #4 -->
				<section class="grid gap-5 grid-cols-1">
					<BaseTextarea label="OTROS: ¿CUAL?" placeholder="Descripción" name="others" v-model="form.others"
						:validator="v$" rows="3" />
					<BaseTextarea label="OBJETIVO DE LA VISITA: *" placeholder="Objetivo de la visita"
						name="objective_visit" v-model="form.objective_visit" :validator="v$" rows="3" />

				</section>

				<!-- #5 -->
				<section class="grid gap-5 grid-cols-1 md:grid-cols-2">

					<BaseTextarea class="col-span-full" label="ASPECTOS PREVIOS A TENER EN CUENTA: *"
						placeholder="Aspectos previos a tener en cuenta" name="aspects_comments"
						v-model="form.aspects_comments" :validator="v$" rows="3" />
					<BaseTextarea class="col-span-full" label="OBSERVACIONES *" placeholder="Observaciones" name="comments"
						v-model="form.comments" :validator="v$" rows="3" />
				</section>

				<!-- #6 -->
				<section class="grid gap-5 grid-cols-1 md:grid-cols-2">
					<BaseFilepond label="FOTO DEL DESARROLLO *" tooltip="Arrastra una imagen JPG/PNG"
						name="development_activity_image" ref="development_activity_image"
						:files="files.development_activity_image" :validator="v$"
						@addfile="(err, val) => addFile(err, val, 'development_activity_image')"
						@removefile="removeFile('development_activity_image')"
						:to_edit="methodological_accompaniment_services.data.one.development_activity_image"
						v-model="form.development_activity_image" />


					<BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra una imagen JPG/PNG"
						name="evidence_participation_image" ref="evidence_participation_image"
						:files="files.evidence_participation_image" :validator="v$" @addfile="
							(err, val) => addFile(err, val, 'evidence_participation_image')
						" @removefile="removeFile('evidence_participation_image')"
						:to_edit="methodological_accompaniment_services.data.one.evidence_participation_image"
						v-model="form.evidence_participation_image" />

				</section>
				<section id="asistentes" class="flex flex-col justify-evenly mb-8">
					<div>
						<h3 class="intro-y form-label font-bold uppercase">
							<span>
								Asistentes Agregados
							</span>
							<span>
								# {{ form.aggregates.assistants.length }}
							</span>
						</h3>
					</div>
					<div class="overflow-x-auto overflow-y-hidden">
						<Aggregates @pop="(id) => methodologicalAccompaniments_store.pop_aggregate(id)"
							@push="(aggregate) => methodologicalAccompaniments_store.push_aggregate(aggregate)"
							:headers="users_headers" :aggregates="form.aggregates.assistants" :options="assistants"
							:validator="v$" name="aggregates">
						</Aggregates>
					</div>
				</section>
				<div class="flex justify-center">
					<button type="submit" class="btn btn-primary w-24 mr-1 mb-2" :disabled="disabledButton">
						Ingresar
					</button>
				</div>
			</form>
		</fieldset>
	</div>
</template>
