<script setup lang="ts">
import get_status from "@/utils/get_status";

defineProps<{
	consecutive?: string;
	status?: string;
	reject_message?: string;
	files?: Array<{ href: string; text: string }>;
}>();
</script>

<template>
	<div
		class="grid grid-cols-1 justify-start divide-y divide-slate-300 space-y-2 md:space-y-0 md:space-x-4 md:divide-y-0 md:flex md:divide-x md:divide-slate-300"
	>
		<div class="flex flex-col items-start">
			<span class="uppercase font-bold"> Consecutivo </span>
			<p class="text-base text-gray-500 break-words">
				{{ consecutive }}
			</p>
		</div>
		<div
			class="flex flex-col items-start pt-2 md:pt-0 md:pl-4"
			v-if="status"
		>
			<span class="uppercase font-bold"> Estado </span>
			<p
				:class="
					status == 'REC'
						? 'text-danger'
						: status == 'APRO'
						? 'text-success'
						: 'text-gray-500'
				"
				class="text-base"
			>
				{{ get_status(status) }}
			</p>
		</div>
		<div
			v-if="files && status == 'APRO'"
			class="flex flex-col items-start pt-2 md:pt-0 md:pl-4"
		>
			<span class="uppercase font-bold"> PDF's </span>
			<div class="flex gap-3 justify-evenly">
				<Tippy
					v-for="file in files"
					variant="primary"
					:content="file.text"
				>
					<a
						:href="file.href"
						target="_blank"
						class="flex flex-col items-center p-2.5 bg-slate-200 fill-primary active:bg-primary active:fill-slate-200 rounded-lg transition"
					>
						<svg width="20" height="20" viewBox="0 0 256 256">
							<path
								d="M48 136a8 8 0 0 0 8-8V40h88v48a8 8 0 0 0 8 8h48v32a8 8 0 0 0 16 0V88a7.7 7.7 0 0 0-2.4-5.7l-55.9-56A8.1 8.1 0 0 0 152 24H56a16 16 0 0 0-16 16v88a8 8 0 0 0 8 8Zm112-84.7L188.7 80H160ZM64 160H48a8 8 0 0 0-8 8v48a8 8 0 0 0 16 0v-8h8a24 24 0 0 0 0-48Zm0 32h-8v-16h8a8 8 0 0 1 0 16Zm132-16v12h16a8 8 0 0 1 0 16h-16v12a8 8 0 0 1-16 0v-48a8 8 0 0 1 8-8h28a8 8 0 0 1 0 16Zm-68-16h-14a8 8 0 0 0-8 8v48a8 8 0 0 0 8 8h14a32 32 0 0 0 0-64Zm0 48h-6v-32h6a16 16 0 0 1 0 32Z"
							/>
						</svg>
					</a>
				</Tippy>
			</div>
		</div>
		<div
			class="flex flex-col items-start pt-2 md:pt-0 md:pl-4"
			v-if="reject_message && status == 'REC'"
		>
			<span class="uppercase font-bold"> Mensaje de Rechazo </span>
			<p
				class="w-full md:max-w-sm text-base text-left text-gray-500 break-words"
			>
				{{ reject_message }}
			</p>
		</div>
	</div>
</template>
