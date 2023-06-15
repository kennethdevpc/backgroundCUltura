import { module } from "@/stores/top-menu"

const modules_supervisory_supports: module[] = [
    {
        name: "Coordinador de supervisión",
        icon: "UsersIcon",
        items: [
            {
                name: "Visita a Territorio",
                route: "coordinadores.index",
                icon: "MinusIcon",
            },
            {
                name: "Informe de supervisión",
                route: "monthly_monitoring_reports.index",
                icon: "MinusIcon",
            }
        ]
    },
    {
        name: "Revision Apoyo a la supervisión",
        icon: "UsersIcon",
        items: [
            {
                name: "Fortalecimiento a la supervisión monitores e instructores",
                route: "strengtheningSuperMonIns.index",
                icon: "MinusIcon",
            },
            {
                name: "Fortalecimiento a la supervisión gestores",
                route: "strengtheningSupervisionMans.index",
                icon: "MinusIcon",
            }
        ]
    },
    {
        name: "Informes",
        icon: "LayersIcon",
        items: [
            {
                name: "Reportes",
                route: "reports.index",
                icon: "MinusIcon",
            }
        ]
    },
    // {
    //     name: "Coordinador supervisor",
    //     icon: "UsersIcon",
    //     items: [
    //         {
    //             name: "Visita a Territorio",
    //             route: "reportsTerritories.index",
    //             icon: "MinusIcon",
    //         },
    //         {
    //             name: "Informe mensual de Supervisión",
    //             route: "monthly_monitoring_reports.index",
    //             icon: "MinusIcon",
    //         }
    //     ]
    // },
    // {
    //     name: "Coordinadores",
    //     icon: "UsersIcon",
    //     items: [
    //         {
    //             name: "Visita a Territorio",
    //             route: "reportTerritoryCoordinators.index",
    //             icon: "MinusIcon",
    //         },
    //     ]
    // },
    {
        name: "Monitores",
        icon: "UsersIcon",
        items: [
            {
                name: "Ficha Pedagógica",
                route: "pedagogicals.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitácora Jornada Pacto",
                route: "binnacles.index",
                icon: "MinusIcon",
            },
            {
                name: "Encuesta de Deserción",
                route: "pollDesertions.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Instructores",
        icon: "UsersIcon",
        items: [
            {
                name: "Grupos",
                route: "groups.index",
                icon: "MinusIcon",
            },
            {
                name: "Ficha metodológica de Planeación",
                route: "methodologicalsheetsone.index",
                icon: "MinusIcon",
            },
            {
                name: "Ficha metodológica de Evaluación",
                route: "methodologicalsheetstwo.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitácora Ensamble Cultural",
                route: "culturalEnsembles.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitácora Circulación Cultural",
                route: "culturalCirculations.index",
                icon: "MinusIcon",
            },
            {
                name: "Semillero Cultural",
                route: "culturalSeedbeds.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Monitores e Instructores",
        icon: "UsersIcon",
        items: [
            {
                name: "Inscripción",
                icon: "MinusIcon",
                route: "inscriptions.index",
            },
            {
                name: "PEC",
                icon: "LayersIcon",
                route: "pecs.index",
            },
        ]
    },
    {
        name: "Gestores",
        icon: "UsersIcon",
        items: [
            {
                name: "Mesa de Dialogo",
                route: "dialoguetables.index",
                icon: "MinusIcon",
            },
            {
                name: "Instrucción Metodológica",
                route: "methodologicalInstructions.index",
                icon: "MinusIcon",
            },
            {
                name: "Seguimiento de Gestor Cultural",
                route: "managermonitorings.index",
                icon: "MinusIcon",
            },
            {
                name: "Activación cultural",
                route: "binnacleManagers.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Apoyo Psicosocial",
        icon: "UsersIcon",
        items: [
            {
                name: "Instrucción Psicosocial",
                route: "psychosocialinstructions.index",
                icon: "MinusIcon",
            },
            {
                name: "Escuela de Padres",
                route: "parentschools.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitácora Psicopedagógica",
                route: "psychopedagogicallogs.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Embajadores",
        icon: "UsersIcon",
        items: [
            {
                name: "Bitácora Show Cultural",
                route: "binnacleculturalshow.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Seguimiento y monitoreo",
        icon: "UsersIcon",
        items: [
            {
                name: "Fortalecimiento al seguimiento",
                route: "strengtheningOfMonitorings.index",
                icon: "MinusIcon",
            },
            {
                name: "Informe de seguimiento",
                route: "monitoringReports.index",
                icon: "MinusIcon",
            }

        ]
    },
    {
        name: "Metodologo",
        icon: "UsersIcon",
        items: [
            {
                name: "Acompañamiento metodológico",
                route: "methodologicalAccompaniments.index",
                icon: "MinusIcon",
            },
            {
                name: "Fortalecimiento metodológico",
                route: "methodologicalStrengthenings.index",
                icon: "MinusIcon",
            },
            {
                name: "Seguimiento metodológico",
                route: "methodologicalMonitorings.index",
                icon: "MinusIcon",
            },
        ]
    }
]

export default modules_supervisory_supports