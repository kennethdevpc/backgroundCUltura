import axiosInstance from "@/plugins/axios";
import { setLoading } from "@/utils/loading";
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const module = "permissions";
const apiPath = import.meta.env.VITE_API_PATH;

export default function permissionService() {
  const data = ref([]);
  const dataOne = ref([]);
  const router = useRouter();
  const errors = ref("");
  const loading = ref(false);

  const get = async (groupBy = false) => {
    let path = `/${apiPath}/${module}`;
    if (groupBy) {
      path += `?group_by=true`;
    }
    try {
      setLoading(true);
      let response = await axiosInstance.get(path).finally(() => {
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
        dataOne.value = response.data.items
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

  const create = async (data, config) => {
    errors.value = "";
    try {
      loading.value = true;
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, data, config)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        alerts.create();
        router.push({ name: "permissions.index", params: {} });
      }
    } catch (e) {
      loading.value = false;
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
        router.push({ name: "permissions.index", params: {} });
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
        alerts.destroy("Permiso", id);
        await router.push({ name: "permissions.index" });
      }
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const assignPermissions = async (form) => {
    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/assign-permissions`, form)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        alerts.saved_permissions();
      }
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

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
    assignPermissions,
  };
}
