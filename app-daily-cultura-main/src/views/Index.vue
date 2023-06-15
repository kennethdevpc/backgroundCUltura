<script setup lang="ts">
import dayjs from 'dayjs'
import useNotificationServices from '@/services/notification.service';

const { getByAuthenticated: getNotifications, markAsRead, trash } = useNotificationServices();

const { state: notifications, execute: notificationsRefresh } = useAsyncState(async () => await getNotifications().then((response) => response.data), null, {
    resetOnExecute: false
})
</script>

<template>
    <!-- BEGIN: Page Layout -->
    <div class="intro-y box px-6 py-12 sm:px-8 sm:py-16 mt-8">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <div class="flex justify-center">
                    <img src="@/assets/img/government_banner.webp" class="w-64" alt="GOBERNACIÓN VALLE DEL CAUCA">
                </div>
                <h2 class="text-lg font-semibold text-primary mt-4">RECREAVALLE</h2>
                <div class="flex justify-center">
                </div>
                <p class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl lg:text-4xl">
                    SSISET - CULTURA
                </p>
            </div>
            <!-- <div class="relative mt-5">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300" />
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-lg font-medium text-gray-900">
                        Notificaciones
                    </span>
                </div>
            </div> -->
            <!-- <div class="flow-root">
                <template v-if="notifications != null">
                    <div>
                        <ul role="list" class="divide-y divide-gray-200">
                            <li v-for="notification in notifications" :key="notification.id" class="py-4 group">
                                <div class="flex space-x-3 relative">
                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium">{{ notification.sender.name }}</h3>
                                            <p class="text-sm text-gray-500">{{
                                                dayjs(notification.created_at).format('HH:mm')
                                            }}</p>
                                        </div>
                                        <p class="text-sm text-gray-500">
                                            {{ notification.description }}
                                        </p>
                                    </div>
                                    <div
                                        class="absolute bottom-0 right-0 flex bg-white h-full items-center opacity-0 group-hover:opacity-100 transition-opacity gap-2 mb-1.5">
                                        <button :disabled="notification.read === 1" type="button" @click="async () => {
                                            await markAsRead(notification.id)
                                            notificationsRefresh()
                                        }" title="Leído"
                                            class="bg-secondary flex justify-center items-center rounded-full w-8 h-8 disabled:cursor-default">
                                            <ListChecksIcon class="w-4 h-4" />
                                        </button>
                                        <button type="button" @click="async () => {
                                            await trash(notification.id)
                                            notificationsRefresh()
                                        }" title="Borrar"
                                            class="bg-danger flex justify-center items-center rounded-full w-8 h-8 disabled:cursor-default">
                                            <TrashIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </template>
            </div> -->
        </div>
    </div>
    <!-- END: Page Layout -->
</template>
