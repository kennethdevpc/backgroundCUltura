<script setup lang="ts">
import { useOnboardingStore } from "@/stores/onboarding"
import { useSelectStore } from "@/stores/selects"
import { storeToRefs } from "pinia"

const router = useRouter();
const useOnboarding = useOnboardingStore();
const useSelects = useSelectStore();

const { user, get_user_role } = storeToRefs(useOnboarding)

const fetchSelects = async () => {
	await useSelects.dataSelects(false);
};

onBeforeRouteUpdate(async (to, from, next) => {
	if (String(from.name).includes('create') || String(from.name).includes('edit')) {
		await fetchSelects()
		next()
	}
	else {
		next()
	}
})

onBeforeMount(async () => {
	await fetchSelects()
});

const searchDropdown = ref(false);
const showSearchDropdown = () => {
	searchDropdown.value = true;
};
const hideSearchDropdown = () => {
	searchDropdown.value = false;
};

const logout = async () => {
	const result = await useOnboarding.logout();
	if (result.status === 200) {
		router.push({
			name: "Login",
		})
	}
};
</script>


<template>
	<!-- BEGIN: Top Bar -->
	<div
		class="top-bar-boxed h-[70px] md:h-[65px] z-[51] border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-10 md:bg-gradient-to-b md:from-slate-100 md:to-transparent dark:md:from-darkmode-700">
		<div class="flex items-center h-full">
			<!-- BEGIN: Logo -->
			<a href="" class="logo -intro-x hidden md:flex xl:w-[180px] items-center relative">
				<img src="@/assets/img/logo-white.svg" class="w-8" alt="SSISET - Cultura">
				<span class="ml-2 text-base text-white logo__text">
					<b>SSISET - Cultura</b>
				</span>
			</a>
			<!-- END: Logo -->
			<!-- BEGIN: Breadcrumb -->
			<nav aria-label="breadcrumb" class="-intro-x h-[45px] mr-auto">
				<ol class="breadcrumb breadcrumb-light">
					<li class="breadcrumb-item"></li>
					<li class="breadcrumb-item" aria-current="page">
						<router-link :to="{ name: 'dashboard' }"> Dashboard </router-link>
					</li>
					<li v-if="$route.path.includes('settings')"
						:class="$route.path.includes('settings') ? 'active' : ''" class="breadcrumb-item"
						aria-current="page">
						Ajustes
					</li>
				</ol>
			</nav>
			<!-- END: Breadcrumb -->
			<!-- BEGIN: Account Menu -->
			<Dropdown class="w-8 h-8 intro-x">
				<DropdownToggle tag="div" role="button"
					class="w-8 h-8 overflow-hidden scale-110 rounded-full shadow-lg image-fit zoom-in">
					<div class="bg-secondary w-full h-full flex justify-center items-center">
						<UserIcon class="w-4 h-4" />
					</div>
				</DropdownToggle>
				<DropdownMenu class="w-56">
					<DropdownContent
						class="bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
						<DropdownHeader tag="div" class="!font-normal">
							<div v-if="user.profile" class="font-medium">
								{{ user.profile.contractor_full_name ?? user.name }}
							</div>
							<div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">
								{{ get_user_role.name ?? 'Sin rol'}}
							</div>
						</DropdownHeader>
						<DropdownDivider class="border-white/[0.08]" />
						<DropdownItem @click="logout" class="dropdown-item hover:bg-white/5">
							<ToggleRightIcon class="w-4 h-4 mr-2" />Salir
						</DropdownItem>
					</DropdownContent>
				</DropdownMenu>
			</Dropdown>
			<!-- END: Account Menu -->
		</div>
	</div>
	<!-- END: Top Bar -->
</template>