<script setup lang="ts">
import { useOnboardingStore } from '@/stores/onboarding'
import { useSelectStore } from '@/stores/selects'
import BaseCrudDelete from "./BaseCrudDelete.vue"
import BaseCrudSee from "./BaseCrudSee.vue"
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import dayjs from 'dayjs'
import get_status from '@/utils/get_status'
import mixins from '@/mixins'
import statuses from '@/types/statuses'
import ToggleStatus from '@/components/users/ToggleStatus.vue'
import useManagement from '@/utils/composables/useManagement'
import type { FilterOption, Header, Item } from "vue3-easy-data-table"
import ManagementButton from '../crud/ManagementButton.vue'
import MultipleOptions from '../crud/MultipleOptions.vue'
import DatasheetFormat from '../crud/DatasheetFormat.vue'

const storagePath = import.meta.env.VITE_BASE_IMAGE_URL
const onboarding_store = useOnboardingStore()

const router = useRouter()
const route = useRoute()

const { options } = useSelectStore()

// Spliting route.name >>> example >>> pecs.index = pecs
const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
|
|   headers for table headers
|
|   items for table items (only refered headers will rendered)
|
|   label for delete modal
|
|   onDeleteFnc for button of delete modal
|
*/

const props = withDefaults(defineProps<{
    edit_gestor?: boolean
    headers: Header[]
    items: Item[]
    item_see_fnc?: Function | boolean
    item_see_fullview?: boolean
    label: string
    management_permissions?: boolean
    onDeleteFnc: Function
    show_exports?: boolean,
    hiddenButton?: boolean,
}>(), {
    hiddenButton: false,
    edit_gestor: false,
    item_see_fnc: () => (false),
    item_see_fullview: false,
    label: '',
    management_permissions: false,
    onDeleteFnc: () => (false),
    show_exports: false
})
const emit = defineEmits(['change_status', 'change_filter'])

const searchOptions = computed(() => {
    const filter = props.headers
        .filter((item: Header) => (item.value != "actions" && item.value != "created_at" && item.value != "status" && item.value != "status"))
        .map((item: Header) => {
            return {
                value: item.value,
                label: item.text
            }
        }).sort((a, b) => {
            const label_a = a.label.toLowerCase()
            const label_b = b.label.toLowerCase()

            if (label_a < label_b) {
                return -1;
            }
            if (label_a > label_b) {
                return 1;
            }

            // names must be equal
            return 0;
        })

    return filter
})

const status_options = computed(() => {
    let arr = options.status

    arr.push({
        label: 'TODOS',
        value: 'all'
    })

    return arr
})

const conditions_role_admin = computed(() => {
    return (mixins.computed.is_role('super.root') || mixins.computed.is_role('root')) ? true : false
})

const sorts = reactive({
    by: '',
    type: ''
})

const filters = reactive({
    search_field: '',
    search_value: '',
    status_criteria: 'all',
    user_status_criteria: '',
    date_criteria_start: '',
    date_criteria_end: ''
})

watch(() => filters.search_field, () => {
    filters.search_value = ''
})

const getOptionsByField = computed(() => {
  // Mapa de campos a opciones
  const fieldToOptionsMap = {
    binnacle_name: 'binnacles',
    lineament_id: 'lineaments'
  };

  // Obtener las opciones según el campo de búsqueda seleccionado
  let optionsByField = [];
  if (fieldToOptionsMap[filters.search_field]) {
    optionsByField = options[fieldToOptionsMap[filters.search_field]];
  } else {
    optionsByField = options[filters.search_field];
  }

  // Devolver las opciones o un arreglo vacío si no hay opciones disponibles
  return optionsByField || [];
});

const filter = () => {
    emit('change_filter', filters)
}

const clean = () => {
    filters.search_field = ''
    filters.search_value = ''
    filters.status_criteria = 'all'
    filters.user_status_criteria = ''
    filters.date_criteria_start = ''
    filters.date_criteria_end = ''
    emit('change_filter', filters)
}

