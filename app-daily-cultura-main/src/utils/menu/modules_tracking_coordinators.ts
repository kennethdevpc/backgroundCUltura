import { module } from "@/stores/top-menu"

const modules_tracking_coordinators: module[] = [
    {
        name: "Coordinador de seguimiento",
        icon: "UsersIcon",
        items: [
            {
                name: "Bitacora  de Territorrio",
                route: "coordinadores.index",
                icon: "MinusIcon",
            },
            // {
            //     name: "Semillero cultural",
            //     route: "culturalSeedbeds.index",
            //     icon: "MinusIcon",
            // },
        ]
    },
    {
        name: "Revisar Apoyo al seguimiento y monitoreo",
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

export default modules_tracking_coordinators