import { setLoading } from "@/utils/loading";
import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const module = "binnacleculturalshow";
const apiPath = import.meta.env.VITE_API_PATH;

interface data {
    all: any[]
    one: { [key: string]: any }
    count_page: number
}

export default function binnacleCulturalShows() {
    const data = reactive<data>({
        all: [],
        one: {},
        count_page: 0,
    });

    const router = useRouter();
    const errors = ref("");

    const get = async (page, searchParams = null) => {
        try {
            setLoading(true);
            let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
            let response = await axiosInstance
                .get(`/${apiPath}/${module}${query}`)
                .finally(() => {
                    setLoading(false);
                });
            if (response.status === 200) {
                data.all = response.data.items;
                data.count_page = response.data.count_page;
            }
            return response;
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    };

    const getAllByUserId = async () => {
        try {
            setLoading(true);
            let response = await axiosInstance
                .get(`/${apiPath}/getAllByUserLogged`)
                .finally(() => {
                    setLoading(false);
                });
            if (response.status === 200) {
                data.all = response.data.items;
            }
            return response;
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    };

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
            return response;
        } catch (e) {
            if (e.response.status === 404) {
                mixins.not_found_by_id();
            } else {
                alerts.custom_validation(e.response.data.error ?? e.response.data.message);
            }
        }
    };

    const create = async (data) => {
        errors.value = "";

        // Creating FormData
        const dt = new FormData();

        const { file_1, file_2, ...rest } = data;

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value as string);
        }

        dt.append("development_activity_image", file_1 as Blob);
        dt.append("evidence_participation_image", file_2 as Blob);

        try {
            setLoading(true);
            const response = await axiosInstance
                .post(`/${apiPath}/${module}`, dt)
                .finally(() => {
                    setLoading(false);
                });
            if (response.status === 200) {
                if (response.data.success) {
                    alerts.create();
                }
            }
            return response;
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    };

    const update = async (id, payload) => {
        errors.value = "";

        // Creating FormData
        const dt = new FormData();

        const { file_1, file_2, ...rest } = payload;

        for (const [name, value] of Object.entries(rest)) {
            dt.append(name, value as string);
        }

        dt.append("development_activity_image", file_1 as Blob);
        dt.append("evidence_participation_image", file_2 as Blob);

        try {
            setLoading(true);
            const response = await axiosInstance
                .post(`/${apiPath}/${module}/${id}`, dt)
                .finally(() => {
                    setLoading(false);
                });

            if (response.status === 200) {
                alerts.update();
            }

            return response;
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    };

    const destroy = async (id) => {
        try {
            setLoading(true);
            const response = await axiosInstance
                .delete(`/${apiPath}/${module}/${id}`)
                .finally(() => {
                    setLoading(false);
                });
            if (response.status == 200) {
                alerts.destroy("Show cultural", id);
            }
        } catch (e) {
            alerts.custom_validation(e.response.data.error ?? e.response.data.message);
        }
    };

    return {
        data,
        errors,
        get,
        getOne,
        update,
        create,
        destroy,
        getAllByUserId
    };
}
