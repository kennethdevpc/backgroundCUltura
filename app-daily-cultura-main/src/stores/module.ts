import { defineStore } from "pinia";
import { required } from '../utils/validations'

export const useModules = defineStore("modules", {
	state: () => ({
        form: {
            name:'',
            slug:'',
            description:'',
            icon:'',
            available:'',
            hasItems:'',
            position:'',
        },
        form_rules: {
            name: { required },
            slug: { required },
            icon: {  },
            description: { required },
            available: { required },
            hasItems: { required },
            position: { required },
          
        },
        form_options: {
            decisions: [
                { value: '1', text: 'Si'},
                { value: '0', text: 'No'}
            ]
        },
        validator: {
            generalData: false,
            modules: false
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