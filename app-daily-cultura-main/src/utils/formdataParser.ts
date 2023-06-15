export const formdataParser = (payload: any): FormData => {
    const dt = new FormData()

    for (const [name, value] of Object.entries(payload)) {
        dt.append(name, value as string | Blob)
    }

    return dt
}