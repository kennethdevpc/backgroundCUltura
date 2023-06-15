import { createI18n } from 'vue-i18n'
import { setLocale } from 'yup'

const messages = {
    es: {
        validations: {
            required: 'Campo requerido.',
            minLength: 'Debe tener por lo menos {min} caracteres.',
            maxLength: 'Debe tener {max} caracteres como máximo.',
            minValue: 'El valor mínimo es {min}.',
            maxValue: 'El valor máximo es {max}.',
            alphaNum: 'Solo se admiten letras y números',
            email: 'Debes colocar un email valido.',
            alpha: 'Solo se admiten letras.',
            numeric: 'Solo se admiten números.',
            checkMinMaxHours: 'La hora inicial no debe ser mayor a la hora final. Asi mismo, la hora final no debe ser menor a la hora inicial.',
            phone: 'Debes colocar un numero de teléfono valido.',
            checkMinMaxMonths: 'La fecha inicial no debe ser mayor a la fecha final. Asi mismo, la fecha final no debe ser menor a la fecha inicial.'
        },
    },
}

setLocale({
    mixed: {
        required: 'Campo requerido.'
    },
    string: {
        length: ({ length }) => (`Debe tener por lo menos ${length} caracteres.`),
        max: ({ max }) => (`Debe tener ${max} caracteres como máximo.`),
        email: 'Debes colocar un email valido.'
    },
})

const i18n = createI18n({
    locale: 'es',
    messages,
})

export default i18n
