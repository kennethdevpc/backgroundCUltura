<script lang="ts" setup>
// Importing Overall librearies / Dependencies
import { storeToRefs } from "pinia";
import { useVuelidate } from "@vuelidate/core";
import { loading } from "@/utils/loading";
import services from "@/services/strengthening_supervision_manager.service";
// Testing both imports
import permissions from "@/permissions";
import mixins from "@/mixins";
import alerts from "@/utils/alerts";
import { strengtheningSupervisionManager } from "@/stores/strengthening_supervision_manager";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseManagement from "@/components/base/Management.vue";
import BaseSelect from "@/components/base/Select.vue";
import { scroll_top } from "@/utils/scroll";

import servicesConsecutive from "@/services/generals.service";
import { required } from "@/utils/validations";
import FormHeader from "@/components/base/FormHeader.vue";
const consecutive_services = servicesConsecutive();
const strengthening_supervision_manager_services = services();
const strengthening_supervision_manager_store =
  strengtheningSupervisionManager();
const {
  form,
  form_rules: _form_rules,
  form_options,
  instance,
} = storeToRefs(strengthening_supervision_manager_store);

// [Computed] => Getting Edit Status
const route = useRoute();
const isEditing = computed(() => (route.params.id ? true : false));
const roles = ref([]);
const methodological_instructions = ref([]);
const disabledButton = ref(false);
const form_rules = computed(() => {
  return {
    ..._form_rules.value,
    methodological_instruction_id:
      methodological_instructions.value.length >= 1 ? { required } : {},
  };
});

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, {
  $autoDirty: true,
  $lazy: true,
});

const files = ref({
  development_activity_image: [],
  evidence_participation_image: [],
});
const editing = computed(() => {
  return route.params.id ? true : false;
});

const fetch_methodological_accompaniment = async () => {
  return await strengthening_supervision_manager_services.getOne(
    route.params.id
  );
};
const users_headers = [
  {
    text: "ID",
    value: "id",
  },
  {
    text: "NOMBRE COMPLETO",
    value: "monitor_fullname",
  },
  {
    text: "NUMERO DE CÉDULA",
    value: "document_number",
  },
];

// [onBeforeMount] => Getting fetched data before loading template
onBeforeMount(async () => {
  fetch_consecutive();
  // await select_store.dataSelects()

  if (editing.value) {
    strengthening_supervision_manager_store.$reset();
    v$.value.$reset();
    await fetch_methodological_accompaniment()
      .then(() => {
        const {
          created_at,
          status,
          nac,
          user_manager,
          user_associate_id,
          methodological_instruction_id,
          reject_message,
          ...rest
        } = strengthening_supervision_manager_services.data.one;

        get_profile_user_manager_nac(nac.id);
        get_methodological_instruction_user_manager(user_manager.id);
        strengthening_supervision_manager_store.$patch((state) => {
          Object.assign(state.form, {
            ...rest,
          });
        });

        instance.value.status = status;
        instance.value.reject_message = reject_message;
      })
      .catch(() => {
        mixins.not_found_by_id();
      });
  } else {
    strengthening_supervision_manager_store.$reset();
    v$.value.$reset();
    await fetch_consecutive();
  }
});

