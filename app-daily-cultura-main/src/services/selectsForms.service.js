import axiosInstance from "@/plugins/axios"
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const apiPath = import.meta.env.VITE_API_PATH;

export default function selectsForms() {

    const data = reactive({
        all: [],
        one: {},
    });
    const dataOne = reactive({
        all: [],
        one: {},
    });
    const errors = ref('')

    const get = async (type) => {
        try {
            let response = await axiosInstance.get(`/${apiPath}/${type}`)
            if (response.status === 200) {
                data.all = response.data;
            }
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const getOne = async (type, id) => {
        try {
            let response = await axiosInstance.get(`/${apiPath}/${type}/${id}`)
            if (response.status === 200) {
                dataOne.all = response.data;
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

    return {
        data,
        dataOne,
        errors,
        get,
        getOne,
    }
}