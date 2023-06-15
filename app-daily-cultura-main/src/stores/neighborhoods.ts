import { defineStore } from 'pinia'
import { required, checkMinMaxHours } from '@/utils/validations'

export const neighborhoodsStore = defineStore('neighborhoods', {
    state: () => ({
        form: {
            name: '',
            nac_id: '',
        },
        form_rules: {
            name: { required },
            nac_id: { required },
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