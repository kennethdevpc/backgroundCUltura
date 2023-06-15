<script setup lang="ts">
import { inscriptionStore } from "@/stores/inscriptions";
import { useSelectStore as selectStore } from "@/stores/selects";
import { storeToRefs } from "pinia";
import { useVuelidate } from "@vuelidate/core";
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import Management from "@/components/base/Management.vue";
import mixins from "@/mixins";
import permissions from "@/permissions";
import services from "@/services/inscriptions.service";
import services_general from "@/services/generals.service";
import alerts from "@/utils/alerts";
import { AxiosResponse } from "axios";
import { scroll_top } from "@/utils/scroll";
import FormHeader from "@/components/base/FormHeader.vue";
const inscription_store = inscriptionStore();
const { options } = selectStore();
const inscription_services = services();
const service_inscription = services_general();

const { adult, characterized } = storeToRefs(inscription_store);

const { form_beneficiary, form_beneficiary_rules_computed } =
  storeToRefs(inscription_store);
const v$beneficiary = useVuelidate(
  form_beneficiary_rules_computed,
  form_beneficiary,
  { $lazy: true, $autoDirty: true }
);

const { form_beneficiary_socio, form_socio_rules_computed } =
  storeToRefs(inscription_store);
const v$beneficiary_socio = useVuelidate(
  form_socio_rules_computed,
  form_beneficiary_socio,
  { $lazy: true, $autoDirty: true }
);

const { form_beneficiary_health, form_health_rules_computed } =
  storeToRefs(inscription_store);
const v$beneficiary_health = useVuelidate(
  form_health_rules_computed,
  form_beneficiary_health,
  { $lazy: true, $autoDirty: true }
);

const { form_accudent, form_accudent_rules_computed: form_accudent_rules } =
  storeToRefs(inscription_store);
const v$accudent = useVuelidate(form_accudent_rules, form_accudent, {
  $lazy: true,
  $autoDirty: true,
});

const { form_accudent_socio, form_accudent_socio_rules_computed } =
  storeToRefs(inscription_store);
const v$accudent_socio = useVuelidate(
  form_accudent_socio_rules_computed,
  form_accudent_socio,
  { $lazy: true, $autoDirty: true }
);

const { form_accudent_health, form_accudent_health_rules_computed } =
  storeToRefs(inscription_store);
const v$accudent_health = useVuelidate(
  form_accudent_health_rules_computed,
  form_accudent_health,
  { $lazy: true, $autoDirty: true }
);

const instance = reactive({
  consecutive: "",
  status: "",
  reject_message: "",
  file_1: "",
  file_2: "",
  apro_files: [],
  groups: [],
});

const step = ref(1);

const next_step = (step_to?: number) => {
  if (step_to) {
    step.value = step_to;
  } else {
    if (step.value < 6) {
      step.value++;
    }
  }
};

const prev_step = (step_to?: number) => {
  if (step_to) {
    step.value = step_to;
  } else {
    if (step.value > 0) {
      step.value--;
    }
  }
};

const route = useRoute();

const fetch_one = async () => {
  return await inscription_services.getOne(route.params.id);
};

const fetch_consecutive = async () => {
  return await service_inscription
    .getConsecutive("inscriptions", "F")
    .then(() => {
      inscription_store.form_beneficiary.consecutive =
        service_inscription.data.value;
      instance.consecutive = service_inscription.data.value;
    });
};

const editing = computed(() => {
  if (route.params.id) return true;
  else return false;
});

const validate_is_filled = (payload: any) => {
  return payload != null && payload != undefined && Object.keys(payload).length;
};

