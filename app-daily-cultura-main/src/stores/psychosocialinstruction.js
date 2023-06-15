import { defineStore } from "pinia";
import { requiredUnless } from "@vuelidate/validators"
import { required, checkMinMaxHours, minLength } from '@/utils/validations'

export const usePsychosocialInstruction = defineStore("psychosocialinstruction", {
    state: () => ({
        form: {
            consecutive: '',
            activity_date: '',
            nac_id: '',
            start_time: '',
            final_hour: '',
            objective_day: '',
            themes_day: '',
            development_activity_image: null,
            evidence_participation_image: null,
            development_themes: '',
            conclusions_reflections_commitments : '',
            report_followup_alerts: '',
            aggregates: {
                assistants: [],
                monitors: [],
            },
        },
        form_rules: {
            consecutive: { required },
            activity_date: { required },
            nac_id: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            objective_day: { required },
            themes_day: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            development_themes: { required },
            conclusions_reflections_commitments : { required },
            report_followup_alerts: { required },
            aggregates: {
                assistants: {},
                monitors: {}
            }
        },
    }),
    getters: {
        get_monitors_id: (state) => {
            return state.form.aggregates.monitors.map((item) => item.id)
        },
        get_computed_rules: (state) => {
            return {
                ...state.form_rules,
                aggregates: {
                    assistants: {
                        requiredIf: requiredUnless(() => {
                            return (state.form.aggregates.monitors.length > 0)
                        })
                    },
                    monitors: {
                        requiredIf: requiredUnless(() => {
                            return (state.form.aggregates.assistants.length > 0)
                        })
                    }
                }
            }
        },
        transpiled_data() {
            const { aggregates, ...rest } = this.form
            return {
                ...rest,
                added_wizards: aggregates.assistants,
                assistance_monitors: this.get_monitors_id,
            }
        }
    },
    actions: {
        push_aggregate(payload, type) {
            this.$patch(state => {
                if (type === 'a')
                    state.form.aggregates.assistants.push(payload)
                if (type === 'm')
                    state.form.aggregates.monitors.push(payload)
            })
        },
        pop_aggregate(payload, type) {
            this.$patch(state => {
                if (type === 'a') {
                    const record = state.form.aggregates.assistants.findIndex((item) => item.id === payload)
                    state.form.aggregates.assistants.splice(record, 1)
                }
                if (type === 'm') {
                    const record = state.form.aggregates.monitors.findIndex((item) => item.id === payload)
                    state.form.aggregates.monitors.splice(record, 1)
                }
            })
        },
    }
})
