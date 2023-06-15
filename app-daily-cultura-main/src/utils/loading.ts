import { useLoading } from "@/stores/loading"

export const loading = computed(() => {
    const { value } = useLoading()
    return value
})

export const setLoading = (value: boolean) => {
    const { setLoading } = useLoading()

    setLoading(value)
}