onBeforeMount(async () => {
  if (editing.value) {
    inscription_store.$reset();
    v$beneficiary.value.$reset();
    await fetch_one().catch(() => {
      mixins.not_found_by_id();
    });
    const { consecutive, status, reject_message, apro_file1, apro_file2, id } =
      inscription_services.data.one;
    instance.consecutive = `${consecutive}`;
    instance.status = status;
    instance.reject_message = reject_message;

    instance.apro_files.push({
      href: apro_file1,
      text: "VALIDACIÓN DEL PARTICIPANTE",
    });
    instance.apro_files.push({
      href: apro_file2,
      text: "VALIDACIÓN DEL ACUDIENTE",
    });

    inscription_store.form_update_instance.inscription_id = id;

    const beneficiary = inscription_services.data.one.benefiary;
    const beneficiary_health =
      inscription_services.data.one.health_data_benefiary;
    const beneficiary_socio =
      inscription_services.data.one.sociodemographic_characterization_benefiary;

    if (inscription_services.data.one.benefiary) {
      if (validate_is_filled(beneficiary)) {
        const { id, user_id, ...beneficiary_data } = beneficiary;

        inscription_store.$patch((state) => {
          Object.assign(state.form_beneficiary, {
            ...beneficiary_data,
            group_id: inscription_services.data.one.group_id,
            user_id,
            consecutive: inscription_services.data.one.consecutive,
          });
          state.form_update_instance.beneficiary_id = id;
        });

        instance.file_1 = beneficiary_data.file;
      }
      if (validate_is_filled(beneficiary_socio)) {
        const {
          created_at,
          deleted_at,
          socio_demo_id,
          socio_demo_type,
          updated_at,
          ...beneficiary_socio_data
        } = beneficiary_socio;

        inscription_store.$patch((state) => {
          Object.assign(state.form_beneficiary_socio, {
            ...beneficiary_socio_data,
          });
          state.form_update_instance.socio_demo_beneficiary_id = socio_demo_id;
        });
      }
      if (validate_is_filled(beneficiary_health)) {
        const {
          created_at,
          deleted_at,
          updated_at,
          id,
          health_data_id,
          health_data_type,
          ...beneficiary_health_data
        } = beneficiary_health;

        inscription_store.$patch((state) => {
          Object.assign(state.form_beneficiary_health, {
            ...beneficiary_health_data,
          });
          state.form_update_instance.health_data_beneficiary_id =
            health_data_id;
        });
      }
    }

    const attendant = inscription_services.data.one.attendant;
    const attendant_health =
      inscription_services.data.one.health_data_attendant;
    const attendant_socio =
      inscription_services.data.one.sociodemographic_characterization_attendant;

    if (inscription_services.data.one.attendant) {
      if (validate_is_filled(attendant)) {
        const { id, ...attendant_data } = attendant;

        inscription_store.$patch((state) => {
          Object.assign(state.form_accudent, {
            ...attendant_data,
          });
          state.form_update_instance.attendant_id = id;
        });
      }
      if (validate_is_filled(attendant_socio)) {
        const {
          created_at,
          deleted_at,
          socio_demo_id,
          socio_demo_type,
          updated_at,
          ...attendant_socio_data
        } = attendant_socio;

        inscription_store.$patch((state) => {
          Object.assign(state.form_accudent_socio, {
            ...attendant_socio_data,
          });
          state.form_update_instance.socio_demo_attendant_id = socio_demo_id;
        });
      }
      if (validate_is_filled(attendant_health)) {
        const {
          created_at,
          deleted_at,
          health_data_id,
          health_data_type,
          updated_at,
          ...attendant_health_data
        } = attendant_health;

        inscription_store.$patch((state) => {
          Object.assign(state.form_accudent_health, {
            ...attendant_health_data,
          });
          state.form_update_instance.health_data_attendant_id = health_data_id;
        });
      }
    }
  } else {
    inscription_store.$reset();
    v$beneficiary.value.$reset();
    await fetch_consecutive();
  }
});

const verify_accudent_steps = computed(() => {
  return (
    form_beneficiary_socio.value.age != "" &&
    !adult.value &&
    characterized.value
  );
});

const steps_classes =
  "relative before:hidden before:lg:block before:absolute before:rounded-full before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center";

let files = reactive({
  beneficiary: [],
});

const addFile = (err, val) => {
  if (err) {
    return;
  } else {
    const { file, filename } = val;
    form_beneficiary.value.file = {
      name: "photo",
      file,
      filename,
    };
  }
};

const removeFile = () => {
  form_beneficiary.value.file = null;
  files.beneficiary = [];
};

const beneficiary_text = `Autorizo a la Red de Monitores Culturales el uso de los datos y del uso de la imagen mía y de los niños, niñas y adolescentes a mi cargo y nos comprometemos con todas las actividades que ofrezca el proyecto. Esta autorización se valida de acuerdo a los términos legales de la ley 1581 del 2012, asegurando prevalente de los niños, niñas y adolescentes del proyecto.`;

const on_submit = async (
  type: "uncharacterized" | "characterized" | "characterizedWithAccudent"
) => {
  let response: AxiosResponse = null;
  if (editing.value) {
    response = await inscription_services.update(
      inscription_services.data.one.id,
      { ...inscription_store.get_form_update }
    );
  } else {
    if (type === "uncharacterized") {
      response = await inscription_services.create(
        inscription_store.get_form_uncharactherized,
        "uncharacterized"
      );
    }
    if (type === "characterized") {
      response = await inscription_services.create(
        inscription_store.get_form_charactherized,
        "characterized"
      );
    }
    if (type === "characterizedWithAccudent") {
      response = await inscription_services.create(
        inscription_store.get_form_charactherizedWithAccudent,
        "characterizedWithAccudent"
      );
    }
  }
  return response;
};

