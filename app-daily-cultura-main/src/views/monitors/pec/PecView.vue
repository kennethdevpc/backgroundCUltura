<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue"
import type { Header, Item } from "vue3-easy-data-table";
import axios from "axios";
import services from '../../../services/pecs.service'
import permissions from "@/permissions";
import mixins from "@/mixins";
import ToCreate from "@/components/base/ToCreate.vue";

const pec_services = services()

const router = useRouter()
const route = useRoute()

const { hasPermission } = usePermissions()

const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

const create = () => {
    router.push({ name: `${routeName.value}.create` })
}

async function destroy(id: string | number) {
    await pec_services.destroy(id);
    fetchData();
}
const headers: Header[] = [
   // { text: "#", value: "id" },
    { text: "CONSECUTIVO", value: "consecutive" },
    { text: "FECHA DE ACTIVIDAD", value: "activity_date" },
    { text: "HORA INICIO", value: "start_time" },
    { text: "HORA FINAL", value: "final_hour" },
    { text: "CREADO POR", value: "user.name" },
    { text: "ROL CREADOR", value: "role" },
    { text: "CÉDULA", value: "profile.document_number" },
    { text: "NAC", value: "nac.name" },
    { text: "BARRIO", value: "neighborhood.name" },
    { text: "LUGAR", value: "place" },
    { text: "CREACIÓN", value: "created_at" },
    { text: "ESTADO", value: "status" },
    { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
    const { id, consecutive } = item
    return {
        id,
        consecutive,
        sections: {
            general: {
                title: 'Datos Generales',
                fields: {
                    "CREADO POR":item.user.name,
                    "CÉDULA":item.profile.document_number,
                    "NAC": mixins.get_option_label('nacs', item.nac_id),
                    "BARRIO": mixins.get_option_label('neighborhoods', item.neighborhood_id),
                    "LUGAR": item.place,
                    "OTRO LUGAR": item.other_place_type,
                    "DIRECCION DEL LUGAR": item.place_address,
                    "FECHA": item.activity_date,
                    "HORA INICIO": item.start_time,
                    "HORA FINAL": item.final_hour,
                    "TIPO DE LUGAR": mixins.get_option_label('place_types', item.place_type),
                    "BREVE RESEÑA DEL LUGAR": item.place_description,
                    // "ESTADO": item.status,
                    // "MENSAJE DE RECHAZO": item.reject_message,
                }
            },
            multimedia: {
                title: 'Multimedia',
                fields: {
                    "IMAGEN DEL LUGAR": item.place_image1,
                    "IMAGEN DEL LUGAR 2": item.place_image2,
                }
            },
            aggregates: {
                title: 'Agregados',
                fields: {
                    "BENEFICIARIOS (TABLA)": item.beneficiaries.map((beneficiary) => ({
                        "NOMBRE COMPLETO": beneficiary.full_name,
                        "CÉDULA": beneficiary.nuip
                    }))
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
  fetchData()
}
function updatePage()  {
  fetchData()
}

async function fetchData() {
  const searchParams = Object.keys(filter.value).length 
	? new URLSearchParams(filter.value)
	: null
    await pec_services.get(page.value, searchParams).then(() => {
        items.value = pec_services.data.all
        pageCount.value = pec_services.data.count_page

    })
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
        <h2 class="text-lg font-medium mr-auto">PEC</h2>
        <div v-if="permissions.pecs.create()" class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <ToCreate :to="{ name: 'pecs.create' }">
                Crear un PEC
            </ToCreate>
            <!-- <button class="btn btn-primary shadow-md mr-2" @click="create"></button> -->
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
      <BaseCrud 
        :headers="headers" 
        :items="items" 
        :item_see_fnc="item_map"
        :management_permissions="permissions.pecs.crud_management()" 
        label="el pec" 
        :on-delete-fnc="destroy" 
        @change_filter="updateData"
      />
    </div>
</template>