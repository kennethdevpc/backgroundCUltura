import { defineStore } from "pinia";
import { required } from '../utils/validations'

export const usePermissions = defineStore("permissions", {
	state: () => ({
        form: {
            name: '',
            slug: '',
            description: '',
            controller: '',
        },
        form_rules: {
            name: { required },
            slug: { required },
            description: { required },
            controller: { required },
        },
        validator: {
            generalData: false,
        },
	}),
	getters: {
        isGeneralData: (state) => {
            return state.validator.generalData
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