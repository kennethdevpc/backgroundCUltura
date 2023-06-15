import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength } from '../utils/validations'
export const useMethodological = defineStore("methodological", {
    state: () => ({
        form: {
            consecutive: '',
            place: '',
            activity_date: '',
            start_time: '',
            final_hour: '',
            expertise_id: '',
            nac_id: '',
            goals_met: '',
            explanation: '',
            pedagogical_comments: '',
            technical_practical_comments: '',
            methodological_comments: '',
            others_observations: '',
            place_file1: null,
            place_file2: null,
            aggregates: {
                assistants: []
            }
        },
        form_rules: {
            consecutive: {},
            place: { required },
            activity_date: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            expertise_id: { required },
            nac_id: { required },
            goals_met: { required },
            explanation: { required },
            pedagogical_comments: { required },
            technical_practical_comments: { required },
            methodological_comments: { required },
            others_observations: { required },
            place_file1: { required },
            place_file2: { required },
            aggregates: {
                assistants: { required, minLength: minLength(1) }
            }
        },
        form_options: {
            goals_met: [
                { value: 'si', text: 'Si' },
                { value: 'no', text: 'No' },
            ],
        },
    }),
    getters: {
        getAssistants: (state) => {
            return state.form.aggregates.assistants;
        },
        transpiled_data(state) {
            return {
                ...this.form,
                assistants_id: JSON.stringify(state.form.aggregates.assistants.map((assistant) => assistant.id))
            }
        }
    },
    actions: {
        push_aggregate(payload) {
            this.$patch(state => {
                state.form.aggregates.assistants.push(payload);
            })
        },
        pop_aggregate(payload) {
            this.$patch(state => {
                const record = state.form.aggregates.assistants.findIndex((item) => item.id === payload);
                state.form.aggregates.assistants.splice(record, 1);
            })
        },
    }
})