import axiosInstance from "@/plugins/axios"
import { setLoading } from "@/utils/loading"
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const module = "binnacles"
const apiPath = import.meta.env.VITE_API_PATH;

export default function BinnacleServices() {

    const data = reactive({
        all: [],
        one: {},
        count_page: 0,
    })
    const router = useRouter()
    const errors = ref('')

    const get = async (page, searchParams = null) => {
        try {
            setLoading(true)
            let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
            let response = await axiosInstance.get(`/${apiPath}/${module}${query}`)
            .finally(() => {
                setLoading(false)
            })
            if (response.status === 200) {
                data.all = response.data.items
                data.count_page = response.data.count_page;
            }
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const getOne = async (id) => {
        try {
            setLoading(true)
            let response = await axiosInstance.get(`/${apiPath}/${module}/${id}`)
            .finally(() => {
                setLoading(false)
            })

            if (response.status === 200) {
                data.one = response.data.items;
            }
            return response
        } catch (e) {
            if (e.response.status  === 404) {
                mixins.not_found_by_id();
            }else{
                alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            }
        }
    }

    const create = async (payload, from) => {
        errors.value = ''

        // Creating FormData
        const dt = new FormData()
        const { development_activity_image, evidence_participation_image, aforo_file, beneficiary_id, ...rest } = payload

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value)
        }

        dt.append('created_from', from)
        dt.append('beneficiary_id', JSON.stringify(beneficiary_id))
        dt.append('development_activity_image', development_activity_image)
        dt.append('evidence_participation_image', evidence_participation_image)
        dt.append('aforo_file', aforo_file)

        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}`, dt, {
                'Content-Type': 'multipart/form-data',
            })
            .finally(() => {
                setLoading(false)
            })
            if (response.status === 200) {
                if (response.data.success) {
                    alerts.create()
                }
            }
            return response
        } catch (e) {
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const update = async (id, payload, from) => {
        errors.value = ''

        // Creating FormData
        const dt = new FormData()
        const { development_activity_image, evidence_participation_image, aforo_file, beneficiary_id, ...rest } = payload

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value)
        }

        dt.append('updated_from', from)
        dt.append('beneficiary_id', JSON.stringify(beneficiary_id))
        dt.append('development_activity_image', development_activity_image)
        dt.append('evidence_participation_image', evidence_participation_image)
        dt.append('aforo_file', aforo_file)

        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}/${id}`, dt, {
                'Content-Type': 'multipart/form-data',
            })
            .finally(() => {
                setLoading(false)
            })

            if (response.status === 200) {
                alerts.update()
                if (from == 'gestor')
                    router.push({ name: 'binnacleManagers.index' })
                else if (from == 'monitor')
                    router.push({ name: 'binnacles.index' })
            }
        } catch (e) {
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const destroy = async (id) => {
        try {
            setLoading(true)
            const response = await axiosInstance.delete(`/${apiPath}/${module}/${id}`)
            .finally(() => {
                setLoading(false)
            })
            if (response.status == 200) {
                alerts.destroy('Bitácora', id)
            }
        } catch (e) {
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    return {
        data,
        errors,
        get,
        getOne,
        update,
        create,
        destroy,
    }
}