const get_status = (status: string): string => {
    if (status == 'ENREV'){
        return 'En Revision'
    }
    if (status == 'REC'){
        return 'Rechazado'
    }
    if (status == 'REV'){
        return 'Revisado'
    }
    if (status == 'APRO'){
        return 'Aprobado'
    }
}

export default get_status