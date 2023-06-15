import { defineStore } from 'pinia'
import {
    required,
    maxLength,
    alphanumeric,
    numeric
} from '@/utils/validations'

export const useCulturalCirculations = defineStore('cultural_circulations', {
    state: () => ({
        form: {
            date: '',
            status: '',
            consecutive: '',
            keyactors_circulation_alliance: '',
            pec_id: '',
            datasheet_planning_id: '',
            event_name: '',
            filter_level: '',
            description: '',
            nac_id: '',
            other_nac: '',
            quantity_members: '',
            public_characteristics: '',
            cultural_right_id: '',
            lineament_id: '',
            orientation_id: '',
            values: '',
            artistic_expertise: '',
            participation_observations: '',
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: null,
            number_attendees: '',
            datasheet:null,
        },
        form_rules: {
            date: { required },
            keyactors_circulation_alliance: { required, maxLength: maxLength(3500) },
            pec_id: { required },
            datasheet_planning_id: { required },
            event_name: { required, maxLength: maxLength(3500) },
            filter_level: { required },
            description: { required, maxLength: maxLength(3500) },
            nac_id: { required },
            quantity_members: { required },
            public_characteristics: { required, maxLength: maxLength(3500) },
            cultural_right_id: { required },
            lineament_id: { required },
            orientation_id: { required },
            values: { required },
            artistic_expertise: { required, maxLength: maxLength(3500) },
            participation_observations: { required, maxLength: maxLength(3500) },
            development_activity_image: { required },
            evidence_participation_image: { required },
            aforo_pdf: { required },
            number_attendees: { required },
            other_nac: {},
            datasheet:{required}
        },
        filesOne: {
            development_activity_image: null,
            evidence_participation_image: null,
            aforo_pdf: null,
        }
    }),
    getters: {
        getterForm: (state) => state.form,
        getFulesComputed: (state) => {
            return {
                ...state.form_rules,
                other_nac: (state.form.nac_id == '67') ? { required } : {}
            }
        }
    },
    actions: {
    }
});