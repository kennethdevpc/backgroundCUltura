import axiosInstance from "@/plugins/axios"
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading"
import mixins from "@/mixins";
const module = "inscriptions";
const apiPath = import.meta.env.VITE_API_PATH;

export default function inscriptionsServices() {
    const data = reactive({
        all: [],
        one: {},
        count_page: 0
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
                data.all = response.data.items;
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
            // console.log('status',response.status );
            return response
        } catch (e) {
            if (e.response.status === 404) {
                mixins.not_found_by_id();
            } else {
                alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            }

        }
    }

    const create = async (data, type) => {
        errors.value = ''

        const dt = new FormData()

        if (type === 'uncharacterized') {
            const { beneficiary } = data
            const { general_data } = beneficiary
            const { consecutive, ...rest } = general_data

            dt.append('consecutive', consecutive)
            dt.append('beneficiary', JSON.stringify(rest))
            // dt.append('beneficiary_file', file.file)
        }
        if (type === 'characterized') {
            const { beneficiary } = data
            const { general_data, health_data, sociodemographic_characterization } = beneficiary
            const { file, consecutive, ...rest } = general_data

            dt.append('consecutive', consecutive)
            dt.append('beneficiary', JSON.stringify(rest))
            dt.append('beneficiary_health_data', JSON.stringify(health_data))
            dt.append('beneficiary_sociodemographic_characterization', JSON.stringify(sociodemographic_characterization))
            dt.append('beneficiary_file', file.file)
        }
        if (type === 'characterizedWithAccudent') {
            const { beneficiary, attendant } = data

            const { general_data, health_data, sociodemographic_characterization } = beneficiary
            const { file, consecutive, ...rest } = general_data

            dt.append('consecutive', consecutive)
            dt.append('beneficiary', JSON.stringify(rest))
            dt.append('beneficiary_health_data', JSON.stringify(health_data))
            dt.append('beneficiary_sociodemographic_characterization', JSON.stringify(sociodemographic_characterization))
            dt.append('beneficiary_file', file.file)

            const { general_data: att_general_data, health_data: att_health_data, sociodemographic_characterization: att_sociodemographic_characterization } = attendant
            const { ...att_rest } = att_general_data

            dt.append('attendant', JSON.stringify(att_rest))
            dt.append('attendant_health_data', JSON.stringify(att_health_data))
            dt.append('attendant_sociodemographic_characterization', JSON.stringify(att_sociodemographic_characterization))
        }

        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}?type=${type}`, dt, {
                'Content-Type': 'multipart/form-data',
            })
                .finally(() => {
                    setLoading(false)
                })

            if (response.status == 200) {
                if (response.data.success) {
                    alerts.create()
                }
            }
            return response
        } catch (e) {

            alerts.custom_validation(e.response.data.error ?? e.response.data.message);

        }
    }

    const update = async (id, payload) => {
        errors.value = ''

        const dt = new FormData()

        const { identifiers, beneficiary, attendant } = payload

        const { general_data, health_data, sociodemographic_characterization } = beneficiary
        const { file, consecutive, ...rest } = general_data

        dt.append('consecutive', consecutive)
        dt.append('beneficiary', JSON.stringify(rest))
        dt.append('beneficiary_health_data', JSON.stringify(health_data))
        dt.append('beneficiary_sociodemographic_characterization', JSON.stringify(sociodemographic_characterization))

        dt.append('beneficiary_file', file != null ? file.file : null)

        const { general_data: att_general_data, health_data: att_health_data, sociodemographic_characterization: att_sociodemographic_characterization } = attendant
        const { ...att_rest } = att_general_data

        dt.append('attendant', JSON.stringify(att_rest))
        dt.append('attendant_health_data', JSON.stringify(att_health_data))
        dt.append('attendant_sociodemographic_characterization', JSON.stringify(att_sociodemographic_characterization))

        dt.append('identifiers', JSON.stringify(identifiers))

        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}/${id}`, dt, {
                'Content-Type': 'multipart/formdata',
            })
                .finally(() => {
                    setLoading(false)
                })
            if (response.status == 200) {
                alerts.update()
                router.push({ name: 'inscriptions.index' })
            }
            return response
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
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
                alerts.destroy('Inscripción', id)
            }
        } catch (e) {
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