const on_submit_uncharacterized = async () => {
  const valid = await v$beneficiary.value.$validate();
  if (valid) {
    await fetch_consecutive().finally(() => {
      on_submit("uncharacterized").then(async (response) => {
        if (response.data.success) {
          files.beneficiary = [];
          inscription_store.$reset();
          v$beneficiary.value.$reset();
          scroll_top();
          step.value = 1;
          await fetch_consecutive();
        }
      });
    });
  } else {
    alerts.validation();
  }
};

const on_submit_characterized = async () => {
  const valid = await v$beneficiary.value.$validate();
  const valid2 = await v$beneficiary_socio.value.$validate();
  const valid3 = await v$beneficiary_health.value.$validate();
  if (valid && valid2 && valid3) {
    await fetch_consecutive().finally(() => {
      on_submit("characterized").then(async (response) => {
        if (response.data.success) {
          files.beneficiary = [];
          inscription_store.$reset();
          v$beneficiary.value.$reset();
          v$beneficiary_socio.value.$reset();
          v$beneficiary_health.value.$reset();
          scroll_top();
          step.value = 1;
          await fetch_consecutive();
        }
      });
    });
  } else {
    alerts.validation();
  }
};

const on_submit_characterized_a = async () => {
  const valid = await v$beneficiary.value.$validate();
  const valid2 = await v$beneficiary_socio.value.$validate();
  const valid3 = await v$beneficiary_health.value.$validate();
  const valid4 = await v$accudent.value.$validate();
  const valid5 = await v$accudent_socio.value.$validate();
  const valid6 = await v$accudent_health.value.$validate();

  if (valid && valid2 && valid3 && valid4 && valid5 && valid6) {
    await fetch_consecutive().finally(() => {
      on_submit("characterizedWithAccudent").then(async (response) => {
        if (response.data.success) {
          files.beneficiary = [];
          inscription_store.$reset();
          v$beneficiary.value.$reset();
          v$beneficiary_socio.value.$reset();
          v$beneficiary_health.value.$reset();
          v$accudent.value.$reset();
          v$accudent_socio.value.$reset();
          v$accudent_health.value.$reset();
          scroll_top();
          step.value = 1;
          await fetch_consecutive();
        }
      });
    });
  } else {
    alerts.validation();
  }
};

const on_next = async (step: number) => {
  if (step == 1) {
    if (form_beneficiary.value.participant_type == "C") {
   
      if(!form_beneficiary.value.file){
        alerts.custom_validation("El campo foto es requerido");
          return 0;
      }
      if(form_beneficiary.value.file){
        if (Object.entries(form_beneficiary.value.file).length == 0) {
          alerts.custom_validation("El campo foto es requerido");
          return 0;
        }
      }

    }
    await v$beneficiary.value.$validate().then((valid) => {
      valid ? next_step() : alerts.validation();
    });
  }
  if (step == 2) {
    await v$beneficiary_socio.value.$validate().then((valid) => {
      valid ? next_step() : alerts.validation();
    });
  }
  if (step == 3) {
    await v$beneficiary_health.value.$validate().then((valid) => {
      valid ? next_step() : alerts.validation();
    });
  }
  if (step == 4) {
    await v$accudent.value.$validate().then((valid) => {
      valid ? next_step() : alerts.validation();
    });
  }
  if (step == 5) {
    await v$accudent_socio.value.$validate().then((valid) => {
      valid ? next_step() : alerts.validation();
    });
  }
};

const groups = computedAsync(async () => {
  return await service_inscription
    .getGroups()
    .then((response) => response.data);
}, []);

const ages = computed(() => {
  if (form_beneficiary.value.type_document == "CC") {
    return Array.from({ length: 82 }, (_, i) => ({
      label: i + 18,
      value: i + 18,
    }));
  } else {
    return Array.from({ length: 17 }, (_, i) => ({
      label: i + 1,
      value: i + 1,
    }));
  }
});
// const linkage_project = computed(() => {
//   return form_beneficiary.value.linkage_project;
// });

// watch(linkage_project, () => {
//   if (form_beneficiary.value.linkage_project == "PMGCP" || form_beneficiary.value.linkage_project == 'PMMCP') {
//        form_beneficiary.value.user_id= "";
//   }
// });
function linkage_project_action(linkage_project) {
  if (linkage_project != "PMGCP" || linkage_project != "PMMCP") {
    form_beneficiary.value.user_id = "";
  }
}
</script>

