import { module } from "@/stores/top-menu"

const modules_instructors: module[] = [
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
                name: "Inscripción",
                route: "inscriptions.index",
                icon: "MinusIcon",
            },
            {
                name: "PEC",
                route: "pecs.index",
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
            ,
            {
                name: "Semillero Cultural",
                route: "culturalSeedbeds.index",
                icon: "MinusIcon",
            },
        ]
    },
    

]

export default modules_instructors