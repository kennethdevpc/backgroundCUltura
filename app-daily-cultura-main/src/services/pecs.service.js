import { addMonths, subMonths } from "date-fns";
import { setLoading } from "@/utils/loading";
import axiosInstance from "@/plugins/axios";
import dayjs from 'dayjs'
import alerts from "@/utils/alerts";
import mixins from "@/mixins";
const module = "pecs";
const apiPath = import.meta.env.VITE_API_PATH;

export default function pecsServices() {
  const data = reactive({
    all: [],
    one: {},
    count_page: 0,
    beneficiaries: []
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

    const { place_image1, place_image2, ...rest } = data;

    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }

    dt.append("place_image1", place_image1.file);
    dt.append("place_image2", place_image2.file);

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}`, dt, {
          "Content-Type": "multipart/form-data",
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
      setLoading(false);
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const update = async (id, payload) => {
    errors.value = "";

    // Creating FormData
    const dt = new FormData();

    const { place_image1, place_image2, ...rest } = payload;

    for (const [name, value] of Object.entries(rest)) {
      dt.append(name, value);
    }

    dt.append("place_image1", place_image1.file);
    dt.append("place_image2", place_image2.file);

    try {
      setLoading(true);
      const response = await axiosInstance
        .post(`/${apiPath}/${module}/${id}`, dt, {
          "Content-Type": "multipart/form-data",
        })
        .finally(() => {
          setLoading(false);
        });

      if (response.status === 200) {
        alerts.update();
        router.push({ name: "pecs.index" });
      }
    } catch (e) {
      setLoading(false);
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
        alerts.destroy("PEC", id);
      }
    } catch (e) {
      setLoading(false);
      alerts.custom_validation(e.response.data.error ?? e.response.data.message);
    }
  };

  const byActivityDate = async (filter = '', initDate, lastDate) => {
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

    return axiosInstance.post(
      `/${apiPath}/${module}/query/byRangeActvityDate`,
      {
        filter,
        initDate: initDate || _initDate,
        lastDate: lastDate || _lastDate,
      }
    );
  };

  return {
    data,
    errors,
    get,
    getOne,
    update,
    create,
    destroy,
    byActivityDate
  };
}
