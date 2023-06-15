import { defineStore } from "pinia";
import { helpers } from "@vuelidate/validators";
import { required, minLength } from "../utils/validations";

export const useUser = defineStore("user", {
	state: () => ({
		form: {
			email: "",
			password: "",
			password_confirm: "",
			user_id: "",
			contractor_full_name: "",
			nac_id: "",
			document_number: "",
			role_id: "",
			gestor_id: "",
			psychosocial_id: "",
			methodological_support_id: "",
			support_tracing_monitoring_id: "",
			ambassador_leader_id: "",
			instructor_leader_id: "",
		},
		form_change_password: {
			id: "",
			password: "",
			password_confirmation: "",
		},
		form_change_password_rules: {
			id: {},
			password: {
				required: helpers.withMessage(
					"Se requiere la nueva contraseña",
					required
				),
				minLength: helpers.withMessage(
					"Se requiere minimo 6 caracteres",
					minLength(6)
				),
			},
			password_confirmation: {
				required: helpers.withMessage(
					"Se requiere confirmar contraseña",
					required
				),
				minLength: helpers.withMessage(
					"Se requiere minimo 6 caracteres",
					minLength(6)
				),
				sameAs: helpers.withMessage(
					"Las contraseñas deben ser iguales",
					(value, state) => {
						return state.password === value;
					}
				),
			},
		},
		form_rules: {
			email: { required },
			password: {
				required: helpers.withMessage(
					"Se requiere la nueva contraseña",
					required
				),
				minLength: helpers.withMessage(
					"Se requiere minimo 6 caracteres",
					minLength(6)
				),
			},
			password_confirm: {
				required: helpers.withMessage(
					"Se requiere la nueva contraseña",
					required
				),
				minLength: helpers.withMessage(
					"Se requiere minimo 6 caracteres",
					minLength(6)
				),
				sameAs: helpers.withMessage(
					"Las contraseñas deben ser iguales",
					(value, state) => {
						return state.password === value;
					}
				),
			},
			user_id: {},
			contractor_full_name: {
				required: helpers.withMessage(
					"Se requiere el nombre completo",
					required
				),
			},
			nac_id: {
				required: helpers.withMessage("Se requiere el nac", required),
			},
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
			role_id: {
				required: helpers.withMessage(
					"Se requiere seleccionar un rol",
					required
				),
			},
			gestor_id: {},
			psychosocial_id: {},
			methodological_support_id: {},
			support_tracing_monitoring_id: {},
			ambassador_leader_id: {},
			instructor_leader_id: {},
		},
		validator: {},
	}),
	getters: {},
	actions: {
		clearInputs() {
			this.$reset();
		},
		fillData(data) {
			this.$state.form.name = data?.name;
			this.$state.form.email = data?.email;
		},
	},
});
