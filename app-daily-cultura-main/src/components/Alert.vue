<script setup lang="ts">
const props = defineProps<{
	alert: {
		type: "info" | "warn" | "critical" | "achievement";
		title: string;
		description: string;
	};
}>();

const { type, title, description } = toRefs(props.alert);
</script>

<template>
	<div
		:class="
			type === 'info'
				? 'bg-blue-50'
				: type === 'warn'
				? 'bg-yellow-50'
				: type === 'critical'
				? 'bg-red-50'
				: 'bg-green-50'
		"
		class="rounded-md p-4 snap-center"
	>
		<div class="flex">
			<div class="flex-shrink-0">
				<template v-if="type === 'info'">
					<svg
						class="w-5 h-5 text-blue-400"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 20 20"
					>
						<path
							fill="currentColor"
							fill-rule="evenodd"
							d="M18 10a8 8 0 1 1-16 0a8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
							clip-rule="evenodd"
						/>
					</svg>
				</template>
				<template v-else-if="type === 'critical'">
					<svg
						class="w-5 h-5 text-red-400"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 20 20"
					>
						<path
							fill="currentColor"
							fill-rule="evenodd"
							d="M10 18a8 8 0 1 0 0-16a8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94L8.28 7.22Z"
							clip-rule="evenodd"
						/>
					</svg>
				</template>
				<template v-else-if="type === 'warn'">
					<svg
						class="w-5 h-5 text-yellow-400"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 20 20"
					>
						<path
							fill="currentColor"
							fill-rule="evenodd"
							d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625l6.28-10.875ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2a1 1 0 0 0 0 2Z"
							clip-rule="evenodd"
						/>
					</svg>
				</template>
				<template v-else-if="type === 'achievement'">
					<svg
						class="w-5 h-5 text-green-400"
						xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 20 20"
					>
						<path
							fill="currentColor"
							fill-rule="evenodd"
							d="M10 18a8 8 0 1 0 0-16a8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79l-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
							clip-rule="evenodd"
						/>
					</svg>
				</template>
			</div>
			<div class="ml-3">
				<h3
					:class="
						type === 'info'
							? 'text-blue-800'
							: type === 'warn'
							? 'text-yellow-800'
							: type === 'critical'
							? 'text-red-800'
							: 'text-green-800'
					"
					class="text-sm font-medium"
				>
					{{ title }}
				</h3>
				<div
					:class="
						type === 'info'
							? 'text-blue-700'
							: type === 'warn'
							? 'text-yellow-700'
							: type === 'critical'
							? 'text-red-700'
							: 'text-green-700'
					"
					class="mt-2 text-sm"
				>
					<p>
						{{ description }}
					</p>
				</div>
			</div>
		</div>
	</div>
</template>
