<script setup lang="ts">
import { useVuelidate } from '@vuelidate/core'
// Importing Store
import { storeToRefs } from 'pinia'
import { Poll } from '@/stores/poll'

// Importing Options Store
import { useSelectStore } from '@/stores/selects'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import alerts from '@/utils/alerts'
import pollServices from '@/services/polls.service'
import { scroll_top } from '@/utils/scroll'
// import * as relativeTime from 'dayjs/plugin/relativeTime'
import dayjs from 'dayjs'

// dayjs.extend(relativeTime)
const router = useRouter()

// Extracting Store Data
const poll = Poll()
const services = pollServices()
const { form, form_rules_computed: form_rules, form_options } = storeToRefs(poll)

// Extracting Store Data
const select_store = useSelectStore()
const { options } = storeToRefs(select_store)

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

const step = ref(1)

const next_step = () => {
    if (step.value < 3) {
        step.value++
    }
};

const prev_step = () => {
    if (step.value > 0) {
        step.value--
    }
};

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
    if (form.value.artistic_group === "1") {
        form_rules.value.artistic_group_name = null;
        form_rules.value.role_artistic_group = null;
    }
});

const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        services.create(form.value).then((response) => {
            if (response.data.response) {
                poll.$reset()
                v$.value.$reset()
                scroll_top()
                step.value = 1
                router.push({ name: 'polls.index', hash: '#after_created' })
            }
        });
    }
    else {
        alerts.validation()
    }
}

const on_next = async (step: number) => {
    await v$.value.$validate()
    if (step == 1) {

        const valid = computed(() => (
            form.value.gender != '' &&
            form.value.birth_date != '' &&
            form.value.age != '' &&
            form.value.marital_state != '' &&
            form.value.stratum != '' &&
            form.value.neighborhood_id != '' &&
            (form.value.neighborhood_id == '' ? form.value.other_neighborhoods != '' : true) &&
            form.value.phone != '' &&
            form.value.email != '' &&
            form.value.number_children != '' &&
            // form.value.dependents != '' &&
            form.value.relationship_head_household != '' &&
            form.value.ethnicity != '' &&
            form.value.victim_armed_conflict != '' &&
            (form.value.victim_armed_conflict == '1' ? form.value.single_registry_victims != '' : true) &&
            form.value.study != '' &&
            //form.value.study == '1'
             form.value.educational_level != ''
        ))

     
        if (valid.value) {
            v$.value.$reset()
            next_step()
        }
        else {
            alerts.validation()
        }
    }
    if (step == 2) {
        const valid = computed(() => (
            form.value.medical_service != '' &&
            form.value.entity_name_id != '' &&
            form.value.health_condition != '' &&
            form.value.suffers_disease != '' &&
            (
                form.value.suffers_disease == '0' ||
                (
                    (form.value.suffers_disease == '1' ? form.value.type_disease != '' : true) &&

                    form.value.type_disease != '' &&
                    (form.value.type_disease == 'O' ? form.value.other_disease_type != '' : true)
                )
            ) &&
            form.value.disability != '' &&
            (form.value.disability == '0' ||
                (form.value.disability == '1' ? form.value.disability_type != '' : true)) &&
            form.value.takes_medication != '' &&
            form.value.assessed_disability != '' &&
            form.value.receives_therapy != ''
        ))

        if (valid.value) {
            v$.value.$reset()
            next_step()
        }
        else {
            alerts.validation()
        }
    }
    if (step == 3) {
        const valid = computed(() => (
            form.value.expertises != '' &&
            form.value.artistic_experience != '' &&
            form.value.artistic_group != '' &&
            (form.value.artistic_group == '1' ? form.value.artistic_group_name != '' : true) &&
            (form.value.artistic_group == '1' ? form.value.role_artistic_group != '' : true)
        ))
        if (valid.value) {
            v$.value.$reset()
            onSubmit()
        }
        else {
            alerts.custom_validation('Por favor verifique que todos los campos estén llenos.')
        }
    }
}

const steps_classes = "relative before:hidden before:lg:block before:absolute before:rounded-full before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center"

onMounted(async () => {
    poll.$reset()
    v$.value.$reset()
});

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

const birth_computed = computed(() => {
    return form.value.birth_date
})

function calculateAge(date: string) {
    const formattedDate = dayjs(date)
    const birthTimeStamp = new Date(formattedDate.year(), formattedDate.month(), formattedDate.date()).getTime()
    const currentDate = new Date().getTime()
    const difference = currentDate - birthTimeStamp
    const age = Math.floor(difference / 31557600000)

    return age
}

watch(birth_computed, () => {
    form.value.age = calculateAge(form.value.birth_date).toString()
})
</script>

