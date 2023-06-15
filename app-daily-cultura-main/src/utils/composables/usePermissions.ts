import mixins from "@/mixins"
import { useOnboardingStore } from "@/stores/onboarding"
import { RouteRecordName } from "vue-router"

function usePermissions() {
    const { get_user } = useOnboardingStore()
    const { is_admin } = mixins.computed

    const roles = get_user.roles
    // console.log(roles)

    const hasPermission = (to: string | RouteRecordName) => {
        if (is_admin()) {
            return true
        }
        else {
            return roles.some((role) => role.permissions.some((permission) => permission.slug === to))
        }
    }

    return {
        hasPermission
    }
}

export default usePermissions