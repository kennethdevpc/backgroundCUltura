import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength, checkMinMaxMonths } from '../utils/validations'
export const useStrengtheningSuperMonIns = defineStore("strengtheningSuperMonIns", {
    state: () => ({
        form: {
            consecutive: '',
            revision_date: null,
            nac_id: null,
            role_id: null,
            supervised_user_full_name: null,
            platform_registration_date: null,
            address: null,
            pec_reached_target: null,
            evidence_participation_image: null,
            attendance_list: null,
            validated_pec_time: null,
            description: null,
            comments: null,
            pedagogicals_reached_target: null,
            development_activity_image: null,

        },
        form_rules: {
            consecutive: { required },
            revision_date: { required },
            nac_id: {
                required,
            },
            role_id: {
                required,
            },
            supervised_user_full_name: { required },
            platform_registration_date: { required },
            address: { required },
            pec_reached_target: { required },
            pedagogicals_reached_target: { required },
            attendance_list: {required},
            validated_pec_time: {required},
            description: {required},
            comments: {required},
            development_activity_image: { required },
            evidence_participation_image: { required },
        },
        form_options: {
            radio: [
                { value: 1, text: 'Si' },
                { value: 0, text: 'No' },
            ],
            roles: [
                { value: 14, label: 'Monitor' },
                { value: 15, label: 'Embajador' },
                { value: 16, label: 'Instructor' },
            ],
        },
        update_instance: {
            consecutive: null,
            status: null,
            reject_message: null
        }
    }),
    getters: {
        get_form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                attendance_list: (state.form.revision_date === 'C' || state.form.revision_date === 'R') ? { required } : {},
                validated_pec_time: (state.form.revision_date === 'C' || state.form.revision_date === 'R') ? { required } : {},
            }
        },
    },
    actions: {
        async clear() {
            this.$state.form.revision_date = null;
            this.$state.form.nac_id = null;
            this.$state.form.role_id = null;
            this.$state.form.supervised_user_full_name = null;
            this.$state.form.platform_registration_date = null;
            this.$state.form.address = null;
            this.$state.form.description = null;
            this.$state.form.pec_reached_target = null;
            this.$state.form.pedagogicals_reached_target = null;
            this.$state.form.development_activity_image = null;
            this.$state.form.evidence_participation_image = null;
            this.$state.form.attendance_list = null;
            this.$state.form.validated_pec_time = null;
            this.$state.form.comments = null;
        },
    }
})