// [Submit] => Checking Payload before adding or editing
const onSubmit = async () => {
  const valid = await v$.value.$validate();
  if (valid) {
    disabledButton.value =true;
    if (editing.value) {
      await strengthening_supervision_manager_services.update(
        strengthening_supervision_manager_services.data.one.id,
        strengthening_supervision_manager_store.form
      ).finally(() => {
				disabledButton.value = false;
			});
    } else {
      await fetch_consecutive().finally(() => {
      strengthening_supervision_manager_services
        .create(strengthening_supervision_manager_store.form)
        .then(async (response) => {
          //console.log('response', response);
          if (response.data.success) {
            files.value.development_activity_image = [];
            files.value.evidence_participation_image = [];
            methodological_instructions.value = [];
            roles.value = [];
            strengthening_supervision_manager_store.$reset();
            v$.value.$reset();
            scroll_top();
            await fetch_consecutive();
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
  return await consecutive_services
    .getConsecutive("strengthening_super_mangs", "SGT")
    .then(() => {
      form.value.consecutive = consecutive_services.data.value;
    });
};

const get_nac = computed(() => {
  return form.value.nac_id;
});

const get_user_manager = computed(() => {
  return form.value.user_associate_id;
});

watch(get_nac, async (new_val, old_val) => {
  if (new_val != "" && new_val != null) {
    if (new_val != old_val) {
      await get_profile_user_manager_nac(new_val);
    }
  }
});
watch(get_user_manager, async (new_val, old_val) => {
  if (new_val != "" && new_val != null) {
    if (new_val != old_val) {
      await get_methodological_instruction_user_manager(new_val);
    }
  }
});

const get_profile_user_manager_nac = async (id) => {
  roles.value = [];
  methodological_instructions.value = [];
  form.value.user_associate_id = "";
  form.value.methodological_instruction_id = "";
  const response =
    await strengthening_supervision_manager_services.getProfileRoleUserNac(id);

  if (response.status == 200) {
    let roles_data =
      strengthening_supervision_manager_services.data.allUserManager;

    if (editing.value) {
      form.value.user_associate_id =
        strengthening_supervision_manager_services.data.one.user_associate_id;
    }
    if (roles_data.length > 0) {
      roles.value = roles_data;
    } else {
      alerts.personalized("El nac seleccionado no poseé roles.", "info");
    }
  }
};
const get_methodological_instruction_user_manager = async (id) => {
  const response =
    await strengthening_supervision_manager_services.getMethodologicalInstructionUserManager(
      id
    );

  if (response.status == 200) {
    let methodological_instructions_data =
      strengthening_supervision_manager_services.data.allUserManager;

    if (editing.value) {
      form.value.methodological_instruction_id =
        strengthening_supervision_manager_services.data.one.methodological_instruction_id;
    }
    if (methodological_instructions_data.length > 0) {
      methodological_instructions.value = methodological_instructions_data;
    } else {
      alerts.personalized(
        "El usuario seleccionado no poseé Instrucciones metedologicas.",
        "info"
      );
      methodological_instructions.value = [];
    }
  }
};
</script>

<template>
  <FormHeader
    :state="{
      consecutive: form.consecutive,
      status: instance.status,
      reject_message: instance.reject_message,
    }"
  >
    {{
      isEditing
        ? `Edición de Visita de supervisión de gestor #${route.params.id}`
        : `Visita de supervisión de gestor`
    }}
  </FormHeader>
  <BaseManagement
    v-if="
      isEditing
        ? permissions.strengtheningSupervisionManager.management()
        : false
    "
    @send="
		(payload) =>
			mixins.methods.send_management(
				'strengthening_super_mangs',
				route.params.id as string,
				payload
			)
	"
    class="mt-5"
  />
  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.strengtheningSupervisionManager.no_edit()">
      <form
        @submit.prevent="onSubmit"
        class="grid gap-3 grid-cols-1 p-5 border-t border-slate-200/60"
      >
        <section class="grid gap-5 grid-cols-1 md:grid-cols-3">
          <BaseInput
            type="date"
            label="FECHA REVISIÓN *"
            name="revision_date"
            placeholder="Fecha"
            v-model="form.revision_date"
            :validator="v$"
          />

          <BaseSelect
            label="NAC *"
            tooltip="Ingrese el NAC"
            placeholder="Seleccione"
            name="nac_id"
            v-model="form.nac_id"
            :options="form_options.nacs"
            :validator="v$"
          />

          <BaseSelect
            label="USUARIO (GESTOR CULTURAL) *"
            tooltip="Seleccione un usuario gestor"
            placeholder="Seleccione"
            name="user_associate_id"
            v-model="form.user_associate_id"
            :options="roles"
            :validator="v$"
          />
        </section>

        <div class="intro-y flex justify-between items-start md:items-end mt-5">
          <div class="intro-y flex flex-col items-start gap-1">
            <h2 class="text-lg font-medium mr-auto">CRITERIOS DE EVALUACIÓN</h2>
          </div>
        </div>
        <section class="grid gap-5 grid-cols-1 md:grid-cols-3">
          <BaseSelect
            label="TRANSFERENCIA *"
            tooltip="Seleccione un transferencia"
            placeholder="Seleccione"
            name="methodological_instruction_id"
            v-model="form.methodological_instruction_id"
            :options="methodological_instructions"
            :validator="v$"
          />
          <BaseInput
            type="text"
            label="DIRECCIÓN *"
            name="revision_date"
            placeholder="DIRECCIÓN"
            v-model="form.address"
            :validator="v$"
          />
          <BaseRadio
            label="¿CUMPLIO? *"
            tooltip=""
            name="methodological_instruction_reached_target"
            v-model="form.methodological_instruction_reached_target"
            :options="form_options.decisions || null"
            :validator="v$"
          />
        </section>
        <div class="intro-y flex justify-between items-start md:items-end mt-5">
          <div class="intro-y flex flex-col items-start gap-1">
            <h2 class="text-lg font-medium mr-auto">
              VISITA DE SUPERVISIÓN DEL GESTOR AL TERRITORIO
            </h2>
          </div>
        </div>
        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <BaseInput
            type="number"
            label="FRECUENCIA *"
            name="frequency"
            placeholder="FRECUENCIA"
            v-model="form.frequency"
            :validator="v$"
            min="0"
          />

          <BaseInput
            type="number"
            label="BITACORAS REGISTRADAS EN PLATAFORMA *"
            name="binnacle_registered_plataform"
            placeholder="BITACORAS REGISTRADAS EN PLATAFORMA"
            v-model="form.binnacle_registered_plataform"
            :validator="v$"
            min="0"
          />
        </section>
        <section class="grid gap-5 grid-cols-1">
          <BaseTextarea
            label="DESCRIPCIÓN DE LA JORNADA *"
            placeholder="DESCRIPCIÓN DE LA JORNADA"
            name="description"
            v-model="form.description"
            :validator="v$"
            rows="3"
          />
        </section>
        <section class="grid gap-5 grid-cols-2">
          <BaseInput
            type="time"
            label="HORA INICIO"
            tooltip="Ingrese la hora de inicio"
            placeholder="Hora inicio"
            name="start_time"
            v-model="form.start_time"
            :validator="v$"
          />

          <BaseInput
            type="time"
            label="HORA FINAL"
            tooltip="Ingrese la hora final"
            placeholder="Hora final"
            name="final_time"
            v-model="form.final_time"
            :validator="v$"
          />
        </section>
        <section class="grid gap-5 grid-cols-1">
          <BaseTextarea
            label="OBSERVACIONES: *"
            placeholder="OBSERVACIONES"
            name="comments"
            v-model="form.comments"
            :validator="v$"
            rows="3"
          />
        </section>

        <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
          <BaseFilepond
            label="FOTO DEL DESARROLLO *"
            tooltip="Arrastra una imagen JPG/PNG"
            name="development_activity_image"
            ref="development_activity_image"
            :files="files.development_activity_image"
            :validator="v$"
            @addfile="
              (err, val) => addFile(err, val, 'development_activity_image')
            "
            @removefile="removeFile('development_activity_image')"
            :to_edit="
              strengthening_supervision_manager_services.data.one
                .development_activity_image
            "
            v-model="form.development_activity_image"
          />

          <BaseFilepond
            label="EVIDENCIA DE PARTICIPACIÓN *"
            tooltip="Arrastra una imagen JPG/PNG"
            name="evidence_participation_image"
            ref="evidence_participation_image"
            :files="files.evidence_participation_image"
            :validator="v$"
            @addfile="
              (err, val) => addFile(err, val, 'evidence_participation_image')
            "
            @removefile="removeFile('evidence_participation_image')"
            :to_edit="
              strengthening_supervision_manager_services.data.one
                .evidence_participation_image
            "
            v-model="form.evidence_participation_image"
          />
        </section>

        <div
          v-if="!permissions.strengtheningSupervisionManager.no_edit()"
          class="flex justify-center"
        >
          <button
            type="submit"
            class="btn btn-primary w-24 mr-1 mb-2"
            :disabled="disabledButton"
          >
            {{ isEditing ? "Actualizar" : "Ingresar" }}
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
