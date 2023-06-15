<script setup lang="ts">
import { useVuelidate } from '@vuelidate/core'

// Importing Store
import { storeToRefs } from 'pinia'
import { Poll } from '@/stores/poll'

// Importing Options Store
import { useSelectStore } from '@/stores/selects'
import Swal from "sweetalert2";
import { required } from '@/utils/validations'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import pollsServices from '@/services/polls.service';
import alerts from '@/utils/alerts';
import mixins from '@/mixins';
import dayjs from 'dayjs'

// Extracting Store Data
const poll = Poll()
const services = pollsServices()
const { form, form_rules_computed: form_rules, form_options } = storeToRefs(poll)

// Extracting Store Data
const select_store = useSelectStore()
const { options } = storeToRefs(select_store)

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true });

const router = useRouter()
const route = useRoute();

const fetch_poll = async () => {
    return services.getOne(route.params.id).then(() => {
        poll.fillInputs(services.data.one)
    })
}

onMounted(async () => {
    poll.$reset()
    v$.value.$validate()
    await fetch_poll().catch(() => {
        mixins.not_found_by_id()
    })
});

watch(form.value, () => {
    if (form.value.victim_armed_conflict === "0") {
        form.value.single_registry_victims = null;
    }
    if (form.value.study === "0") {
        form.value.educational_level = null;
    }
    if (form.value.neighborhood_id != '357') {
        form.value.other_neighborhoods = null;
    }
})

const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        services.update(route.params.id, form.value).then((response) => {
            if (response.data.items) {
                poll.$reset()
                v$.value.$validate()
            }
        })
    }
    else {
        alerts.validation()
    }
}

const max_birth = computed(() => {
    const date = dayjs()
    const mm = date.month() + 1
    const dd = date.date()
    const yy = date.year()

    const yyCalc = yy - 18
    const mmCalc = () => ((mm < 10) ? `0${mm}` : mm)
    const ddCalcOne = () => ((dd > 2) ? dd - 1 : dd)
    const ddCalc = () => ((ddCalcOne() < 10) ? `0${ddCalcOne()}` : ddCalcOne())

    return `${yyCalc}-${mmCalc()}-${ddCalc()}`
})

function calculateAge(date: string) {
    const formattedDate = dayjs(date)
    const birthTimeStamp = new Date(formattedDate.year(), formattedDate.month(), formattedDate.date()).getTime()
    const currentDate = new Date().getTime()
    const difference = currentDate - birthTimeStamp
    const age = Math.floor(difference / 31557600000)

    return age
}

const cancel = () => {
    router.push({ name: 'polls.index' })
}
</script>

