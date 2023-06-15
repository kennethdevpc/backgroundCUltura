<script lang="ts" setup>
import type { Header, Item } from "vue3-easy-data-table"
import services from '@/services/psychosocialinstruction.service'
import permissions from "@/permissions";
import mixins from "@/mixins";
import BaseCrudPDF from "@/components/base/BaseCrudPDF.vue";

const psychosocialInstructionService = services()

const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

const create = () => {
    router.push({ name: `${routeName.value}.create` })
}

async function destroy(id: string | number) {
    await psychosocialInstructionService.destroy(id)
    fetchData()
}
const headers: Header[] = [
    { text: "CONSECUTIVO", value: "consecutive" },
    { text: "CREADO POR", value: "user.name" },
    { text: "FECHA", value: "activity_date", width: 120 },
    { text: "NAC", value: "nac.name" },
    { text: "HORA INICIO", value: "start_time" },
    { text: "HORA FINAL", value: "final_hour" },
    // { text: "DESARROLLO", value: "development_activity_image" },
    // { text: "PARTICIPACIÓN", value: "evidence_participation_image" },
    { text: "CREACIÓN", value: "created_at" },
    { text: "ESTADO", value: "status" },
    { text: "ACCIONES", value: "actions" },
]


const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
    //copio el objeto para que no se modifique el original
    const { id, consecutive } = item
    const monitors = item.monitors.map(mon => {
        return {
            "ID": mon.id,
            "NOMBRE COMPLETO": mon.name,
            "CI": mon.email
        }
    })
    const assistants = item.assistants.map(asis => {
        return {
            NOMBRE: asis.assistant_name,
            CEDULA: asis.assistant_document_number,
            EMAIL: asis.assistant_email,
            TELEFONO: asis.assistant_phone,
            CARGO: asis.assistant_position,
        }
    })
    return {
        id,
        consecutive,
        sections: {
            general: {
                title: 'Datos Generales',
                fields: {
                    "FECHA": item.activity_date,
                    "NAC": mixins.get_option_label('nacs', item.nac_id),
                    "HORA INICIO": item.start_time,
                    "HORA FINAL": item.final_hour,
                    "TEMAS A TRATAR": item.themes_day,
                    "OBJETIVO": item.objective_day,
                    "DESARROLLO DE LOS TEMAS": item.development_themes,
                    "CONCLUSIONES Y REFLEXIONES Y COMPROMISOS DE LA JORNADA": item.conclusions_reflections_commitments,
                    "¿REPORTE ALERTAS PARA HACER SEGUIMIENTO? ": item.report_followup_alerts == '1' ? 'SI' : 'NO',
                    "IMAGEN DE LA ACTIVIDAD": item.development_activity_image,
                    "IMAGEN DEL DESARROLLO": item.evidence_participation_image,
                    "MONITORES": monitors,

                },
            },
            asistances: {
                title: "Datos de asistencia",
                fields: {
                    "ASISTENTES": assistants,
                    //"MONITORES ASISTENTES":monitors,
                }
            }
        }
    }
}

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await psychosocialInstructionService.get(page.value, searchParams);
  items.value = psychosocialInstructionService.data.all;
  pageCount.value = psychosocialInstructionService.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}

function updatePage() {
  fetchData();
}

onMounted(async () => {
    await fetchData()
    items.value.map((item) => {
        return {
            ...item,
            actions: 'Acciones'
        }
    })
})
</script>

<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Instrucción Psicosocial</h2>
        <div v-if="permissions.psychosocialinstructions.create()" class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" @click="create">Crear una instrucción psicosocial</button>
        </div>
    </div>
    <div class="intro-y box mt-5 p-5">
        <div class="flex items-center justify-center p-2 text-base">
            <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
              @update:modelValue="updatePage" />
          </div>
        <BaseCrudPDF module="psychosocialInstructions" :headers="headers" :items="items" label="la Instrucción Psicosocial" :item_see_fnc="item_map"
            :management_permissions="permissions.psychosocialinstructions.crud_management()" :on-delete-fnc="destroy"  
            @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }" />
    </div>
</template>
