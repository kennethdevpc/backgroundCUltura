import { defineStore } from 'pinia'

export const useLoading = defineStore('loading', () => {
    const show_loading = ref(false)

    const toggle = useToggle(show_loading)

    const setLoading = (value: boolean) => {
        toggle(value)
    }

    return {
        value: show_loading,
        setLoading,
    }
})