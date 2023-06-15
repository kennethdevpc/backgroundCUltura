import { defineStore } from 'pinia'
import { required, checkMinMaxHours } from '@/utils/validations'

export const binnacleTerritories = defineStore('binnacle_territories', {
    state: () => ({
        form: {
            consecutive: '',
            nac_id: '',
            role_id: '',
            user_id: '',
            activity_date: '',
            start_time: '',
            final_hour: '',
            place: '',
            // 
            strategic_objectives_area: '',
            purpose_visit: '',
            //
            topics_covered: '',
            participants_perception: '',
            problems_identified: '',
            //
            recommendations_actions: '',
            comments_analysis: '',
            //
            file_1: null,
            file_2: null,
            reviewed_by: '',
        },
        form_rules: {
            consecutive: { required },
            nac_id: { required },
            role_id: { required },
            user_id: { required },
            activity_date: { required },
            start_time: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour')
            },
            final_hour: {
                required,
                checkMinMaxHours: checkMinMaxHours('start_time', 'final_hour')
            },
            place: { required },
            //
            strategic_objectives_area: { required },
            purpose_visit: { required },
            //
            topics_covered: { required },
            participants_perception: { required },
            problems_identified: { required },
            //
            recommendations_actions: { required },
            comments_analysis: { required },
            //
            file_1: { required },
            file_2: { required },
            reviewed_by: {},
        }
    }),
    actions: {
    },
    getters: {
    }
})