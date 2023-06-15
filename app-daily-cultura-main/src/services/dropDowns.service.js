import axiosInstance from "@/plugins/axios"
import { setLoading } from "@/utils/loading";
import alerts from "@/utils/alerts";
const apiPath = import.meta.env.VITE_API_PATH;

export default function dropDownsService() {

    const data = ref([])
    const dataOne = ref([])
    const dataPaginate = reactive({
        all: [],
        one: {},
		no_paginate: [],
        count_page: 0,
    });
    const router = useRouter()
    const errors = ref('')

    /* const get = async (module) => {
        setLoading(true)
        return axiosInstance.get(`/${apiPath}/${module}`)
            .finally(() => {
                setLoading(false)
            })
    } */

    const get = async (page, module, searchParams = null) => {
		try {
			setLoading(true)
			let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
			let response = await axiosInstance.get(`/${apiPath}/${module}${query}`).finally(() => {
				setLoading(false)
			});
			if (response.status === 200) {
				dataPaginate.all = response.data.items;
				dataPaginate.count_page = response.data.count_page;
			}
		} catch (e) {
			console.log(e);
		}
	};

    const getOne = async (module, id) => {
        try {

            setLoading(true)
            let response = await axiosInstance.get(`/${apiPath}/${module}/${id}`)
                .finally(() => {
                    setLoading(false)
                })

            if (response.status === 200) {
                dataOne.value = response.data.items;
            }
            return response
        } catch (e) {
            setLoading(false)
            if (e.response.status  === 404) {
                mixins.not_found_by_id();
            }else{
                alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            }
        }
    }

    const create = async (module, data, routeFront) => {
        errors.value = ''
        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}`, data)
                .finally(() => {
                    setLoading(false)
                })
            if (response.status === 201)
                router.push({ name: routeFront + '.index' })
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    const update = async (module, id, payload, routeFront) => {
        errors.value = ''
        try {

            setLoading(true)
            const response = await axiosInstance.put(`/${apiPath}/${module}/${id}`, payload)
                .finally(() => {
                    setLoading(false)
                })

            if (response.status === 200)
                router.push({ name: routeFront + '.index', params: {} })
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    const destroy = async (module, id, routeFront) => {
        try {
            setLoading(true)
            const response = await axiosInstance.delete(`/${apiPath}/${module}/${id}`)
                .finally(() => {
                    setLoading(false)
                })
            if (response.status === 200)
                //await router.push({ name: routeFront+'.index' })
                location.reload()
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    return {
        dataPaginate,
        data,
        dataOne,
        errors,
        get,
        getOne,
        update,
        create,
        destroy
    }
}