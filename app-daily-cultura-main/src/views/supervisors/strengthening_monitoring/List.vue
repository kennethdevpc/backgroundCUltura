<script lang="ts" setup>
import axios from "axios";
import BaseCrud from "@/components/base/BaseCrud.vue";
import mixins from "@/mixins";
import permissions from "@/permissions";
import strengthening_monitoringService from "@/services/strengthening_monitoring.service";
import type { Header, Item } from "vue3-easy-data-table";
import { useOnboardingStore } from "@/stores/onboarding";
import ToCreate from "@/components/base/ToCreate.vue";

const services = strengthening_monitoringService();

const { hasPermission } = usePermissions();

const { get_user, is_role } = useOnboardingStore();

const strengthening_monitoring = strengthening_monitoringService();
const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
	return String(route.name).split(".")[0];
});

const create = () => {
	router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
	await services.destroy(id);
	fetchData();
}

const headers: Header[] = [
	{ text: "CONSECUTIVO", value: "consecutive" },
	{ text: "USUARIO", value: "user_id" },
	{ text: "FECHA", value: "activity_date", width: 120 },
	{ text: "HORA INICIO", value: "start_time" },
	{ text: "HORA FINAL", value: "final_hour" },
	{ text: "NAC", value: "nac.name" },
	{ text: "CREACIÓN", value: "created_at" },
	{ text: "ESTADO", value: "status" },
	{ text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
    const { id, consecutive, roles } = item
    const rol = computed(() => {
        return roles[0].name
    })
    // console.log('rol',rol.value)
    return {
        id,
        consecutive,
        sections: {
            general: {
                title: 'Datos Generales',
                fields: {
                    "NAC": item.nac.name,
                    "ROL": rol.value,
                    "USUARIO": mixins.get_option_label('users_table', item.user.id),
                    "FECHA": item.activity_date,
                    "HORA INICIO": item.start_time,
                    "HORA FINAL": item.final_hour,
                    "LUGAR": item.place,
                    // "ESTADO": item.status,
                    // "MENSAJE DE RECHAZO": item.reject_message,
                }
            },
            development: {
                title: 'DESARROLLO DE LA VISITA',
                fields: {
                    "OBJETIVOS ESTRATÉGICOS DEL ÁREA": item.strategic_objectives_area,
                    "PROPÓSITO DE LA VISITA": item.purpose_visit,
                }
            },
            diagnostic: {
                title: 'DIAGNÓSTICO INICIAL',
                fields: {
                    "TEMÁTICAS ABORDADAS": item.topics_covered,
                    "PERCEPCIÓN DE LOS PARTICIPANTES FRENTE A LAS ACTIVIDADES DESARROLLADAS POR EL ÁREA": item.participants_perception,
                    "DIFICULTADES O PROBLEMÁTICAS IDENTIFICADAS": item.problems_identified,
                    "RECOMENDACIONES Y ACCIONES DE MEJORA PROPUESTAS POR LOS PARTICIPANTES ": item.recommendations_actions,
                    "PERCEPCIONES/COMENTARIOS/ANÁLISIS FRENTE AL AVANCE DEL PROCESO": item.comments_analysis,
                }
            },
            multimedia: {
                title: 'Multimedia',
                fields: {
                    "IMAGEN DESARROLLO": item.development_activity_image,
                    "IMAGEN EVIDENCIA DE PARTICIPACIÓN": item.evidence_participation_image,
                }
            },
        }
    }
}

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

/*async function fetchData() {
    // if (is_role('coordinador_seguimiento') || mixins.computed.is_admin()) {
    await services.get().then(() => {
        items.value = services.data.all
    });
    // }
    //  else {

    //     await services.getAllByUserId().then(() => {
    //         items.value = services.data.all
    //     });
    // }

}*/

// <p>{{  }}</p>

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await strengthening_monitoring.get(page.value, searchParams);
  items.value = strengthening_monitoring.data.all;
  pageCount.value = strengthening_monitoring.data.count_page;
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
	await fetchData();
	items.value.map((item) => {
		return {
			...item,
			actions: "Acciones",
		};
	});
});
</script>

<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Fortalecimiento seguimiento</h2>
        <div v-if="hasPermission('strengtheningOfMonitorings.create')" class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <ToCreate :to="{ name: 'strengtheningOfMonitorings.create' }">
                Crear una Fortalecimiento seguimiento
            </ToCreate>
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
        <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map"
            :management_permissions="permissions.strengthening_monitorings.crud_management()"
            label="Fortalecimiento seguimiento" :on-delete-fnc="destroy"
            @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }" />
    </div>
</template>