<template>
    <div class="intro-y flex justify-between items-start md:items-end mt-5">
        <div class="intro-y flex flex-col items-start gap-1">
            <BaseBackButton />
            <h2 class="text-lg font-medium mr-auto">
                {{ `Edición de Encuesta` }}
            </h2>
        </div>
    </div>
    <form @submit.prevent="onSubmit">
        <div class="intro-y box p-5 mt-5">
            <div class="globaltransition">
                <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ">
                    <div>
                        <BaseSelect label="Genero *" tooltip="" placeholder="Seleccione" name="gender"
                            v-model="form.gender" :value="form.gender" :options="options.genders" :validator="v$"
                            required />
                    </div>
                    <div class="grid grid-cols-2 gap-3 justify-evenly">
                    <div>
                        <BaseInput type="date" label="Fecha de nacimiento *" tooltip="Fecha de nacimiento *"
                            placeholder="" name="birth_date" v-model="form.birth_date" :max="max_birth"
                            :validator="v$" />
                    </div>
                    <div>
                        <BaseInput type="number" min="0" label="Edad" tooltip="" placeholder="" name="age"
                            v-model="form.age" :validator="v$" disabled />
                    </div>
                </div>
                    <div>
                        <BaseSelect label="Estado civil *" tooltip="" placeholder="Seleccione" name="marital_state"
                            v-model="form.marital_state" :options="options.marital_status" :validator="v$" required />

                    </div>
                    <div>
                        <BaseSelect label="Estrato *" tooltip="Estrato *" placeholder="Seleccione" name="stratum"
                            v-model="form.stratum" :options="options.stratums" :validator="v$" required />
                    </div>
                    <div :class="form.neighborhood_id == '357' ? 'grid grid-flow-dense grid-cols-3' : ''">
                        <BaseSelect :class="form.neighborhood_id == '357' ? 'col-span-1 mr-4' : ''" label="Barrio *"
                            tooltip="Barrio *" placeholder="Seleccione" name="neighborhood_id"
                            v-model="form.neighborhood_id" :options="options.neighborhoods" :validator="v$" />

                        <BaseInput v-if="form.neighborhood_id == '357'" type="text" label="Otro ¿Cual?"
                            :class="form.neighborhood_id == '357' ? 'col-span-2' : ''" tooltip="Otro ¿Cual?"
                            placeholder="" name="other_neighborhoods" v-model="form.other_neighborhoods"
                            :validator="v$" />
                    </div>
                    <div>
                        <BaseInput type="text" label="Numero de teléfono" tooltip="Numero de teléfono" placeholder=""
                            name="phone" v-model="form.phone" :validator="v$" />
                    </div>
                    <div>
                        <BaseInput type="email" label="Correo electrónico *" tooltip="Correo electrónico" placeholder=""
                            name="email" v-model="form.email" :validator="v$" />
                    </div>
                    <div>
                        <BaseInput type="number" min="0" max="99" label="Numero de hijos" tooltip="Numero de hijos"
                            placeholder="" name="number_children" v-model="form.number_children" :validator="v$" />
                    </div>
                    <div>
                        <BaseInput type="number" min="0" max="99" label="Personas a cargo" tooltip="Personas a cargo"
                            placeholder="" name="dependents" v-model="form.dependents" :validator="v$" />
                    </div>
                    <div>
                        <BaseSelect label="Parentesco con jefe de hogar" tooltip="" placeholder="Seleccione"
                            name="relationship_head_household" v-model="form.relationship_head_household"
                            :options="options.relationship_households" :validator="v$" required />

                    </div>
                    <div>
                        <BaseSelect label="¿Con qué tipo de etnia se representa?" tooltip="" placeholder="Seleccione"
                            name="ethnicity" v-model="form.ethnicity" :options="options.ethnicities" :validator="v$"
                            required />
                    </div>
                    <div>
                        <BaseRadio label="¿Víctima de conflicto armado?" tooltip="" name="victim_armed_conflict"
                            v-model="form.victim_armed_conflict" :options="form_options.victim_armed_conflict"
                            :validator="v$" required />
                    </div>

                    <div v-if="form.victim_armed_conflict === '1'"
                        class="intro-x grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4">
                        <BaseSelect label="Registro único de víctimas" tooltip="" placeholder="Seleccione"
                            name="single_registry_victims" v-model="form.single_registry_victims"
                            :options="options.single_registry_victims" :validator="v$" />
                    </div>

                    <div class="intro-x grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 intro-y">
                        <BaseSelect label="Nivel educativo alcanzado" placeholder="Seleccione" tooltip=""
                            name="educational_level" v-model="form.educational_level"
                            :options="options.educational_levels" :validator="v$" />
                    </div>

                    <div>
                        <BaseRadio label="¿Estudia actualmente?" tooltip="" name="study" v-model="form.study"
                            :options="form_options.study" :validator="v$" required />
                    </div>

                    <div>
                        <BaseSelect label="¿A que régimen de salud colombiano pertenece?" placeholder="Seleccione"
                            tooltip="" name="medical_service" v-model="form.medical_service"
                            :options="options.medical_services" :validator="v$" required />
                    </div>
                    <div>
                        <BaseSelect label="Nombre de EPS" placeholder="Seleccione" tooltip="" name="entity_name_id"
                            v-model="form.entity_name_id" :options="options.entity_names" :validator="v$" required />
                    </div>
                    <div>
                        <BaseSelect label="Estado de salud" placeholder="Seleccione" tooltip="" name="health_condition"
                            v-model="form.health_condition" :options="options.health_conditions" :validator="v$"
                            required />
                    </div>
                    <div>
                        <BaseRadio label="¿Toma medicamentos?" tooltip="" name="takes_medication"
                            v-model="form.takes_medication" :options="form_options.takes_medication" :validator="v$"
                            required />
                    </div>
                    <div>
                        <BaseRadio label="¿Padece alguna enfermedad?" tooltip="" name="suffers_disease"
                            v-model="form.suffers_disease" :options="form_options.suffers_disease" :validator="v$"
                            required />
                    </div>
                    <div v-if="form.suffers_disease === '1'" class="intro-x  grid grid-cols-1">
                        <BaseSelect label="¿Tipo de enfermedad?" placeholder="Seleccione" tooltip="" name="type_disease"
                            v-model="form.type_disease" :options="options.disease_types" :validator="v$" />
                    </div>
                    <div v-if="form.type_disease === 'O' && form.suffers_disease === '1'"
                        class="intro-x grid grid-cols-1">
                        <BaseInput type="text" label="Otro ¿Cual?" placeholder="" name="other_disease_type"
                            v-model="form.other_disease_type" :validator="v$" />
                    </div>
                    <div>
                        <BaseRadio label="¿Presenta alguna discapacidad?" tooltip="" name="disability"
                            v-model="form.disability" :options="form_options.disability" :validator="v$" required />
                    </div>

                    <div v-if="form.disability === '1'" class="intro-x  grid grid-cols-1">
                        <BaseSelect label="¿Indique la discapacidad que presenta?" placeholder="Seleccione" tooltip=""
                            name="disability_type" v-model="form.disability_type" :options="options.disability_types"
                            :validator="v$" />
                    </div>
                    <div v-if="form.disability_type === 'O' && form.disability === '1'"
                        class="intro-x grid grid-cols-1">
                        <BaseInput type="text" label="Otro ¿Cual?" placeholder="" name="other_disability_type"
                                    v-model="form.other_disability_type" :validator="v$" />
                    </div>

                    <div v-if="form.disability === '1'" class="intro-x">
                        <BaseRadio label="¿Discapacidad ha sido valorada?" tooltip="" name="receives_therapy"
                            v-model="form.receives_therapy" :options="form_options.receives_therapy" :validator="v$"
                            required />
                    </div>

                    <div>
                        <BaseSelect label="Arte en el que se desempeña" placeholder="Seleccione" tooltip=""
                            name="expertises" v-model="form.expertises" :options="options.expertises" :validator="v$"
                            required />
                    </div>
                    <div>
                        <BaseSelect label="Experiencia en el sector artístico (Años)" placeholder="Seleccione" tooltip=""
                            name="artistic_experience" v-model="form.artistic_experience"
                            :options="form_options.artistic_experience" :validator="v$" required />

                    </div>
                    <div>
                        <BaseRadio label="¿Pertenece a algún grupo artístico?" tooltip="" name="artistic_group"
                            v-model="form.artistic_group" :options="form_options.artistic_group" :validator="v$"
                            required />
                    </div>
                    <div v-if="form.artistic_group === '1'" class="intro-x grid grid-cols-1">
                        <BaseInput type="text" label="Nombre del grupo" tooltip="Nombre del grupo" placeholder=""
                            name="artistic_group_name" v-model="form.artistic_group_name" :validator="v$" />

                    </div>
                    <div v-if="form.artistic_group === '1'" class="intro-x grid grid-cols-1">
                        <BaseInput type="text" label="Rol en el grupo" tooltip="Rol en el grupo" placeholder=""
                            name="role_artistic_group" v-model="form.role_artistic_group" :validator="v$" />
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y box p-5 mt-5">
            <div class="flex justify-center sm:flex-row items-center">
                <div class="flex justify-center sm:flex-row items-center gap-2">
                    <button type="submit" class="btn btn-primary w-24">Actualizar</button>
                    <button type="button" @click="cancel" class="btn btn-danger bg-red-600 w-24">Cancelar</button>
                </div>
            </div>
        </div>
    </form>
</template>