import { defineStore } from "pinia";
import { required, checkMinMaxHours } from '../utils/validations'

export const useManagerMonitoring = defineStore("managermonitoring", {
	state: () => ({
        form: {
            consecutive: '',
            user_id: '',
            activity_date: '',
            start_time: '',
            final_hour: '',
            // tutorial_name: '',
            target_tracking: '',
            nac_id: '',
            cultural_process: '',
            cultural_guidelines: '',
            cultural_communication: '',
            difficulty_cultural_process: '',
            proposal_improvement: '',
        },
        form_rules: {
            consecutive: {},
            user_id: { required },
            activity_date: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour'),
            },
            // tutorial_name: { required },
            target_tracking: { required },
            nac_id: { required },
            cultural_process: { required },
            cultural_guidelines: { required },
            cultural_communication: { required },
            difficulty_cultural_process: { required },
            proposal_improvement: { required },
        },
        form_options: {
            cultural_process: [
                { value: '1', text: '1' },
                { value: '2', text: '2' },
                { value: '3', text: '3' },
                { value: '4', text: '4' },
                { value: '5', text: '5' },
            ],
            cultural_guidelines: [
                { value: '1', text: '1' },
                { value: '2', text: '2' },
                { value: '3', text: '3' },
                { value: '4', text: '4' },
                { value: '5', text: '5' },
            ],
            cultural_communication: [
                { value: '1', text: '1' },
                { value: '2', text: '2' },
                { value: '3', text: '3' },
                { value: '4', text: '4' },
                { value: '5', text: '5' },
            ],
        },
        validator: {
            generalData: true,
        },
	}),
	getters: {
        isValid: (state) => {
            if (state.validator.generalData)
                return true
            else
                return false
        },
        transpiledData() {
            return {
                ...this.form,
            }
        }
	},
	actions: {
        toggleGeneralData(boolean){
            this.$patch(state => {
                state.validator.generalData = boolean
            })
        },
	}
})