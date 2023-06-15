import { defineStore } from "pinia";
import { required, minLength, phone } from '../utils/validations'
import { email } from '@vuelidate/validators'

export const useAssistants = defineStore("assistants", {
	state: () => ({
        form: {
            nac_id: '',
            assistant_name: '',
            assistant_document_number: '',
            assistant_position: '',
            assistant_phone: '',
            assistant_email: ''
        },
        form_rules: {
            nac_id: { required },
            assistant_name: { required },
            assistant_document_number: { required, minLength: minLength(10) },
            assistant_position: { required },
            assistant_phone: { phone, required },
            assistant_email: { required, email },
        },
	}),
	getters: {
	},
	actions: {
	}
})