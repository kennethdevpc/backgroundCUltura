import { Method } from 'axios'
import { useOnboardingStore } from "./stores/onboarding"
import { Select, SelectsState, useSelectStore } from '@/stores/selects'
import axiosInstance from "@/plugins/axios"
import { RouteLocationNormalized } from 'vue-router'
import Swal from "sweetalert2"
import alerts from './utils/alerts'
import router from './router'
import { storeToRefs } from 'pinia'

const apiPath = import.meta.env.VITE_API_PATH

function send_success() {
    Swal.fire('Envió', 'Los datos de gestión se han enviado correctamente!', 'info')
}

export type roles =
    | 'direccion'
    | 'gestores_culturales'
    | 'monitor_cultural'
    | 'instructor'
    | 'embajador'
    | 'apoyo_seguimiento_monitoreo'
    | 'apoyo_metodologico'
    | 'apoyo_supervision'
    | 'coordinador_metodologico'
    | 'coordinador_psicosocial'
    | 'coordinador_supervision'
    | 'psicosocial'
    | 'lider_instructor'
    | 'lider_embajador'
    | 'super.root'
    | 'root'
    | 'subdireccion'
    | 'direccion'
    | 'secretaria_cultural'
    | 'coordinador_seguimiento'
    | 'coordinador_administrativo'
    | 'lider_metodologico'
    ;

export type SelectKey = keyof Omit<SelectsState, 'beneficiaries_table' | 'beneficiaries' | 'monitors_table' | 'monitors_parentschools'>

export default {
    methods: {
        send_management: async (module: string, id: string | number | string[], payload: any) => {

            const { management } = payload
            const { status, reject_message, file_1, file_2 } = management

            const data = reactive({
                status,
                reject_message,
                table: module,
                id
            })

            const fd = new FormData()
            const route = computed(() => router.currentRoute.value)

            const keys = Object.keys(data)
            for (let i = 0; i < keys.length; i++) {
                fd.append(keys[i], data[keys[i]])
            }

            // Comment
            fd.append('file_1', file_1)
            fd.append('file_2', file_2)


            try {
                let response = await axiosInstance.post(`/${apiPath}/management`, fd, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                })

                if (response.status == 200) {
                    if (response.data.success) {
                        alerts.custom('', response.data.message, 'info')
                        router.push({ name: `${route.value.name.toString().split('.')[0]}.index` })
                    }
                }

            } catch (error) {
                if (error?.response?.status == 400) {
                    alerts.custom('Validación', error.response.data.message, 'warning')
                }
                else if (error?.response?.status == 403) {
                    alerts.custom('', error.response.data.message, 'error')
                }
            }
        }
    },
    computed: {
        is_role: (roleSlug: roles) => {
            const { is_role } = useOnboardingStore()

            return is_role(roleSlug)
        },
        is_admin: () => {
            const { is_role } = useOnboardingStore()

            return is_role('super.root') || is_role('root')
        },
        is_slug: (slug_search: string, route: RouteLocationNormalized) => {
            const validate = route.name.toString().includes(slug_search)
            return (validate) ? true : false
        },
    },
    get_option_label: (key: SelectKey, value: string): Select | string => {
        const { options } = storeToRefs(useSelectStore())
        let record = options.value[key].find((option) => option.value == value)
        if (key == 'users_table') {
            record = options.value[key].find((option) => option.id == value)
            return record.name
        }

        if (record != undefined)
            return record.label
        else
            return 'No encontrad@'
    },
    get_monitor_by_id: (value: string) => {
        const { options } = storeToRefs(useSelectStore())

        const record = options.value.monitors_table.find((option) => option.id == value)

        if (record != undefined)
            return record
        else
            return 'No encontrad@'
    },
    not_found_by_id: () => {
        const module = router.currentRoute.value
        const transform_name = String(module.name).split('.')[0]
        let id = module.params.id

        router.push({
            name: `${transform_name}.index`
        })
        alerts.custom('Información', `El registro con ID: ${id}, no existe en nuestra base de datos.`, 'info')
    }
}