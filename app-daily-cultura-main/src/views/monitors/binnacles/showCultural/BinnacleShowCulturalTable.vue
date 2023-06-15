<script setup lang="ts">
import BaseCrud from "@/components/base/BaseCrud.vue";
import bscService from "@/services/binnacle_show_cultural.service";
import axios from "axios";
import permissions from "@/permissions";
import mixins from "@/mixins";
import services from '@/services/generals.service'
const service = services()
const service_binnacle = bscService();

const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

const createBinnacle = () => {
    router.push({ name: `${routeName.value}.create` })
}

const editBinnacle = (id) => {
    router.push({ name: `${routeName.value}.edit`, params: { id: id } })
}

async function onDeleteBinnacle(id) {
    service_binnacle.destroy(id).then(() => {
        getData();
    })

}

const headers = [
    { text: "CONSECUTIVO", value: "consecutive" },
   // { text: "ACTIVIDAD", value: "activity" },
    { text: "FECHA", value: "date_range", width: 120 },
   // { text: "EXPERTICIA ARTISTICA", value: "expertise" },
    { text: "EXITOSA", value: "reached_target" },
    { text: "CREADO POR", value: "created_by.name" },
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
                    "ACTIVIDAD": item.activity_name,
                    "FECHA": item.date_range,
                    "EXPERTICIA ARTISTICA": item.expertise,
                    "ESPECÍFICA TU PARTICIPACIÓN ARTISTICA EN EL EVENTO": item.artistic_participation,
                    "¿FUE EXITOSA TU PARTICIPACIÓN?": item.reached_target,
                    "SUSTENTACIÓN": item.sustein,
                }
            },
            multimedia: {
                title: 'Multimedia',
                fields: {
                    "IMAGEN (DESARROLLO DE LA JORNADA DE PACTO)": item.development_activity_image,
                    "IMAGEN (EVIDENCIA DE PARTICIPACIÓN)": item.evidence_participation_image,
                    "DOCUMENTO (AFORO)": item.aforo_pdf,
                }
            }

        }
    }
}

let page = ref(1)
let pageCount = ref(0)
const filter = ref({})

function updateData(values) {
  page.value = 1
  filter.value = values
  getData()
}
function updatePage()  {
  getData()
}

const getItems = computed(() => {
    return items.value.map((item) => {
        return {
            ...item,
            reached_target: item?.reached_target ? 'SI' : 'NO',
            actions: 'Acciones'
        }
    })
})

async function getData() {
    const searchParams = Object.keys(filter.value).length 
	? new URLSearchParams(filter.value)
	: null
    await service_binnacle.get(page.value, searchParams)
    items.value = service_binnacle.data.all;
    pageCount.value = service_binnacle.data.count_page
    
}

onMounted(async () => {
    await getData();
});
</script>

<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Bitácoras show cultural</h2>
        <div v-if="permissions.binnacleculturalshow.create()" class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" @click="createBinnacle">Crear una bitácora</button>
        </div>
    </div>
    <div class="intro-y box mt-5 p-5">
        <div class="flex items-center justify-center p-2 text-base">
        <v-pagination 
          v-model="page" 
          :pages="pageCount" 
          :range-size="1" 
          active-color="#DCEDFF"
          @update:modelValue="updatePage" 
        />
      </div>
        <BaseCrud :headers="headers" :items="getItems" :item_see_fnc="item_map"
            :management_permissions="permissions.binnacleculturalshow.crud_management()" label="la bitácora"
            :on-delete-fnc="onDeleteBinnacle" @change_filter="updateData" />
    </div>
</template>