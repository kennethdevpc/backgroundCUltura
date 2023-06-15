import axiosInstance from "@/plugins/axios"
import alerts from "@/utils/alerts"
import { setLoading } from "@/utils/loading"
import mixins from "@/mixins"
const module = "parentschools"
const apiPath = import.meta.env.VITE_API_PATH;

export default function parentschools_services() {
  const data = reactive({
    all: [],
    one: {},
    no_paginate: [],
		count_page: 0
  });
  const router = useRouter();
  const errors = ref("");

  const get = async (page, searchParams = null) => {
    setLoading(true);
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
      return response
    } catch (e) {
      if (e.response.status  === 404) {
        mixins.not_found_by_id();
    }else{
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
    }
  };

  const create = async (payload) => {
    errors.value = "";

    // Creating FormData
    const dt = new FormData();

    const {
      development_activity_image,
      evidence_participation_image,
      added_wizards,
      assistance_monitors,
      ...rest
    } = payload;

    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }

    dt.append("added_wizards", JSON.stringify(added_wizards));
    dt.append("assistance_monitors", JSON.stringify(assistance_monitors));

    dt.append("development_activity_image", development_activity_image.file);
    dt.append(
      "evidence_participation_image",
      evidence_participation_image.file
    );

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, dt, {
          "Content-Type": "multipart/form-data",
        })
        .finally(() => {
          setLoading(false);
        });
      if (response.status == 200) {
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
      start_time,
      final_time,
      added_wizards,
      assistance_monitors,
      ...rest
    } = payload;

    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }
    
    // var st = dayjs(start_time, ["HH:mm"]).format("h:mm A")
    // var ft = dayjs(final_time, ["HH:mm"]).format("h:mm A")

    dt.append("start_time", start_time);
    dt.append("final_time", final_time);

    dt.append("added_wizards", JSON.stringify(added_wizards));
    dt.append("assistance_monitors", JSON.stringify(assistance_monitors));

    if (development_activity_image.file) {
      dt.append("development_activity_image", development_activity_image.file);
    }
    if (evidence_participation_image.file) {
      dt.append(
        "evidence_participation_image",
        evidence_participation_image.file
      );
    }

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/${id}?_method=PUT`, dt, {
          "Content-Type": "multipart/form-data",
        })
        .finally(() => {
          setLoading(false);
        });

      if (response.status === 200) {
        alerts.update();
        router.push({ name: "parentschools.index", params: {} });
      }
      return response
    } catch (e) {
      alerts.custom_validation(e.response.data.message);
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
        alerts.destroy("Escuela de Padres", id);
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
