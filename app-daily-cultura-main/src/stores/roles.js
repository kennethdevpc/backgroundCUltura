import { defineStore } from "pinia";
import { required } from '../utils/validations'

export const useRoles = defineStore("roles", {
	state: () => ({
        form: {
            name: '',
            slug: '',
            description: '',
            public: false,
            'full-access': '',
        },
        form_rules: {
            name: { required },
            slug: { required },
            description: { required },
            public: { required },
            'full-access': { required },
        },
        form_options: {
            public: [
                { value: 1, text: 'Si'},
                { value: 0, text: 'No'}
            ],
            full_access: [
                { value: 'yes', text: 'Si'},
                { value: 'no', text: 'No'}
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