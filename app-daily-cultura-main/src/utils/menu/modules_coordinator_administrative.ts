import { module } from "@/stores/top-menu"

const modules_ambassador_or_leaders: module[] = [
    {
        name: "Coordinador administrativo",
        icon: "UsersIcon",
        items: [
            {
                "name": "Visita a Territorrio",
                "route": "coordinadores.index",
                "icon": "MinusIcon",
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

export default modules_ambassador_or_leaders