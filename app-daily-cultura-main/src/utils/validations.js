import * as validators from "@vuelidate/validators";
import dayjs from "dayjs";
import { string } from "yup";
import i18n from "../utils/i18n";

// Applying Translations
const withI18nMessage = validators.createI18nMessage({ t: i18n.global.t });
// Wrap validators
export const required = withI18nMessage(validators.required);
export const numeric = withI18nMessage(validators.numeric);
export const email = withI18nMessage(validators.email);
export const minLength = withI18nMessage(validators.minLength, {
	withArguments: true,
});
export const maxLength = withI18nMessage(validators.maxLength, {
	withArguments: true,
});
export const maxValue = withI18nMessage(validators.maxValue, {
	withArguments: true,
});
export const minValue = withI18nMessage(validators.minValue, {
	withArguments: true,
});
export const alphaOrAlphanumeric = validators.helpers.withMessage(
	"Solo se admiten letras, o letras y números",
	validators.helpers.regex(/[a-zA-Z\s]|[a-zA-Z\s]+\d[a-z\d]*/)
);
export const alphanumeric = withI18nMessage(validators.alphaNum, {
	withArguments: true,
});

//  [Validator] => Based on property keys, eval both hours and evaluate
const minMaxHours = (min_hour_key, max_hour_key) =>
	validators.helpers.withParams(
		{ min_hour: min_hour_key, max_hour: max_hour_key },
		(value, state) => {
			return state[min_hour_key] < state[max_hour_key];
		}
	);

const minMaxMonths = (min_month_key, max_month_key) => {
	return validators.helpers.withMessage("malito", (value, state) => {
		const dateIni = dayjs(state[min_month_key]);
		const dateFin = dayjs(state[max_month_key]);
		return dateIni.isBefore(dateFin);
	});
};

const phone_regex = validators.helpers.regex(/^[0-9()-]+$/);
// const phone_regex = validators.helpers.regex(/^(\+\d{1,2}\s?)?1?\-?\.?\s?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/g)

const alpha_regex = validators.helpers.regex(/^[a-zA-ZÀ-ÿ\s\u00f1\u00d1]+$/);

export const alpha = validators.helpers.withMessage(
	"Solo se admiten letras.",
	alpha_regex
);

export const phone = validators.helpers.withMessage(
	"Debes colocar un numero de teléfono valido.",
	phone_regex
);

export const cedula_valid = string()
	.required()
	.matches(/^[0-9]+$/, "Solo se admiten números.")
	.min(7, "Debe tener por lo menos 7 caracteres.")
	.max(10, "El máximo es de 10 caracteres.");

export const phone_valid = string()
	.required()
	.matches(/^[0-9()-]+$/, "Debes colocar un numero de teléfono valido.");

export const alphas = string()
	.required()
	.matches(/^[a-zA-ZÀ-ÿ\s\u00f1\u00d1]+$/, "Solo se admiten letras.");

export const only_letters = string()
	.required()
	.matches(/^[a-zA-Z\s]+$/, "Solo se admiten letras.");

export const checkMinMaxHours = withI18nMessage(minMaxHours, {
	withArguments: true,
});

export const checkMinMaxMonths = withI18nMessage(minMaxMonths, {
	withArguments: true,
});
