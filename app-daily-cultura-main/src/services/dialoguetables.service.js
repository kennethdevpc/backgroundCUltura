import axiosInstance from "@/plugins/axios"
import alerts from "@/utils/alerts"
import { setLoading } from "@/utils/loading"
import mixins from "@/mixins";
const module = "dialoguetables"
const apiPath = import.meta.env.VITE_API_PATH

export default function dialoguetablesServices() {

    const data = reactive({
        all: [],
        one: {},
        no_paginate: [],
        count_page: 0
      });
      
      const router = useRouter();
      const errors = ref("");
    
      const get = async (page, searchParams = null) => {
        try {
          setLoading(true)
          let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
          let response = await axiosInstance.get(`/${apiPath}/${module}${query}`).finally(() => {
            setLoading(false)
          });
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
            if (response.status === 200){
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

    const create = async (payload) => {
        errors.value = ''

        // Creating FormData
        const dt = new FormData()

        const { place_image1, place_image2, asistents, ...rest } = payload

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value)
        }
        
        dt.append('asistents', JSON.stringify(asistents))
        dt.append('place_image1', place_image1.file)
        dt.append('place_image2', place_image2.file)

        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}`, dt, {
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
            errors.value = e.response.data.errors
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    }

    const update = async (id, payload) => {
        errors.value = ''

        // Creating FormData
        const dt = new FormData()

        const { place_image1, place_image2, asistents, ...rest } = payload

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value)
        }

        dt.append('asistents', JSON.stringify(asistents))
        if (place_image1.file){
            dt.append('place_image1', place_image1.file)
        }
        if (place_image2.file){
            dt.append('place_image2', place_image2.file)
        }
        
        try {
            setLoading(true)
            const response = await axiosInstance.post(`/${apiPath}/${module}/${id}`, dt, {
                'Content-Type': 'multipart/form-data',
            })
            .finally(() => {
                setLoading(false)
            })

            if (response.status == 200){
                if (response.data.success) {
                    alerts.update()
                    router.push({ name: 'dialoguetables.index', params: {} })
                }
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
                alerts.destroy('Mesa de Dialogo', id)
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