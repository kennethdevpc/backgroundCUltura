import { setLoading } from "@/utils/loading";
import axiosInstance from "../plugins/axios";

const apiPath = import.meta.env.VITE_API_PATH;
const module = "permissionRole";

export default {

  async store(form) {
    setLoading(true)
    return axiosInstance.post(`/${apiPath}/${module}`, form)
    .finally(() => {
        setLoading(false)
    })
  },
};
