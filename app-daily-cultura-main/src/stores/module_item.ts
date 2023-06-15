import { defineStore } from "pinia";
import { required } from '../utils/validations'

export const useModuleItems = defineStore("moduleItems", {
	state: () => ({
      
        form: {
            name:'',
            route:'',
            description:'',
            icon:'',
            available:'',
            module_id:'',
        },
        form_rules: {
            name: { required },
            route: { required },
            description: { required },
            icon: {  },
            available: { required },
            module_id: { required },
          
        },
        form_options: {
            decisions: [
                { value: '1', text: 'Si'},
                { value: '0', text: 'No'}
            ]
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