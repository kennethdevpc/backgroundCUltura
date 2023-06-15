import { storeToRefs,defineStore } from 'pinia'
import { required, checkMinMaxHours } from '@/utils/validations'
import { requiredUnless } from "@vuelidate/validators"
// [Importing Store] => ['selects']
import { useSelectStore } from "@/stores/selects";
import alert from "@/utils/alerts";
// [Getting Store Options] =>  ['selects']
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);
export const methodologicalAccompaniments = defineStore('methodologicalAccompaniments', {
    state: () => ({
        form: {
            consecutive: '',
            date:'',
            development_activity_image: null,
            evidence_participation_image: null,
            nac_id: "",
            // user_id: "",
            others: "",
            objective_visit: "",
            aspects: "",
            aspects_comments: "",
            comments: "",
            aggregates: {
                assistants: [],
            },
            roles:""

        },
        form_rules: {
            date: { required },
            development_activity_image: { required },
            evidence_participation_image: { required },
            nac_id: { required },
            // user_id: { required },
            others: { required },
            objective_visit: { required },
            aspects: { required },
            aspects_comments: { required },
            comments: { required },
            aggregates: {
                assistants: []
            },
            roles:{required}

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
            users: options.value.users,
            aspects:options.value.aspects

        },
        // [Instance] => Getting Initial Form Instance
        instance: {
            status: null,
            reject_message: null,
        },
   
    }),
    getters: {
        get_assistants_id: (state) => {
            return state.form.aggregates.assistants.map((item) => item.id)
        },
        form_rules_computed: (state) => {
            return {
                ...state.form_rules,
                place_image1: (state.form.development_activity_image === false) ? {} : { required },
                place_image2: (state.form.evidence_participation_image === false) ? {} : { required },
            }
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
                if(record == -1){
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