import { module } from "@/stores/top-menu"

const modules_monitors: module[] = [
    {
        name: "Monitores",
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
            {
                name: "Encuesta de deserción",
                route: "pollDesertions.index",
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