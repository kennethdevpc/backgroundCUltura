import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";
import mixins from "@/mixins";
const module = "assistants";
const apiPath = import.meta.env.VITE_API_PATH;

export default function assistantsService() {

    const data = ref([])
    const dataOne = ref([])
    const router = useRouter()
    const errors = ref('')
    const loading = ref(false)

    const get = async () => {
        try {
            setLoading(true)
            let response = await axiosInstance.get(`/${apiPath}/${module}`)
            .finally(() => {
                setLoading(false)
            })

          //  console.log('assistants',response.data)
            if (response.status === 200){
              data.value = response.data
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
            if (response.status === 200){
              dataOne.value = response.data.items;
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

    const create = async (data, config) => {
        errors.value = ''
        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}`, data, config)
            .finally(() => {
                setLoading(false)
            })

            if (response.status === 200){
                alerts.create()
            }
            return response
        } catch (e) {
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const update = async (id, payload) => {
        errors.value = ''
        try {
            setLoading(true)
            const response = await axiosInstance.put(`/${apiPath}/${module}/${id}`, payload)
            .finally(() => {
                setLoading(false)
            })
            if (response.status === 200){
                alerts.update()
            }
            return response
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
            if (response.status === 200){
                alerts.destroy('Asistente', id)
                await router.push({ name: 'assistants.index' })
            }
        } catch (e) {
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    return {
        data,
        dataOne,
        errors,
        loading,
        get,
        getOne,
        update,
        create,
        destroy,
    }
}