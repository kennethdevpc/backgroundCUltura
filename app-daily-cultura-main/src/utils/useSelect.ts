import api from '@/plugins/axios'

const apiPath = import.meta.env.VITE_API_PATH

export interface Select {
    [key: string]: any
    label?: string | number
    value?: string | number
}

function useSelect() {
    const getFromTable = (table: string) => {
        return api.get<Array<Select>>(`/${apiPath}/selectFromTable?table=${table}`)
    }

    const getFromDefaults = (select: string) => {
        return api.get<Array<Select>>(`/${apiPath}/selectFromDefaults?select=${select}`)
    }

    return {
        getFromTable,
        getFromDefaults
    }
}

export default useSelect