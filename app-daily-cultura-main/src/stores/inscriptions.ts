import { defineStore } from "pinia"
import { required, phone, numeric, email, minLength, maxLength, alpha } from '../utils/validations'

export const inscriptionStore = defineStore('inscriptionStore', {
    state: () => ({
        form_beneficiary: {
            accept: 0,
            linkage_project: "",
            participant_type: "",
            user_id: "",
            institution_entity_referred: "",
            referrer_name: "",
            full_name: "",
            type_document: "",
            document_number: "",
            nac_id: "",
            neighborhood_id: "",
            neighborhood_new: "",
            zone: "",
            stratum: "",
            phone: "",
            email: "",
            file: {},
            consecutive: "",
            reject_message: "",
            status: "",
            group_id: ""
        },
        form_beneficiary_socio: {
            gender: '',
            age: '',
            decision_study: '',
            educational_level: '',
            decision_disability: '',
            disability_type: '',
            ethnicity: '',
            condition: '',
        },
        form_beneficiary_health: {
            medical_service: '',
            entity_name_id: '',
            other_entity_name: '',
            other_disability: '',
            health_condition: '',
        },
        form_accudent: {
            full_name: "",
            relationship: "",
            other_relationship: "",
            type_document: "",
            document_number: "",
            zone: "",
            phone: "",
            email: "",
        },
        form_accudent_socio: {
            gender: '',
            age: '',
            decision_study: '',
            educational_level: '',
            decision_disability: '',
            disability_type: '',
            ethnicity: '',
            condition: '',
        },
        form_accudent_health: {
            medical_service: '',
            entity_name_id: '',
            other_entity_name: '',
            health_condition: '',
            other_disability: ''
        },
        form_beneficiary_rules: {
            accept: { required },
            participant_type: { required },
            full_name: { required, alpha },
            linkage_project: { required },
            type_document: { required },
            document_number: { numeric, required, minLength: minLength(6), maxLength: maxLength(10) },
            nac_id: { required },
            neighborhood_id: { required },
            zone: { required },
            stratum: { required },
            phone: { phone, required, minLength: minLength(7), maxLength: maxLength(10) },
            email: { email },
            file: {},
            consecutive: { required },
            reject_message: {},
            status: {},
        },
        form_accudent_rules: {
            full_name: { required, alpha },
            relationship: { required },
            type_document: { required },
            document_number: { numeric, required, minLength: minLength(6), maxLength: maxLength(10) },
            zone: { required },
            phone: { phone, required, minLength: minLength(7), maxLength: maxLength(10) },
            email: { email },
        },
        form_socio_rules: {
            gender: { required },
            age: { required },
            decision_study: { required },
            educational_level: { required },
            decision_disability: { required },
            ethnicity: { required },
            condition: { required },
        },
        form_health_rules: {
            medical_service: { required },
            entity_name_id: { required },
            health_condition: { required },
            other_disability: {},
        },
        form_update_instance: {
            inscription_id: null,
            attendant_id: null,
            health_data_attendant_id: null,
            socio_demo_attendant_id: null,
            beneficiary_id: null,
            health_data_beneficiary_id: null,
            socio_demo_beneficiary_id: null,
        },
        validation: {
            beneficiary: false,
            socio: false,
            health: false,
            accudent: false,
            accudent_socio: false,
            accudent_health: false,
        }
    }),
    getters: {
        characterized: (state) => {
            return (state.form_beneficiary.participant_type === 'C') ? true : false
        },
        adult: (state) => {
            return (Number(state.form_beneficiary_socio.age) >= 18) ? true : false
        },
        adultAccudent: (state) => {
            return (Number(state.form_accudent_socio.age) >= 18) ? true : false
        },
        form_beneficiary_rules_computed: (state) => {
            return {
                ...state.form_beneficiary_rules,
                institution_entity_referred: (
                    state.form_beneficiary.linkage_project === 'PMEPUB' ||
                    state.form_beneficiary.linkage_project === 'PMEPRI' ||
                    state.form_beneficiary.linkage_project === 'PMIE') ? { required } : {},
                neighborhood_new: (
                    state.form_beneficiary.neighborhood_id === '357') ? { required } : {},
                user_id: (
                    state.form_beneficiary.linkage_project === 'PMGCP' ||
                    state.form_beneficiary.linkage_project === 'PMMCP') ? { required } : {},
                referrer_name: (state.form_beneficiary.linkage_project === 'PMR') ? { required } : {},
                group_id: (state.form_beneficiary.participant_type === 'C') ? { required } : {},
            }
        },
        form_socio_rules_computed: (state) => {
            return { ...state.form_socio_rules, disability_type: (state.form_beneficiary_socio.decision_disability == '1') ? { required } : {} }
        },
        form_health_rules_computed: (state) => {
            return {
                ...state.form_health_rules,
                other_entity_name: (state.form_beneficiary_health.entity_name_id === '16') ? { required } : {}
            }
        },
        form_accudent_health_rules_computed: (state) => {
            return {
                ...state.form_health_rules,
                other_entity_name: (state.form_accudent_health.entity_name_id === '16') ? { required } : {}
            }
        },
        form_accudent_rules_computed: (state) => {
            return {
                ...state.form_accudent_rules,
                other_relationship: (state.form_accudent.relationship === 'O') ? { required } : {},
            }
        },
        form_accudent_socio_rules_computed: (state) => {
            return { ...state.form_socio_rules, disability_type: (state.form_accudent_socio.decision_disability == '1') ? { required } : {} }
        },
        get_form_uncharactherized: (state) => {
            return {
                beneficiary: {
                    general_data: { ...state.form_beneficiary }
                }
            }
        },
        get_form_charactherized: (state) => {
            return {
                beneficiary: {
                    general_data: { ...state.form_beneficiary },
                    health_data: { ...state.form_beneficiary_health },
                    sociodemographic_characterization: { ...state.form_beneficiary_socio }
                }
            }
        },
        get_form_charactherizedWithAccudent: (state) => {
            return {
                beneficiary: {
                    general_data: { ...state.form_beneficiary },
                    health_data: { ...state.form_beneficiary_health },
                    sociodemographic_characterization: { ...state.form_beneficiary_socio }
                },
                attendant: {
                    general_data: { ...state.form_accudent },
                    health_data: { ...state.form_accudent_health },
                    sociodemographic_characterization: { ...state.form_accudent_socio }
                }
            }
        },
        get_form_update: (state) => {
            return {
                identifiers: { ...state.form_update_instance },
                beneficiary: {
                    general_data: { ...state.form_beneficiary },
                    health_data: { ...state.form_beneficiary_health },
                    sociodemographic_characterization: { ...state.form_beneficiary_socio }
                },
                attendant: {
                    general_data: { ...state.form_accudent },
                    health_data: { ...state.form_accudent_health },
                    sociodemographic_characterization: { ...state.form_accudent_socio }
                },
            }
        }
    },
    actions: {

    },
})