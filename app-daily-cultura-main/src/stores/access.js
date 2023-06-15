import { defineStore } from "pinia";

import accessService from "@/services/access.service";

export const useAccessStore = defineStore("access", {
	state: () => ({
		modules: [],
		poll: false,
		safeRoutes: ['Login', '/', '403', '404'],
	}),
	getters: {
		hasModules: (state) => {
			return state.modules.length
		},
		getModules: (state) => {
			return state.modules
		},
		hasPoll: (state) => {
			return state.poll
		}
	},
	actions: {
		async getAccess() {
			try {
				const result = await accessService.getAccess()
				if (result.status === 200) {
					this.modules = result.data.items;
				}
			} catch (error) {
			}
		},
		async getPoll() {
			try {
				if (!this.poll) {
					const result = await accessService.getPollOnly()
					if (result.status >= 200 && result.status <= 300) {
						//console.log('hasPoll', result.data.items.polls)
						
						if (result.data.items.polls > 0) {
							this.poll = true
						}
						else {
							this.poll = false
						}

						return this.poll
					}
				}
				else {
					return this.poll
				}
			} catch (error) {
			}
		},
		async canUserAccess(slug) {
			try {
				if (this.safeRoutes.includes(slug)) return true

				const result = await accessService.userHaveAccess({
					permission: slug
				})

				return result.data
			} catch (error) {

			}
		}
	}
})