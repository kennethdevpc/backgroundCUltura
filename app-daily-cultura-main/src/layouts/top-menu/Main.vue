<!-- <script setup lang="ts">
import { useTopMenuStore } from "@/stores/top-menu";
import TopBar from "@/components/top-bar/Main.vue";
import MobileMenu from "@/components/mobile-menu/Main.vue";
import DarkModeSwitcher from "@/components/dark-mode-switcher/Main.vue";
import MainColorSwitcher from "@/components/main-color-switcher/Main.vue";
import { linkTo } from "@/layouts/side-menu";
import dom from "@left4code/tw-starter/dist/js/dom";
import Loading from "@/components/base/Loading.vue";
import { storeToRefs } from "pinia";

const route = useRoute();
const router = useRouter();

const top_menu_store = useTopMenuStore();
const { get_menu_instance, loading: loading_menu } = storeToRefs(top_menu_store)
console.log("first")
onBeforeMount(async () => {
  await top_menu_store.get_menu()
})

router.beforeResolve(async (to, from, next) => {
  if (String(from.name).includes('create')) {
    await top_menu_store.get_menu()
    next()
  }
  else {
    next()
  }
})

onMounted(() => {
  dom("body").removeClass("error-page").removeClass("login").addClass("main");
});
</script>

<template>
  <div class="py-5 md:py-0">

    <Loading />
    <DarkModeSwitcher />
    <MainColorSwitcher />
    <MobileMenu />
    <TopBar class="top-bar-boxed--top-menu" />
    <nav class="top-nav">
      <div v-if="loading_menu" class="px-6 h-[55px] flex justify-center w-full">
        <loading-icon icon="three-dots" class="w-16" color="rgb(255 255 255)" />
      </div>
      <ul v-else>
        <li v-for="(menu, menuKey) in get_menu_instance" :key="menuKey">
          <a :href="menu.subMenu ? 'javascript:;' : router.resolve({ name: menu.pageName }).path" class="top-menu"
            :class="{ 'top-menu--active': menu.active }" @click="linkTo(menu, router, $event)">
            <div class="top-menu__icon">
              <component :is="menu.icon" />
            </div>
            <div class="top-menu__title">
              {{ menu.title }} 
              <ChevronDownIcon v-if="menu.subMenu" class="top-menu__sub-icon" />
            </div>
          </a>
          <ul v-if="menu.subMenu">
            <li v-for="(subMenu, subMenuKey) in menu.subMenu" :key="subMenuKey">
              <a :href="subMenu.subMenu ? 'javascript:;' : router.resolve({ name: subMenu.pageName }).path"
                class="top-menu" @click="linkTo(subMenu, router, $event)">
                <div class="top-menu__icon">
                  <ActivityIcon />
                </div>
                <div class="top-menu__title">
                  {{ subMenu.title }} 
                  <ChevronDownIcon v-if="subMenu.subMenu" class="top-menu__sub-icon" />
                </div>
              </a>
              <ul v-if="subMenu.subMenu">
                <li v-for="(lastSubMenu, lastSubMenuKey) in subMenu.subMenu" :key="lastSubMenuKey">
                  <a :href="lastSubMenu.subMenu ? 'javascript:;' : router.resolve({ name: lastSubMenu.pageName }).path"
                    class="top-menu" @click="linkTo(lastSubMenu, router, $event)">
                    <div class="top-menu__icon">
                      <component :is="'zap-icon'" />
                    </div>
                    <div class="top-menu__title">
                      {{ lastSubMenu.title }}
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>

    </nav>
    <div class="content content--top-nav">
      <router-view />
    </div>
  </div>
</template> -->
<script setup lang="ts">
import { useTopMenuStore } from "@/stores/top-menu";
import TopBar from "@/components/top-bar/Main.vue";
import MobileMenu from "@/components/mobile-menu/Main.vue";
import DarkModeSwitcher from "@/components/dark-mode-switcher/Main.vue";
import MainColorSwitcher from "@/components/main-color-switcher/Main.vue";
import { linkTo } from "@/layouts/side-menu";
import dom from "@left4code/tw-starter/dist/js/dom";
import Loading from "@/components/base/Loading.vue";
import { storeToRefs } from "pinia";
import Alert from "@/components/Alert.vue";
import useApi from "@/utils/useApi";

