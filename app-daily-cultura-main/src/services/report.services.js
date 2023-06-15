import { setLoading } from "@/utils/loading";
import dayjs from "dayjs";
import alerts from "@/utils/alerts";
import axiosInstance from "@/plugins/axios";
import { saveAs } from 'file-saver';

const apiPath = import.meta.env.VITE_API_PATH;
const url_laravel = import.meta.env.VITE_BASE_URL;
const module = "exports";
const moduleCut = "generateCut";

export default function roleService() {
	const translations = {
		inscriptions: "Inscripciones",
		pecs: "Pecs",
		variables: "Variables",
		sesion: "Sesión",
		users: "Usuarios",
		polls: "Encuesta",
		pollDesertions: "Deserciones de Encuestas",
		pedagogicals: "Pedagógicos",
		beneficiariesMonitor: "Beneficiarios monitores",
		beneficiariesInstructor: "Beneficiarios instructores",
		culturalEnsembles: "Bitácora Ensamble Cultural",
		culturalCirculations: "Bitácora Circulación Cultural",
		culturalSeedbeds: "Bitácora Semilleros Cultural",
		attendats: "Acudientes",
		parentschools: "Escuela de Padres",
		dialogueTables: "Mesa de Dialogo",
		binnacles_monitor: "Bitácora de Monitores",
		managerMonitorings: "Seguimiento Gestor Cultural",
		methodologicalInstructionModels: "Instrucción metodológica",
		binnacleManagers: "Activación cultural",
		psychosocialInstructions: "Instrucciones Psicosocial",
		psychopedagogicallogs: "Bitácora Psicopedagógica",
		methodologicalSheetsOne: "Ficha metodólogica de planeación",
		methodologicalsheetstwo: "Ficha metodólogica de evaluación",
		ambassador: "Embajadores",
		revisions: "Revisiones",
		methodologicalMonitorings: "Seguimiento metodológico",
		methodologicalAccompaniments: "Acompañamiento metodológico",
		strengtheningSuperMonIns: "Fortalecimiento supervisión monitores e instructores",
		strengtheningOfMonitorings: "Fortalecimiento seguimiento",
		managerSupervisionvisit: "Visita de supervisión de gestor",
		revisions: "Revisiones",
		monitoringReport: "Seguimiento",
		methodologicalStrengthening: "Fortalecimiento metodologico",
		binnacleTerritorie: "Visita territorio supervision",
		binnacleImpacts: "Bitácoras de impacto",
		input_history: "Historial de Entradas",
		groups: 'Grupos',
		permissions: 'Permisos',
	};

	const data = ref([]);
	const dataOne = ref([]);
	const errors = ref("");
	const count = ref(0);
	const date = ref(dayjs().format("DD_MM_YYYY"));
	const exportExcel = async (type, form) => {
		try {
			await axiosInstance
				.post(`/${apiPath}/${module}/excel/${type}`, form, {
					responseType: "blob",
				})
				.then((response) => {
					const url = URL.createObjectURL(
						new Blob([response.data], {
							type: "application/vnd.ms-excel",
						})
					);
					const link = document.createElement("a");
					link.href = url;
					link.setAttribute(
						"download",
						translations[type] + "_" + date.value
					);
					document.body.appendChild(link);
					link.click();
					alerts.general("La exportación fue exitosa");
				});
		} catch (e) {
			// console.log('ex', e)
			if (e.response.status === 404) {
				alerts.info("Algo salio mal en la exportación");
			} else {
				alerts.custom_validation(
					e.response.data.error ?? e.response.data.message
				);
			}
		}
	};

	const exportZipExcel = async (type, form) => {
		try {
		const config = {
			headers: {
				'Access-Control-Allow-Origin': 'true',
				'Content-Type': 'application/json'
			},
			responseType: 'blob'
		};

		await axiosInstance
			.post(`/${apiPath}/${module}/excel/${type}`, form, config)
			.then((response) => {
				const blob = new Blob([response.data], { type: 'application/zip' });
				saveAs(blob, translations[type] + '.zip');
			});
		} catch (e) {
			if (e.response && e.response.status === 404) {
				alerts.info('Algo salió mal en la exportación');
			} else {
				alerts.custom_validation(e.response.data.error ?? e.response.data.message);
			}
		}
	};

	const exportPdf = async (type, form) => {
		try {
			let response = await axiosInstance
				.post(`/${apiPath}/${module}/pdf/${type}`, form)
				.then((response) => {
					const url = window.URL.createObjectURL(
						new Blob([response.data])
					);
					const link = document.createElement("a");
					link.href = url;
					link.setAttribute(
						"download",
						type + "_" + date.value + ".pdf"
					);
					document.body.appendChild(link);
					link.click();
				});
			if (response.status === 201) {
				alerts.info(response.data.message);
			}
		} catch (e) {
			if (e.response.status === 400) {
				alerts.info(e.response.data.message);
			} else {
				alerts.custom_validation(
					e.response.data.error ?? e.response.data.message
				);
			}
		}
	};
	const exportZIP = async (type, form) => {
		try {
			let response = await axiosInstance
				.post(`/${apiPath}/${module}/pdf/${type}`, form)
				.then((response) => {
					// const url = window.URL.createObjectURL(new Blob([response.data],{type:'application/vnd.zip'}))
					// const link = document.createElement('a')
					// link.href = url
					// link.setAttribute('download', type + '_' + date.value + '.zip')
					// document.body.appendChild(link)
					// link.click();
					window.open(
						`${url_laravel}/${apiPath}/${module}/download/${type}`,
						"_blank"
					);
				});
			if (response.status === 201) {
				alerts.info(response.data.message);
			}
		} catch (e) {
			if (e.response.status === 400) {
				alerts.info(e.response.data.message);
			} else {
				alerts.custom_validation(
					e.response.data.error ?? e.response.data.message
				);
			}
		}
	};
	const searchInfoReport = async (form) => {
		try {
			setLoading(true);
			let response = await axiosInstance
				.post(`/${apiPath}/${module}/excel`, form)
				.finally(() => {
					setLoading(false);
				});
			if (response.status === 200) {
				count.value = response.data.count;
			}
		} catch (e) {
			alerts.custom_validation(
				e.response.data.error ?? e.response.data.message
			);
		}
	};

	const generateCut = async (type, form) => {
		try {
			alerts.info("En aproximadamente 30 minutos sus informes quedaran actualizados y listos para su descarga, no cierre la pestaña.");
			let response = await axiosInstance
				.post(`/${apiPath}/${moduleCut}/excel/${type}`, form);
			if (response.status === 200) {
				count.value = response.data.count;
			}
			alerts.general(response.data);
		} catch (e) {
			alerts.custom_validation(
				e.response.data.error ?? e.response.data.message
			);
		}
	}

	return {
		data,
		errors,
		exportExcel,
		exportPdf,
		searchInfoReport,
		exportZipExcel,
		count,
		exportZIP,
		generateCut,
	};
}