const filter_options = computed((): FilterOption[] => {
    const filter_options_arr: FilterOption[] = [];
    if (filters.status_criteria != 'all') {
        filter_options_arr.push({
            field: 'status',
            comparison: '=',
            criteria: filters.status_criteria,
        });
    }
    if (filters.user_status_criteria !== '') {
        filter_options_arr.push({
            field: 'status',
            comparison: '=',
            criteria: filters.user_status_criteria,
        });
    }
    if (filters.date_criteria_start !== '') {
        filter_options_arr.push({
            field: 'created_at',
            criteria: filters.date_criteria_start,
            comparison: (value, criteria) => {
                const format_value = dayjs(value).format("YYYY-MM-DD")
                const date = new Date(format_value).getTime()
                const start_date = new Date(criteria).getTime()
                const end_date = computed(() => {
                    if (filters.date_criteria_end !== '') {
                        return new Date(filters.date_criteria_end).getTime()
                    }
                    else {
                        return new Date().getTime()
                    }
                })

                return (date >= start_date && date <= end_date.value)
            },
        });
    }
    return filter_options_arr;
});

const show_status = ref(false)
const show_user_status = ref(false)
const show_date = ref(false)

// Function for Edit
const editAction = (id: string | number) => {
    if (props.edit_gestor) {
        router.push({ name: `${routeName.value}.edit`, params: { id: id } })
    }
    else {
        router.push({ name: `${routeName.value}.edit`, params: { id: id } })
    }
}

const managementAction = (id: string | number) => {
    if (props.edit_gestor) {
        router.push({ name: `${routeName.value}.edit`, params: { id: id } })
    }
    else {
        router.push({ name: `${routeName.value}.edit`, params: { id: id } })
    }
}

const changePasswordAction = (id: string | number) => {
    router.push({ name: `${routeName.value}.changePassword`, params: { id: id } })
}

const get_edit = (item: { user_id: string | number, created_by: any, status: statuses }) => {
    const logged_id = onboarding_store.get_user.id as string | number
    const { user_id, created_by, status } = item
    const is_equals = (created_by as string) == logged_id || user_id == logged_id || created_by && created_by.id === logged_id;

    if (status == 'REC' && is_equals) {
        return true
    }
    else {
        return false
    }
}

const { hasDoubleManagement, reviewersFromDouble } = useManagement()

/*
|--------------------------------------------------------------------------
| Headers example
|--------------------------------------------------------------------------
|
|   const headers: Header[] = [
|      { text: "#", value: "id" },
|      { text: "NAC", value: "nac_id" },
|      { text: "ACCIONES", value: "actions"}
|   ];
|
|   Any question: pezedev
*/

/*
|--------------------------------------------------------------------------
| Items example
|--------------------------------------------------------------------------
|
|   const items = ref<Item[]>([])
|   items.value.map((item) => {
|        return {
|            ...item,
|            actions: 'Acciones'
|        }
|    })
|
|   const items: Item[] = [
|       {
|           id: 1,
|           nac_id: '12312',
|           actions: 'Acciones'
|       },
|   ];
|
|   Any question: pezedev
*/

/*
|--------------------------------------------------------------------------
| Label example
|--------------------------------------------------------------------------
|
|   label: la mesa de dialogo
|
*/

/*
|--------------------------------------------------------------------------
| onDeleteFnc example
|--------------------------------------------------------------------------
|
|    function deleteAction (id: string | number): any {
|        router.push({ name: 'pecs.delete', params: { id: id } })
|    }
|
*/
</script>

