import axiosInstance from "@/plugins/axios";
import { setLoading } from "@/utils/loading";
import { useRouter } from "vue-router";
import alerts from "@/utils/alerts"
import mixins from "@/mixins";
import { ref } from "vue";
const module = "profiles";
const apiPath = import.meta.env.VITE_API_PATH;

export default function userservice() {
  const data = ref([]);
  const dataNoPaginate = ref([]);
  const dataOne = ref([]);
  const router = useRouter();
  const errors = ref("");

  const getProfile = async () => {
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

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, data)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        router.push({ name: "users.index" });
      }
      return response;
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
      if (response.status === 200){
        router.push({ name: "users.index", params: {} });
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
      if (response.status === 200) await router.push({ name: "users.index" });
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  return {
    data,
    dataOne,
    errors,
    getProfile,
    getOne,
    update,
    create,
    destroy,
  };
}
