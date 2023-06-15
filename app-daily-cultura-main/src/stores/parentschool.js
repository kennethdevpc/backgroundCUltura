import { defineStore } from "pinia";
import { requiredUnless } from '@vuelidate/validators'
import { required, checkMinMaxHours, minLength } from '../utils/validations'

export const useParentSchoolStore = defineStore("parentschool", {
    state: () => ({
        form: {
            consecutive: '',
            date: '',
            start_time: '',
            final_time: '',
            place_attention: '',
            contact: '',
            monitor_id: '',
            objective: '',
            development: '',
            conclusions: '',
            development_activity_image: null,
            evidence_participation_image: null,
            aggregates: {
                assistants: [],
                monitors: [],
            },
        },
        form_rules: {
            consecutive: { required },
            date: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_time'),
            },
            final_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_time'),
            },
            place_attention: { required },
            contact: { required },
            monitor_id: { required },
            objective: { required },
            development: { required },
            conclusions: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            aggregates: {
                assistants: {},
                monitors: {}
            }
        },
    }),
    getters: {
        get_monitors_id: (state) => {
            return state.form.aggregates.monitors.map((item) => {
                return item.id
            })
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
});