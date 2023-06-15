function useSlug() {
    const route = useRoute()

    const isSlug = (slug: string) => {
        return route.name.toString().includes(slug)
    }
    const currentSlugName = () => {
        return route.name.toString().split('.')[0]
    }

    return {
        isSlug,
        currentSlugName
    }
}

export default useSlug