import { defineStore } from "pinia";
import { required, checkMinMaxHours, minLength, alphaOrAlphanumeric } from '../utils/validations'

export const coordinators_follow_method = defineStore("coordinators_follow", {
	state: () => ({
        form: {
            date_range:'',
    
            nac:'',
            role:'',
            ficha_no:'',
            date_planning_range:'',
            cultural_right_id:'',
            lineament_id:'',
            orientation_id:'',
            values:'',
            objective_process:'',
            comments:'',
            strengthening_type:'',
            strengthening_comments:'',
            topics_to_strengthened:'',
            firstFile:null,
            secondFile:null,
           
        },
        form_rules: {
            date_range:{required},
            ficha_no:{required},
            nac:{required},
            role:{required},
            date_planning_range:{required},
            cultural_right_id:{required},
            lineament_id:{required},
            orientation_id:{required},
            values:{required},
            objective_process:{required},
            comments:{required},
            strengthening_type:{required},
            strengthening_comments:{required},
            topics_to_strengthened:{required},
            firstFile:{required},
            secondFile:{required},
        },
	}),
	getters: {
      
    //code here
       
	},
	actions: {
      //code here
	}
})