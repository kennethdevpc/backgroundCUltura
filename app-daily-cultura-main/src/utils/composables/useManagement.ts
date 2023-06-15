import { doubleManagement } from "@/types/doubleManagement"

function useManagement() {
    const route = useRoute()

    const hasDoubleManagement = computed(() => (Object.hasOwn(route.meta, 'doubleManagement')))

    const reviewersFromDouble = computed(() => {
        if (hasDoubleManagement.value) {
            const reviewers = route.meta.doubleManagement as doubleManagement
            return { ...reviewers }
        }
    })

    return {
        hasDoubleManagement,
        reviewersFromDouble
    }
}

export default useManagement