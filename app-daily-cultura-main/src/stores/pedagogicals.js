import { defineStore } from "pinia";
import { required, alphaOrAlphanumeric } from '@/utils/validations'

export const Pedagogical = defineStore("pedagogicals", {
    state: () => ({
        form: {
            consecutive: '',
            activity_name: '',
            activity_date: '',
            expertise_id: '',
            nac_id: '',
            cultural_right_id: '',
            lineament_id: '',
            orientation_id: '',
            experiential_objective: '',
            manifestation: '',
            process: '',
            product: '',
            resources: ''
        },
        form_rules: {
            'activity_name': { required, alphaOrAlphanumeric },
            'activity_date': { required },
            'expertise_id': { required },
            'nac_id': { required },
            'cultural_right_id': { required },
            'lineament_id': { required },
            'orientation_id': { required },
            'experiential_objective': { required },
            'manifestation': { required },
            'process': { required },
            'product': { required },
            'resources': { required }
        },
        form_options: {
            seleccion: [
                { value: 'si', text: 'Si' },
                { value: 'no', text: 'No' },
            ],
        },
        update_instance: {
            consecutive: '',
            status: '',
            reject_message: ''
        }
    }),
    getters: {
    },
    actions: {
    }
})