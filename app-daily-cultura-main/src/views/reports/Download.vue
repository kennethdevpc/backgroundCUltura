<script setup lang="ts">
import reportServices from "@/services/report.services";

const route = useRoute();
const services = reportServices();

onMounted(async () => {
	const { format, type, ...restQuery } = route.query;

	switch (format) {
		case "excel":
			await services.exportExcel(type, restQuery).finally(() => {
				window.close();
			});
			break;
		case "pdf":
			await services.exportPdf(type, restQuery).finally(() => {
				window.close();
			});
			break;
			case "pdf":
		case "generator":
			await services.generateCut(type, restQuery).finally(() => {
				window.close();
			});
			break;
		case "zip":
			if(type == "binnacleImpacts") {
				await services.exportZipExcel(type, restQuery).finally(() => {
					window.close();
				});
			} else {
				await services.exportZIP(type, restQuery).finally(() => {
					window.close();
				});
			}
			break;
	}
});
</script>

<template>
	<div class="box mt-5 p-5">
		<div class="flex flex-col items-center gap-2">
			<svg
				class="animate-spin h-5 w-5 text-primary"
				xmlns="http://www.w3.org/2000/svg"
				fill="none"
				viewBox="0 0 24 24"
			>
				<circle
					class="opacity-25"
					cx="12"
					cy="12"
					r="10"
					stroke="currentColor"
					stroke-width="4"
				></circle>
				<path
					class="opacity-75"
					fill="currentColor"
					d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
				></path>
			</svg>
			<p class="text-gray-500">Tu archivo se esta exportando...</p>
		</div>
	</div>
</template>
