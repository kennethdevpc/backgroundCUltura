import { defineStore } from "pinia";
import { required, helpers, minLength } from "@vuelidate/validators";

export const useProfile = defineStore("profile", {
	state: () => ({
		form: {
			user_id: "",
			contractor_full_name: "",
			nac_id: "",
			document_number: "",
			email: "",
			role_id: null,
			gestor_id: null,
			psychosocial_id: null,
			methodological_support_id: null,
			support_tracing_monitoring_id: null,
			ambassador_leader_id: null,
			instructor_leader_id: null,
		},
	}),
	getters: {
		form_rules(state) {
			return {
				user_id: {},
				contractor_full_name: { required },
				nac_id: { required },
				email: { required },
				document_number: {
					required: helpers.withMessage(
						"Se requiere el número de identificación",
						required
					),
					minLength: helpers.withMessage(
						"Se requiere minimo 6 caracteres",
						minLength(6)
					),
				},
				role_id: { required },
				gestor_id: {},
				// Gestor
				psychosocial_id:
					state.form.role_id === "gestores_culturales"
						? { required }
						: {},
				methodological_support_id:
					state.form.role_id === "gestores_culturales"
						? { required }
						: {},
				// Monitor - Embajador - Instructor
				ambassador_leader_id:
					state.form.role_id === "embajador" ? { required } : {},
				instructor_leader_id:
					state.form.role_id === "instructor" ? { required } : {},
				support_tracing_monitoring_id: {},
			};
		},
	},
	actions: {},
});
