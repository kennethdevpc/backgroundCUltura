<script setup lang="ts">
import State from "./StateHeader.vue";
import BackButton from "./BaseBackButton.vue";

defineProps<{
	state?: {
		consecutive: string;
		status: string;
		reject_message: string;
		files?: any;
	};
}>();
</script>

<template>
	<div
		class="intro-y flex flex-col space-y-2 justify-between items-start md:flex md:flex-row md:space-y-0 md:items-start mt-5"
	>
		<div class="intro-y flex flex-col items-start gap-1">
			<BackButton />
			<h2 class="text-lg font-medium mr-auto">
				<slot />
			</h2>
		</div>
		<State v-if="state" v-bind="{ ...state }" />
	</div>
</template>
