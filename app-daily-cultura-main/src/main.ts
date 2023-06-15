import { createApp } from "vue";
import { createPinia } from "pinia";
import "./assets/css/app.css";
import App from "./App.vue";
import globalComponents from "./global-components";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import router from "./router";
import utils from "./utils";
import Vue3EasyDataTable from 'vue3-easy-data-table'
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

const app = createApp(App)
const pinia = createPinia()

app.component('DataTable', Vue3EasyDataTable)
app.component('VPagination', VPagination)
pinia.use(piniaPluginPersistedstate)
app.use(router)
app.use(pinia)

globalComponents(app);
utils(app);

app.mount("#app");
