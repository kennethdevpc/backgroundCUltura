<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue"
import type { Header, Item } from "vue3-easy-data-table";
import axios from "axios";
import services from '../../../services/role.service'

const role_services = services()

const router = useRouter()
const route = useRoute()

const routeName = computed(() => {
    return String(route.name).split('.')[0]
})

const create = () => {
    router.push({ name: `roles.create` })
}

const update = (id: string | number) => {
    router.push({ name: `roles.edit`, params: { id: id } })
}

async function destroy(id: string | number) {
    await role_services.destroy(id);
    fetchData();
}

const headers: Header[] = [
    { text: "NOMBRE", value: "name" },
    { text: "SLUG", value: "slug" },
    { text: "DESCRIPCION", value: "description" },
    { text: "ACCESO COMPLETO", value: "full-access" },
    { text: "PUBLICO", value: "public" },
    { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
    const { id } = item

    return {
        id,
        consecutive: `Rol-${id}`,
        sections: {
            general: {
                title: 'Datos Generales',
                fields: {
                    "NOMBRE": item.name,
                    "SLUG": item.slug,
                    "DESCRIPCIÓN": item.description,
                    "ACCESO COMPLETO": (item['full-access'] == 'si') ? 'SI' : 'NO',
                    "PUBLICO": (item.public == 1) ? 'SI' : 'NO',
                    "PERMISOS (TABLA)": item.permissions.map((permission) => ({
                        "NOMBRE": permission.name,
                        "SLUG": permission.slug,
                        "CONTROLADOR": permission.controller,
                        "DESCRIPCIÓN": permission.description,
                    })),
                }
            },
        }
    }
}

async function fetchData() {
    await role_services.get()
    items.value = role_services.data.value

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
        <h2 class="text-lg font-medium mr-auto">Roles</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" @click="create">Crear un rol</button>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5">
        <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map" label="el rol" :on-delete-fnc="destroy" />
    </div>
</template>