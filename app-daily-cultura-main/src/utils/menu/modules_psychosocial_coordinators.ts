import { module } from "@/stores/top-menu"

const modules_support_psychosocials: module[] = [
    {
        name: "Revisión psicosocial",
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
                name: "Bitacora Psicopedagogica",
                route: "psychopedagogicallogs.index",
                icon: "MinusIcon",
            },
        ]
    },
    {
        name: "Coordinador psicosocial",
        icon: "LayersIcon",
        items: [
            {
                name: "Visita a Territorio",
                route: "coordinadores.index",
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

export default modules_support_psychosocials