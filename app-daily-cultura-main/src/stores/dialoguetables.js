import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength } from "../utils/validations";

export const useDialogueTables = defineStore("dialoguetables", {
	state: () => ({
		form: {
			consecutive: "",
			nac_id: "",
			activity_date: "",
			start_time: "",
			final_hour: "",
			target_workday: "",
			theme: "",
			schedule_day: "",
			workday_description: "",
			achievements_difficulties: "",
			alerts: "",
			place_image1: {},
			place_image2: {},
			assistants: [],
		},
		form_rules: {
			consecutive: {},
			nac_id: { required },
			activity_date: { required },
			start_time: {
				required,
				checkMinMaxHours: checkMinMaxHours("start_time", "final_hour"),
			},
			final_hour: {
				required,
				checkMinMaxHours: checkMinMaxHours("start_time", "final_hour"),
			},
			target_workday: { required },
			theme: { required },
			schedule_day: { required },
			workday_description: { required },
			achievements_difficulties: { required },
			alerts: { required },
			place_image1: { required },
			place_image2: { required },
			assistants: { required, minLength: minLength(1) },
		},
	}),
	getters: {
		transpiled_data() {
			const { assistants, ...rest } = this.form

			return {
				...rest,
				asistents: assistants
			}
		}
	},
	actions: {
		push_aggregate(payload) {
			this.$patch(state => {
				state.form.assistants.push(payload)
			})
		},
		pop_aggregate(payload) {
			this.$patch(state => {
				const record = state.form.assistants.findIndex((item) => item.id === payload)
				state.form.assistants.splice(record, 1)
			})
		},
	},
});
