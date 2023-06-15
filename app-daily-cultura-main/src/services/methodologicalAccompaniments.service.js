import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";
import mixins from "@/mixins";
const module = "methodologicalAccompaniments";
const apiPath = import.meta.env.VITE_API_PATH;

export default function methodologicalAccompanimentsServices() {
  const data = reactive({
    all: [],
    one: {},
    no_paginate: [],
    count_page:0
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
				data.count_page =response.data.count_page;
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
      if (e.response.status  === 404) {
        mixins.not_found_by_id();
    }else{
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
    }
  };

  const create = async (data) => {
    errors.value = "";

    // Creating FormData
    const dt = new FormData();

    const {
      development_activity_image,
      evidence_participation_image,
      assistants,
      ...rest
    } = data;
    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }

    dt.append("development_activity_image", development_activity_image.file);
    dt.append(
      "evidence_participation_image",
      evidence_participation_image.file
    );

    dt.append("assistants", JSON.stringify(assistants));

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, dt, {
          "Content-Type": "multipart/form-data",
        })
        .finally(() => {
          setLoading(false);
        });
      if (response.data.success === true) {
        alerts.create();
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

    const {
      development_activity_image,
      evidence_participation_image,
      assistants,
      ...rest
    } = payload;
    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }

    dt.append("assistants", JSON.stringify(assistants));

    if (development_activity_image != null) {
      dt.append("development_activity_image", development_activity_image.file);
    }
    if (evidence_participation_image != null) {
      dt.append(
        "evidence_participation_image",
        evidence_participation_image.file
      );
    }

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/${id}`, dt, {
          "Content-Type": "multipart/form-data",
        })
        .finally(() => {
          setLoading(false);
        });

      if (response.status == 200) {
        alerts.update();
        router.push({ name: "methodologicalAccompaniments.index" });
      }
    } catch (e) {
      alerts.custom_validation(e.response.data.message);
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
        alerts.destroy("Acompañamiento metodológico", id);
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
