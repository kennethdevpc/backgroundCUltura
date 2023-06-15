import { defineStore } from "pinia";
import { Form } from "vee-validate";
import service from "../services/selects.service";
const select_service = service();

export interface Select {
	[x: string]: string;
	value: string;
	label: string;
}

interface SelectBeneficiary {
	id: string;
	label: string;
	nuip: string;
}

interface monitors_tableT {
	id: string;
	monitor_fullname: string;
	document_number: string;
}

export interface SelectsState {
	assistants: Select[]
	beneficiaries_table: SelectBeneficiary[]
	beneficiaries: SelectBeneficiary[]
	beneficiary_attrition_factors: Select[]
	binnacles: Select[]
	conditions: Select[]
	cultural_rights: Select[]
	methodologicalsheetsone: Select[]
	decisions: Select[]
	disabilitis: Select[]
	disability_types: Select[]
	educational_levels: Select[]
	entity_names: Select[]
	ethnicities: Select[]
	expertises: Select[]
	genders: Select[]
	gestors: Select[]
	health_conditions: Select[]
	lineaments: Select[],
	linkage_projects: Select[]
	managers: Select[]
	medical_services: Select[]
	modules: Select[]
	monitors_parentschools: monitors_tableT[]
	monitors_table: monitors_tableT[]
	monitors: Select[]
	nacs: Select[]
	nacs_with_other: Select[]
	neighborhoods: Select[]
	orientations: Select[]
	participant_types: Select[]
	place_types: Select[]
	relationships: Select[],
	roles: Select[]
	roles_display: Select[]
	status: Select[]
	stratums: Select[]
	type_documents: Select[]
	users_table: Select[]
	zones: Select[],
	disease_types: Select[];
	marital_status: Select[];
	pecs: Select[]
	pedagogicals: Select[]
	relationship_households: Select[];
	single_registry_victims: Select[];
	groups: Select[];
	group_beneficiaries: Select[];
	values: Select[];
	filter_level: Select[];
	activity_type: Select[];
	aspects: Select[];
	users: Select[];
	evaluate_aspects: Select[];

}

export const useSelectStore = defineStore("selects", {
	state: () => ({
		options: useStorage("options", <SelectsState>{}),
	}),
	actions: {
		async dataSelects(force: boolean) {
			if (this.options) {
				await select_service.getSelectAll()
			}

			this.$patch((state) => {
				state.options = select_service.data.all;

				var monitors_mapped = [];
				if (state.options.monitors_table.length > 0) {
					monitors_mapped = toRaw(state.options.monitors_table).map(
						({ id, profile }) => {
							if (profile != null) {
								return {
									id,
									monitor_fullname: profile.contractor_full_name,
									document_number: profile.document_number,
									nac_id: profile.nac_id,
									role_id: profile.role_id
								};
							}
						}
					);
				}

				state.options.monitors_table = monitors_mapped;
				var mons = [];
				if (state.options.monitors_table.length > 0) {

					mons = state.options.monitors_table.filter((monitor) => monitor.role_id == 14).map(
						({ id, monitor_fullname }) => {
							return {
								value: id,
								label: monitor_fullname
							}
						}
					)
				}

				state.options.monitors = mons
				var monitors_parentschools_mapped = [];
				if (state.options.monitors_parentschools.length > 0) {

					monitors_parentschools_mapped = toRaw(state.options.monitors_parentschools).map(({ id, name, profile }) => {
						if (profile != null) {
							return {
								value: id,
								label: profile.contractor_full_name,
							}
						}
					})
				}

				state.options.monitors_parentschools = monitors_parentschools_mapped

				var users_mapped = [];
				if (state.options.users_table.length > 0) {
					users_mapped = toRaw(state.options.users_table).map(({ id, name, profile }) => {
						if (profile != null) {
							return {
								id,
								name: profile.contractor_full_name,
								document_number: profile.document_number
							}
						}
					})
				}

				state.options.users_table = users_mapped;

				state.options.nacs_with_other = state.options.nacs

				const nacs_filtered = toRaw(state.options.nacs).filter((select) => select.value != '67')

				state.options.nacs = nacs_filtered
			});
		},
	},
});
