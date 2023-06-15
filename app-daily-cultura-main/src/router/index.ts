import {
	NavigationGuardNext,
	RouteLocationNormalized,
	createRouter,
	createWebHistory,
} from "vue-router";
import { useAccessStore } from "@/stores/access";
import { useOnboardingStore } from "@/stores/onboarding";
import mixins from "@/mixins";
import alerts from "@/utils/alerts";
import { doubleManagement } from "@/types/doubleManagement";

const storagePath = import.meta.env.VITE_BASE_URL;

const routes = [
	{
		path: "/",
		name: "/",
		beforeEnter: (
			_to: RouteLocationNormalized,
			_from: RouteLocationNormalized,
			next: NavigationGuardNext
		) => {
			next({ name: "Login" });
		},
	},
	{
		path: "/",
		component: () => import("@/layouts/top-menu/Main.vue"),
		meta: { requiresAuth: true },
		children: [
			{
				path: "",
				name: "dashboard",
				component: () => import("@/views/Index.vue"),
			},
			{
				path: "groups",
				children: [
					{
						path: "",
						name: "groups.index",
						component: () =>
							import("@/views/monitors/groups/GroupView.vue"),
					},
					{
						path: "create",
						name: "groups.create",
						component: () =>
							import("@/views/monitors/groups/GroupNew.vue"),
					},
					{
						path: ":id",
						name: "groups.edit",
						component: () =>
							import("@/views/monitors/groups/GroupNew.vue"),
					},
				],
			},
			{
				path: "inscriptions",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("monitor_cultural") ||
						is_role("instructor") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("secretaria_cultural") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						if (is_role("apoyo_seguimiento_monitoreo")) {
							if (to.path.includes("create")) {
								alerts.inaccesible();
								next({ name: from.name });
							} else {
								next();
							}
						} else {
							alerts.inaccesible();
							next({ name: from.name });
						}
					}
				},
				children: [
					{
						path: "",
						name: "inscriptions.index",
						component: () =>
							import("@/views/monitors/inscriptions/Index.vue"),
					},
					{
						path: "create",
						name: "inscriptions.create",
						component: () =>
							import("@/views/monitors/inscriptions/Form.vue"),
					},
					{
						path: ":id",
						name: "inscriptions.edit",
						component: () =>
							import("@/views/monitors/inscriptions/Form.vue"),
					},
				],
			},
			{
				path: "binnacles",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("monitor_cultural") ||
						is_role("instructor") ||
						is_role("embajador") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("secretaria_cultural") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						if (
							is_role("apoyo_seguimiento_monitoreo") ||
							is_role("gestores_culturales") ||
							is_role("lider_instructor") ||
							is_role("lider_embajador")
						) {
							if (to.path.includes("create")) {
								alerts.inaccesible();
								next({ name: from.name });
							} else {
								next();
							}
						} else {
							alerts.inaccesible();
							next({ name: from.name });
						}
					}
				},
				children: [
					{
						path: "",
						name: "binnacles.index",
						meta: {
							doubleManagement: {
								first: "apoyo_seguimiento_monitoreo",
								second: "gestores_culturales",
							} as doubleManagement,
						},
						component: () =>
							import(
								"@/views/monitors/binnacles/BinnacleView.vue"
							),
					},
					{
						path: "create",
						name: "binnacles.create",
						component: () =>
							import(
								"@/views/monitors/binnacles/BinnacleNew.vue"
							),
					},
					{
						path: ":id",
						name: "binnacles.edit",
						component: () =>
							import(
								"@/views/monitors/binnacles/BinnacleNew.vue"
							),
					},
				],
			},
			{
				path: "pedagogicals",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("monitor_cultural") ||
						is_role("instructor") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("secretaria_cultural") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						if (
							is_role("gestores_culturales") ||
							is_role("lider_instructor")
						) {
							if (to.path.includes("create")) {
								alerts.inaccesible();
								next({ name: from.name });
							} else {
								next();
							}
						} else {
							alerts.inaccesible();
							next({ name: from.name });
						}
					}
				},
				children: [
					{
						path: "",
						name: "pedagogicals.index",
						component: () =>
							import(
								"@/views/monitors/pedagogicals/PedagogicalsView.vue"
							),
					},
					{
						path: "create",
						name: "pedagogicals.create",
						component: () =>
							import(
								"@/views/monitors/pedagogicals/PedagogicalsForm.vue"
							),
					},
					{
						path: ":id",
						name: "pedagogicals.edit",
						component: () =>
							import(
								"@/views/monitors/pedagogicals/PedagogicalsForm.vue"
							),
					},
				],
			},
			{
				path: "polls",
				beforeEnter: (
					_to: RouteLocationNormalized,
					_from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					next();
				},
				children: [
					{
						path: "",
						name: "polls.index",
						component: () =>
							import(
								"@/views/monitors/characterization/Characterization.vue"
							),
					},
					{
						path: "create",
						name: "polls.create",
						component: () =>
							import(
								"@/views/monitors/characterization/CharacterizationNew.vue"
							),
					},
					{
						path: ":id",
						name: "polls.edit",
						component: () =>
							import(
								"@/views/monitors/characterization/CharacterizationEdit.vue"
							),
					},
				],
			},
			{
				path: "pollsDesertions",
				beforeEnter: (
					_to: RouteLocationNormalized,
					_from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					next();
				},
				children: [
					{
						path: "",
						name: "pollDesertions.index",
						component: () =>
							import(
								"@/views/monitors/characterization/PollDesertions.vue"
							),
					},
					{
						path: "create",
						name: "pollDesertions.create",
						component: () =>
							import(
								"@/views/monitors/characterization/PollDesertionsForm.vue"
							),
					},
					{
						path: ":id",
						name: "pollDesertions.edit",
						component: () =>
							import(
								"@/views/monitors/characterization/PollDesertionsForm.vue"
							),
					},
				],
			},
			{
				path: "pecs",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { hasPermission } = usePermissions();

					if (hasPermission(to.name)) {
						next();
					} else {
						alerts.inaccesible();
						next({ name: from.name });
					}
				},
				children: [
					{
						path: "",
						name: "pecs.index",
						component: () =>
							import("@/views/monitors/pec/PecView.vue"),
					},
					{
						path: "create",
						name: "pecs.create",
						component: () =>
							import("@/views/monitors/pec/PecNew.vue"),
					},
					{
						path: ":id",
						name: "pecs.edit",
						component: () =>
							import("@/views/monitors/pec/PecNew.vue"),
					},
				],
			},
			{
				path: "culturalEnsembles",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { hasPermission } = usePermissions();

					if (hasPermission(to.name)) {
						next();
					} else {
						alerts.inaccesible();
						next({ name: from.name });
					}
				},
				children: [
					{
						path: "",
						name: "culturalEnsembles.index",
						meta: {
							doubleManagement: {
								first: "apoyo_seguimiento_monitoreo",
								second: "lider_instructor",
							} as doubleManagement,
						},
						component: () =>
							import("@/views/culturalEnsembles/index.vue"),
					},
					{
						path: "create",
						name: "culturalEnsembles.create",
						component: () =>
							import("@/views/culturalEnsembles/create.vue"),
					},
					{
						path: ":id",
						name: "culturalEnsembles.edit",
						component: () =>
							import("@/views/culturalEnsembles/create.vue"),
					},
				],
			},
			{
				path: "culturalCirculations",
				children: [
					{
						path: "",
						name: "culturalCirculations.index",
						meta: {
							doubleManagement: {
								first: "apoyo_seguimiento_monitoreo",
								second: "lider_instructor",
							} as doubleManagement,
						},
						component: () =>
							import(
								"@/views/monitors/culturalCirculations/culturalCirculationView.vue"
							),
					},
					{
						path: "",
						name: "culturalCirculations.create",
						component: () =>
							import(
								"@/views/monitors/culturalCirculations/culturalCirculationNew.vue"
							),
					},

					{
						path: ":id",
						name: "culturalCirculations.edit",
						component: () =>
							import(
								"@/views/monitors/culturalCirculations/culturalCirculationNew.vue"
							),
					},
				],
			},
			{
				path: "culturalSeedbeds",
				children: [
					{
						path: "",
						name: "culturalSeedbeds.index",
						meta: {
							doubleManagement: {
								first: "apoyo_seguimiento_monitoreo",
								second: "lider_instructor",
							} as doubleManagement,
						},
						component: () =>
							import("@/views/culturalSeedbeds/Index.vue"),
					},
					{
						path: "create",
						name: "culturalSeedbeds.create",
						component: () =>
							import("@/views/culturalSeedbeds/Form.vue"),
					},
					{
						path: ":id",
						name: "culturalSeedbeds.edit",
						component: () =>
							import("@/views/culturalSeedbeds/Form.vue"),
					},
				],
			},
			{
				path: "binnacleculturalshow",
				children: [
					{
						path: "",
						name: "binnacleculturalshow.index",
						meta: {
							doubleManagement: {
								first: "apoyo_seguimiento_monitoreo",
								second: "lider_embajador",
							} as doubleManagement,
						},
						component: () =>
							import(
								"@/views/monitors/binnacles/showCultural/BinnacleShowCulturalTable.vue"
							),
					},
					{
						path: "create",
						name: "binnacleculturalshow.create",
						component: () =>
							import(
								"@/views/monitors/binnacles/showCultural/BinnacleShowCulturalForm.vue"
							),
					},
					{
						path: ":id",
						name: "binnacleculturalshow.edit",
						component: () =>
							import(
								"@/views/monitors/binnacles/showCultural/BinnacleShowCulturalForm.vue"
							),
					},
				],
			},
			// GESTORS SECTION
			{
				path: "gestors",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("gestores_culturales") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("apoyo_seguimiento_monitoreo") ||
						is_role("secretaria_cultural") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						if (is_role("apoyo_metodologico")) {
							if (to.path.includes("create")) {
								// alerts.inaccesible()
								next({ name: from.name });
							} else {
								next();
							}
						} else {
							alerts.inaccesible();
							next({ name: from.name });
						}
					}
				},
				children: [
					{
						path: "dialoguetables",
						children: [
							{
								path: "",
								name: "dialoguetables.index",
								component: () =>
									import(
										"@/views/gestors/dialoguetables/DialogueTablesView.vue"
									),
							},
							{
								path: "create",
								name: "dialoguetables.create",
								component: () =>
									import(
										"@/views/gestors/dialoguetables/DialogueTablesNew.vue"
									),
							},
							{
								path: ":id",
								name: "dialoguetables.edit",
								component: () =>
									import(
										"@/views/gestors/dialoguetables/DialogueTablesNew.vue"
									),
							},
						],
					},
					{
						path: "methodologicalInstructions",
						children: [
							{
								path: "",
								name: "methodologicalInstructions.index",
								component: () =>
									import(
										"@/views/gestors/methodological/MethodologicalView.vue"
									),
							},
							{
								path: "create",
								name: "methodologicalInstructions.create",
								component: () =>
									import(
										"@/views/gestors/methodological/MethodologicalNew.vue"
									),
							},
							{
								path: ":id",
								name: "methodologicalInstructions.edit",
								component: () =>
									import(
										"@/views/gestors/methodological/MethodologicalNew.vue"
									),
							},
						],
					},
					{
						path: "managermonitorings",
						children: [
							{
								path: "",
								name: "managermonitorings.index",
								component: () =>
									import(
										"@/views/gestors/manager-monitoring/ManagerMonitoringView.vue"
									),
							},
							{
								path: "create",
								name: "managermonitorings.create",
								component: () =>
									import(
										"@/views/gestors/manager-monitoring/ManagerMonitoringNew.vue"
									),
							},
							{
								path: ":id",
								name: "managermonitorings.edit",
								component: () =>
									import(
										"@/views/gestors/manager-monitoring/ManagerMonitoringNew.vue"
									),
							},
						],
					},
					{
						path: "binnacles",
						children: [
							{
								path: "",
								name: "binnacleManagers.index",
								meta: {
									doubleManagement: {
										first: "apoyo_seguimiento_monitoreo",
										second: "apoyo_metodologico",
										three: "coordinador_supervision",
									} as doubleManagement,
								},
								component: () =>
									import(
										"@/views/gestors/binnacles/BinnacleView.vue"
									),
							},
							{
								path: "create",
								name: "binnacleManagers.create",
								component: () =>
									import(
										"@/views/gestors/binnacles/BinnacleNew.vue"
									),
							},
							{
								path: ":id",
								name: "binnacleManagers.edit",
								component: () =>
									import(
										"@/views/gestors/binnacles/BinnacleNew.vue"
									),
							},
						],
					},
				],
			},
			{
				path: "psychosocial",
				beforeEnter: (
					to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("psicosocial") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("secretaria_cultural") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						if (is_role("coordinador_psicosocial")) {
							if (to.path.includes("create")) {
								alerts.inaccesible();
								next({ name: from.name });
							} else {
								next();
							}
						} else {
							alerts.inaccesible();
							next();
						}
					}
				},
				children: [
					{
						path: "parentschools",
						children: [
							{
								path: "",
								name: "parentschools.index",
								component: () =>
									import(
										"@/views/psychosocial/parentschool/Index.vue"
									),
							},
							{
								path: "create",
								name: "parentschools.create",
								component: () =>
									import(
										"@/views/psychosocial/parentschool/Form.vue"
									),
							},
							{
								path: ":id",
								name: "parentschools.edit",
								component: () =>
									import(
										"@/views/psychosocial/parentschool/Form.vue"
									),
							},
						],
					},
					{
						path: "psychosocialinstructions",
						children: [
							{
								path: "",
								name: "psychosocialinstructions.index",
								component: () =>
									import(
										"@/views/psychosocial/psychosocialinstruction/Index.vue"
									),
							},
							{
								path: "create",
								name: "psychosocialinstructions.create",
								component: () =>
									import(
										"@/views/psychosocial/psychosocialinstruction/Form.vue"
									),
							},
							{
								path: ":id",
								name: "psychosocialinstructions.edit",
								component: () =>
									import(
										"@/views/psychosocial/psychosocialinstruction/Form.vue"
									),
							},
						],
					},
					{
						path: "psychopedagogicallogs",
						children: [
							{
								path: "",
								name: "psychopedagogicallogs.index",
								component: () =>
									import(
										"@/views/psychosocial/psychopedagogicalog/PsychopedagogicalogView.vue"
									),
							},
							{
								path: "create",
								name: "psychopedagogicallogs.create",
								component: () =>
									import(
										"@/views/psychosocial/psychopedagogicalog/PsychopedagogicalogNew.vue"
									),
							},
							{
								path: ":id",
								name: "psychopedagogicallogs.edit",
								component: () =>
									import(
										"@/views/psychosocial/psychopedagogicalog/PsychopedagogicalogNew.vue"
									),
							},
						],
					},
				],
			},
			{
				path: "users",
				beforeEnter: (
					_to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (is_admin()) {
						next();
					} else {
						alerts.inaccesible();
						next({ name: from.name });
					}
				},
				children: [
					{
						path: "",
						name: "users.index",
						component: () => import("@/views/users/list/Index.vue"),
					},
					{
						path: "create",
						name: "users.create",
						component: () => import("@/views/users/list/Form.vue"),
					},
					{
						path: ":id",
						name: "users.edit",
						component: () =>
							import("@/views/users/list/FormProfile.vue"),
					},
					{
						path: "changePassword/:id",
						name: "users.changePassword",
						component: () =>
							import("@/views/users/list/FormChangePassword.vue"),
					},
				],
			},
			{
				path: "reports",
				beforeEnter: (
					_to: RouteLocationNormalized,
					from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role, is_admin } = mixins.computed;

					if (
						is_role("secretaria_cultural") ||
						is_admin() ||
						is_role("direccion") ||
						is_role("coordinador_supervision") ||
						is_role("apoyo_supervision")
					) {
						next();
					} else {
						alerts.inaccesible();
						next({ name: from.name });
					}
				},
				children: [
					{
						path: "",
						name: "reports.index",
						component: () => import("@/views/reports/Index.vue"),
					},
					{
						path: "download",
						name: "reports.download",
						component: () => import("@/views/reports/Download.vue"),
					},
					{
						path: "loginAccess",
						name: "reports.loginaccess.index",
						component: () =>
							import("@/views/reports/accesslogin/Index.vue"),
					},
					{
						path: "parentschools",
						name: "reports.parentschools.index",
						component: () =>
							import("@/views/reports/parentschools/Index.vue"),
					},
				],
			},
			{
				path: "supervisorsSupport",
				children: [
					{
						path: "supervisionReports",
						name: "supervision_reports.index",
						component: () => import("@/views/200.vue"),
					},
					{
						path: "phoneReports",
						name: "phone_reports.index",
						component: () => import("@/views/200.vue"),
					},
					{
						path: "supervisionProducts",
						name: "supervision_products.index",
						component: () => import("@/views/200.vue"),
					},
				],
			},
			{
				path: "supervisors",
				children: [
					{
						path: "binnacleTerritories",
						children: [
							{
								path: "",
								name: "binnacle_territories.index",
								component: () =>
									import(
										"@/views/supervisors/binnacle_territories.vue"
									),
							},
							{
								path: "create",
								name: "binnacle_territories.create",
								component: () =>
									import(
										"@/views/supervisors/binnacle_territories_form.vue"
									),
							},
							{
								path: ":id",
								name: "binnacle_territories.edit",
								component: () =>
									import(
										"@/views/supervisors/binnacle_territories_form.vue"
									),
							},
						],
					},
					{
						path: "reportsTerritories",
						children: [
							{
								path: "",
								name: "reportsTerritories.index",
								component: () =>
									import(
										"@/views/subdirector/reporteTerritorial/ReportTerritorial.vue"
									),
							},
							{
								path: "create",
								name: "reportsTerritories.create",
								component: () =>
									import(
										"@/views/supervisors/binnacle_territories_form.vue"
									),
							},
							{
								path: ":id",
								name: "reportsTerritories.edit",
								component: () =>
									import(
										"@/views/supervisors/binnacle_territories_form.vue"
									),
							},
						],
					},
					{
						path: "monthly_monitoring_reports",
						children: [
							{
								path: "",
								name: "monthly_monitoring_reports.index",
								component: () =>
									import(
										"@/views/supervisors/monthly_monitoring_reports.vue"
									),
							},
							{
								path: "create",
								name: "monthly_monitoring_reports.create",
								component: () =>
									import(
										"@/views/supervisors/monthly_monitoring_reports_form.vue"
									),
							},
							{
								path: ":id",
								name: "monthly_monitoring_reports.edit",
								component: () =>
									import(
										"@/views/supervisors/monthly_monitoring_reports_form.vue"
									),
							},
						],
					},
				],
			},
			{
				path: "reportsTerritories/coordinadores",
				children: [
					{
						path: "",
						name: "reportTerritoryCoordinators.index",
						component: () =>
							import(
								"@/views/subdirector/reporteTerritorial/ReportTerritorialCoordinador.vue"
							),
					},
					{
						path: ":id",
						name: "reportTerritoryCoordinators.edit",
						component: () =>
							import("@/views/supervisors/binnacle_territories_form.vue"),
						},
				],
			},
			/* 
		ROL INSTRUCTOR
	  */
			// SHEET ONE
			{
				path: "methodologicalsheetsone",
				name: "methodologicalsheetsone.index",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetsone/MethodologicalSheetsOne.vue"
					),
			},
			{
				path: "methodologicalsheetsone/create",
				name: "methodologicalsheetsone.create",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetsone/MethodologicalSheetsOneForm.vue"
					),
			},
			{
				path: "methodologicalsheetsone/:id",
				name: "methodologicalsheetsone.edit",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetsone/MethodologicalSheetsOneForm.vue"
					),
			},
			// SHEET TWO
			{
				path: "methodologicalsheetstwo",
				name: "methodologicalsheetstwo.index",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetstwo/MethodologicalSheetsTwo.vue"
					),
			},
			{
				path: "methodologicalsheetstwo/create",
				name: "methodologicalsheetstwo.create",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetstwo/MethodologicalSheetsTwoForm.vue"
					),
			},
			{
				path: "methodologicalsheetstwo/:id",
				name: "methodologicalsheetstwo.edit",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetstwo/MethodologicalSheetsTwoForm.vue"
					),
			},
			// SUBDIRECCION
			{
				path: "reportsTerritories",
				name: "reportsTerritories.index",
				component: () =>
					import(
						"@/views/subdirector/reporteTerritorial/ReportTerritorial.vue"
					),
			},
			{
				path: "reportsTerritories/:id",
				name: "reportsTerritories.edit",
				component: () =>
					import("@/views/supervisors/binnacle_territories_form.vue"),
			},
			{
				path: "strengtheningSuperMonIns",
				children: [
					{
						path: "",
						name: "strengtheningSuperMonIns.index",
						component: () =>
							import(
								"../views/supervisors/strengthening_supervision_monitors_instructors/List.vue"
							),
					},
					{
						path: "create",
						name: "strengtheningSuperMonIns.create",
						component: () =>
							import(
								"../views/supervisors/strengthening_supervision_monitors_instructors/Form.vue"
							),
					},
					{
						path: ":id",
						name: "strengtheningSuperMonIns.edit",
						component: () =>
							import(
								"../views/supervisors/strengthening_supervision_monitors_instructors/Form.vue"
							),
					},
				],
			},
			{
				path: "strengtheningSupervisionMans",
				children: [
					{
						path: "",
						name: "strengtheningSupervisionMans.index",
						component: () =>
							import(
								"../views/supervisors/strengthening_supervision_manager/List.vue"
							),
					},
					{
						path: "create",
						name: "strengtheningSupervisionMans.create",
						component: () =>
							import(
								"@/views/supervisors/strengthening_supervision_manager/Form.vue"
							),
					},
					{
						path: ":id",
						name: "strengtheningSupervisionMans.edit",
						component: () =>
							import(
								"@/views/supervisors/strengthening_supervision_manager/Form.vue"
							),
					},
				],
			},
			{
				path: "supervisoryReports",
				children: [
					{
						path: "",
						name: "supervisoryReports.index",
						component: () =>
							import(
								"@/views/supervisors/monthly_monitoring_reports.vue"
							),
					},
					{
						path: "create",
						name: "supervisoryReports.create",
						component: () =>
							import(
								"@/views/supervisors/monthly_monitoring_reports_form.vue"
							),
					},
					{
						path: ":id",
						name: "supervisoryReports.edit",
						component: () =>
							import(
								"@/views/supervisors/monthly_monitoring_reports_form.vue"
							),
					},
				],
			},
			// Rutas para los coordinadores (SEGUIMIENTO, METODOLOGICO, ADMINISTRATIVO, PSICOSOCIAL)
			{
				path: "coordinadores",
				name: "coordinadores.index",
				component: () =>
					import("@/views/supervisors/binnacle_territories.vue"),
			},
			{
				path: "coordinadores",
				name: "coordinadores.create",
				component: () =>
					import("@/views/supervisors/binnacle_territories_form.vue"),
			},
			{
				path: "coordinadores/:id",
				name: "coordinadores.edit",
				component: () =>
					import("@/views/supervisors/binnacle_territories_form.vue"),
			},
			{
				path: "strengtheningSupervisionMonitorsInstructors",
				name: "strengtheningSupervisionMonitorsInstructors.index",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetstwo/MethodologicalSheetsTwoForm.vue"
					),
			},
			{
				path: "strengtheningSupervisionManagers",
				name: "strengtheningSupervisionManagers.index",
				component: () =>
					import(
						"@/views/instructors/methodologicalsheetstwo/MethodologicalSheetsTwoForm.vue"
					),
			},
			{
				path: "settings",
				beforeEnter: (
					_to: RouteLocationNormalized,
					_from: RouteLocationNormalized,
					next: NavigationGuardNext
				) => {
					const { is_role } = mixins.computed;

					// if (is_role('root')) {
					// 	alerts.inaccesible()
					// 	next({ name: 'dashboard' })
					// }
					if (is_role("super.root") || is_role("root")) {
						next();
					} else {
						alerts.inaccesible();
						next({ name: "dashboard" });
					}
				},
				children: [
					{
						path: "culturalRights",
						children: [
							{
								path: "",
								name: "cultural-rights.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/CulturalRightView.vue"
									),
							},
							{
								path: "create",
								name: "cultural-rights.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/CulturalRightNew.vue"
									),
							},
							{
								path: ":id",
								name: "cultural-rights.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/CulturalRightNew.vue"
									),
							},
						],
					},
					{
						path: "alerts",
						children: [
							{
								path: "",
								name: "alerts.index",
								component: () =>
									import("@/views/settings/alerts/Index.vue"),
							},
							{
								path: "create",
								name: "alerts.create",
								component: () =>
									import("@/views/settings/alerts/Form.vue"),
							},
							// {
							// 	path: ":id",
							// 	name: "alerts.edit",
							// 	component: () =>
							// 		import(
							// 			"@/views/settings/dropDownList/EntityNameNew.vue"
							// 		),
							// },
						],
					},
					{
						path: "entities",
						children: [
							{
								path: "",
								name: "entities.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/EntityNameView.vue"
									),
							},
							{
								path: "create",
								name: "entities.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/EntityNameNew.vue"
									),
							},
							{
								path: ":id",
								name: "entities.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/EntityNameNew.vue"
									),
							},
						],
					},
					{
						path: "expertises",
						children: [
							{
								path: "",
								name: "expertises.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/ExpertiseView.vue"
									),
							},
							{
								path: "create",
								name: "expertises.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/ExpertiseNew.vue"
									),
							},
							{
								path: ":id",
								name: "expertises.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/ExpertiseNew.vue"
									),
							},
						],
					},
					{
						path: "neighborhoods",
						children: [
							{
								path: "",
								name: "neighborhoods.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/NeighBorhoodView.vue"
									),
							},
							{
								path: "create",
								name: "neighborhoods.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/NeighBorhoodNew.vue"
									),
							},
							{
								path: ":id",
								name: "neighborhoods.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/NeighBorhoodNew.vue"
									),
							},
						],
					},
					{
						path: "orientations",
						children: [
							{
								path: "",
								name: "orientations.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/OrientationView.vue"
									),
							},
							{
								path: "create",
								name: "orientations.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/OrientationNew.vue"
									),
							},
							{
								path: ":id",
								name: "orientations.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/OrientationNew.vue"
									),
							},
						],
					},
					{
						path: "nacs",
						children: [
							{
								path: "",
								name: "nacs.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/NacView.vue"
									),
							},
							{
								path: "create",
								name: "nacs.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/NacNew.vue"
									),
							},
							{
								path: ":id",
								name: "nacs.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/NacNew.vue"
									),
							},
						],
					},
					{
						path: "assistants",
						children: [
							{
								path: "",
								name: "assistants.index",
								component: () =>
									import(
										"@/views/settings/dropDownList/AsistantView.vue"
									),
							},
							{
								path: "create",
								name: "assistants.create",
								component: () =>
									import(
										"@/views/settings/dropDownList/AsistantNew.vue"
									),
							},
							{
								path: ":id",
								name: "assistants.edit",
								component: () =>
									import(
										"@/views/settings/dropDownList/AsistantNew.vue"
									),
							},
						],
					},
					{
						path: "modules",
						children: [
							{
								path: "modules",
								name: "modules.index",
								component: () =>
									import(
										"@/views/settings/modules/ModuleView.vue"
									),
							},
							{
								path: "modules/create",
								name: "modules.create",
								component: () =>
									import(
										"@/views/settings/modules/ModuleNew.vue"
									),
							},
							{
								path: "modules/:id",
								name: "modules.edit",
								component: () =>
									import(
										"@/views/settings/modules/ModuleNew.vue"
									),
							},
						],
					},
					{
						path: "items",
						children: [
							{
								path: "",
								name: "items.index",
								component: () =>
									import(
										"@/views/settings/modules/ModuleItemView.vue"
									),
							},
							{
								path: "create",
								name: "items.create",
								component: () =>
									import(
										"@/views/settings/modules/ModuleItemNew.vue"
									),
							},
							{
								path: ":id",
								name: "items.edit",
								component: () =>
									import(
										"@/views/settings/modules/ModuleItemNew.vue"
									),
							},
						],
					},
					{
						path: "permissions",
						children: [
							{
								path: "",
								name: "permissions.index",
								component: () =>
									import(
										"@/views/settings/permissions/PermissionsView.vue"
									),
							},
							{
								path: "create",
								name: "permissions.create",
								component: () =>
									import(
										"@/views/settings/permissions/PermissionsNew.vue"
									),
							},
							{
								path: ":id",
								name: "permissions.edit",
								component: () =>
									import(
										"@/views/settings/permissions/PermissionsNew.vue"
									),
							},
						],
					},
					{
						path: "roles",
						children: [
							{
								path: "",
								name: "roles.index",
								component: () =>
									import(
										"@/views/settings/roles/RolesView.vue"
									),
							},
							{
								path: "create",
								name: "roles.create",
								component: () =>
									import(
										"@/views/settings/roles/RolesNew.vue"
									),
							},
							{
								path: ":id",
								name: "roles.edit",
								component: () =>
									import(
										"@/views/settings/roles/RolesNew.vue"
									),
							},
						],
					},
					{
						path: "changeDataModels",
						children: [
							{
								path: "",
								name: "changeDataModels.index",
								component: () =>
									import(
										"@/views/settings/control/index.vue"
									),
							},
						],
					},
					{
						path: "activityLogs",
						children: [
							{
								path: "",
								name: "activityLogs.index",
								component: () =>
									import(
										"@/views/settings/activityLog/index.vue"
									),
							},
						],
					},
				],
			},
			{
				path: "methodologicalMonitorings",
				children: [
					{
						path: "",
						name: "methodologicalMonitorings.index",
						component: () =>
							import(
								"@/views/methodologicalLeader/methodologicalMonitorings/Index.vue"
							),
					},
					{
						path: "create",
						name: "methodologicalMonitorings.create",
						component: () =>
							import(
								"@/views/methodologicalLeader/methodologicalMonitorings/Form.vue"
							),
					},
					{
						path: ":id",
						name: "methodologicalMonitorings.edit",
						component: () =>
							import(
								"@/views/methodologicalLeader/methodologicalMonitorings/Form.vue"
							),
					},
				],
			},
			{
				path: "strengtheningOfMonitorings",
				children: [
					{
						path: "",
						name: "strengtheningOfMonitorings.index",
						component: () =>
							import(
								"@/views/supervisors/strengthening_monitoring/List.vue"
							),
					},
					{
						path: "create",
						name: "strengtheningOfMonitorings.create",
						component: () =>
							import(
								"@/views/supervisors/strengthening_monitoring/Form.vue"
							),
					},
					{
						path: ":id",
						name: "strengtheningOfMonitorings.edit",
						component: () =>
							import(
								"@/views/supervisors/strengthening_monitoring/Form.vue"
							),
					},
				],
			},
			{
				path: "monitoringReports",
				children: [
					{
						path: "",
						name: "monitoringReports.index",
						component: () =>
							import(
								"@/views/supervisors/monitoring_report/List.vue"
							),
					},
					{
						path: "create",
						name: "monitoringReports.create",
						component: () =>
							import(
								"@/views/supervisors/monitoring_report/Form.vue"
							),
					},
					{
						path: ":id",
						name: "monitoringReports.edit",
						component: () =>
							import(
								"@/views/supervisors/monitoring_report/Form.vue"
							),
					},
				],
			},
			{
				path: "methodologicalAccompaniments",
				children: [
					{
						path: "",
						name: "methodologicalAccompaniments.index",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalAccompaniments/List.vue"
							),
					},
					{
						path: "create",
						name: "methodologicalAccompaniments.create",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalAccompaniments/Form.vue"
							),
					},
					{
						path: ":id",
						name: "methodologicalAccompaniments.edit",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalAccompaniments/Form.vue"
							),
					},
				],
			},
			{
				path: "methodologicalStrengthenings",
				children: [
					{
						path: "",
						name: "methodologicalStrengthenings.index",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalStrengthenings/List.vue"
							),
					},
					{
						path: "create",
						name: "methodologicalStrengthenings.create",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalStrengthenings/Form.vue"
							),
					},
					{
						path: ":id",
						name: "methodologicalStrengthenings.edit",
						component: () =>
							import(
								"@/views/methodological_support/methodologicalStrengthenings/Form.vue"
							),
					},
				],
			},
		],
	},
	{
		path: "/login",
		name: "Login",
		component: () => import("@/views/autenticacion/Login.vue"),
		beforeEnter: (
			to: RouteLocationNormalized,
			from: RouteLocationNormalized,
			next: NavigationGuardNext
		) => {
			const { isAuth } = useOnboardingStore();

			if (isAuth) {
				next({ name: "dashboard" });
			} else {
				next();
			}
		},
	},
	{
		path: "/403",
		name: "403",
		component: () => import("@/views/403.vue"),
	},
	{
		path: "/telescope",
		// redirect: `/${storagePath}/telescope`,
		component: () => import("@/views/autenticacion/Login.vue"),
		beforeEnter(to, from, next) {
			window.location.href = `${storagePath}/telescope`;
		},
	},
];

