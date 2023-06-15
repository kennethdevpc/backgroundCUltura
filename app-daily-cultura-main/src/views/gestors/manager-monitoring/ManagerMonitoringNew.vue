<script setup>
import services from "@/services/managermonitoring.service";
import servicesConsecutive from "@/services/generals.service";

// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseRadio from "@/components/base/Radio.vue";

// Store
import { useManagerMonitoring } from "@/stores/manager-monitoring";
import { useSelectStore } from "@/stores/selects";
import { storeToRefs } from "pinia";
import Management from "@/components/base/Management.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import { scroll_top } from "@/utils/scroll";
import Swal from "sweetalert2";
import FormHeader from "@/components/base/FormHeader.vue";
const route = useRoute();

// Extracting Store Data
const manager_monitoring = useManagerMonitoring();
const { form, form_rules, form_options } = storeToRefs(manager_monitoring);

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true });

// Extracting Select Data
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);
const lists = options.value;

const managermonitoring_services = services();
const consecutive_services = servicesConsecutive();

const instance = reactive({
  status: "",
  reject_message: "",
});
const disabledButton = ref(false);
const fetch_consecutive = async () => {
  return await consecutive_services
    .getConsecutive("manager_monitorings", "IMSG")
    .then(() => {
      form.value.consecutive = consecutive_services.data.value;
    });
};

const fetchOne = async () => {
  return await managermonitoring_services.getOne(route.params.id);
};

const editing = computed(() => {
  if (route.params.id) return true;
  else return false;
});

onMounted(async () => {
  if (route.params.id) {
    manager_monitoring.$reset();
    await fetchOne().catch(() => {
      mixins.not_found_by_id();
    });

    const {
      id,
      created_at,
      updated_at,
      user,
      status,
      reject_message,
      nac,
      monitor_id,
      ...rest
    } = managermonitoring_services.data.one;

    manager_monitoring.$patch((state) => {
      Object.assign(state.form, {
        ...rest,
        user_id: monitor_id,
      });
    });

    instance.status = status;
    instance.reject_message = reject_message;
  } else {
    manager_monitoring.$reset();
    await fetch_consecutive();
  }
});

const onSubmit = async () => {
  const valid = await v$.value.$validate();
  if (valid) {
    disabledButton.value = true;
    if (editing.value) {
      await managermonitoring_services.update(
        managermonitoring_services.data.one.id,
        manager_monitoring.transpiledData
      ).finally(() => {
        disabledButton.value = false
      });
      manager_monitoring.$reset();
    } else {

      await fetch_consecutive().finally(() => {
        managermonitoring_services
          .create(manager_monitoring.transpiledData)
          .then(async (response) => {
            if (response.status == 201) {
              manager_monitoring.$reset();
              v$.value.$reset();
              scroll_top();
              await fetch_consecutive();
            }
          }).finally(() => {
            disabledButton.value = false
          })
      });
    }
  } else {
    Swal.fire(
      "Validación",
      "Por favor valide los campos solicitados.",
      "error"
    );
  }
};
</script>

<template>
  <FormHeader :state="{
    consecutive: form.consecutive,
    status: instance.status,
    reject_message: instance.reject_message,
  }">
    {{ editing ? `Edición de Seguimiento` : "Seguimiento de Gestión Cultural" }}
  </FormHeader>
  <Management v-if="editing ? permissions.managermonitorings.management() : false" class="mt-5" @send="
    (payload) =>
      mixins.methods.send_management(
        'manager_monitorings',
        managermonitoring_services.data.one.id,
        payload
      )
  " />
  <div class="intro-y box mt-5">
    <fieldset :disabled="permissions.managermonitorings.no_edit()">
      <form @submit.prevent="onSubmit" class="px-5 sm:px-10 py-10 border-t border-slate-200/60 dark:border-darkmode-400">
        <section id="GeneralData"
          class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
          <div class="w-full">
            <BaseSelect required label="MONITOR *" tooltip="Ingrese el MONITOR" placeholder="Seleccione" name="user_id"
              v-model="form.user_id" :options="lists.monitors" :validator="v$" />
          </div>

          <div class="w-full relative">
            <BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Seleccione"
              name="activity_date" v-model="form.activity_date" :validator="v$" />
          </div>
          <div class="w-full">
            <BaseInput type="time" label="HORA INICIO *" tooltip="Ingrese la hora de inicio" placeholder="Hora inicio"
              name="start_time" v-model="form.start_time" :validator="v$" />
          </div>
          <div class="w-full">
            <BaseInput type="time" label="HORA FINAL *" tooltip="Ingrese la hora final" placeholder="Hora final"
              name="final_hour" :min="form.start_time" v-model="form.final_hour" :validator="v$" />
          </div>
          <!-- <div class="w-full">
      								<BaseInput type="text" label="NOMBRE *" tooltip="Ingrese el nombre del tutorial"
      									placeholder="Nombre del tutorial" name="tutorial_name" v-model="form.tutorial_name"
      									:validator="v$" />
      							</div> -->
          <div class="w-full">
            <BaseInput type="text" label="OBJETIVO DEL SEGUIMIENTO *" tooltip="Ingrese el nombre del objetivo"
              placeholder="Nombre del objetivo" name="target_tracking" v-model="form.target_tracking" :validator="v$" />
          </div>
          <div class="w-full">
            <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
              v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
          </div>
        </section>
        <section id="Descriptions" class="mb-8">
          <div class="flex items-center mb-8">
            <h3 class="intro-y text-lg font-medium mr-auto">
              Evaluación y retroalimentación metodológica *
            </h3>
          </div>
          <div class="grid grid-cols-1 gap-6 justify-evenly mb-8">
            <div class="w-full">
              <BaseRadio class="flex items-center justify-end gap-6"
                label="La ficha pedagógica da cuenta del proceso cultural (manifestación cultural)a trabajar en el mes *"
                tooltip="" name="cultural_process" v-model="form.cultural_process"
                :options="form_options.cultural_process" :validator="v$" />
            </div>
            <div class="w-full">
              <BaseRadio class="flex items-center justify-end gap-6"
                label="La ficha Pedagógica refleja los derechos culturales y lineamientos a trabajar en el mes *"
                tooltip="" name="cultural_guidelines" v-model="form.cultural_guidelines"
                :options="form_options.cultural_guidelines" :validator="v$" />
            </div>
            <div class="w-full">
              <BaseRadio class="flex items-center justify-end gap-6"
                label="El monitor cultural maneja una comunicación acorde a las particularidades de los beneficiarios *"
                tooltip="" name="cultural_communication" v-model="form.cultural_communication"
                :options="form_options.cultural_communication" :validator="v$" />
            </div>
          </div>
          <div class="grid grid-cols-1 gap-6 justify-evenly">
            <div class="w-full">
              <BaseTextarea label="Mencione un aspecto evidenciado *" tooltip="Ingrese la dificultad" placeholder=""
                name="difficulty_cultural_process" v-model="form.difficulty_cultural_process" :validator="v$" rows="3" />
            </div>
            <div class="w-full">
              <BaseTextarea label="Mencione una propuesta para el mejoramiento metodológica y pedagógico de la ficha *"
                tooltip="Ingrese la propuesta" placeholder="" name="proposal_improvement"
                v-model="form.proposal_improvement" :validator="v$" rows="3" />
            </div>
          </div>
        </section>
        <div class="flex justify-center">
          <button :disabled="disabledButton" type="submit" class="btn btn-primary w-24 ml-2">
            Ingresar
          </button>
        </div>
      </form>
    </fieldset>
  </div>
</template>
