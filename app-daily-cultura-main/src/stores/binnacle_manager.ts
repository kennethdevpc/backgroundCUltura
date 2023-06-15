import { defineStore } from 'pinia'
import { required, checkMinMaxHours, minLength, alpha } from '../utils/validations'
// import { helpers } from '@vuelidate/validators'

// import { addMonths, compareAsc } from 'date-fns';

export const binnacleManagerStore = defineStore('binnacleManagerStore', {
    state: () => ({
        form: {
            activity_date: null,
            activity_development: null,
            type:'manager',
            activity_name: null,
            binnacle_id: 'AC',
            consecutive: '',
            cultural_right_id: '',
            development_activity_image: null,
            end_of_activity: null,
            evidence_participation_image: null,
            experiential_objective: null,
            expertise_id: '',
            explain_goals_met: null,
            final_hour: null,
            goals_met: null,
            lineament_id: '',
            nac_id: '',
            observations_activity: null,
            orientation_id: '',
            place: null,
            start_activity: null,
            start_time: null,
            aforo_file: '',
            // assistants: '',
            beneficiaries_capacity: '',
            // aggregates: {
            //     beneficiaries: []
            // }
        },
        form_rules: {
            consecutive: {},
            binnacle_id: { required },
            activity_name: {
                alpha,
                required,
            },
            activity_date: {
                required,
                // verify: helpers.withMessage('La fecha sale del rango disponible', (value, state) => {
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
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            place: { required, alpha },
            type: {},
            nac_id: { required },
            expertise_id: { required },
            cultural_right_id: { required },
            experiential_objective: { required },
            lineament_id: { required },
            orientation_id: { required },
            goals_met: { required },
            explain_goals_met: { required },
            start_activity: { required },
            activity_development: { required },
            end_of_activity: { required },
            observations_activity: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            // Aforo
            beneficiaries_capacity: { required },
            // assistants: {},
            aforo_file: { required },
            // aggregates: {
            //     beneficiaries: {}
            // },
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
                // aggregates: {
                //     beneficiaries: (
                //         state.form.beneficiaries_capacity == 'beneficiaries'
                //         && state.form.binnacle_id == 'TP'
                //     ) ? { required, minLength: minLength(1) } : {}
                // },
                // aforo_file: (
                //     state.form.beneficiaries_capacity == 'aforo' || state.form.binnacle_id == 'AC'
                // ) ? { required } : {},
                // assistants: (
                //     state.form.beneficiaries_capacity == 'aforo' || state.form.binnacle_id == 'AC'
                // ) ? { required } : {}
            }
        },
        get_transpiled_data() {
            const { aggregates, ...rest } = this.form
            return {
                ...rest,
                // beneficiary_id: aggregates.beneficiaries.map(beneficiary => beneficiary.id),
            }
        },
    },
    actions: {
        // push_aggregate_gestor(payload) {
        //     this.$patch(state => {
        //         state.form.aggregates.beneficiaries.push(payload)
        //     })
        // },
        // pop_aggregate_gestor(payload) {
        //     this.$patch(state => {
        //         const record = state.form.aggregates.beneficiaries.findIndex((item) => item.id === payload)
        //         state.form.aggregates.beneficiaries.splice(record, 1)
        //     })
        // },
    }
});
