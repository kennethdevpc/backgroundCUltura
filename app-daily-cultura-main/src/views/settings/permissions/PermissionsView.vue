<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue"
import type { Header, Item } from "vue3-easy-data-table";
import services from '../../../services/permission.service'

const permission_services = services()

const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

const create = () => {
    router.push({ name: `${routeName.value}.create` })
}

const edit = (id: string | number) => {
    router.push({ name: `${routeName.value}.edit`, params: { id: id } })
}

async function destroy(id: string | number) {
    await permission_services.destroy(id);
    fetchData();

}

const headers: Header[] = [
    { text: "NOMBRE", value: "name" },
    { text: "SLUG", value: "slug" },
    { text: "DESCRIPCION", value: "description" },
    { text: "CONTROLADOR", value: "controller" },
    { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id } = item

  return {
    id,
    consecutive: `Permiso-${id}`,
    sections: {
      general: {
        title: 'Datos Generales',
        fields: {
          "NOMBRE": item.name,
          "SLUG": item.slug,
          "CONTROLADOR": item.controller,
          "DESCRIPCIÓN": item.description,
        }
      },
    }
  }
}

async function fetchData() {
    await permission_services.get()

    items.value = permission_services.data.value

}

onMounted(async () => {
    await fetchData()
    items.value.map((item) => {
        return {
            ...item,
            actions: 'Acciones'
        }
    })
})
</script>

<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Permisos</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" @click="create">Crear un Permiso</button>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5">
        <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map" label="el permiso" :on-delete-fnc="destroy" />
    </div>
</template>