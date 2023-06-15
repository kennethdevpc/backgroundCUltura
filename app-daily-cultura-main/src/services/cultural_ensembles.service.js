
import axiosInstance from "@/plugins/axios";
import { setLoading } from "@/utils/loading";
import { ref } from "vue";
import { useRouter } from "vue-router";
import mixins from "@/mixins";
const module = "culturalEnsembles";
const apiPath = import.meta.env.VITE_API_PATH;

export default function culturalEnsembles() {
  //const data = ref([]);
  const dataOne = ref([]);
  const router = useRouter();
  const errors = ref("");

  const data = reactive({
    all: [],
    one: {},
    no_paginate: [],
    count_page:0
  });

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
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	}

  const getOne = async (id) => {
    // console.log('Getting One...');
    try {
      setLoading(true);
      let response = await axiosInstance
        .get(`/${apiPath}/${module}/${id}`)
        .finally(() => {
          setLoading(false);
        });
      if (response.status === 200) {
        dataOne.value = response.data.items;
        return response.data.items
      }
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

    for (const [name, value] of Object.entries(data)) {
      dt.append(name, value);
    }


    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, dt, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
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

    for (const [name, value] of Object.entries(payload)) {
      dt.append(name, value);
    }


    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/${id}`, dt, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
        .finally(() => {
          setLoading(false);
        });
      if (response.data.success) {
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
      if (response.status === 200) await router.push({ name: "culturalEnsembles.index" });
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
        data.value = response.data.items;
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
