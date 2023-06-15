
import axiosInstance from "@/plugins/axios";
import { setLoading } from "@/utils/loading";
import { ref } from "vue";
import { useRouter } from "vue-router";
import mixins from "@/mixins";
const module = "methodologicalsheetsone";
const apiPath = import.meta.env.VITE_API_PATH;

export default function userservice() {
  const data = reactive({
		all: [],
		one: {},
		no_paginate: [],
		user_id: null,
		count_page:0
	})
  const dataOne = ref([]);
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
        data.value = response.data.items;
				data.count_page = response.data.count_page;
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
        dataOne.value = response.data.items;
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

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, data)
        .finally(() => {
          setLoading(false);
        });

      /* if (response.status === 200) {
        router.push({ name: "users.index" });
      } */
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
        router.push({ name: 'methodologicalsheetsone.index' })
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
      if (response.status === 200) await router.push({ name: "methodologicalsheetsone.index" });
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const getCountLimit = async () => {
    try {
      setLoading(true);
      let response = await axiosInstance
        .get(`/${apiPath}/getCountLimit/${module}`)
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
  }

  return {
    get,
    data,
    dataOne,
    errors,
    getOne,
    update,
    create,
    destroy,
    getCountLimit
  };
}
