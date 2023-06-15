<script setup lang="ts">
import { RouteLocationRaw, RouterLinkProps } from 'vue-router';

const router = useRouter()

const props = defineProps<{
    to: RouteLocationRaw
}>()
</script>

<template>
    <router-link :to="props.to" class="btn btn-primary shadow-md mr-2" v-bind="$attrs">
        <slot></slot>

    </router-link>
</template>