import { setLoading } from "@/utils/loading";
import axiosInstance from "../plugins/axios";

const apiPath = import.meta.env.VITE_API_PATH;
const module = "onboarding";

export default {
  getCsrfCookie() {
    return axiosInstance.get("/sanctum/csrf-cookie");
  },
  async register(form) {
    await this.getCsrfCookie();
    setLoading(true)
    return axiosInstance.post(`/${apiPath}/${module}/register`, form)
      .finally(() => {
        setLoading(false)
      })
  },
  async login(form) {
    await this.getCsrfCookie();
    setLoading(true)
    return axiosInstance.post(`/${apiPath}/${module}/login`, form)
      .finally(() => {
        setLoading(false)
      })
  },
  logout() {
    setLoading(true)
    return axiosInstance.post(`/${apiPath}/${module}/logout`)
      .finally(() => {
        setLoading(false)
      })
  },
};
