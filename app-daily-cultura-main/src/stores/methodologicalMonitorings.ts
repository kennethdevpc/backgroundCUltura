import { defineStore } from 'pinia'
import { required, alphaOrAlphanumeric } from '@/utils/validations'

export const useMethodologicalMonitorings = defineStore('methodologicalMonitorings', {
    state: () => ({
        form: {
            consecutive: '',
            datasheet: '',
            roles: '',
            nac_id: '',
            date_realization: '',
            date_planning_ini: '',
            date_planning_fin: '',
            //
            cultural_right_id: '',
            lineament_id: '',
            orientation_id: '',
            value: '',
            //
            objective_process: '',
            comments: '',
            strengthening_type: '',
            strengthening_comments: '',
            topics_to_strengthened: '',
            //
            development_activity_image: null,
            evidence_participation_image: null,
            //
            aggregates: []
        }
    }),
    getters: {
        form_rules() {
            return {
                consecutive: { required },
                datasheet: { required },
                roles: { required },
                nac_id: { required },
                date_realization: { required },
                date_planning_ini: { required },
                date_planning_fin: { required }, 
                //
                cultural_right_id: { required },
                lineament_id: { required },
                orientation_id: { required },
                value: { required },
                //
                objective_process: { required, alphaOrAlphanumeric },
                comments: { required, alphaOrAlphanumeric },
                strengthening_type: { required },
                strengthening_comments: { required, alphaOrAlphanumeric },
                topics_to_strengthened: { required, alphaOrAlphanumeric },
                //
                development_activity_image: { required },
                evidence_participation_image: { required },
                //
                aggregates: { required }
            }
        },
        transpiledData(state) {
            return {
                ...state.form,
                roles: state.form.roles.toString(),
                aggregates: state.form.aggregates.map((aggregate) => aggregate.id).toString()
            }
        }
    },
    actions: {
    }
})