<template>
    <div class="intro-y flex justify-between items-start md:items-end mt-5">
        <div class="intro-y flex flex-col items-start gap-1">
            <BaseBackButton />
            <h2 class="text-lg font-medium mr-auto">
                {{ `Encuesta` }}
            </h2>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5 flex flex-col">
        <div>
            <div :class="['before:w-[65%]', steps_classes]">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <button
                        :class="(step == 1) ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9' : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'">
                        1
                    </button>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">
                        Datos generales
                    </div>
                </div>
                <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                    <button
                        :class="(step == 2) ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9' : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'">
                        2
                    </button>
                    <div class="
				lg:w-32
				text-base
				lg:mt-3
				ml-3
				lg:mx-auto
				text-slate-600
			  ">
                        Estado que pertenece
                    </div>
                </div>
                <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                    <button
                        :class="(step == 3) ? 'inline-block rounded-full bg-blue-800 text-white leading-normal uppercase shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9' : 'inline-block rounded-full bg-gray-200 text-white leading-normal uppercase shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-9 h-9'">
                        3
                    </button>
                    <div class="
				lg:w-32
				text-base
				lg:mt-3
				ml-3
				lg:mx-auto
				text-slate-600
			  ">
                        Grupos
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5">
        <div v-if="(step == 1)" class="globaltransition">
            <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ">
                <div>
                    <BaseSelect label="Genero *" tooltip="" placeholder="Seleccione" name="gender" v-model="form.gender"
                        :options="options.genders" :validator="v$" />
                    <!-- <BaseSelect label="Genero *" tooltip="" placeholder="Seleccione" name="genders"
                    v-model="form.genders" :options="options.genders || null" /> -->
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
                        v-model="form.marital_state" :options="options.marital_status" :validator="v$" />

                </div>
                <div>
                    <BaseSelect label="Estrato *" tooltip="Estrato *" placeholder="Seleccione" name="stratum"
                        v-model="form.stratum" :options="options.stratums" :validator="v$" />
                </div>
                <div :class="form.neighborhood_id == '357' ? 'grid grid-flow-dense grid-cols-3' : ''">
                    <BaseSelect :class="form.neighborhood_id == '357' ? 'col-span-1 mr-4' : ''" label="Barrio *"
                        tooltip="Barrio *" placeholder="Seleccione" name="neighborhood_id"
                        v-model="form.neighborhood_id" :options="options.neighborhoods" :validator="v$" />

                    <BaseInput v-if="form.neighborhood_id == '357'" type="text" label="Otro ¿Cual?"
                        :class="form.neighborhood_id == '357' ? 'col-span-2' : ''" tooltip="Otro ¿Cual?" placeholder=""
                        name="other_neighborhoods" v-model="form.other_neighborhoods" :validator="v$" />
                </div>

                <div>
                    <BaseInput type="text" label="Numero de teléfono" tooltip="Numero de teléfono"
                        placeholder="" name="phone" v-model="form.phone" :validator="v$" />
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
                        :options="options.relationship_households" :validator="v$" />
                </div>
                <div>
                    <BaseSelect label="¿Con qué tipo de etnia se representa?" tooltip="" placeholder="Seleccione"
                        name="ethnicity" v-model="form.ethnicity" :options="options.ethnicities" :validator="v$" />
                </div>
            </div>

            <BaseRadio class="mt-4" label="¿Víctima de conflicto armado?" tooltip="" name="victim_armed_conflict"
                v-model="form.victim_armed_conflict" :options="form_options.victim_armed_conflict" :validator="v$" />

            <div v-if="form.victim_armed_conflict === '1'"
                class="intro-y mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <BaseSelect label="Registro único de víctimas" tooltip="" placeholder="Seleccione"
                    name="single_registry_victims" v-model="form.single_registry_victims"
                    :options="options.single_registry_victims" :validator="v$" />
            </div>

            <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ">
                <div>
                    <BaseSelect label="Nivel educativo alcanzado" placeholder="Seleccione" tooltip=""
                        name="educational_level" v-model="form.educational_level" :options="options.educational_levels"
                        :validator="v$" />
                </div>
                <div>
                    <BaseRadio label="¿Estudia actualmente?" tooltip="" name="study" v-model="form.study"
                        :options="form_options.study" :validator="v$" />
                </div>
            </div>

        </div>

        <div v-if="(step == 2)" class="globaltransition">
            <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ">
                <div>
                    <BaseSelect label="¿A que régimen de salud colombiano pertenece?" placeholder="Seleccione"
                        tooltip="" name="medical_service" v-model="form.medical_service"
                        :options="options.medical_services" :validator="v$" />
                </div>
                <div :class="(form.entity_name_id == '16') ? 'grid-cols-2' : 'grid_cols-1'"
                    class="grid gap-3">
                    <div class="w-full">
                        <BaseSelect label="EPS *" placeholder="Seleccione"
                            name="entity_name_id" v-model="form.entity_name_id"
                            :options="options.entity_names || null" :validator="v$" />
                    </div>
                    <div class="w-full intro-x" v-if="form.entity_name_id == '16'">
                        <BaseInput type="text" label="OTRO EPS *" tooltip="Ingrese el otro EPS" placeholder="EPS..."
                            name="other_entity_name" v-model="form.other_entity_name"
                            :validator="v$" />
                    </div>
                </div>
                <div>
                    <BaseSelect label="Estado de salud" placeholder="Seleccione" tooltip="" name="health_condition"
                        v-model="form.health_condition" :options="options.health_conditions" :validator="v$" />
                </div>

            </div>

            <div>
                <BaseRadio class="mt-6" label="¿Padece alguna enfermedad?" tooltip="" name="suffers_disease"
                    v-model="form.suffers_disease" :options="form_options.suffers_disease" :validator="v$" />
            </div>
            <div v-if="form.suffers_disease === '1'" class="intro-y mt-6 grid grid-cols-2">
                <BaseSelect label="¿Tipo de enfermedad?" placeholder="Seleccione" tooltip="" name="type_disease"
                    v-model="form.type_disease" :options="options.disease_types" :validator="v$" />

                <div v-if="form.type_disease === 'O'" class="intro-x ml-6">
                    <BaseInput type="text" label="Otro ¿Cual?" placeholder="" name="other_disease_type"
                        v-model="form.other_disease_type" :validator="v$" />
                </div>
            </div>

            <BaseRadio class="mt-6" label="¿Presenta alguna discapacidad?" tooltip="" name="disability"
                v-model="form.disability" :options="form_options.disability" :validator="v$" />

            <div v-if="form.disability === '1'" class="intro-y mt-6 grid grid-cols-2">
                <BaseSelect label="¿Indique la discapacidad que presenta?" placeholder="Seleccione" tooltip=""
                    name="disability_type" v-model="form.disability_type" :options="options.disability_types"
                    :validator="v$" />
                <div v-if="form.disability_type === 'O'" class="intro-x ml-6">
                    <BaseInput type="text" label="Otro ¿Cual?" placeholder="" name="other_disability_type"
                        v-model="form.other_disability_type" :validator="v$" />
                </div>
            </div>

            <div class="grid grid-cols-3 mt-6">
                <div>
                    <BaseRadio label="¿Toma medicamentos?" tooltip="" name="takes_medication"
                        v-model="form.takes_medication" :options="form_options.takes_medication" :validator="v$" />
                </div>

                <div>
                    <BaseRadio label="¿Discapacidad ha sido valorada?" tooltip="" name="assessed_disability"
                        v-model="form.assessed_disability" :options="form_options.assessed_disability"
                        :validator="v$" />
                </div>
                <div>
                    <BaseRadio label="¿Recibe terapia de la discapacidad?" tooltip="" name="receives_therapy"
                        v-model="form.receives_therapy" :options="form_options.receives_therapy" :validator="v$" />
                </div>
            </div>

        </div>

        <div v-if="(step == 3)" class="globaltransition">
            <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 ">
                <div>
                    <BaseSelect label="Arte en el que se desempeña" placeholder="Seleccione" tooltip=""
                        name="expertises" v-model="form.expertises" :options="options.expertises" :validator="v$" />
                </div>
                <div>
                    <BaseSelect label="Experiencia en el sector artístico (Años)" placeholder="Seleccione" tooltip=""
                        name="artistic_experience" v-model="form.artistic_experience"
                        :options="form_options.artistic_experience" :validator="v$" />
                </div>
                <div>
                    <BaseRadio label="¿Pertenece a algún grupo artístico?" tooltip="" name="artistic_group"
                        v-model="form.artistic_group" :options="form_options.artistic_group" :validator="v$" />
                </div>
                <div v-if="form.artistic_group === '1'" class="intro-x grid grid-cols-2">
                    <BaseInput type="text" label="Nombre del grupo" tooltip="Nombre del grupo" placeholder=""
                        name="artistic_group_name" v-model="form.artistic_group_name" :validator="v$" />
                </div>
                <div v-if="form.artistic_group === '1'" class="intro-x grid grid-cols-2">
                    <BaseInput type="text" label="Rol en el grupo" tooltip="Rol en el grupo" placeholder=""
                        name="role_artistic_group" v-model="form.role_artistic_group" :validator="v$" />
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box p-5 mt-5">
        <div v-if="step == 1" class="flex justify-center py-5 sm:flex-row items-center">
            <div class="flex justify-center gap-2">
                <button type="button" @click="on_next(1)" class="btn btn-pending w-24">Siguiente</button>
            </div>
        </div>
        <div v-if="step == 2" class="flex justify-center py-5 sm:flex-row items-center">
            <div class="flex justify-center gap-2">
                <button type="button" @click="prev_step" class="btn btn-second w-24">Anterior</button>
                <button type="button" @click="on_next(2)" class="btn btn-pending w-24">Siguiente</button>
            </div>
        </div>
        <div class="flex justify-center sm:flex-row items-center">
            <div v-if="step == 3" class="flex justify-center sm:flex-row items-center gap-2">
                <button type="button" @click="prev_step" class="btn btn-second w-24">Anterior</button>
                <button type="submit" class="btn btn-primary w-24" @click="on_next(3)">Ingresar</button>
                <!-- <button type="button" @click="cancelar" class="btn btn-danger bg-red-600 w-24">Cancelar</button> -->
            </div>
        </div>
    </div>
</template>