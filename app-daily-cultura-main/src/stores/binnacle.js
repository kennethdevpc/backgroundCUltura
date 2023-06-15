import { defineStore } from 'pinia'
import { required, checkMinMaxHours, minLength, alphaOrAlphanumeric, alpha } from '../utils/validations'
import { addMonths, compareAsc } from 'date-fns';
import mixins from '@/mixins';

export const binnacleStore = defineStore('binnacleStore', {
    state: () => ({
        form: {
            consecutive: '',
            binnacle_id: mixins.computed.is_role('monitor_cultural')?'JP':'', // NOTE: This requires an initial value
            type: 'other',
            activity_name: null,
            activity_date: null,
            start_time: null,
            final_hour: null,
            place: null,
            nac_id: '', // NOTE: This requires an initial value
            expertise_id: '', // NOTE: This requires an initial value
            cultural_right_id: '', // NOTE: This requires an initial value
            experiential_objective: null,
            lineament_id: '',
            orientation_id: '', // NOTE: This requires an initial value
            goals_met: null,
            explain_goals_met: null,
            start_activity: null,
            activity_development: null,
            end_of_activity: null,
            observations_activity: null,
            development_activity_image: null,
            evidence_participation_image: null,
            // beneficiary_id: [],
            pedagogical_id: '',
            pec_id: '',
            // Aforo
            beneficiaries_capacity: '',
            assistants: '',
            aforo_file: '',
            aggregates: {
                beneficiaries: []
            },
            group_id:'',
            beneficiaries_or_capacity:''
        },
        form_rules: {
            // First Part
            consecutive: {},
            type: {},
            binnacle_id: { required }, //si
            activity_name: { //si
                alpha,
                required,
                alphaOrAlphanumeric,
            },
            activity_date: { //si
                required
                //, verify: helpers.withMessage('La fecha sale del rango disponible', (value, state) => {
                //     const date = momnent(value).toDate();
                //     const [year, month] = momnent().format("YYYY-MM-DD").split("-");
                //     const initDate = momnent(`${year}-${month}-11`).toDate();
                //     const lastDate = momnent(addMonths(momnent(`${year}-${month}-10`).toDate(), 1)).toDate();

                //     if (compareAsc(date, lastDate) === 1) {
                //         return false;
                //     }
                //     if (compareAsc(date, initDate) === -1) {
                //         return false;
                //     }

                //     return true;
                // }),
            },
            start_time: { //si
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: { //si
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            place: { required, alpha },//si
            nac_id: { required }, //si
            // Second Part
            // activation_mode: { required },
            expertise_id: { required },
            cultural_right_id: { required },
            experiential_objective: { required },
            lineament_id: { required },
            orientation_id: { required },
            // Third Part
            goals_met: { required },
            explain_goals_met: { required },
            start_activity: { required },
            activity_development: { required },
            end_of_activity: { required },
            observations_activity: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            // Fourth Part
            // beneficiary_id: {},
            beneficiariesTable: {},
            pedagogical_id: { required },
            pec_id: { required },
            // Aforo
            beneficiaries_capacity: {},
            // assistants: {},
            aforo_file: {},
            aggregates: {
                beneficiaries: {}
            },
            group_id:{},
            beneficiaries_or_capacity:{}
        },
        form_options: {
            activation_mode: [
                { value: 'presencial', text: 'Presencial' },
                { value: 'virtual', text: 'Virtual' },
            ],
            goals_met: [
                { value: 'si', text: 'Si' },
                { value: 'no', text: 'No' },
            ],
        },
    }),
    getters: {
        get_form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                aforo_file: (state.form.beneficiaries_or_capacity === 'aforo') ? { required } : {},
                beneficiaries_capacity: (state.form.beneficiaries_or_capacity === 'aforo') ? { required } : {},
                pec_id: (!mixins.computed.is_role('embajador')) ? { required } : {},
                pedagogical_id: (!mixins.computed.is_role('embajador')) ? { required } : {},
                aggregates: {
                    beneficiaries: (!mixins.computed.is_role('embajador') && state.form.beneficiaries_or_capacity === 'beneficiaries') ? { required, minLength: minLength(1) } : {}
                }
                // beneficiary_id: (state.form.beneficiaries_capacity === 'beneficiaries') ? { required } : {},
            }
        },
        get_transpiled_data() {

            const { aggregates, ...rest } = this.form
            return {
                ...rest,
                beneficiary_id: aggregates.beneficiaries.map(beneficiary => beneficiary.id),
            }
        },
    },
    actions: {
        push_aggregate(payload) {
            this.$patch(state => {
                state.form.aggregates.beneficiaries.push(payload)
            })
        },
        pop_aggregate(payload) {
            this.$patch(state => {
                const record = state.form.aggregates.beneficiaries.findIndex((item) => item.id === payload)
                state.form.aggregates.beneficiaries.splice(record, 1)
            })
        },
    }
});
