import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength, alphaOrAlphanumeric } from '../utils/validations'

export const usePEC = defineStore("pec", {
	state: () => ({
        form: {
            consecutive: '',
            nac_id: '',
            neighborhood_id: '',
            other_neighborhoods: '',
            place: '',
            place_address: '',
            activity_date: '',
            start_time: '',
            final_hour: '',
            place_type: '',
            other_place_type: '',
            place_description: '',
            place_image1: null,
            place_image2: null,
            aggregates: {
                beneficiaries: []
            },
            group_id:''
        },
        form_rules: {
            consecutive: {},
            nac_id: { required },
            neighborhood_id: { required },
            place: { required, alphaOrAlphanumeric },
            place_address: { required },
            activity_date: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            place_type: { required },
            place_description: { required },
            place_image1: { required },
            place_image2: { required },
            aggregates: {
                beneficiaries: { required, minLength: minLength(1) }
            },
            group_id:{}
        },
	}),
	getters: {
        get_beneficiaries_id: (state) => {
            return state.form.aggregates.beneficiaries.map((item) => item.id)
        },
        form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                other_neighborhoods: (state.form.neighborhood_id === '357') ? { required } : {},
                place_image1: (state.form.place_image1 === false) ? {} : { required },
                place_image2: (state.form.place_image2 === false) ? {} : { required },
                other_place_type: (state.form.other_place_type === 'O') ? { required } : {},
            }
        },
        transpiled_data() {
            const { aggregates, ...rest } = this.form
            return {
                ...rest,
                beneficiary_list: this.get_beneficiaries_id
            }
        }
	},
	actions: {
        push_aggregate(payload){
            this.$patch(state => {
                state.form.aggregates.beneficiaries.push(payload)
            })
        },
        pop_aggregate(payload){
            this.$patch(state => {
                const record = state.form.aggregates.beneficiaries.findIndex((item) => item.id === payload)
                state.form.aggregates.beneficiaries.splice(record, 1)
            })
        },
	}
})