import { defineStore } from "pinia";
import { required } from '../utils/validations'

export const useDropDowns = defineStore("dropdowns", {
	state: () => ({
        form: {
            name: '',

        },
        form_rules: {
            name: { required },
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