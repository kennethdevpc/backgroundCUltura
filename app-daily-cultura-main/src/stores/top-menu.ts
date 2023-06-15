import { defineStore } from "pinia"
import access_services from "@/services/access.service"
import { useAccessStore } from "./access"
import mixins from "@/mixins"
import module_mapper from "@/utils/module_mapper"
import modules_monitors from "@/utils/menu/modules_monitors"
import modules_managers from "@/utils/menu/modules_managers"
import modules_ambassador from "@/utils/menu/modules_ambassador"
import modules_ambassador_leaders from '@/utils/menu/modules_ambassador_leaders'
import modules_instructors from "@/utils/menu/modules_instructors"
import modules_instructor_leaders from "@/utils/menu/modules_instructor_leaders"
import modules_psychosocials from "@/utils/menu/modules_psychosocial"
import modules_support_psychosocials from "@/utils/menu/modules_psychosocial_coordinators"
import modules_culture_secretaries from "@/utils/menu/modules_culture_secretaries"
import modules_monitoring_and_tracking_supports from '@/utils/menu/modules_monitoring_and_tracking_supports'
import modules_coordinator_administrative from '@/utils/menu/modules_coordinator_administrative'
import modules_methodology_leaders from "@/utils/menu/modules_methodology_leaders"
import modules_tracking_coordinators from "@/utils/menu/modules_tracking_coordinators"
import modules_methodological_supports from "@/utils/menu/modules_methodological_supports"
import modules_methodology_coordinators from "@/utils/menu/modules_methodology_coordinators"
import modules_supervisory_supports from "@/utils/menu/modules_supervisory_supports"
import modules_supervisory_coordinator from "@/utils/menu/modules_supervisory_coordinators"
import modules_sub_directions from "@/utils/menu/modules_sub_directions"
import modules_directions from '@/utils/menu/modules_directions'
import modules_root from "@/utils/menu/modules_root"
import modules_super_root from "@/utils/menu/modules_super_root"

export interface module {
	icon: string
	items?: module[]
	name: string
	route?: string
}

interface permission {
	slug: string
}

export interface Menu {
	icon: string;
	title: string;
	pageName?: string;
	subMenu?: Menu[];
	ignore?: boolean;
}

export interface TopMenuState {
	menu: Array<Menu>
	modules: Array<module>
	permissions: Array<permission>
	loading: boolean
}

