import axiosInstance from "@/plugins/axios";
const apiPath = import.meta.env.VITE_API_PATH;
import alerts from "@/utils/alerts";
import { setLoading } from "@/utils/loading";

export default function consecutiveService() {
	const data = ref("");
	const dataControl = reactive({
        all: [],
        one: {},
        count_page: 0
    });
	const data_beneficiaries = ref("");
	const data_count_form = ref("");
	const dataGroups = ref([]);
	const count_page = ref(0)

	const data2 = reactive({
		all: [],
		one: {},
		no_paginate: [],
		count_page: 0
	});

	const getConsecutive = async (table, abreviature) => {
		try {
			let response = await axiosInstance.get(
				`/${apiPath}/consecutive/generate/${table}/${abreviature}`
			);

			if (response.status === 200) {
				data.value = response.data;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	}

	const get = async (page, searchParams = null) => {
		try {
			setLoading(true)
			let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
			let response = await axiosInstance.get(`/${apiPath}/${module}${query}`).finally(() => {
				setLoading(false)
			});
			if (response.status === 200) {
				data2.all = response.data.items;
				data2.count_page = response.data.count_page;
			}
		} catch (e) {
			alerts.custom_validation((e.response?.data?.error) ?? (e.response?.data?.message) ?? 'Ocurrió un error en la respuesta');
		}
	}

	const getChangeDataModels = async (page, searchParams = null) => {
		try {
			setLoading(true)
            let query = Boolean(searchParams) ? `?page=${page}&${searchParams}` : `?page=${page}`
            let response = await axiosInstance.get(`/${apiPath}/getChangeDataModels${query}`)
                .finally(() => {
                    setLoading(false)
                })
            if (response.status === 200) {
                dataControl.all = response.data.data.items;
                dataControl.count_page = response.data.data.count_page;
            }
		} catch (e) {
			alerts.custom_validation(e.response);
		}
	};
	const destroy = async (id) => {
		try {
			setLoading(true);
			const response = await axiosInstance
				.delete(`/${apiPath}/destroyChangeDataModel/${id}`)
				.finally(() => {
					setLoading(false);
				});
			if (response.status == 200) {
				alerts.destroy("Control de data", id);
			}
		} catch (e) {
			errors.value = e.response.data.errors;
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getGroupBeneficiaries = async (id, user_id) => {
		const _user_id = user_id || 'without_beneficiaries';
		try {

			if (id) {
				setLoading(true);
				let response = await axiosInstance
					.get(`/${apiPath}/getGroupBeneficiaries/${id}/${_user_id}`)
					.finally(() => {
						setLoading(false);
					});
				if (response.status === 200) {
					data_beneficiaries.value = response.data.items;
				}
				return response
			}

		} catch (e) {
			setLoading(false);
			data_beneficiaries.value = [];
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getCountDataForm = async () => {
		try {


			setLoading(true);
			let response = await axiosInstance
				.get(`/${apiPath}/getCountDataForm`)
				.finally(() => {
					setLoading(false);
				});
			//console.log('response', response)
			if (response.status === 200) {
				data_count_form.value = response.data.items;
			}
			return response


		} catch (e) {
			setLoading(false);
			data_count_form.value = {
				'monitor': {
					'pecs': 0,
					'instructions': 0,
					'pedagogicals': 0,
					'binnacles': 0,
					'pollDesertion': 0
				},
				'polls': 0
			};
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getGroups = async (module) => {
		try {
			let response = await axiosInstance.get(
				`/${apiPath}/getGroups`
			);
			//console.log(' response.data', response.data)
			if (response.status === 200) {
				dataGroups.value = response.data;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getGroupsCreatedBy = async (user_id) => {
		try {
			let response = await axiosInstance.get(
				`/${apiPath}/getGroupsCreatedBy/${user_id}`
			);
			if (response.status === 200) {
				dataGroups.value = response.data;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getAllDataCreatedBy = async (table_name, page, searchParams = null) => {
		try {
			setLoading(true);
			let response = await axiosInstance
				.get(`/${apiPath}/getAllDataCreatedBy/${table_name}`)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				data.all = response.data.items;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getDataSheet = async (table_name, $date) => {
		try {
			setLoading(true);
			let response = await axiosInstance
				.get(`/${apiPath}/getDataSheet/${table_name}/${$date}`)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				data.all = response.data.items;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};
	const getProfileRoleUserNac = async (nac_id) => {
		try {
			setLoading(true);
			let response = await axiosInstance
				.get(`/${apiPath}/profileRoleUserNac/${nac_id}`)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				data.all = response.data.items;
			}
			return response;
		} catch (e) {
			alerts.custom_validation(e.response.data.error ?? e.response.data.message);
		}
	};

	return {
		data,
		getConsecutive,
		getChangeDataModels,
		destroy,
		getGroupBeneficiaries,
		data_beneficiaries,
		data_count_form,
		getCountDataForm,
		getGroups,
		dataGroups,
		getGroupsCreatedBy,
		getAllDataCreatedBy,
		getDataSheet,
		getProfileRoleUserNac,
		count_page,
		get,
		dataControl
	};
}
