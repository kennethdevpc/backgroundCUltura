<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import services from "../../../services/user.service";
import { useRouter } from "vue-router";
import mixins from "@/mixins";
import BaseCrudExcel from "@/components/base/BaseCrudExcel.vue";
const router = useRouter();
const route = useRoute();

const user = services();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const createModule = () => {
  router.push({ name: `${routeName.value}.create` });
};

const editModule = (id: string | number) => {
  router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteModule(id: string | number) {
  await user.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "#", value: "id" },
  { text: "NOMBRE", value: "name" },
  { text: "EMAIL", value: "email" },
  { text: "ROLES", value: "roles" },
  { text: "ESTADO", value: "user_status" },
  { text: "FECHA DE CREACIÓN", value: "created_at" },
  { text: "AJUSTES", value: "change_password" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id } = item;

  const profile = computed(() => {
    const record = item.profile;

    return record != undefined ? record : "Sin perfil";
  });

  const permissions = ref([]);

  item.roles.forEach((role) => {
    role.permissions.forEach((permission) => {
      permissions.value.push({
        NOMBRE: permission.name,
        SLUG: permission.slug,
        CONTROLADOR: permission.controller,
        DESCRIPCIÓN: permission.description,
      });
    });
  });

  return {
    id,
    consecutive: `Usuario-${id}`,
    sections: {
      general: {
        title: "Datos Generales",
        fields: {
          NOMBRE: item.name,
          EMAIL: item.email,
          ESTADO: item.status == 1 ? "ACTIVO" : "INACTIVO",
        },
      },
      profile: {
        title: "Perfil",
        fields: {
          ID: profile.value.id,
          "NOMBRE COMPLETO": profile.value.contractor_full_name,
          CÉDULA: profile.value.document_number,
          NAC: mixins.get_option_label("nacs", profile.value.nac_id),
          // "gestor_id": item.profile.gestor_id,
          // "psychosocial_id": item.profile.psychosocial_id,
          // "methodological_support_id": item.profile.methodological_support_id,
          // "support_tracing_monitoring_id": item.profile.support_tracing_monitoring_id,
          // "ambassador_leader_id": item.profile.ambassador_leader_id,
          // "instructor_leader_id": item.profile.instructor_leader_id,
          // "created_at": item.profile.created_at,
          // "updated_at": item.profile.updated_at,
        },
      },
      roles: {
        title: "Roles",
        fields: {
          "ROLES (TABLA)": item.roles.map((role) => ({
            NOMBRE: role.name,
            SLUG: role.slug,
            DESCRIPCIÓN: role.description,
            "ACCESO COMPLETO": role["full-access"] == "yes" ? "SI" : "NO",
            PUBLICO: role.public == 1 ? "SI" : "NO",
          })),
        },
      },
      permissions: {
        title: "Permisos",
        fields: {
          "PERMISOS (TABLA)":
            permissions.value.length > 0 ? permissions.value : "?",
          // "PERMISOS (TABLA)": permissions.value
        },
      },
    },
  };
};

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await user.get(page.value, searchParams);
  items.value = user.data.all;
  pageCount.value = user.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}
function updatePage() {
  fetchData();
}

onMounted(async () => {
  await fetchData();
  items.value.map((item) => {
    return {
      ...item,
      actions: "Acciones",
    };
  });
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Usuarios</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <button class="btn btn-primary shadow-md mr-2" @click="createModule">
        Crear usuario
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination
        v-model="page"
        :pages="pageCount"
        :range-size="1"
        active-color="#DCEDFF"
        @update:modelValue="updatePage"
      />
    </div>
    <BaseCrudExcel
      :headers="headers"
      :items="items"
      :item_see_fnc="item_map"
      label="Usuarios"
      :on-delete-fnc="deleteModule"
      @change_status="fetchData()"
      @change_filter="updateData"
      :server_options="{ page: 1, rowsPerPage: 15 }"
    />
  </div>
</template>
