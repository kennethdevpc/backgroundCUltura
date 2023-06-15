<script setup lang="ts">
import userServices from '@/services/user.service'

const user_services = userServices()

const props = defineProps<{
    id: string | number
    actual_status: string | number
}>()

const emit = defineEmits(['toggle'])

const form = reactive({
    status: false
})

const handle_statuses = () => {
    switch (props.actual_status) {
        case '1':
            form.status = true
            break;
        case '0':
            form.status = false
            break;
    }
}

onBeforeMount(() => {
    handle_statuses()
})

onUpdated(() => {
    handle_statuses()
})

const computed_value = computed(() => {
    switch (form.status) {
        case true:
            return '0'
            break;
        case false:
            return '1'
            break;
    }
})

const on_change = async (event: Event) => {
    let payload = reactive({
        id: props.id,
        status: computed_value.value
    })

    const payload_raw = toRaw(payload)
    user_services.changeStatus(payload_raw).finally(() => {
        emit('toggle')
    })
}
</script>

<template>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" v-model="form.status" @input="on_change" class="sr-only peer">
        <div
            class="w-11 h-6 bg-secondary peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-success/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success">
        </div>
        <!-- <span class="ml-3 text-sm font-medium text-gray-900"></span> -->
    </label>
</template>