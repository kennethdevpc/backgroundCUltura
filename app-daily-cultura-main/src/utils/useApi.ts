import api from '@/plugins/axios'
import { RawAxiosRequestConfig } from 'axios'

const apiPath = import.meta.env.VITE_API_PATH

function useApi() {

    /**
     * Get a list of {module} entries
     *
     * @param  {string} module - Content type's name pluralized
     * @param  {AxiosRequestConfig} config? - Config
     * @returns Promise<AxiosResponse<T, any>>
     */
    const find = <T>(module: string, config?: RawAxiosRequestConfig) => {
        const path = [apiPath, module].filter(Boolean).join('/')
        return api.get<T>(path, config)
    }

    /**
     * Get a specific {module} entry
     *
     * @param  {string} module - Content type's name pluralized
     * @param  {string|number} id - ID of entry
     * @param  {AxiosRequestConfig} config? - Config
     * @returns Promise<AxiosResponse<T, any>>
     */
    const findOne = <T>(module: string, id?: string | number |RawAxiosRequestConfig, config?:RawAxiosRequestConfig) => {
        if (typeof id === 'object') {
            config = id
            id = undefined
        }

        const path = [apiPath, module, id].filter(Boolean).join('/')
        return api.get<T>(path, {
            ...config
        })
    }

    /**
     * Create a {module} entry
     *
     * @param  {string} module - Content type's name pluralized
     * @param  {Record<string, any>} data - Form data
     * @returns Promise<AxiosResponse<T, any>>
     */
    const create = <T>(module: string, data: Partial<T>, config?: RawAxiosRequestConfig) => {
        const path = [apiPath, module].filter(Boolean).join('/')
        return api.post<T>(path, data, { ...config })
    }

    /**
     * Update an entry
     *
     * @param  {string} module - Module name
     * @param  {string|number} id - ID of entry to be updated
     * @param  {Record<string, any>} data - Form data
     * @returns Promise<AxiosResponse<T, any>>
     */
    const update = <T>(module: string, id: string | number | Partial<T>, data?: Partial<T>) => {
        if (typeof id === 'object') {
            data = id
            // @ts-ignore
            id = undefined
        }

        const path = [apiPath, module, id].filter(Boolean).join('/')

        return api.post<T>(path + '?_method=PUT', data)
    }

    /**
     * Delete an entry
     *
     * @param  {string} module - Module name
     * @param  {string|number} id - ID of entry to be deleted
     * @returns Promise<AxiosResponse<T, any>>
     */
    const _delete = <T>(module: string, id?: string | number) => {
        const path = [apiPath, module, id].filter(Boolean).join('/')

        return api.delete<T>(path)
    }

    return {
        find,
        findOne,
        create,
        update,
        delete: _delete
    }
}

export default useApi