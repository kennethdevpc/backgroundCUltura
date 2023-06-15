import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";
import mixins from "@/mixins";
const module = "items";
const apiPath = import.meta.env.VITE_API_PATH;

export default function moduleservice() {
  const data = ref([]);
  const dataOne = ref([]);
  const router = useRouter();
  const errors = ref("");

  const get = async () => {
    try {
      setLoading(true);
      let response = await axiosInstance
        .get(`/${apiPath}/${module}`)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        data.value = response.data.items;
      }
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
  };

  const create = async (data) => {
    errors.value = "";
    // const dataTranspiled = new FormData()
    // dataTranspiled.values = data

  

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, data)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        alerts.create();
        router.push({ name: "items.index" });
      }
    } catch (e) {
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const update = async (id, payload) => {
    errors.value = "";
    try {

      setLoading(true);
      const response = await axiosInstance
        .put(`/${apiPath}/${module}/${id}`, payload)
        .finally(() => {
          setLoading(false);
        });

      if (response.status === 200) {
        alerts.update();
        router.push({ name: "items.index", params: {} });
      }
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
      if (response.status === 200) {
        alerts.destroy("Modulo", id);
        await router.push({ name: "items.index" });
      }
    } catch (e) {
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  return {
    data,
    dataOne,
    errors,
    get,
    getOne,
    update,
    create,
    destroy,
  };
}
