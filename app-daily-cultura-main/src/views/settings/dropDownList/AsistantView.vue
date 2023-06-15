<script lang="ts" setup>
    import BaseCrud from "../../../components/base/BaseCrud.vue"
    import type { Header, Item } from "vue3-easy-data-table";
    import services from "@/services/assistants.service"

    const assistants_services = services()

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

    async function deleteNac (id: string | number) {
        await assistants_services.destroy(id)
    }

    const headers: Header[] = [
        { text: "#", value: "id" },
        { text: "NOMBRE", value: "assistant_name" },
        { text: "NAC", value: "nac_id" },
        { text: "DOCUMENTO", value: "assistant_document_number" },
        { text: "CARGO", value: "assistant_position" },
        { text: "TELEFONO", value: "assistant_phone" },
        { text: "EMAIL", value: "assistant_email" },
        { text: "ACCIONES", value: "actions"},

    ];

    const items = ref<Item[]>([]);

    async function fetchData () {
        await assistants_services.get().then(() => {
            items.value = assistants_services.data.value
        })
	}

    onMounted(async () => {
        await fetchData()
        items.value.map((item) => {
            return {
                ...item,
                actions: 'Acciones',
            }
        })
    })
</script>

<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Asistentes</h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="btn btn-primary shadow-md mr-2" @click="create">Crear un asistente</button>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5">
        <BaseCrud :headers="headers" :items="items" label="el Asistente" :on-delete-fnc="deleteNac" />
    </div>
</template>
