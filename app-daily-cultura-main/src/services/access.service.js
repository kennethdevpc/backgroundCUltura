import axiosInstance from "@/plugins/axios";
const apiPath = import.meta.env.VITE_API_PATH;
export default {
  getAccess() {
    return axiosInstance.get(`/${apiPath}/get-access`);
  },
  userHaveAccess(data) {
    return axiosInstance.post(`/${apiPath}/have-access`, data);
  },
  getPermissions() {
    return axiosInstance.get(`/${apiPath}/get-permissions`);
  },
  getButtonBooleanCreates(data) {
    return axiosInstance.get(`/${apiPath}/get-button-boolean-creates`);
  },
  getCountDataForm() {
    return axiosInstance.get(`/${apiPath}/getCountDataForm`);
  },
  getPollOnly() {
    return axiosInstance.get(`/${apiPath}/getPollOnly`);
  }
};
