import { module, Menu } from "@/stores/top-menu";

const module_mapper = (modules: module[]) => {
    const map = modules.filter((module) => module.name != 'Dashboard').map((module: module): Menu => {
        if (Object.hasOwn(module, 'route')){
            return {
                icon: module.icon,
                pageName: module.route,
                title: module.name,
            }
        }
        else {
            return {
                icon: module.icon,
                pageName: module.name,
                title: module.name,
                subMenu: module.items.map((item: module): Menu => ({
                    icon: item.icon,
                    pageName: item.route,
                    title: item.name
                }))
            }
        }
    })

    return map
}

export default module_mapper