import { module } from "@/stores/top-menu"

const modules_psychosocials: module[] = [
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
]

export default modules_psychosocials