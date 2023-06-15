import { module } from "@/stores/top-menu"

const modules_managers: module[] = [
    {
        name: "Revisiones Monitores",
        icon: "UsersIcon",
        items: [
            {
                name: "Pec",
                route: "pecs.index",
                icon: "MinusIcon",
            },
            {
                name: "Ficha pedagógica",
                route: "pedagogicals.index",
                icon: "MinusIcon",
            },
            {
                name: "Bitácora Jornada Pacto Monitor",
                route: "binnacles.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Gestores",
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

export default modules_managers