<template>
    <div class="intro-y flex flex-col gap-2">
        <section class="flex flex-col gap-3 lg:grid lg:grid-cols-4 lg:items-end">
            <div class="grid grid-cols-2 gap-3">
                <div class="w-full">
                    <BaseSelect label="BUSCAR EN" tooltip="Seleccione en que campo quiere buscar" placeholder="Seleccione"
                        name="search_field" v-model="filters.search_field" :options="searchOptions" />
                </div>
                <div v-if="getOptionsByField.length" class="w-full">
                    <BaseSelect label="NOMBRE" tooltip="Seleccione el valor a buscar" placeholder="Seleccione"
                        name="search_value" v-model="filters.search_value" :options="getOptionsByField" />
                </div>

                <div v-else class="w-full">
                    <BaseInput type="text" label="VALOR" tooltip="Ingrese el valor a buscar" placeholder="Valor"
                        name="search_value" v-model="filters.search_value" />
                </div>
            </div>
            <div v-if="show_date" class="flex flex-col justify-start h-full">
                <label for="regular-form-2" class="form-label font-bold min-w-max mr-2">FECHA RANGO</label>
                <div class="grid grid-cols-2 gap-1.5 w-full intro-x">
                    <BaseInput class="" type="date" tooltip="Desde" name="date_criteria_start"
                        v-model="filters.date_criteria_start" />
                    <BaseInput class="" type="date" tooltip="Hasta" name="date_criteria_end"
                        v-model="filters.date_criteria_end" />
                </div>
            </div>
            <div v-if="show_status" class="flex items-start gap-6 h-full">
                <div class="w-full intro-x">
                    <BaseSelect label="ESTADO" tooltip="" placeholder="Seleccione" name="status_criteria"
                        v-model="filters.status_criteria" :options="status_options" :allowEmpty="true" />
                </div>
            </div>
            <div v-if="show_user_status" class="flex items-start gap-6 h-full">
                <div class="w-full intro-x">
                    <BaseSelect label="ESTADO" tooltip="" placeholder="Seleccione" name="user_status_criteria"
                        v-model="filters.user_status_criteria" :options="[
                            { label: 'ACTIVO', value: '1' },
                            { label: 'INACTIVO', value: '0' }
                        ]" :allowEmpty="false" />
                </div>
            </div>
           <div class="flex">
                <div class="">
                        <button class="btn btn-primary shadow-md mr-2" @click="filter">Filtrar</button>
                </div>
                <div class="">
                    <button class="btn btn-danger shadow-md mr-2" @click="clean">Limpiar</button>
                </div>
           </div>
        </section>
        <DataTable :headers="headers" :items="items" :sort-by="sorts.by" 
            :sort-type="sorts.type" :rows-per-page="100" :hide-rows-per-page="true" :hide-footer="true"
            table-class-name="customize-table">
            <template #header-status="{ text }">
                <div class="flex gap-1 relative">
                    <p>{{ text }}</p>
                    <button @click="show_status = !show_status" :class="(show_status) ? 'opacity-100' : 'opacity-50'"
                        class="hover:opacity-100 text-primary transition">
                        <FilterIcon size="20" />
                    </button>
                </div>
            </template>
            <template #header-user_status="{ text }">
                <div class="flex gap-1 relative">
                    <p>{{ text }}</p>
                    <button @click="show_user_status = !show_user_status"
                        :class="(show_user_status) ? 'opacity-100' : 'opacity-50'"
                        class="hover:opacity-100 text-primary transition">
                        <FilterIcon size="20" />
                    </button>
                </div>
            </template>
            <template #header-created_at="{ text }">
                <div class="flex gap-1 relative">
                    <p>{{ text }}</p>
                    <button @click="show_date = !show_date" :class="(show_date) ? 'opacity-100' : 'opacity-50'"
                        class="hover:opacity-100 text-primary transition">
                        <FilterIcon size="20" />
                    </button>
                </div>
            </template>
            <template #header-actions="{ text }">
                <div class="flex justify-end">
                    {{ text }}
                </div>
            </template>
            <template #item-development_activity_image="item">
                <div class="customize-item">
                    <span v-if="item.development_activity_image.slice(-3) === 'pdf'"
                        class="w-10 h-10 rounded-lg bg-gray-800 text-white flex items-center justify-center">PDF</span>
                    <img v-else :src="storagePath + item.development_activity_image"
                        class="w-10 h-10 rounded-lg object-cover">
                </div>
            </template>
            <template #item-evidence_participation_image="item">
                <div class="customize-item">
                    <img :src="storagePath + item.evidence_participation_image" class="w-10 h-10 rounded-lg object-cover">
                </div>
            </template>
            <template #item-disinterest_apathy="{ disinterest_apathy }">
                {{ (disinterest_apathy == 1) ? 'SI' : 'NO' }}
            </template>
            <template #item-role="{ role }">
                <p
                    :class="(role == 'Monitor Cultural') ? 'opacity-100 text-danger transition' : (role == 'Instructor') ? 'opacity-100 text-success' : 'opacity-90 transition'">
                    <b> {{ role }}</b>
                </p>
            </template>
            <template #item-roles="{ roles }">
                <span v-for="role in roles"
                    class="px-4 py-1 rounded-full text-white bg-blue-400 font-semibold text-sm flex align-center w-max cursor-pointer active:bg-gray-300 transition duration-300 ease">{{
                        role.name
                    }}
                </span>
            </template>
            <template #item-reintegration="{ reintegration }">
                {{ (reintegration == 1) ? 'SI' : 'NO' }}
            </template>
            <template #item-created_at="{ created_at }">
                <div class="customize-item">
                    <p>{{ (created_at != null && created_at != '') ? dayjs(created_at).format("DD/MM/YYYY") : '' }}</p>
                </div>
            </template>
            <template #item-start_time="{ start_time }">
                <div class="customize-item">
                    <p>{{ (start_time != null && start_time != '') ? dayjs(`2000-01-01 ${start_time}`).format("hh:mm") :
                        '' }}</p>
                </div>
            </template>
            <template #item-final_hour="{ final_hour }">
                <div class="customize-item">
                    <p>{{ (final_hour != null && final_hour != '') ? dayjs(`2000-01-01 ${final_hour}`).format("hh:mm") :
                        '' }}</p>
                </div>
            </template>
            <template #item-final_time="{ final_time }">
                <div class="customize-item">
                    <p>{{ (final_time != null && final_time != '') ? dayjs(`2000-01-01 ${final_time}`).format("hh:mm") :
                        '' }}</p>
                </div>
            </template>
            <template #item-place_type="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('place_types', item.place_type) }}</p>
                </div>
            </template>
            <template #item-beneficiary_attrition_factors="item">
                <div class="customize-item">
                    <p>{{
                        mixins.get_option_label('beneficiary_attrition_factors', item.beneficiary_attrition_factors)
                    }}</p>
                </div>
            </template>
            <template #item-binnacle_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('binnacles', item.binnacle_id) }}</p>
                </div>
            </template>
            <template #item-user_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('users_table', item.user.id) }}</p>
                </div>
            </template>
            <template #item-created_by="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('users_table', item.created_by.id) }}</p>
                </div>
            </template>
            <template #item-nac_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('nacs', item.nac_id) }}</p>
                </div>
            </template>
            <template #item-expertise_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('expertises', item.expertise_id) }}</p>
                </div>
            </template>
            <template #item-lineament_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('lineaments', item.lineament_id) }}</p>
                </div>
            </template>
            <template #item-cultural_right_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('cultural_rights', item.cultural_right_id) }}</p>
                </div>
            </template>
            <template #item-orientation_id="item">
                <div class="customize-item">
                    <p>{{ mixins.get_option_label('orientations', item.orientation_id) }}</p>
                </div>
            </template>
            <template #item-aspects="item">
                <div class="block">
                    <ol class="list-decimal list-outside flex flex-col gap-y-1 justify-end text-sm font-medium">
                        <template v-for="aspect in item.aspects">
                            <li>
                                <p class="inline-flex items-center py-0.5 text-gray-800 whitespace-nowrap">
                                    {{ mixins.get_option_label('aspects', aspect) }}
                                </p>
                            </li>
                        </template>
                    </ol>
                </div>
            </template>
            <template #item-roles_mm="{ roles }">
                <MultipleOptions type="roles_display" :options="roles.map((r) => r.role_id)" />
            </template>
            <template #item-roles_associate="{ roles_associate }">
                <MultipleOptions type="roles_display" :options="roles_associate" />
            </template>
            <template #item-datasheet_planning="{ datasheet_planning }">
                <DatasheetFormat :datasheet="datasheet_planning.datasheet" />
            </template>
            <template #item-status="item">
                <div class="customize-item">
                    <p
                        :class="(item.status == 'REC') ? 'opacity-100 text-danger transition' : (item.status == 'APRO') ? 'opacity-100 text-success' : 'opacity-90 transition'">
                        {{ get_status(item.status) }}</p>
                </div>
            </template>
            <template #item-user_status="item">
                <div class="flex items-center">
                    <ToggleStatus @toggle="emit('change_status')" :id="item.id" :actual_status="item.status" />
                </div>
            </template>
            <template #item-has_profile="item">
                <div class="flex items-center">
                    <p :class="item.has_profile == 'NO' ? 'text-danger':''"><b>{{ item.has_profile }}</b></p>
                </div>
            </template>

            <template #item-change_password="item">
                <div class="flex gap-2 justify-end">
                    <button @click="changePasswordAction(item.id)"
                        class="btn btn-success text-white flex flex-nowrap gap-1 items-center" v-if="conditions_role_admin">
                        <ExternalLinkIcon icon="Password" class="w-5 h-5" />
                        <span class="text-sm whitespace-nowrap">
                            Cambiar contraseña
                        </span>
                    </button>
                </div>
            </template>
            <template #item-actions="item">
                <div class="flex gap-2 justify-end">
                    <template v-if="item_see_fnc">
                        <div :id="`see_${item.id}`">
                            <BaseCrudSee :item="(item_see_fnc as Function)(item)" :full_view="item_see_fullview" />
                        </div>
                    </template>
                    <template v-if="get_edit(item) || conditions_role_admin && !hiddenButton">
                        <button @click="editAction(item.id)" class="btn btn-primary flex gap-1 items-center">
                            <EditIcon class="w-5 h-5" />
                            <span class="text-sm">
                                Editar
                            </span>
                        </button>
                    </template>
                    <template v-if="hasDoubleManagement">
                        <template v-if="mixins.computed.is_role(reviewersFromDouble.first) && item.status === 'ENREV'">
                            <ManagementButton :id="item.id" />
                        </template>
                        <template v-else-if="mixins.computed.is_role(reviewersFromDouble.second) && item.status === 'REV'">
                            <ManagementButton :id="item.id" />
                        </template>
                    </template>
                    <template v-else>
                        <template v-if="management_permissions && (item.status == 'ENREV')">
                            <ManagementButton :id="item.id" />
                        </template>
                    </template>
                    <!-- <template
                                        v-if="management_permissions && (item.status == 'ENREV' || item.status == 'REV') && !conditions_direcction && !conditions_secretaria_cultural">
                                        <button @click="managementAction(item.id)" class="btn btn-secondary flex gap-1 items-center">
                                            <CheckSquareIcon class="w-5 h-5" />
                                            <span class="text-sm">
                                                Revisión
                                            </span>
                                        </button>
                                    </template>
                                    <template
                                        v-if="management_permissions && (item.status == 'ENREV' || item.status == 'REV' && conditions_direcction)">
                                        <button @click="managementAction(item.id)" class="btn btn-danger flex gap-1 items-center text-white"
                                            v-if="item.role_slug == 'coordinador_supervision'">
                                            <CheckSquareIcon class="w-5 h-5" />
                                            <span class="text-sm">
                                                Revisión
                                            </span>
                                        </button>
                                    </template> -->
                    <BaseCrudDelete :item="item" :label="label" :on-delete="onDeleteFnc" v-if="conditions_role_admin && !hiddenButton" />
                </div>
            </template>
        </DataTable>
    </div>
</template>
<style>
.customize-table {
    --easy-table-body-row-height: 60px;
    --easy-table-header-height: 60px;
    --easy-table-header-background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity));
}
</style>