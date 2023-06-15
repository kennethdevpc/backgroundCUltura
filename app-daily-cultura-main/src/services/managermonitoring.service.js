import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";
import mixins from "@/mixins";
const module = "managermonitorings";
const apiPath = import.meta.env.VITE_API_PATH;

export default function managermonitoringService() {
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
      setLoading(true);
      let response = await axiosInstance
        .get(`/${apiPath}/${module}/${id}`)
        .finally(() => {
          setLoading(false);
        });

      if (response.status === 200) {
        data.one = response.data.items;
      }
      return response
    } catch (e) {
      if (e.response.status === 404) {
        mixins.not_found_by_id();
      } else {
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
      }
    }
  };

  const create = async (data, config) => {
    errors.value = "";
    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, data, config)
        .finally(() => {
          setLoading(false);
        });

      if (response.status === 201) {
        alerts.create();
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

      if (response.status === 200) {
        alerts.update();
        router.push({ name: "managermonitorings.index", params: {} });
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
        alerts.destroy("Seguimiento", id);
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
  };
}
