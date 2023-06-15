import alerts from "@/utils/alerts";
import axiosInstance from "../plugins/axios"

const apiPath = import.meta.env.VITE_API_PATH;
const module = "notifications"

function useNotificationServices () {
    async function get() {
        try {
			let response = await axiosInstance.get(`/${apiPath}/${module}`)
            return response
		} catch (e) {
          //  console.log(e.response)
			// alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
    }

    async function getByAuthenticated() {
        try {
			let response = await axiosInstance.get(`/${apiPath}/${module}/authenticated`)
            return response
		} catch (e) {
          //  console.log(e.response)
			// alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
    }

    async function markAsRead(id: string | number) {
        try {
			let response = await axiosInstance.put(`/${apiPath}/${module}/markAsRead/${id}`)
            return response
		} catch (e) {
          // console.log(e.response)
			// alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
    }

    async function trash(id: string | number) {
        try {
			let response = await axiosInstance.put(`/${apiPath}/${module}/trash/${id}`)
            return response
		} catch (e) {
          //  console.log(e.response)
			// alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
    }

    return {
        get,
        getByAuthenticated,
        markAsRead,
        trash
    }
}

export default useNotificationServices