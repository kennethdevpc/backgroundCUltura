import { storeToRefs, defineStore } from 'pinia'
import { requiredUnless } from "@vuelidate/validators"
import { required, maxLength, minLength, alpha, numeric, checkMinMaxHours } from '@/utils/validations'
// [Importing Store] => ['selects']
import { useSelectStore } from "@/stores/selects";
import alert from "@/utils/alerts";
// [Getting Store Options] =>  ['selects']
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);
export const strengtheningSupervisionManager = defineStore('strengtheningSupervisionManager', {
    state: () => ({
        form: {
            consecutive: '',
            revision_date: '',
            nac_id: "",
            user_associate_id: "",
            methodological_instruction_id: '',
            address: "",
            frequency: "",
            methodological_instruction_reached_target: "",
            description: '',
            start_time: '',
            final_time: '',
            comments: '',
            development_activity_image: null,
            evidence_participation_image: null,
            binnacle_registered_plataform: ''
        },
        form_rules: {
            revision_date: { required },
            nac_id: { required },
            user_associate_id: { required },
            methodological_instruction_id: {},
            address: { required },
            frequency: { required, numeric, minLength: minLength(0) },
            methodological_instruction_reached_target: { required },
            description: { required, maxLength: maxLength(3500) },
            start_time: { required, checkMinMaxHours: checkMinMaxHours('start_time', 'final_time') },
            final_time: { required, checkMinMaxHours: checkMinMaxHours('start_time', 'final_time') },
            comments: { required, maxLength: maxLength(3500) },
            development_activity_image: { required },
            evidence_participation_image: { required },
            binnacle_registered_plataform: { required },
        },
        validator: {
            generalData: true,
        },
        form_options: {
            nacs: options.value.nacs,
            decisions: options.value.decisions
        },
        instance: {
            status: null,
            reject_message: null,
        }

    }),
    getters: {

        form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                development_activity_image: (state.form.development_activity_image === false) ? {} : { required },
                evidence_participation_image: (state.form.evidence_participation_image === false) ? {} : { required },
            }
        }
    },
    actions: {
    }
})