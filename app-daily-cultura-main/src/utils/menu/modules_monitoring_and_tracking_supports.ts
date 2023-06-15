import { module } from "@/stores/top-menu"

const modules_monitors: module[] = [
    {
        name: "Apoyo al Seguimiento y Monitoreo",
        icon: "UsersIcon",
        items: [
            {
                name: "Fortalecimiento al seguimiento",
                route: "strengtheningOfMonitorings.index",
                icon: "MinusIcon",
            },

            {
                name: "Informes de seguimiento",
                route: "monitoringReports.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Revisar Monitores",
        icon: "UsersIcon",
        items: [
            // {
            //     name: "Inscripciónes",
            //     route: "inscriptions.index",
            //     icon: "MinusIcon",
            // },
            {
                name: "Bitácoras Jornada Pacto Monitor",
                route: "binnacles.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Revisar Gestores",
        icon: "UsersIcon",
        items: [
            {
                name: "Activación cultural",
                route: "binnacleManagers.index",
                icon: "MinusIcon",
            },
        ]
    },

    {
        name: "Inscripciones",
        icon: "UsersIcon",
        items: [
            {
                name:  "Beneficiarios",
                route: "inscriptions.index",
                icon:  "MinusIcon",
            }
        ]
    },

    {
        name: "Revisiones Instructores",
        icon: "UsersIcon",
        items: [
            // {
            //     name:  "Beneficiarios",
            //     route: "inscriptions.index",
            //     icon:  "MinusIcon",
            // },
            {
                name: "Bitacora ensamble cultural",
                route: "culturalEnsembles.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitacora circulación cultural",
                route: "culturalCirculations.index",
                icon: "MinusIcon",
            },
            ,
            {
                name: "Semillero cultural",
                route: "culturalSeedbeds.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Revisiones Embajadores",
        icon: "UsersIcon",
        items: [
            {
                name: "Bitacora Show cultural",
                route: "binnacleculturalshow.index",
                icon: "MinusIcon",
            },
        ]
    },
    // {
    //     name: "Caracterización",
    //     icon: "LayersIcon",
    //     items: [
    //         {
    //             name: "Encuesta",
    //             route: "polls.index",
    //             icon: "MinusIcon",
    //         }
    //     ]
    // }
]

export default modules_monitors