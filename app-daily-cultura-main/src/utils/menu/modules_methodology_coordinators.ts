import { module } from "@/stores/top-menu"

const modules_methodology_coordinators: module[] = [
    {
        name: "Coordinador metodológico",
        icon: "UsersIcon",
        items: [
            {
                name: "Visita a Territorio",
                route: "coordinadores.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Revisar Apoyo metodológico",
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
        ]
    },
    {
        name: "Revisar Lider Metodológico",
        icon: "UsersIcon",
        items: [
            {
                name: "Seguimiento metodológico",
                route: "methodologicalMonitorings.index",
                icon: "MinusIcon",
            }
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

export default modules_methodology_coordinators