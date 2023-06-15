import { defineStore } from "pinia";
import { required } from "@/utils/validations"

export const PollDesertions = defineStore("pollDesertions", {
    state: () => ({
        form: {
            beneficiary_id: '',
            consecutive: '',
            date: '',
            nac_id: '',
            beneficiary_attrition_factors: '',
            beneficiary_attrition_factor_other: '',
            disinterest_apathy: '',
            disinterest_apathy_explanation: '',
            reintegration: '',
            reintegration_explanation: '',
            //user_id: jso.id,
        },
        form_rules: {
            'beneficiary_id': { required },
            'date': { required },
            'consecutive': {},
            'nac_id': { required },
            'beneficiary_attrition_factors': { required },
            'beneficiary_attrition_factor_other': {},
            'disinterest_apathy': { required },
            'disinterest_apathy_explanation': { required },
            'reintegration': { required },
            'reintegration_explanation': { required },
        },
        form_options: {
            disinterest_apathy: [
                { value: 1, text: 'Si' },
                { value: 0, text: 'No' },
            ],
            reintegration: [
                { value: 1, text: 'Si' },
                { value: 0, text: 'No' },
            ],
        },
    }),
    getters: {
        get_form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                beneficiary_attrition_factor_other: (state.form.beneficiary_attrition_factors === 'OTRO') ? { required } : {}
            }
        }
    },
    actions: {
    }
})