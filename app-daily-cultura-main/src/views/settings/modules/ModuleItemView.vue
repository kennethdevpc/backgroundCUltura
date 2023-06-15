<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import service from "../../../services/module_item.service";

const router = useRouter();
const route = useRoute();
const moduleItem = service()
const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const createModuleItem = () => {
  router.push({ name: `${routeName.value}.create` });
};

const editModuleItem = (id: string | number) => {
  router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteModuleItem(id: string | number) {
  await moduleItem.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "#", value: "id" },

  { text: "NOMBRE", value: "name" },
  { text: "RUTA", value: "route" },
  { text: "DESCRIPCIÓN", value: "description" },
  { text: "ICONO", value: "icon" },
  { text: "DISPONIBLE", value: "available" },
  { text: "MODULO", value: "module.name" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id } = item

  return {
    id,
    consecutive: `ModuloItem-${id}`,
    sections: {
      general: {
        title: 'Datos Generales',
        fields: {
          "NOMBRE": item.name,
          "ICONO": item.icon,
          "DISPONIBLE": (item.available == 1) ? 'SI' : 'NO',
          "RUTA": item.route,
          "MODULO": item.module.name,
        }
      },
    }
  }
}

async function fetchData() {
  await moduleItem.get()
  items.value = moduleItem.data.value

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
    <h2 class="text-lg font-medium mr-auto">Modulos Item</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <button class="btn btn-primary shadow-md mr-2" @click="createModuleItem">
        Crear un modulo item
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map" label="el modulo item" :on-delete-fnc="deleteModuleItem" />
  </div>
</template>