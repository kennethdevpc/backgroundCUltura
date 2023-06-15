import { defineStore } from "pinia";

export const useDataIncription = defineStore("dataIncription", {
	state: () => ({
		 type:'',
		 characterized:false,
		 beneficiary:false,
		 data:{
		 	dataGeneral:{},
		 	dataGeneralBeneficiary:{},
		 }
		 

	}),
	getters: {
		// hasGetDataGeneral: (state) => {
		//   return state.dataGeneral.length;
		// },
		getData:(state) => {
			return state.data;
		}
	},
	actions: {
		 async getDataGeneral(state,type) {
			 	if(type !='beneficiary'){
					this.data.dataGeneral = await  state;
			 	}else{
			 		this.data.dataGeneralBeneficiary = await  state;
			 	}
		},
		getCharacterized(state) {
			this.characterized = state;
   		},
   		getBeneficiary(state) {

			this.beneficiary= state;
   		}
	}
})