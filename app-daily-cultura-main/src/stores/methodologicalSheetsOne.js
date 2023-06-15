import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength, checkMinMaxMonths } from '../utils/validations'
export const useMethodologicalSheetsOne = defineStore("methodologicalSheets", {
    state: () => ({
        form: {
            semillero_name: '',
            date_ini: '',
            date_fin: '',
            filter_level: '',
            worked_expertise: '',
            characteristics_process: '',
            objective_process: '',
            comments: '',
            group_id: '',
            cultural_right_id: '',
            orientation_id: '',
            value: '',
            lineament_id: ''
        },
        form_rules: {
            semillero_name: { required },
            date_ini: {
                required,
                checkMinMaxMonths: checkMinMaxMonths('date_ini', 'date_fin')
            },
            date_fin: {
                required,
                checkMinMaxMonths: checkMinMaxMonths('date_ini', 'date_fin')
            },
            filter_level: { required },
            worked_expertise: { required },
            characteristics_process: { required },
            objective_process: { required },
            comments: { required },
            group_id: { required },
            cultural_right_id: { required },
            orientation_id: { required },
            value: { required },
            lineament_id: { required }
        },
        form_options: {

        },
    }),
    getters: {

    },
    actions: {
        fillData(payload) {
            this.$state.form.semillero_name = payload.semillero_name;
            this.$state.form.date_ini = payload.date_ini;
            this.$state.form.date_fin = payload.date_fin;
            this.$state.form.filter_level = payload.filter_level;
            this.$state.form.worked_expertise = payload.worked_expertise;
            this.$state.form.characteristics_process = payload.characteristics_process;
            this.$state.form.objective_process = payload.objective_process;
            this.$state.form.comments = payload.comments;
            this.$state.form.cultural_right_id = payload.cultural_right_id;
            this.$state.form.orientation_id = payload.orientation_id;
            this.$state.form.value = payload.value;
            this.$state.form.lineament_id = payload.lineament_id; 
            this.$state.form.group_id = payload.group_id;
        },
        clear() {
            this.$state.form.semillero_name = '';
            this.$state.form.date_ini = '';
            this.$state.form.date_fin = '';
            this.$state.form.filter_level = '';
            this.$state.form.worked_expertise = '';
            this.$state.form.characteristics_process = '';
            this.$state.form.objective_process = '';
            this.$state.form.comments = '';
            this.$state.form.group_id = '';
            this.$state.form.cultural_right_id = '';
            this.$state.form.orientation_id = '';
            this.$state.form.value = '';
            this.$state.form.lineament_id = '';
        }
    }
})