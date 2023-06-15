import { defineStore } from "pinia";
import { required, email, phone, minLength, maxLength, numeric, maxValue, minValue, alphaOrAlphanumeric } from '@/utils/validations'
import axiosInstance from "@/plugins/axios";
import { ref } from "vue";

const apiPath = import.meta.env.VITE_API_PATH;
const apiBase = import.meta.env.VITE_BASE_URL;
const consecutivoEncuesta = ref()
const consecutivo = ref()

export const Poll = defineStore("polls", {
    state: () => ({
        form: {
            gender: "",
            age: "",
            birth_date: "",
            marital_state: "",
            stratum: "",
            neighborhood_id: "",
            other_neighborhoods: "",
            phone: "",
            email: "",
            number_children: "0",
            dependents: "0",
            relationship_head_household: "",
            ethnicity: "",
            victim_armed_conflict: "",
            single_registry_victims: "",
            study: "",
            educational_level: "",
            medical_service: "",
            entity_name_id: "",
            other_entity_name: "",
            health_condition: "",
            takes_medication: "",
            suffers_disease: "",
            type_disease: "",
            other_disease_type: "",
            disability: "",
            disability_type: "",
            assessed_disability: "",
            receives_therapy: "",
            expertises: "",
            artistic_experience: "",
            artistic_group: "",
            artistic_group_name: "",
            role_artistic_group: "",
            other_disability_type: "",
            other_entity_name:""
            //user_id: jso.id,
        },
        form_rules: {
            'gender': { required },
            'age': { required },
            'birth_date': { required },
            'marital_state': { required },
            'stratum': { required },
            'neighborhood_id': { required },
            'other_neighborhoods': {},
            'phone': { phone, required, minLength: minLength(7), maxLength: maxLength(10)},
            'email': { required, email },
            'number_children': { required, numeric, maxValue: maxValue(99), minValue: minValue(0) },
            'dependents': { numeric, maxValue: maxValue(99), minValue: minValue(0) },
            'relationship_head_household': { required },
            'ethnicity': { required },
            'victim_armed_conflict': { required },
            'single_registry_victims': {},
            'study': { required },
            'educational_level': { required },
            'medical_service': { required },
            'entity_name_id': { required },
            'health_condition': { required },
            'takes_medication': { required },
            'suffers_disease': { required },
            'type_disease': {},
            'other_disease_type': {},
            'disability': { required },
            'disability_type': {},
            'assessed_disability': { required },
            'receives_therapy': { required },
            'expertises': { required },
            'artistic_experience': { required },
            'artistic_group': { required },
            'artistic_group_name': {},
            'role_artistic_group': {},
            'other_disability_type': {},
            "other_entity_name":{}
        },
        form_options: {
            victim_armed_conflict: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            study: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            takes_medication: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            suffers_disease: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            disability: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            assessed_disability: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            receives_therapy: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
            artistic_experience: [
                { value: "1", label: 1 },
                { value: "2", label: 2 },
                { value: "3", label: 3 },
                { value: "4", label: 4 },
                { value: "5", label: 5 },
                { value: "6-10", label: '6 a 10' },
                { value: "10-15", label: '10 a 15' },
                { value: "15-20", label: '15 a 20' },
                { value: "+20", label: 'mas de 20' },
            ],
            artistic_group: [
                { value: "1", text: 'Si' },
                { value: "0", text: 'No' },
            ],
        },
        validator: {

        }
    }),
    getters: {
        form_rules_computed: (state) => ({
            ...state.form_rules,
            single_registry_victims: (state.form.victim_armed_conflict == '1') ? { required } : {},
            educational_level: { required },
            other_entity_name: (state.form.entity_name_id == '16') ? { required } : {},
            other_neighborhoods: (state.form.neighborhood_id == '357') ? { required } : {},
            type_disease: (state.form.suffers_disease == '1') ? { required } : {},
            other_disease_type: (state.form.type_disease == 'O') ? { required, alphaOrAlphanumeric } : {},
            disability_type: (state.form.disability == '1') ? { required } : {},
            artistic_group_name: (state.form.artistic_group == '1') ? { required } : {},
            role_artistic_group: (state.form.artistic_group == '1') ? { required, alphaOrAlphanumeric } : {},
        })
    },
    actions: {
        clearInputs() {
            this.$reset();
        },
        fillInputs(payload) {
            this.$state.form.gender = payload?.gender || '';
            this.$state.form.age = payload?.age || '';
            this.$state.form.birth_date = payload?.birth_date || '';
            this.$state.form.marital_state = payload?.marital_state || '';
            this.$state.form.stratum = payload?.stratum || '';
            this.$state.form.other_neighborhoods = payload?.geother_neighborhoodsnder || '';
            this.$state.form.phone = payload?.phone || '';
            this.$state.form.email = payload?.email || '';
            this.$state.form.number_children = payload?.number_children || '';
            this.$state.form.dependents = payload?.dependents || '';
            this.$state.form.relationship_head_household = payload?.relationship_head_household || '';
            this.$state.form.ethnicity = payload?.ethnicity || '';
            this.$state.form.educational_level = payload?.educational_level || '';
            this.$state.form.medical_service = payload?.medical_service || '';
            this.$state.form.entity_name_id = payload?.entity_name_id || '';
            this.$state.form.health_condition = payload?.health_condition || '';
            this.$state.form.artistic_experience = payload?.artistic_experience || '';
            this.$state.form.type_disease = payload?.type_disease || '';
            this.$state.form.expertises = payload?.expertises || '';
            this.$state.form.single_registry_victims = payload?.single_registry_victims || '';
            this.$state.form.other_disease_type = payload?.other_disease_type || '';
            this.$state.form.disability_type = payload?.disability_type || '';
            this.$state.form.artistic_group_name = payload?.artistic_group_name || '';
            this.$state.form.role_artistic_group = payload?.role_artistic_group || '';
            this.$state.form.other_disability_type = payload?.other_disability_type || '';
            /* 
            */
            // Selects
            this.$state.form.victim_armed_conflict = payload?.victim_armed_conflict || '';
            this.$state.form.neighborhood_id = payload?.neighborhood_id || '';
            this.$state.form.study = payload?.study || '';
            this.$state.form.takes_medication = payload?.takes_medication || '';
            this.$state.form.suffers_disease = payload?.suffers_disease || '';
            this.$state.form.disability = payload?.disability || '';
            this.$state.form.assessed_disability = payload?.assessed_disability || '';
            this.$state.form.receives_therapy = payload?.receives_therapy || '';
            this.$state.form.artistic_group = payload?.artistic_group || '';
            /*  
            */
        }
    },
})