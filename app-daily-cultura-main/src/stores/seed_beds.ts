import { defineStore } from "pinia";
import { required, minLength, minValue } from "@/utils/validations";

const FORM_STRUCTURE = {
	date: "",
	last_status: "",
	status: "",
	consecutive: "",
	pec_id: "",
	datasheet_planning_id: "",
	filter_level: "",
	quantity_members: "",
	level_domain_description: "",
	objective_process: "",
	cultural_right_id: "",
	lineament_id: "",
	orientation_id: "",
	values: "",
	artistic_expertise: "",
	observations: "",
	development_activity_image: null,
	evidence_participation_image: null,
	group_id: "",
	aggregates: {
		beneficiaries: [],
	},
	datasheet: null,
};

export const seedBeds = defineStore("seed_beds", {
	state: () => ({
		form: Object.assign({}, FORM_STRUCTURE),
		form_rules: {
			date: { required },
			pec_id: { required },
			datasheet_planning_id: { required },
			filter_level: { required },
			quantity_members: { required, minValue: minValue(0) },
			level_domain_description: { required },
			objective_process: { required },
			cultural_right_id: { required },
			lineament_id: { required },
			orientation_id: { required },
			values: { required },
			artistic_expertise: { required },
			observations: { required },
			development_activity_image: { required },
			evidence_participation_image: { required },
			group_id: { required },
			aggregates: {
				beneficiaries: { required, minLength: minLength(1) },
			},
			datasheet: { required },
		},
		validator: {
			generalData: true,
		},
		filesOne: {
			development_activity_image: null,
			evidence_participation_image: null,
		},
	}),
	actions: {
		push_aggregate(payload) {
			this.$patch((state) => {
				state.form.aggregates.beneficiaries.push(payload);
			});
		},
		pop_aggregate(payload) {
			this.$patch((state) => {
				const record = state.form.aggregates.beneficiaries.findIndex(
					(item) => item.id === payload
				);
				state.form.aggregates.beneficiaries.splice(record, 1);
			});
		},
		toggleGeneralData(boolean) {
			this.$patch((state) => {
				state.validator.generalData = boolean;
			});
		},
		async clear() {
			Object.assign(this.form, FORM_STRUCTURE);
		},
	},

	getters: {
		isValid: (state) => {
			if (state.validator.generalData) return true;
			else return false;
		},
		transpiledData() {
			return {
				...this.form,
			};
		},
	},
});
