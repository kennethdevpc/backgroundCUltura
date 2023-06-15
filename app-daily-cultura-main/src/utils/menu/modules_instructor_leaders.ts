import { module } from "@/stores/top-menu"

const modules_instructor_leaders: module[] = [
    {
        name: "Revisiones Instructores",
        icon: "UsersIcon",
        items: [

            {
                name: "Pec",
                route: "pecs.index",
                icon: "MinusIcon",
            },
            {
                name: "Ficha metodológica de planeación",
                route: "methodologicalsheetsone.index",
                icon: "MinusIcon",
            },
            {
                name: "Ficha metodológica de evaluación",
                route: "methodologicalsheetstwo.index",
                icon: "MinusIcon",
            },
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

export default modules_instructor_leaders