export const useTopMenuStore = defineStore("topMenu", {
	state: (): TopMenuState => ({
		menu: [],
		modules: [],
		permissions: [],
		loading: false,
	}),
	actions: {
		async get_menu() {
			this.loading = true
			const { is_role, is_admin } = mixins.computed
			const { getAccess: get_access, getPermissions: get_permissions, getCountDataForm, getPollOnly } = access_services
			let filter_modules: module[] = []

			const map_modules = (modules: module[]) => {
				const map = modules.filter((module) => module.name != 'Dashboard').map((module: module): Menu => ({
					icon: module.icon,
					pageName: module.name,
					title: module.name,
					subMenu: module.items.map((item: module): Menu => ({
						icon: item.icon,
						pageName: item.route,
						title: item.name
					}))
				}))

				return map
			}

			// if (is_role('monitor_cultural') || is_role('instructor')) {
			// 	let counts = reactive({
			// 		monitor: {
			// 			pecs: 0,
			// 			inscriptions: 0,
			// 			pedagogicals: 0,
			// 			binnacles: 0,
			// 			pollDesertions: 0
			// 		},
			// 		polls: 0
			// 	})

			// 	if (this.modules.length == 0) {
			// 		await get_access().then((response) => {
			// 			this.$patch((state: TopMenuState) => {
			// 				state.modules = response.data.items
			// 				console.log(state.modules)
			// 			})
			// 		})
			// 	}

			// 	await getCountDataForm().then((res) => {
			// 		Object.assign(counts, res.data.items)
			// 		console.log(res.data.items)
			// 	})

			// 	this.modules.forEach((module) => {
			// 		const { icon, name } = module

			// 		let new_module = {
			// 			icon,
			// 			name,
			// 			items: []
			// 		}

			// 		if (is_role('instructor')) {
			// 			if (new_module.name == 'Monitores') {
			// 				new_module.name = 'Instructores'
			// 			}
			// 		}

			// 		module.items.forEach((module_item) => {
			// 			const { route } = module_item

			// 			if (route == 'polls.index') {
			// 				new_module.items.push(module_item)
			// 			}
			// 			else {
			// 				if (counts.polls > 0) {
			// 					if (route == 'groups.index') {
			// 						new_module.items.push(module_item)
			// 					}
			// 					if (route == 'inscriptions.index') {
			// 						new_module.items.push(module_item)
			// 					}

			// 					if (counts.monitor.inscriptions > 0) {
			// 						if (route == 'pecs.index') {
			// 							new_module.items.push(module_item)
			// 						}
			// 					}
			// 					if (counts.monitor.pecs > 0) {
			// 						if (route == 'pedagogicals.index') {
			// 							new_module.items.push(module_item)
			// 						}
			// 					}
			// 					if (counts.monitor.pedagogicals > 0) {
			// 						if (route == 'binnacles.index') {
			// 							new_module.items.push(module_item)
			// 						}
			// 					}
			// 					if (counts.monitor.binnacles > 0) {
			// 						if (route == 'pollDesertions.index') {
			// 							new_module.items.push(module_item)
			// 						}
			// 					}
			// 				}
			// 			}
			// 		})

			// 		if (counts.polls > 0 && new_module.name == 'Monitores' || counts.polls > 0 && new_module.name == 'Instructores') {
			// 			filter_modules.push(new_module)
			// 		}
			// 		else {
			// 			if (new_module.name == 'Caracterización') {
			// 				filter_modules.push(new_module)
			// 			}
			// 		}
			// 	})

			// 	this.$patch((state: TopMenuState) => {
			// 		state.menu = map_modules(filter_modules)
			// 		state.loading = false
			// 	})
			// }
			// else {
			await get_access().then((response) => {
				this.$patch((state: menu_state) => {
					//console.info(response);
					state.modules = response.data.items
					//console.log('módulos', state.modules)
				})
			})

			await get_permissions().then((response) => {
				this.$patch((state: menu_state) => {
					state.permissions = response.data.items
				})
			})

			// if (useAccessStore().hasPoll || is_admin()) {
			this.modules.forEach((module) => {
				const { icon, name } = module
				let new_module = {
					icon,
					name,
					items: []
				}

				if (is_role('gestores_culturales')
					|| is_role('apoyo_seguimiento_monitoreo')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Revisiones Monitores'
					}
				}
				else if (is_role('lider_instructor')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Revisiones Instructores #2'
					}
					if (new_module.name == 'Instructores #1') {
						new_module.name = 'Revisiones Instructores #1'
					}
				}
				else if (is_role('lider_embajador')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Revisiones Embajadores #2'
					}
				}
				else if (is_role('apoyo_metodologico')) {
					if (new_module.name == 'Gestores') {
						new_module.name = 'Revisiones Gestores'
					}
				}
				else if (is_role('coordinador_psicosocial')) {
					if (new_module.name == 'Psicosocial') {
						new_module.name = 'Revisiones Psicosocial'
					}
					if (new_module.name == 'Coordinador de supervisión') {
						new_module.name = 'Coordinador de psicosocial'
					}
				}
				else if (is_role('instructor')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Instructores #2'
					}
				}
				else if (is_role('embajador')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Embajadores'
					}
				}
				else if (is_role('coordinador_seguimiento')) {
					if (new_module.name == 'Coordinación de seguimiento') {
						new_module.name = 'Coordinador de seguimiento'
					}
				}
				else if (is_role('coordinador_metodologico')) {
					if (new_module.name == 'Coordinación de seguimiento') {
						new_module.name = 'Coordinador metodológico'
					}
				}
				else if (is_role('coordinador_administrativo')) {
					if (new_module.name == 'Coordinación de seguimiento') {
						new_module.name = 'Coordinador administrativo'
					}
				}
				else if (is_role('coordinador_psicosocial')) {
					if (new_module.name == 'Coordinación de seguimiento') {
						new_module.name = 'Coordinador psicosocial'
					}
				} else if (is_role('direccion')) {
					if (new_module.name == 'Monitores') {
						new_module.name = 'Monitores';
					}
					if (new_module.name == 'Instructores #1') {
						new_module.name = 'Instructores';
					}
				}


				module.items.forEach((module_item) => {
					const { route } = module_item
					this.permissions.forEach((permission) => {
						const { slug } = permission
						if (route == slug) {
							if (is_role('direccion') && slug === 'binnacleculturalshow.index') {
								// Cuando es el rol direccion, no añadir el item (Bitácora show cultural) en el menu de Monitor
								return;
							}
							new_module.items.push(module_item)
						}
					})
				})

				filter_modules.push(new_module)
			});

			// Si es el rol direccion, añadir el menu Embajador con el item (Bitácora show cultural)
			if (is_role('direccion')) {
				filter_modules.push({
					name: 'Embajador',
					icon: 'UsersIcon',
					items: [{
						icon: 'MinusIcon',
						name: "Bitácora show cultural",
						route: "binnacleculturalshow.index"
					}]
				});
			}
			// }
			// else {
			// 	this.modules.forEach((module) => {
			// 		const { icon, name } = module

			// 		let new_module = {
			// 			icon,
			// 			name,
			// 			items: []
			// 		}

			// 		module.items.forEach((module_item) => {
			// 			const { route } = module_item

			// 			if (route == 'polls.index') {
			// 				new_module.items.push(module_item)
			// 			}
			// 		})
			// 		if (new_module.name == 'Caracterización') {
			// 			filter_modules.push(new_module)
			// 		}
			// 	})
			// }

			this.$patch((state: TopMenuState) => {
				state.menu = module_mapper(filter_modules)
				state.loading = false
			})
			// }
		},
		async computed_menu() {
			const { is_role } = mixins.computed
			const { hasPoll } = useAccessStore()
			const { getCountDataForm, getPermissions, getAccess } = access_services
			let filter_modules: module[] = []

			const pollModule = {
				name: "Encuesta",
				icon: "LayersIcon",
				route: "polls.index",
			}

			const modules_maker = (modules: module[]) => {
				if (hasPoll) {
					modules.forEach((module) => {
						const { icon, name } = module

						let new_module = {
							icon,
							name,
							items: []
						}

						module.items.forEach((module_item) => {
							const { route } = module_item
							this.permissions.forEach((permission) => {
								const { slug } = permission
								if (route == slug) {
									new_module.items.push(module_item)
								}
							})
						})

						filter_modules.push(new_module)
					})

					filter_modules.push(pollModule)
				}
				else {
					filter_modules.push(pollModule)
				}
			}
			const modules_maker_without_poll = (modules: module[]) => {
				modules.forEach((module) => {
					const { icon, name } = module

					let new_module = {
						icon,
						name,
						items: []
					}

					module.items.forEach((module_item) => {
						const { route } = module_item
						this.permissions.forEach((permission) => {
							const { slug } = permission
							if (route == slug) {
								new_module.items.push(module_item)
							}
						})
					})

					filter_modules.push(new_module)
				})
			}

			if (this.permissions.length < 1) {
				await getPermissions().then((response) => {
					this.$patch((state: TopMenuState) => {
						state.permissions = response.data.items
						//(state.permissions)
					})
				})
			}

			let counts = reactive({
				monitor: {
					pecs: 0,
					inscriptions: 0,
					pedagogicals: 0,
					binnacles: 0,
					pollDesertions: 0
				},
				polls: 0
			})

			if (is_role('monitor_cultural') || is_role('instructor')) {
				await getCountDataForm().then((response) => {
					Object.assign(counts, response.data.items)
					//console.log(response.data.items)
				})
			}

			if (is_role('monitor_cultural')) {
				if (hasPoll) {
					modules_monitors.forEach((module) => {
						const { icon, name } = module

						let new_module = {
							icon,
							name,
							items: []
						}

						module.items.forEach((module_item) => {
							const { route } = module_item

							if (hasPoll) {
								if (route == 'groups.index')
									new_module.items.push(module_item)
								if (route == 'inscriptions.index')
									new_module.items.push(module_item)

								if (counts.monitor.inscriptions > 0)
									route == 'pecs.index' && new_module.items.push(module_item)
								if (counts.monitor.pecs > 0)
									route == 'pedagogicals.index' && new_module.items.push(module_item)
								if (counts.monitor.pedagogicals > 0)
									route == 'binnacles.index' && new_module.items.push(module_item)
								if (counts.monitor.binnacles > 0)
									route == 'pollDesertions.index' && new_module.items.push(module_item)
							}
						})
						filter_modules.push(new_module)
					})
					filter_modules.push(pollModule)
				}
				else {
					filter_modules.push(pollModule)
				}
			}
			else if (is_role('gestores_culturales')) {
				modules_maker(modules_managers)
			}
			else if (is_role('embajador')) {
				modules_maker(modules_ambassador)
			}
			else if (is_role('lider_embajador')) {
				modules_maker(modules_ambassador_leaders)
			}
			else if (is_role('instructor')) {
				if (hasPoll) {
					modules_instructors.forEach((module) => {
						const { icon, name } = module

						let new_module = {
							icon,
							name,
							items: []
						}

						module.items.forEach((module_item) => {
							const { route } = module_item

							if (hasPoll) {
								if (counts.monitor.inscriptions > 0) {
									new_module.items.push(module_item)
								}
								else {
									if (route == 'groups.index')
										new_module.items.push(module_item)
									if (route == 'inscriptions.index')
										new_module.items.push(module_item)
								}
							}
						})
						filter_modules.push(new_module)
					})
					filter_modules.push(pollModule)
				}
				else {
					filter_modules.push(pollModule)
				}
			}
			else if (is_role('lider_instructor')) {
				modules_maker(modules_instructor_leaders)
			}
			else if (is_role('psicosocial')) {
				modules_maker(modules_psychosocials)
			}
			else if (is_role('coordinador_psicosocial')) {
				modules_maker(modules_support_psychosocials)
			}
			else if (is_role('secretaria_cultural')) {
				modules_maker_without_poll(modules_culture_secretaries)
			}
			else if (is_role('apoyo_seguimiento_monitoreo')) {
				modules_maker(modules_monitoring_and_tracking_supports)
			}
			else if (is_role('coordinador_administrativo')) {
				modules_maker(modules_coordinator_administrative)
			}
			else if (is_role('lider_metodologico')) {
				modules_maker(modules_methodology_leaders)
			}
			else if (is_role('coordinador_seguimiento')) {
				modules_maker(modules_tracking_coordinators)
			}
			else if (is_role('apoyo_metodologico')) {
				modules_maker(modules_methodological_supports)
			}
			else if (is_role('coordinador_metodologico')) {
				modules_maker(modules_methodology_coordinators)
			}
			else if (is_role('apoyo_supervision')) {
				modules_maker(modules_supervisory_supports)
			}
			else if (is_role('coordinador_supervision')) {
				modules_maker(modules_supervisory_coordinator)
			}
			else if (is_role('subdireccion')) {
				modules_maker(modules_sub_directions)
			}
			else if (is_role('direccion')) {
				modules_maker(modules_directions)
			}
			else if (is_role('root')) {
				modules_maker_without_poll(modules_root)
			}
			else if (is_role('super.root')) {
				modules_maker_without_poll(modules_super_root)
			}

			this.$patch((state: TopMenuState) => {
				state.menu = module_mapper(filter_modules)
				state.loading = false
			})
		},
		clear_menu() {
			this.$patch((state: TopMenuState) => {
				state.menu = []
				state.modules = []
			})
		}
	},
	getters: {
		get_menu_instance: (state) => {
			return state.menu
		},
	},
	persist: true,
});