const { find } = useApi();

const route = useRoute();
const router = useRouter();

const { state: alerts, execute: refreshAlerts } = useAsyncState(
	() =>
		find<{ items: Array<any> }>("alerts").then(
			(response) => response.data.items
		),
	null,
	{ resetOnExecute: false }
);

router.beforeEach(() => {
	refreshAlerts();
});

const top_menu_store = useTopMenuStore();
const { get_menu_instance, loading: loading_menu } =
	storeToRefs(top_menu_store);

onBeforeMount(async () => {
	await top_menu_store.computed_menu();
});

router.beforeResolve(async (to, from, next) => {
	if (String(from.name).includes("create")) {
		await top_menu_store.computed_menu();
		next();
	} else {
		next();
	}
});

onMounted(() => {
	dom("body").removeClass("error-page").removeClass("login").addClass("main");
});
</script>

<template>
	<div class="py-5 md:py-0">
		<Loading />
		<DarkModeSwitcher />
		<MainColorSwitcher />
		<MobileMenu />
		<TopBar class="top-bar-boxed--top-menu" />
		<!-- BEGIN: Top Menu -->
		<nav class="top-nav">
			<!-- {{ top_menu_store.menu }} -->
			<div
				v-if="loading_menu"
				class="px-6 h-[55px] flex justify-center w-full"
			>
				<loading-icon
					icon="three-dots"
					class="w-16"
					color="rgb(255 255 255)"
				/>
			</div>
			<ul v-else>
				<li v-for="(menu, menuKey) in get_menu_instance" :key="menuKey">
					<a
						:href="
							menu.subMenu
								? 'javascript:;'
								: router.resolve({ name: menu.pageName }).path
						"
						class="top-menu"
						:class="{ 'top-menu--active': menu.active }"
						@click="linkTo(menu, router, $event)"
					>
						<div class="top-menu__icon">
							<component :is="menu.icon" />
						</div>
						<div class="top-menu__title">
							{{ menu.title }}
							<ChevronDownIcon
								v-if="menu.subMenu"
								class="top-menu__sub-icon"
							/>
						</div>
					</a>
					<!-- BEGIN: Second Child -->
					<ul v-if="menu.subMenu">
						<li
							v-for="(subMenu, subMenuKey) in menu.subMenu"
							:key="subMenuKey"
						>
							<a
								:href="
									subMenu.subMenu
										? 'javascript:;'
										: router.resolve({
												name: subMenu.pageName,
										  }).path
								"
								class="top-menu"
								@click="linkTo(subMenu, router, $event)"
							>
								<div class="top-menu__icon">
									<ActivityIcon />
								</div>
								<div class="top-menu__title">
									{{ subMenu.title }}
									<ChevronDownIcon
										v-if="subMenu.subMenu"
										class="top-menu__sub-icon"
									/>
								</div>
							</a>
							<!-- BEGIN: Third Child -->
							<ul v-if="subMenu.subMenu">
								<li
									v-for="(
										lastSubMenu, lastSubMenuKey
									) in subMenu.subMenu"
									:key="lastSubMenuKey"
								>
									<a
										:href="
											lastSubMenu.subMenu
												? 'javascript:;'
												: router.resolve({
														name: lastSubMenu.pageName,
												  }).path
										"
										class="top-menu"
										@click="
											linkTo(lastSubMenu, router, $event)
										"
									>
										<div class="top-menu__icon">
											<component :is="'zap-icon'" />
										</div>
										<div class="top-menu__title">
											{{ lastSubMenu.title }}
										</div>
									</a>
								</li>
							</ul>
							<!-- END: Third Child -->
						</li>
					</ul>
					<!-- END: Second Child -->
				</li>
			</ul>
		</nav>
		<!-- END: Top Menu -->
		<!-- BEGIN: Content -->
		<template v-if="alerts != null">
			<div class="px-12 mt-5 flex flex-col gap-y-2">
				<template v-for="alert in alerts">
					<Alert :alert="alert" />
				</template>
			</div>
		</template>
		<div class="content content--top-nav">
			<router-view />
		</div>
		<!-- END: Content -->
	</div>
</template>
