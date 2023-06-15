<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import services from "../../../services/module.service";
import { useRouter } from 'vue-router';
const router = useRouter();
const route = useRoute();
const module = services()
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
  await module.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "#", value: "id" },
  { text: "NOMBRE", value: "name" },
  { text: "SLUG", value: "slug" },
  { text: "DESCRIPCIÓN", value: "description" },
  { text: "ICONO", value: "icon" },
  { text: "DISPONIBLE", value: "available" },
  { text: "TIENE ARTICULO", value: "hasItems" },
  { text: "POSICIÓN", value: "position" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id } = item

  return {
    id,
    consecutive: `Modulo-${id}`,
    sections: {
      general: {
        title: 'Datos Generales',
        fields: {
          "NOMBRE": item.name,
          "SLUG": item.slug,
          "DESCRIPCIÓN": item.description,
          "ICONO": item.icon,
          "DISPONIBLE": (item.available == 1) ? 'SI' : 'NO',
          "TIENE ARTICULO": (item.hasItems == 1) ? 'SI' : 'NO',
          "POSICIÓN": item.position,
        }
      },
    }
  }
}

async function fetchData() {
  await module.get()
  items.value = module.data.value

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
    <h2 class="text-lg font-medium mr-auto">Modulos</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <button class="btn btn-primary shadow-md mr-2" @click="createModule">
        Crear un modulo
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map" label="el modulo" :on-delete-fnc="deleteModule"
      :server_options="{ page: 1, rowsPerPage: 10 }" />
  </div>
</template>