import { setLoading } from "@/utils/loading";
import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
const apiPath = import.meta.env.VITE_API_PATH;

interface data {
    roles: { [key: string]: any }[]
    users: { [key: string]: any }[]
    pecs: { [key: string]: any }[]
}

let data = reactive<data>({
    roles: [],
    users: [],
    pecs: [],
})

const getRoles = async () => {
    try {
        setLoading(true);
        let response = await axiosInstance
            .get(`/${apiPath}/getRole`)
            .finally(() => {
                setLoading(false);
            });

        if (response.status == 200) {
            if(response.data.length>0){
                data.roles = response.data
            }else{
                data.roles = []
            }
        }
        return response;
    } catch (e) {
        alerts.custom('Error', e.response.data.error ?? e.response.data.message, 'error')
    }
}

const getUsers = async (role_id: string | number) => {
    try {
        if (role_id == '') {
            role_id = 0
        }
        setLoading(true)
        let response = await axiosInstance
            .get(`/${apiPath}/getUser/${role_id}`)
            .finally(() => {
                setLoading(false)
            })

        if (response.status == 200) {
            if(response.data.items.length>0){
                data.users = response.data.items
            }else{
                data.users = [];
            }
        }
        return response
    } catch (e) {
        alerts.custom('Error', e.response.data.error ?? e.response.data.message, 'error')
    }
}

const getPecs = async (user_id: string | number) => {
    try {
        if (user_id == '') {
            return
        }
        setLoading(true)
        let response = await axiosInstance
            .get(`/${apiPath}/getPecsCreatedBy/${user_id}`)
            .finally(() => {
                setLoading(false)
            })

        if (response.status == 200) {
            data.pecs = response.data.items
        }
        return response
    } catch (e) {
        alerts.custom('Error', e.response.data.error ?? e.response.data.message, 'error')
    }
}

const getRolesCustom = async (nac_id: string | number) => {
    try {
        if (nac_id == '' || nac_id == null) {
            nac_id = 0
        }
        setLoading(true);
        let response = await axiosInstance
            .get(`/${apiPath}/getRoleCustom/${nac_id}`)
            .finally(() => {
                setLoading(false);
            });

        if (response.status == 200) {
            if(response.data.items.length>0){
                data.roles = response.data.items
            }else{
                data.roles = []
            }
        }
        return response;
    } catch (e) {   
        alerts.custom('Error', e.response.data.error ?? e.response.data.message, 'error')
    }
}

export default {
    data,
    getRoles,
    getUsers,
    getPecs,
    getRolesCustom
}
