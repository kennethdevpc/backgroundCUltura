import axiosInstance from "@/plugins/axios";
import alerts from "@/utils/alerts";
import dayjs from "dayjs";

const apiPath = import.meta.env.VITE_API_PATH;
const module = "generate";

export default function generate() {
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
	};

	const data = ref([]);
	const errors = ref("");

	const exportPdf = async (id, type) => {
        try{
            const response = await axiosInstance.get(`/${apiPath}/${module}/pdf/${id}?type="${type}"`)
            return response
        }catch (error) {
			if (error.status == 404) {
                alerts.custom("No encontrado", "El archivo no se encuentra disponible.", "error");
            } else {
				alerts.custom('ERROR', error.message, 'error');
			}
        }
    }

	return {
		data,
		errors,
		exportPdf,
	};
}
