import { defineStore } from "pinia";
import { requiredUnless } from '@vuelidate/validators'
import { required, checkMinMaxHours, minLength } from '../utils/validations'



export const useBinnacleShowCultural = defineStore('binnacleshowcultual', {

    state: () => ({

        form: {
            consecutive: '',
            date_range: '',
            activity: '',
            expertise: '',
            artistic_participation: '',
            reached_target: '',
            sustein: '',
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: null,
            number_attendees: '',
        },
        form_rules: {
            consecutive: { required },
            date_range: { required },
            activity: { required },
            expertise: { required },
            artistic_participation: { required },
            reached_target: { required },
            sustein: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            aforo_pdf: { required },
            number_attendees: { required }
        },
        form_options: {
            reached_target: [
                { value: 1, text: 'Si' },
                { value: 0, text: 'No' },
            ],
        },
        filesOne: {
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: null,
        }

    })





})