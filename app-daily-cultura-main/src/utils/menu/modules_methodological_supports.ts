import { module } from "@/stores/top-menu"

const modules_methodological_supports: module[] = [
    {
        name: "Apoyo metodológico",
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
        name: "Revision Gestores",
        icon: "UsersIcon",
        items: [
            {
                name: "Mesa de dialogo",
                route: "dialoguetables.index",
                icon: "MinusIcon",
            },
            {
                name: "Instrucción metodológica",
                route: "methodologicalInstructions.index",
                icon: "MinusIcon",
            },
            {
                name: "Seguimiento de gestor cultural",
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

export default modules_methodological_supports