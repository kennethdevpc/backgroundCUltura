import { defineStore } from "pinia";

export const useExport = defineStore("export", {
	state: () => ({
        form: {
            type:"" ,
            fields:[],
        },
	}),
})