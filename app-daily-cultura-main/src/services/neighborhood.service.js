import axiosInstance from "@/plugins/axios"
import { setLoading } from "@/utils/loading";
import alerts from "@/utils/alerts";

const module = "neighborhoods";
const apiPath = import.meta.env.VITE_API_PATH;

export default function neighborhoodService() {

    const data = reactive({
		all: [],
		one: {},
		no_paginate: [],
		user_id: null,
		count_page:0
	})
	const router = useRouter()
	const errors = ref('')

    const get = async (page, searchParams = null) => {
        try {
			setLoading(true)
			let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
			let response = await axiosInstance.get(`/${apiPath}/${module}${query}`).finally(() => {
				setLoading(false)
			});
			if (response.status === 200) {
				data.all = response.data.items;
				data.count_page =response.data.count_page;
			}
		} catch (e) {
			alerts.custom_validation(e.response ?? e.response);
		}
    }

    const getOne = async (id) => {
        try {
			setLoading(true);
			let response = await axiosInstance
				.get(`/${apiPath}/${module}/${id}`)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				data.one = response.data.items;
			}
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);;
		}
    }

    const create = async (payload) => {
        errors.value = ''
        try {
            setLoading(true);
			const response = await axiosInstance
				.post(`/${apiPath}/${module}`, payload)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				data.user_id = response.data.items.id;
				router.push({ name: 'neighborhoods.index' })
			}
			return response;
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    const update = async (id, payload) => {
        errors.value = ''
        try {
            setLoading(true);
			const response = await axiosInstance
				.put(`/${apiPath}/${module}/${id}`, payload)
				.finally(() => {
					setLoading(false);
				});
			if (response.status == 200) {
				router.push({ name: "neighborhoods.index", params: {} });
			}
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    const destroy = async (id) => {
        try {
            setLoading(true)
            const response = await axiosInstance
				.delete(`/${apiPath}/${module}/${id}`)
				.finally(() => {
					setLoading(false);
				});
            if (response.status === 200) await router.push({ name: "neighborhoods.index" });
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            errors.value = e.response.data.errors
        }
    }

    return {
        data,
        errors,
        get,
        getOne,
        update,
        create,
        destroy
    }
}