const router = createRouter({
	history: createWebHistory(),
	routes,
	scrollBehavior(to, from, savedPosition) {
		return savedPosition || { left: 0, top: 0 };
	},
});

router.beforeResolve(async (to, from, next) => {
	const { isAuth } = useOnboardingStore();
	const { is_admin: isAdmin, is_role: isRole } = mixins.computed;
	const { canUserAccess, getPoll } = useAccessStore();

	if (to.meta.requiresAuth && !isAuth) {
		next({ name: "Login" });
	} else {
		if (isAuth) {
			const canAccess = await canUserAccess(to.name);
			const hasPoll = await getPoll();
			const omitPollCreate = computed(() => {
				if (canAccess) {
					return true;
				} else {
					if (to.name === "polls.create") {
						return true;
					} else {
						return false;
					}
				}
			});

			if (omitPollCreate.value) {
				if (isAdmin() || isRole("secretaria_cultural")) {
					next();
				} else {
					if (!hasPoll) {
						if (
							to.name === "Login" ||
							to.name === "/" ||
							to.name === "dashboard"
						) {
							next();
						} else {
							if (!to.name.toString().includes("polls")) {
								alerts.custom(
									"Aviso",
									"Debes hacer la encuesta para poder acceder a tus módulos.",
									"warning"
								);
								next({ name: "polls.create" });
							} else {
								next();
							}
						}
					} else {
						next();
					}
				}
			} else {
				next();
			}
		} else {
			next();
		}
	}
});
export default router;
