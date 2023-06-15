import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength, checkMinMaxMonths } from '../utils/validations'
export const useMethodologicalSheetsTwo = defineStore("methodologicalSheetsTwo", {
    state: () => ({
        form: {
            activity_type: '',
            date_ini: '',
            date_fin: '',
            keyactors_participating_community: '',
            objective_process: '',
            reached_target: '',
            sustein: '',
            // participants_number: '',
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: '',
            number_attendees: '',
            group_id: '',
            beneficiaries: [],
            beneficiaries_or_capacity: ''

        },
        form_rules: {
            activity_type: { required },
            date_ini: {
                required,
                checkMinMaxMonths: checkMinMaxMonths('date_ini', 'date_fin')
            },
            date_fin: {
                required,
                checkMinMaxMonths: checkMinMaxMonths('date_ini', 'date_fin')
            },
            keyactors_participating_community: { required },
            objective_process: { required },
            reached_target: { required },
            sustein: { required },
            //    participants_number: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            aforo_pdf: {},
            number_attendees: {},
            group_id: {},
            beneficiaries: {},
            beneficiaries_or_capacity: {}
        },
        form_options: {
            reached_target: [
                { value: 1, text: 'Si' },
                { value: 0, text: 'No' },
            ],
        },
        update_instance: {
            consecutive: '',
            status: '',
            reject_message: ''
        },
        filesOne: {
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: null
        }
    }),
    getters: {
        get_form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                aforo_pdf: (state.form.activity_type === 'C' || state.form.activity_type === 'R') ? { required } : {},
                number_attendees: (state.form.activity_type === 'C' || state.form.activity_type === 'R') ? { required } : {},
            }
        },
    },
    actions: {
        clear() {
            this.$state.form.activity_type = '';
            this.$state.form.date_ini = '';
            this.$state.form.date_fin = '';
            this.$state.form.keyactors_participating_community = '';
            this.$state.form.objective_process = '';
            this.$state.form.reached_target = '';
            this.$state.form.sustein = '';
            // this.$state.form.participants_number = '';
            this.$state.form.development_activity_image = null;
            this.$state.form.evidence_participation_image = null;
            this.$state.form.aforo_pdf = null;
            this.$state.form.number_attendees = '';
            this.$state.form.beneficiaries = [];
        },
        push_aggregate(payload) {
            this.$patch(state => {
                state.form.beneficiaries.push(payload)
            })
        },
        pop_aggregate(payload) {
            this.$patch(state => {
                const record = state.form.beneficiaries.findIndex((item) => item.id === payload)
                state.form.beneficiaries.splice(record, 1)
            })
        },
    }
})