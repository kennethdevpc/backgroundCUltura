<script setup lang="ts">
import BaseCrud from "@/components/base/BaseCrud.vue";
import binnaclesService from "@/services/binnacles.service";
import axios from "axios";
import permissions from "@/permissions";
import mixins from "@/mixins";
import services from '@/services/generals.service'
import BaseCrudPDF from "@/components/base/BaseCrudPDF.vue";
const service = services()
const service_binnacle = binnaclesService();

const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
  return String(route.name).split('.')[0]
})

const createBinnacle = () => {
  router.push({ name: `${routeName.value}.create` })
}

async function onDeleteBinnacle(id) {
  /* Swal.fire({
      icon: "question",
      html: "<p>Desea eliminar la bitácora ?</p>",
      cancelButtonText: "No",
      confirmButtonText: "Si",
      showDenyButton: true
  }).then(async (params) => {
      if (params.isConfirmed) {
          
      }
  }) */
  service_binnacle.destroy(id).then(() => {
    getData();
  })

}
// const validateCountFormPrevious = ref(false)
// async function getCountDataForm() {
//     await service.getCountDataForm().then(() => {
//         validateCountFormPrevious.value = service.data_count_form.value.monitor.pedagogicals > 1 ? true : false
//         console.log('data_count_form', validateCountFormPrevious.value)

//     });
// }
const headers = [
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "FECHA", value: "activity_date", width: 120 },
  { text: "HORA INICIO", value: "start_time" },
  { text: "HORA FINAL", value: "final_hour" },
  { text: "CREADO POR", value: "user.name" },
  { text: "CÉDULA", value: "profile.document_number" },
  { text: "ACTIVIDAD", value: "activity_name" },
  // { text: "BITÁCORA", value: "binnacle_id" },
  { text: "BITÁCORA", value: "binnacle_name" },
  { text: "DERECHO CULTURAL", value: "cultural_right.name" },
  { text: "EXPERTICIA", value: "expertise.name" },
  { text: "LINEAMIENTO", value: "lineament_id" },
  { text: "LUGAR", value: "place" },
  { text: "MODALIDAD", value: "activation_mode" },
  { text: "NAC", value: "nac.name" },
  { text: "ORIENTACIÓN", value: "orientation.name" },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref([]);

const item_map = (item: { [key: string]: any }) => {
  const { id, consecutive } = item
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: 'Datos Generales',
        fields: {
          "CREADO POR": item.user.name,
          "CÉDULA": item.profile.document_number,
          "BITÁCORA": mixins.get_option_label('binnacles', item.binnacle_id),
          "ACTIVIDAD": item.activity_name,
          "MODO DE ACTIVACIÓN": item.activation_mode,
          "FECHA": item.activity_date,
          "HORA INICIO": item.start_time,
          "HORA FINAL": item.final_hour,
          "LUGAR": item.place,
          "NAC": mixins.get_option_label('nacs', item.nac_id),
          "EXPERTICIA": mixins.get_option_label('expertises', item.expertise_id),
          "DERECHO CULTURAL": mixins.get_option_label('cultural_rights', item.cultural_right_id),
          "OBJETIVO VIVENCIAL": item.experiential_objective,
          "LINEAMIENTO": mixins.get_option_label('lineaments', item.lineament_id),
          "ORIENTACIÓN": mixins.get_option_label('orientations', item.orientation_id),
          "PEC": mixins.get_option_label('pecs', item.pec_id),
          "PEDAGÓGICA": mixins.get_option_label('pedagogicals', item.pedagogical_id),
          "SE CUMPLIÓ EL OBJETIVO VIVENCIAL": (item.goals_met as string).toUpperCase(),
          "EL POR QUE": item.explain_goals_met,
          "INICIO": item.start_activity,
          "DESARROLLO": item.activity_development,
          "FINAL": item.end_of_activity,
          "OBSERVACIONES": item.observations_activity,
          "CAPACIDAD DE BENEFICIARIOS": (item.beneficiaries_capacity == 'aforo') ? 'AFORO' : 'BENEFICIARIO',
          // "ESTADO": item.status,
          // "MENSAJE DE RECHAZO": item.reject_message,
        }
      },
      multimedia: {
        title: 'Multimedia',
        fields: {
          "IMAGEN (DESARROLLO DE LA JORNADA DE PACTO)": item.development_activity_image,
          "IMAGEN (EVIDENCIA DE PARTICIPACIÓN)": item.evidence_participation_image,
          "DOCUMENTO (AFORO)": item.aforo_file,
        }
      },
      aggregates: {
        title: 'Agregados',
        fields: {
          "ASISTENTES (TABLA)": item.assistants.map((assistant) => ({
            "NOMBRE": assistant.full_name,
            "CÉDULA": assistant.document_number,
          })),
        }
      }
    }
  }
}

let page = ref(1)
let pageCount = ref(0)
const filter = ref({})

async function getData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null
  await service_binnacle.get(page.value, searchParams).then(() => {
    items.value = service_binnacle.data.all
    pageCount.value = service_binnacle.data.count_page
  })
}

function updateData(values) {
  page.value = 1
  filter.value = values
  getData()
}

function updatePage() {
  getData()
}

onMounted(async () => {
  await getData()
  // getCountDataForm()
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Bitácoras de Monitor</h2>
    <div v-if="permissions.binnacles.create()" class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <button class="btn btn-primary shadow-md mr-2" @click="createBinnacle">Crear una bitácora</button>
    </div>
  </div>
  <div class="intro-y box mt-5 p-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
        @update:modelValue="updatePage" />
    </div>
    <BaseCrudPDF module="binnacles_monitor" :headers="headers" :items="items" :item_see_fnc="item_map"
      :management_permissions="permissions.binnacles.crud_management()" label="la bitácora"
      :on-delete-fnc="onDeleteBinnacle" @change_filter="updateData" />
  </div>
</template>