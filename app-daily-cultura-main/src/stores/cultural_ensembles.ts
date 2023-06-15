import { storeToRefs, defineStore } from "pinia";
import { required, maxLength, alpha, numeric } from '@/utils/validations'

//  [Service] => Importing Cultural Ensemble Service
import useMethodologicalSheetsOne from "@/services/methodologicalSheetsOne.service";
import useCulturalEnsemble from "@/services/cultural_ensembles.service";
import useGenerals from '@/services/generals.service'

// [Service] => Using services
const generalsService = useGenerals()
const culturalEnsemblesService = useCulturalEnsemble();
const methodologicalSheetsOne = useMethodologicalSheetsOne();

// [Importing Store] => ['selects']
import { useSelectStore } from "@/stores/selects";


// [Getting Store Options] =>  ['selects']
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);

const FORM_STRUCTURE = {
    status: 'ENREV',
    consecutive: null,
    date: null,
    pec_id: null,
    datasheet_planning: null,
    filter_level: null,
    description: null,
    assembly_characteristics: null,
    objective_process: null,
    public_characteristics: null,
    cultural_right_id: null,
    lineament_id: null,
    orientation_id: null,
    value: null,
    artistic_expertise: null,
    evaluate_aspects: [],
    evaluate_aspects_comments: null,
    development_activity_image: null,
    evidence_participation_image: null,
    aforo_pdf: null,
    number_attendees: null,
    datasheet:null
}

export const useCulturalEnsembles = defineStore("culturalEnsembles", {
    state: () => ({
        // [Add] => Form Data & Rules
        form: Object.assign({}, FORM_STRUCTURE),
        form_rules: {
            status: { required },
            date: { required },
            pec_id: { required },
            datasheet_planning: { required },
            filter_level: { required },
            description: { required, alpha, maxLength: maxLength(3500) },
            assembly_characteristics: { required, alpha, maxLength: maxLength(3500) },
            objective_process: { required, alpha, maxLength: maxLength(3500) },
            public_characteristics: { required, alpha, maxLength: maxLength(3500) },
            cultural_right_id: { required },
            lineament_id: { required },
            orientation_id: { required },
            value: { required },
            artistic_expertise: { required, alpha, maxLength: maxLength(3500) },
            evaluate_aspects: { required },
            evaluate_aspects_comments: { required, alpha, maxLength: maxLength(3500) },
            development_activity_image: { required },
            evidence_participation_image: { required },
            aforo_pdf: { required },
            number_attendees: { required, numeric },
            datasheet:{required}
        },
        form_options: {
            methodological_sheet: [],
            pecs: options.value.pecs,
            filter_level: options.value.filter_level,
            cultural_rights: options.value.cultural_rights,
            lineaments: options.value.lineaments,
            orientations: options.value.orientations,
            values: options.value.values,
            evaluate_aspects: options.value.evaluate_aspects

        },
        files: {
            development_activity_image: [],
            evidence_participation_image: [],
            aforo_pdf: [],
        },
        // [Instance] => Getting Initial Form Instance
        instance: {
            status: null,
            reject_message: null,
        }
    }),
    actions: {
        async getCulturalConsecutive() {
            return await generalsService.getConsecutive('cultural_ensembles', 'EC').then(() => {
                this.form.consecutive = generalsService.data.value
            })
        },
        async getCulturalEnsemble(id) {
            const data = await culturalEnsemblesService.getOne(id)
            await this.getInstance(data)
            this.form = {
                ...data,
            };

        },
        async getInstance(data) {
            const { status, reject_message } = data
            this.instance.status = status
            this.instance.reject_message = reject_message
        },
        async addCulturalEnsemble(form) {
            return culturalEnsemblesService.create(form)
        },
        async editCulturalEnsemble(id, form) {
            return culturalEnsemblesService.update(id, form)
        },
        async getMethodologicalOptions() {
            await methodologicalSheetsOne.get().then((response) => {
                this.form_options.methodological_sheet = response
            })
        },
        async clearForm() {
            Object.assign(this.form, FORM_STRUCTURE)
        }
    },
    getters: {
    }
})