<template>
  <FormHeader
    :state="{
      consecutive: instance.consecutive,
      status: instance.status,
      reject_message: instance.reject_message,
      files: instance.apro_files,
    }"
  >
    {{
      editing
        ? `Edición de Inscripción #${inscription_services.data.one.id}`
        : "Inscripción"
    }}
  </FormHeader>
  <Management
    v-if="editing ? permissions.inscriptions.management() : false"
    class="mt-5"
    :age="form_beneficiary_socio.age"
    @send="
      (payload) =>
        mixins.methods.send_management(
          'inscriptions',
          inscription_services.data.one.id,
          payload
        )
    "
  />
  <div class="intro-y box p-5 mt-5 flex flex-col">
    <div>
      <div
        :class="[
          !adult
            ? 'before:w-[80%]'
            : characterized
            ? 'before:w-[69%]'
            : 'before:w-[2.5%]',
          steps_classes,
        ]"
      >
        <div
          class="intro-x lg:text-center flex items-center lg:block flex-1 z-10"
        >
          <button
            @click="prev_step(1)"
            :class="
              step == 1
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            1
          </button>
          <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">
            Datos generales de beneficiario
          </div>
        </div>
        <div
          v-if="characterized"
          class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
        >
          <button
            @click="next_step(2)"
            :class="
              step == 2
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            2
          </button>
          <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600">
            Caracteristicas sociodemografica
          </div>
        </div>
        <div
          v-if="characterized"
          class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
        >
          <button
            @click="next_step(3)"
            :class="
              step == 3
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            3
          </button>
          <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600">
            Datos de salud del beneficiario
          </div>
        </div>
        <div
          v-if="verify_accudent_steps && form_beneficiary.type_document != 'CC'"
          class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
        >
          <button
            @click="next_step(4)"
            :class="
              step == 4
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            4
          </button>
          <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600">
            Datos del acudiente
          </div>
        </div>
        <div
          v-if="verify_accudent_steps && form_beneficiary.type_document != 'CC'"
          class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
        >
          <button
            @click="next_step(5)"
            :class="
              step == 5
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            5
          </button>
          <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600">
            Caracteristicas sociodemografica del acudiente
          </div>
        </div>
        <div
          v-if="verify_accudent_steps && form_beneficiary.type_document != 'CC'"
          class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10"
        >
          <button
            @click="next_step(6)"
            :class="
              step == 6
                ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
                : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'
            "
          >
            6
          </button>
          <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600">
            Datos de salud del acudiente
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- BEGIN: Wizard Layout -->
  <div class="intro-y box p-5 mt-5">
    <form @submit.prevent>
      <div v-show="step == 1">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div class="grid grid-cols-1 justify-evenly mb-4">
            <b>
              {{ beneficiary_text }}
            </b>
            <div class="flex mt-2">
              <BaseRadio
                :label="
                  form_beneficiary.accept == 0
                    ? 'ACEPTA * (Campos inhabilitados)'
                    : 'ACEPTA * (Campos habilitados)'
                "
                tooltip=""
                name="accept"
                v-model="form_beneficiary.accept"
                :options="options.decisions || null"
                :validator="v$beneficiary"
              />
            </div>
          </div>
          <fieldset :disabled="form_beneficiary.accept == 0">
            <div
              id="main"
              class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-2 gap-6 justify-evenly"
            >
              <div class="w-full">
                <BaseSelect
                  label="¿COMO SE VINCULO USTED AL PROYECTO? *"
                  tooltip="Ingrese el vinculo"
                  placeholder="Seleccione"
                  name="linkage_project"
                  v-model="form_beneficiary.linkage_project"
                  :options="options.linkage_projects || null"
                  :validator="v$beneficiary"
                  :onSelect="linkage_project_action"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="form_beneficiary.linkage_project == 'PMR'"
              >
                <BaseInput
                  type="text"
                  label="NOMBRE DE REFERIDO *"
                  tooltip="Ingrese nombre"
                  placeholder="Nombre..."
                  name="referrer_name"
                  v-model="form_beneficiary.referrer_name"
                  :validator="v$beneficiary"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="
                  form_beneficiary.linkage_project === 'PMEPUB' ||
                  form_beneficiary.linkage_project === 'PMEPRI' ||
                  form_beneficiary.linkage_project === 'PMIE'
                "
              >
                <BaseInput
                  type="text"
                  label="INSTITUCIÓN, ENTIDAD O REFERIDO *"
                  tooltip="Ingrese institución, entidad o referido"
                  placeholder="Nombre de Institución, entidad o referido"
                  name="institution_entity_referred"
                  v-model="form_beneficiary.institution_entity_referred"
                  :validator="v$beneficiary"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="form_beneficiary.linkage_project === 'PMGCP'"
              >
                <BaseSelect
                  label="GESTOR *"
                  tooltip="Ingrese el gestor"
                  placeholder="Seleccione"
                  name="user_id"
                  v-model="form_beneficiary.user_id"
                  :options="options.managers || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="form_beneficiary.linkage_project === 'PMMCP'"
              >
                <BaseSelect
                  label="MONITOR *"
                  tooltip="Ingrese el monitor"
                  placeholder="Seleccione"
                  name="user_id"
                  v-model="form_beneficiary.user_id"
                  :options="options.monitors || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseSelect
                  label="TIPO DE PARTICIPANTE *"
                  tooltip="Ingrese el tipo"
                  placeholder="Seleccione"
                  name="participant_type"
                  v-model="form_beneficiary.participant_type"
                  :options="options.participant_types || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div
                class="w-full"
                v-if="form_beneficiary.participant_type != 'NC'"
              >
                <BaseSelect
                  label="GRUPO *"
                  tooltip="Seleccione un grupo"
                  placeholder="Seleccione"
                  name="group_id"
                  v-model="form_beneficiary.group_id"
                  :options="groups || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseInput
                  type="text"
                  label="NOMBRE Y APELLIDO *"
                  tooltip="Ingrese nombre y apellido"
                  placeholder="Nombre y apellido..."
                  name="full_name"
                  v-model="form_beneficiary.full_name"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseSelect
                  label="TIPO DE DOCUMENTO DE IDENTIDAD *"
                  tooltip="Ingrese el tipo"
                  placeholder="Seleccione"
                  name="type_document"
                  v-model="form_beneficiary.type_document"
                  :options="options.type_documents || null"
                  :validator="v$beneficiary"
                  :onSelect="
                    () => {
                      form_beneficiary_socio.age = null;
                    }
                  "
                />
              </div>
              <div class="w-full">
                <BaseInput
                  type="text"
                  label="NUMERO DE IDENTIDAD *"
                  tooltip="Ingrese número"
                  placeholder="Número"
                  name="document_number"
                  v-model="form_beneficiary.document_number"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseSelect
                  label="NAC *"
                  tooltip="Ingrese el nac"
                  placeholder="Seleccione"
                  name="nac_id"
                  v-model="form_beneficiary.nac_id"
                  :options="options.nacs || null"
                  :validator="v$beneficiary"
                />
              </div>
              <template
                :class="
                  form_beneficiary.neighborhood_id == '357'
                    ? 'grid-cols-2'
                    : 'grid-cols-1'
                "
                class="grid gap-3"
              >
                <div class="w-full">
                  <BaseSelect
                    label="BARRIO *"
                    tooltip="Ingrese el barrio"
                    placeholder="Seleccione"
                    name="neighborhood_id"
                    v-model="form_beneficiary.neighborhood_id"
                    :options="options.neighborhoods || null"
                    :validator="v$beneficiary"
                  />
                </div>
                <div
                  class="w-full intro-x"
                  v-if="form_beneficiary.neighborhood_id == '357'"
                >
                  <BaseInput
                    type="text"
                    label="NUEVO BARRIO *"
                    tooltip="Ingrese el barrio"
                    placeholder="Barrio"
                    name="neighborhood_new"
                    v-model="form_beneficiary.neighborhood_new"
                    :validator="v$beneficiary"
                  />
                </div>
              </template>
              <div class="w-full">
                <BaseSelect
                  label="ZONA *"
                  tooltip="Ingrese la zona"
                  placeholder="Seleccione"
                  name="zone"
                  v-model="form_beneficiary.zone"
                  :options="options.zones || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseSelect
                  label="ESTRATO *"
                  tooltip="Ingrese el estrato"
                  placeholder="Seleccione"
                  name="zone"
                  v-model="form_beneficiary.stratum"
                  :options="options.stratums || null"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseInput
                  type="tel"
                  label="TELÉFONO *"
                  tooltip="Ingrese el teléfono "
                  placeholder="Teléfono"
                  name="phone"
                  v-model="form_beneficiary.phone"
                  :validator="v$beneficiary"
                />
              </div>
              <div class="w-full">
                <BaseInput
                  type="text"
                  label="EMAIL"
                  tooltip="Ingrese el email"
                  placeholder="Email"
                  name="email"
                  v-model="form_beneficiary.email"
                  :validator="v$beneficiary"
                />
              </div>
              <div
                class="w-full col-span-1 xl:col-span-2 lg:col-span-1"
                v-if="form_beneficiary.participant_type != 'NC'"
              >
                <BaseFilepond
                  label="FOTO *"
                  tooltip="Arrastra una foto"
                  name="file"
                  ref="file_ref"
                  v-model="form_beneficiary.file"
                  @addfile="addFile"
                  @removefile="removeFile"
                  :to_edit="instance.file_1"
                  :files="files.beneficiary"
                  :validator="v$beneficiary"
                />
              </div>
            </div>
          </fieldset>
        </fieldset>
      </div>
      <template v-if="step == 2">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div
            id="main"
            class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-6 justify-evenly"
          >
            <div class="w-full">
              <BaseSelect
                label="GENERO *"
                tooltip="Ingrese el género"
                placeholder="Seleccione"
                name="gender"
                v-model="form_beneficiary_socio.gender"
                :options="options.genders || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="EDAD *"
                tooltip="Ingrese la edad"
                placeholder="Seleccione"
                name="age"
                v-model="form_beneficiary_socio.age"
                :options="ages || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div class="w-full">
              <BaseRadio
                label="¿ESTUDIA ACTUALMENTE? *"
                tooltip="Seleccione si o no"
                name="decision_study"
                v-model="form_beneficiary_socio.decision_study"
                :options="options.decisions || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿NIVEL EDUCATIVO ALCANZADO? *"
                tooltip="Ingrese nivel educativo"
                placeholder="Seleccione"
                name="educational_level"
                v-model="form_beneficiary_socio.educational_level"
                :options="options.educational_levels || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div class="w-full">
              <BaseRadio
                label="¿PRESENTA ALGUNA DISCAPACIDAD? *"
                tooltip="Seleccione si o no"
                name="decision_disability"
                v-model="form_beneficiary_socio.decision_disability"
                :options="options.decisions || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div
              class="w-full"
              v-if="form_beneficiary_socio.decision_disability == '1'"
            >
              <BaseSelect
                label="DISCAPACIDAD QUE PRESENTA *"
                tooltip="Ingrese discapacidad"
                placeholder="Seleccione"
                name="disability_type"
                v-model="form_beneficiary_socio.disability_type"
                :options="options.disability_types || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div
              class="w-full intro-x"
              v-if="form_beneficiary_socio.disability_type == 'O'"
            >
              <BaseInput
                type="text"
                label="OTRO DISCAPACIDAD*"
                tooltip="Ingrese la otra discapacidad"
                placeholder="DISCAPACIDAD..."
                name="other_disability"
                v-model="form_beneficiary_health.other_disability"
                :validator="v$beneficiary_health"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿CON QUE TIPO DE ETNIA SE REPRESENTA? *"
                tooltip="Ingrese etnia"
                placeholder="Seleccione"
                name="ethnicity"
                v-model="form_beneficiary_socio.ethnicity"
                :options="options.ethnicities || null"
                :validator="v$beneficiary_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿CONDICIÓN? *"
                tooltip="Ingrese condición"
                placeholder="Seleccione"
                name="condition"
                v-model="form_beneficiary_socio.condition"
                :options="options.conditions || null"
                :validator="v$beneficiary_socio"
              />
            </div>
          </div>
        </fieldset>
      </template>
      <template v-if="step == 3">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div
            id="main"
            class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-1 gap-6 justify-evenly"
          >
            <div class="w-full">
              <BaseSelect
                label="¿A QUE REGIMEN DE SALUD COLOMBIANO PERTENECE? *"
                tooltip="Ingrese el regimen de salud"
                placeholder="Seleccione"
                name="medical_service"
                v-model="form_beneficiary_health.medical_service"
                :options="options.medical_services || null"
                :validator="v$beneficiary_health"
              />
            </div>
            <div
              :class="
                form_beneficiary_health.entity_name_id == '16'
                  ? 'grid-cols-2'
                  : 'grid_cols-1'
              "
              class="grid gap-3"
            >
              <div class="w-full">
                <BaseSelect
                  label="EPS *"
                  tooltip="Ingrese un eps"
                  placeholder="Seleccione"
                  name="entity_name_id"
                  v-model="form_beneficiary_health.entity_name_id"
                  :options="options.entity_names || null"
                  :validator="v$beneficiary_health"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="form_beneficiary_health.entity_name_id == '16'"
              >
                <BaseInput
                  type="text"
                  label="OTRO EPS *"
                  tooltip="Ingrese el otro EPS"
                  placeholder="EPS..."
                  name="other_entity_name"
                  v-model="form_beneficiary_health.other_entity_name"
                  :validator="v$beneficiary_health"
                />
              </div>
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿ESTADO DE SALUD DEL BENEFICIARIO? *"
                tooltip="Ingrese un estado de salud"
                placeholder="Seleccione"
                name="health_condition"
                v-model="form_beneficiary_health.health_condition"
                :options="options.health_conditions || null"
                :validator="v$beneficiary_health"
              />
            </div>
          </div>
        </fieldset>
      </template>
      <template v-if="step == 4">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div
            id="main"
            class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-2 gap-6 justify-evenly"
          >
            <div
              :class="
                form_accudent.relationship == 'O'
                  ? 'grid-cols-2'
                  : 'grid_cols-1'
              "
              class="grid gap-3"
            >
              <div class="w-full intro-x">
                <BaseSelect
                  label="PARENTESCO *"
                  tooltip="Ingrese el tipo"
                  placeholder="Seleccione"
                  name="relationship"
                  v-model="form_accudent.relationship"
                  :options="options.relationships || null"
                  :validator="v$accudent"
                />
              </div>
              <div
                class="w-full intro-x"
                v-if="form_accudent.relationship == 'O'"
              >
                <BaseInput
                  v-if="form_accudent.relationship == 'O'"
                  type="text"
                  label="OTRO PARENTESCO *"
                  tooltip="Ingrese el parentesco"
                  placeholder="Parentesco"
                  name="other_relationship"
                  v-model="form_accudent.other_relationship"
                  :validator="v$accudent"
                />
              </div>
            </div>
            <div class="w-full">
              <BaseInput
                type="text"
                label="NOMBRE Y APELLIDO *"
                tooltip="Ingrese nombre y apellido"
                placeholder="Nombre y apellido..."
                name="full_name"
                v-model="form_accudent.full_name"
                :validator="v$accudent"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="TIPO DE DOCUMENTO DE IDENTIDAD *"
                tooltip="Ingrese el tipo"
                placeholder="Seleccione"
                name="type_document"
                v-model="form_accudent.type_document"
                :options="
                  options.type_documents.filter(
                    (option) => option.value == 'CC'
                  ) || null
                "
                :validator="v$accudent"
              />
            </div>
            <div class="w-full">
              <BaseInput
                type="text"
                label="NUMERO DE IDENTIDAD *"
                tooltip="Ingrese número"
                placeholder="Número"
                name="document_number"
                v-model="form_accudent.document_number"
                :validator="v$accudent"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="ZONA *"
                tooltip="Ingrese la zona"
                placeholder="Seleccione"
                name="zone"
                v-model="form_accudent.zone"
                :options="options.zones || null"
                :validator="v$accudent"
              />
            </div>
            <div class="w-full">
              <BaseInput
                type="tel"
                label="TELÉFONO *"
                tooltip="Ingrese el teléfono"
                placeholder="Teléfono"
                name="phone"
                v-model="form_accudent.phone"
                :validator="v$accudent"
              />
            </div>
            <div class="w-full">
              <BaseInput
                type="text"
                label="EMAIL"
                tooltip="Ingrese el email"
                placeholder="Email"
                name="email"
                v-model="form_accudent.email"
                :validator="v$accudent"
              />
            </div>
          </div>
        </fieldset>
      </template>
      <template v-if="step == 5">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div
            id="main"
            class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 gap-6 justify-evenly"
          >
            <div class="w-full">
              <BaseSelect
                label="GENERO *"
                tooltip="Ingrese el género"
                placeholder="Seleccione"
                name="gender"
                v-model="form_accudent_socio.gender"
                :options="options.genders || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="EDAD *"
                tooltip="Ingrese la edad"
                placeholder="Seleccione"
                name="age"
                v-model="form_accudent_socio.age"
                :options="
                  Array.from({ length: 82 }, (_, i) => ({
                    label: i + 18,
                    value: i + 18,
                  })) ||
                  null ||
                  null
                "
                :validator="v$accudent_socio"
              />
            </div>
            <div class="w-full">
              <BaseRadio
                label="¿ESTUDIA ACTUALMENTE? *"
                tooltip="Seleccione si o no"
                name="decision_study"
                v-model="form_accudent_socio.decision_study"
                :options="options.decisions || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿NIVEL EDUCATIVO ALCANZADO? *"
                tooltip="Ingrese nivel educativo"
                placeholder="Seleccione"
                name="educational_level"
                v-model="form_accudent_socio.educational_level"
                :options="options.educational_levels || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div class="w-full">
              <BaseRadio
                label="¿PRESENTA ALGUNA DISCAPACIDAD? *"
                tooltip="Seleccione si o no"
                name="decision_disability"
                v-model="form_accudent_socio.decision_disability"
                :options="options.decisions || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div
              class="w-full"
              v-if="form_accudent_socio.decision_disability == '1'"
            >
              <BaseSelect
                label="DISCAPACIDAD QUE PRESENTA *"
                tooltip="Ingrese discapacidad"
                placeholder="Seleccione"
                name="disability_type"
                v-model="form_accudent_socio.disability_type"
                :options="options.disability_types || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div
              class="w-full"
              v-if="form_accudent_socio.disability_type == 'O'"
            >
              <BaseInput
                type="text"
                label="OTRO DISCAPACIDAD*"
                tooltip="Ingrese la otra discapacidad"
                placeholder="DISCAPACIDAD..."
                name="other_disability"
                v-model="form_accudent_health.other_disability"
                :validator="v$accudent_health"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿CON QUE TIPO DE ETNIA SE REPRESENTA? *"
                tooltip="Ingrese etnia"
                placeholder="Seleccione"
                name="ethnicity"
                v-model="form_accudent_socio.ethnicity"
                :options="options.ethnicities || null"
                :validator="v$accudent_socio"
              />
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿CONDICIÓN? *"
                tooltip="Ingrese condicion"
                placeholder="Seleccione"
                name="condition"
                v-model="form_accudent_socio.condition"
                :options="options.conditions || null"
                :validator="v$accudent_socio"
              />
            </div>
          </div>
        </fieldset>
      </template>
      <template v-if="step == 6">
        <fieldset :disabled="permissions.inscriptions.no_edit()">
          <div
            id="main"
            class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-1 gap-6 justify-evenly"
          >
            <div class="w-full">
              <BaseSelect
                label="¿A QUE REGIMEN DE SALUD COLOMBIANO PERTENECE? *"
                tooltip="Ingrese el regimen de salud"
                placeholder="Seleccione"
                name="medical_service"
                v-model="form_accudent_health.medical_service"
                :options="options.medical_services || null"
                :validator="v$accudent_health"
              />
            </div>
            <div
              :class="
                form_accudent_health.entity_name_id == '16'
                  ? 'grid-cols-2'
                  : 'grid_cols-1'
              "
              class="grid gap-3"
            >
              <div class="w-full">
                <BaseSelect
                  label="EPS *"
                  tooltip="Ingrese un eps"
                  placeholder="Seleccione"
                  name="entity_name_id"
                  v-model="form_accudent_health.entity_name_id"
                  :options="options.entity_names || null"
                  :validator="v$accudent_health"
                />
              </div>
              {{ form_accudent_health.other_entity_name }}
              <div
                class="w-full intro-x"
                v-if="form_accudent_health.entity_name_id == '16'"
              >
                <BaseInput
                  type="text"
                  label="OTRO EPS *"
                  tooltip="Ingrese el otro EPS"
                  placeholder="EPS..."
                  name="other_entity_name"
                  v-model="form_accudent_health.other_entity_name"
                  :validator="v$accudent_health"
                />
              </div>
            </div>
            <div class="w-full">
              <BaseSelect
                label="¿ESTADO DE SALUD DEL ACUDIENTE? *"
                tooltip="Ingrese un estado de salud"
                placeholder="Seleccione"
                name="health_condition"
                v-model="form_accudent_health.health_condition"
                :options="options.health_conditions || null"
                :validator="v$accudent_health"
              />
            </div>
          </div>
        </fieldset>
      </template>

      <div class="w-full flex justify-end col-span-full">
        <template v-if="step == 1">
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            :disabled="
              permissions.inscriptions.no_edit() || form_beneficiary.accept == 0
            "
            v-if="!inscription_store.characterized"
            @click="on_submit_uncharacterized()"
          >
            Ingresar
          </button>
          <button
            type="button"
            v-else
            class="btn btn-primary w-24 ml-2"
            @click="on_next(1)"
          >
            Avanzar
          </button>
        </template>
        <template v-if="step == 2 || step == 4 || step == 5">
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            @click="prev_step()"
          >
            Regresar
          </button>
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            @click="
              step == 2 ? on_next(2) : step == 4 ? on_next(4) : on_next(5)
            "
          >
            Avanzar
          </button>
        </template>
        <template v-if="step == 3">
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            @click="prev_step()"
          >
            Regresar
          </button>
          <button
            type="button"
            :disabled="permissions.inscriptions.no_edit()"
            class="btn btn-primary w-24 ml-2"
            v-if="characterized && adult"
            @click="on_submit_characterized"
          >
            Ingresar
          </button>
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            v-if="characterized && !adult"
            @click="on_next(3)"
          >
            Avanzar
          </button>
        </template>
        <template v-if="step == 6">
          <button
            type="button"
            class="btn btn-primary w-24 ml-2"
            @click="prev_step()"
          >
            Regresar
          </button>
          <button
            type="button"
            :disabled="permissions.inscriptions.no_edit()"
            class="btn btn-primary w-24 ml-2"
            @click="on_submit_characterized_a"
          >
            Ingresar
          </button>
        </template>
      </div>
    </form>
  </div>
</template>
