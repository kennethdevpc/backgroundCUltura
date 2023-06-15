import { addMonths, subMonths } from "date-fns";
import { setLoading } from "@/utils/loading";
import axiosInstance from "@/plugins/axios";
import dayjs from 'dayjs'
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const apiPath = import.meta.env.VITE_API_PATH;
const module = "methodologicalsheetstwo";

export default function MethodologicalSheetsTwo() {
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
      return response;
    } catch (e) {
      if (e.response.status === 404) {
        mixins.not_found_by_id();
      } else {
        alerts.custom_validation(e.response.data.error ?? e.response.data.message);
      }
    }
  };

  const create = async (payload) => {
    errors.value = "";
    const dt = new FormData()

    for (const [name, value] of Object.entries(payload)) {
      dt.append(name, value)
    }
    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, dt, {
          'Content-Type': 'multipart/form-data',
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
    const dt = new FormData()

    for (const [name, value] of Object.entries(payload)) {
      dt.append(name, value)
    }

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/${id}`, dt, {
          'Content-Type': 'multipart/form-data',
        })
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
        alerts.destroy("Ficha Pedagógica", id);
      }
    } catch (e) {
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const byRangeActivityDate = (initDate, lastDate) => {
    const [year, month, day] = dayjs().format("YYYY-MM-DD").split("-")
    let _initDate = ``;
    let _lastDate = ``;
    if (day <= 10) {
      _initDate = dayjs(subMonths(dayjs(`${year}-${month}-01`).toDate(), 1)).format("YYYY-MM-DD")
      _lastDate = `${year}-${month}-10`;
    } else {
      _initDate = `${year}-${month}-11`;
      _lastDate = dayjs(addMonths(dayjs(`${year}-${month}-10`).toDate(), 1)).format("YYYY-MM-DD")
    }

    return axiosInstance.post(`/${apiPath}/${module}/byRangeActvityDate`, {
      initDate: initDate || _initDate,
      lastDate: lastDate || _lastDate,
    });
  };

  return {
    data,
    errors,
    get,
    getOne,
    update,
    create,
    destroy,
    byRangeActivityDate
  };
}
