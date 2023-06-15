import { storeToRefs, defineStore } from 'pinia'
import { required, checkMinMaxHours } from '@/utils/validations'
import { requiredUnless } from "@vuelidate/validators"
import alert from "@/utils/alerts";
// [Importing Store] => ['selects']
import { useSelectStore } from "@/stores/selects";
// [Getting Store Options] =>  ['selects']
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);
export const methodologicalStrengthenings = defineStore('methodologicalStrengthenings', {
    state: () => ({
        form: {
            consecutive: '',
            development_activity_image: null,
            evidence_participation_image: null,
            date: "",
            nac_id: "",
            cultural_right_id: "",
            lineament_id: "",
            orientation_id: "",
            value: "",
            comments: "",
            aggregates: {
                assistants: [],
            },
        },
        form_rules: {
            development_activity_image: { required },
            evidence_participation_image: { required },
            date: { required },
            nac_id: { required },
            cultural_right_id: { required },
            lineament_id: { required },
            orientation_id: { required },
            comments: { required },
            value: { required },
            aggregates: {
                assistants: []
            }
        },
        validator: {
            generalData: true,
        },
        form_options: {

            nacs: options.value.nacs,
            cultural_rights: options.value.cultural_rights,
            lineaments: options.value.lineaments,
            orientations: options.value.orientations,
            values: options.value.values,

        },
        // [Instance] => Getting Initial Form Instance
        instance: {
            status: null,
            reject_message: null,
        }
    }),
    getters: {
        get_assistants_id: (state) => {
            return state.form.aggregates.assistants.map((item) => item.id)
        },
        get_computed_rules: (state) => {
            return {
                ...state.form_rules,
                aggregates: {
                    assistants: {
                        requiredIf: requiredUnless(() => {
                            return (state.form.aggregates.assistants.length > 0)
                        })
                    },
                }
            }
        },
        transpiled_data() {
            const { aggregates, ...rest } = this.form
            return {
                ...rest,
                assistants: this.get_assistants_id,
            }
        }
    },
    actions: {
        push_aggregate(payload) {
            this.$patch(state => {
                const record = state.form.aggregates.assistants.findIndex((item) => item.id === payload.id)
                if (record == -1) {
                    state.form.aggregates.assistants.push(payload)
                }else{
                    alert.info('Este asistente ya se agrego')
                }

            })
        },
        pop_aggregate(payload) {
            this.$patch(state => {
                const record = state.form.aggregates.assistants.findIndex((item) => item.id === payload)
                state.form.aggregates.assistants.splice(record, 1)
            })
        },
    }
})