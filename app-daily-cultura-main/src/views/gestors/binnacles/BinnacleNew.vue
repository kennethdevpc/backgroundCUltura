<script setup lang="ts">
import permissions from "@/permissions"

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core'

// Importing Store
import { storeToRefs } from 'pinia'
import { binnacleManagerStore } from '@/stores/binnacle_manager'

// Importing Options Store
import { useSelectStore } from '@/stores/selects'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Management from '@/components/base/Management.vue'
import Textarea from '@/components/base/Textarea.vue'

import services from "@/services/binnacles_managers.service";
import servicesConsecutive from '@/services/generals.service'

import { scroll_top } from '@/utils/scroll';
import mixins from '@/mixins'
import { loading } from '@/utils/loading'
import alerts from '@/utils/alerts'
import FormHeader from "@/components/base/FormHeader.vue"

// Extracting Store Data
const binnacle_store = binnacleManagerStore()
const { form, get_form_rules_computed: form_rules, form_options } = storeToRefs(binnacle_store);

// Extracting Services
const binnacle_services = services()
const consecutive_services = servicesConsecutive()

const router = useRouter();
const route = useRoute();

const files = reactive({
    I: [],
    II: []
})

const beneficiariesForo = [
    {
        value: "aforo",
        label: "Aforo"
    },
    // {
    //     value: "beneficiaries",
    //     label: "Beneficiarios"
    // }
];

// Extracting Store Data
const select_store = useSelectStore();
const { options } = storeToRefs(select_store);

const binnacles_computed = computed(() => {
    return options.value.binnacles.filter((binnacle) => binnacle.value == 'AC')
});

const editing = computed(() => {
    return (route.params.id) ? true : false
})

const fetch_consecutive = async () => {
    return await consecutive_services.getConsecutive('binnacles', 'JP').then(() => {
        form.value.consecutive = consecutive_services.data.value
    })
}

const fetch_binnacle = async () => {
    return await binnacle_services.getOne(route.params.id)
}

const instance = reactive({
    status: '',
    reject_message: ''
})

onBeforeMount(async () => {
    // Find only binnacle by id
    if (editing.value) {
        binnacle_store.$reset()
        v$.value.$reset()
        await fetch_binnacle().catch(() => {
            mixins.not_found_by_id()
        })

        const { beneficiaries, created_at, created_by, deleted_at, id, pec_id, pedagogical_id, reject_message, status, updated_at, user_method_support_id, user_review_instructor_leader_id, user_review_manager_cultural_id, user_review_support_follow_id, ...rest_binnacle } = binnacle_services.data.one

        binnacle_store.$patch((state) => {
            Object.assign(state.form, {
                ...rest_binnacle,
                // aggregates: {
                //     beneficiaries: beneficiaries.map(({ document_number, full_name, id }) => ({
                //         id,
                //         full_name,
                //         nuip: document_number,
                //     }))
                // }
            })
        })

        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        binnacle_store.$reset()
        v$.value.$reset()
        await fetch_consecutive()
    }
});
const disabledButton = ref(false);
// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        disabledButton.value = true;
        if (editing.value) {
            await binnacle_services.update(binnacle_services.data.one.id, binnacle_store.get_transpiled_data, 'gestor').finally(() => {
                disabledButton.value = false;
            });
        }
        else {
            await fetch_consecutive().finally(() => {
                binnacle_services.create(binnacle_store.get_transpiled_data, 'gestor').then(async (response) => {
                    if (response.data.success) {
                        files.I = []
                        files.II = []
                        binnacle_store.$reset()
                        v$.value.$reset()
                        scroll_top()
                        await fetch_consecutive()
                    }
                }).finally(() => {
                    disabledButton.value = false;
                });
            })
        }
    }
    else {
        alerts.validation()
    }
}

// watch(form.value, () => {
//     if (form.value.binnacle_id === 'AC') {
//         form.value.beneficiaries_capacity = 'aforo';
//     }
// });

const addDevActivityImg = (err, val) => {
    if (err) return
    const { file } = val
    form.value.development_activity_image = file;
}

const addParticipationImg = (err, val) => {
    if (err) return
    const { file } = val
    form.value.evidence_participation_image = file;
}

const addAforoFile = (err, val) => {
    if (err) return
    const { file } = val
    form.value.aforo_file = file;
}

const removeDevActivityImg = () => {
    form.value.development_activity_image = null;
}

const removeParticipationImg = () => {
    form.value.evidence_participation_image = null;
}

const removeAforoFile = () => {
    form.value.aforo_file = null;
}

// const beneficiaries_headers = [
//     {
//         text: 'ID',
//         value: 'id',
//     },
//     {
//         text: 'NOMBRE',
//         value: 'full_name',
//     },
//     {
//         text: 'CÉDULA',
//         value: 'nuip',
//     }
// ]
</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
        {{ (editing) ? `Edición de Activación cultural #${binnacle_services.data.one.id}` : `Activación cultural` }}
    </FormHeader>
    <Management class="mt-5" v-if="(editing) ? permissions.binnaclesManagers.management() : false"
        @send="(payload) => mixins.methods.send_management('binnacles', binnacle_services.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.binnaclesManagers.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                <section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
                    <!-- First Part -->
                    <div class="w-full">
                        <BaseSelect label="BITÁCORA" tooltip="Ingrese la bitácora" name="binnacle_id"
                            v-model="form.binnacle_id" :options="binnacles_computed" :validator="v$" />
                    </div>
                    <!-- <template
                                    :class="(form.binnacle_id === 'EC' || form.binnacle_id === 'TP') ? 'grid-cols-2' : 'grid-cols-1'"
                                    class="grid gap-3">
            
                                     <div class="w-full intro-x">
                                        <BaseSelect v-if="form.binnacle_id === 'EC' || form.binnacle_id === 'TP'"
                                            label="BENEFICIARIOS O AFORO" tooltip="Beneficiarios o Aforo"
                                            name="beneficiaries_capacity" v-model="form.beneficiaries_capacity"
                                            :options="beneficiariesForo" :validator="v$" />
                                    </div>
                                </template> -->

                    <BaseInput type="text" label="NOMBRE DE ACTIVIDAD" tooltip="Ingrese el nombre de la actividad"
                        placeholder="Nombre de la actividad" name="activity_name" v-model="form.activity_name"
                        :validator="v$" />

                    <BaseInput type="date" label="FECHA DE ACTIVIDAD" tooltip="Ingrese la fecha de la actividad"
                        placeholder="Fecha de la actividad" name="activity_date" v-model="form.activity_date"
                        :validator="v$" />

                    <BaseInput type="time" label="HORA INICIO" tooltip="Ingrese la hora de inicio" placeholder="Hora inicio"
                        name="start_time" v-model="form.start_time" :validator="v$" />

                    <BaseInput type="time" label="HORA FINAL" tooltip="Ingrese la hora final" placeholder="Hora final"
                        name="final_hour" v-model="form.final_hour" :validator="v$" />

                    <BaseInput type="text" label="LUGAR" tooltip="Ingrese el lugar" placeholder="Lugar" name="place"
                        v-model="form.place" :validator="v$" />

                </section>

                <section class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-3 gap-6 justify-evenly mb-8">
                    <!-- Options Data Overload -->
                    <BaseSelect label="NAC" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                        v-model="form.nac_id" :options="options.nacs || null" :validator="v$" />

                    <!-- Second Part -->
                    <!-- <BaseRadio label="Modalidad de activación" tooltip="Modalidad de activación" name="activation_mode"
                                    v-model="form.activation_mode" :options="form_options.activation_mode" :validator="v$" /> -->

                    <BaseSelect label="EXPERTICIA" tooltip="Ingrese la Experticia" placeholder="Seleccione"
                        name="expertise_id" v-model="form.expertise_id" :options="options.expertises || null"
                        :validator="v$" />

                    <BaseSelect label="DERECHO CULTURAL" tooltip="Ingrese el Derecho Cultural" placeholder="Seleccione"
                        name="cultural_right_id" v-model="form.cultural_right_id" :options="options.cultural_rights || null"
                        :validator="v$" />

                    <BaseInput type="text" label="OBJETIVO VIVENCIAL" tooltip="Ingrese objetivo vivencial"
                        placeholder="Objetivo vivencial..." name="experiential_objective"
                        v-model="form.experiential_objective" :validator="v$" />

                    <BaseSelect label="LINEAMIENTOS" tooltip="Ingrese los lineamientos" placeholder="Seleccione"
                        name="lineament_id" v-model="form.lineament_id" :options="options.lineaments || null"
                        :validator="v$" />

                    <BaseSelect label="ORIENTACIONES" tooltip="Ingrese las orientaciones" placeholder="Seleccione"
                        name="orientation_id" v-model="form.orientation_id" :options="options.orientations || null"
                        :validator="v$" />

                    <!-- Third Part -->
                    <BaseRadio label="¿SE CUMPLIÓ EL OBJETIVO VIVENCIAL DURANTE LA JORNADA DE ACTIVACIÓN CULTURAL?"
                        tooltip="Objetivo" name="goals_met" v-model="form.goals_met" :options="form_options.goals_met"
                        :validator="v$" />
                </section>

                <section class="grid grid-cols-1 gap-6 mb-8">
                    <Textarea rows="5" label="EXPLIQUE EL ¿POR QUE?" tooltip="" placeholder="Porque..."
                        name="explain_goals_met" v-model="form.explain_goals_met" :validator="v$" />

                    <Textarea rows="5" label="INICIO" tooltip="" placeholder="Inicio..." name="start_activity"
                        v-model="form.start_activity" :validator="v$" />

                    <Textarea rows="5" label="DESARROLLO" tooltip="" placeholder="Desarrollo..." name="activity_development"
                        v-model="form.activity_development" :validator="v$" />

                    <Textarea rows="5" label="FINAL (REFLEXIÓN DE LA JORNADA DE EXPERIMENTACIÓN)" tooltip=""
                        placeholder="Reflexión de la jornada de experimentación..." name="end_of_activity"
                        v-model="form.end_of_activity" :validator="v$" />

                    <Textarea rows="5" label="OBSERVACIONES" tooltip="" placeholder="Observaciones..."
                        name="observations_activity" v-model="form.observations_activity" :validator="v$" />
                </section>
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <BaseFilepond label="DESARROLLO DE LA JORNADA DE PACTO *" tooltip="Arrastra una imagen"
                        name="development_activity_image" ref="development_activity_image_ref"
                        :to_edit="binnacle_services.data.one.development_activity_image"
                        v-model="form.development_activity_image" @addfile="addDevActivityImg"
                        @removefile="removeDevActivityImg" :files="files.I" :validator="v$" />

                    <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra una imagen"
                        name="evidence_participation_image" ref="evidence_participation_image"
                        :to_edit="binnacle_services.data.one.evidence_participation_image"
                        v-model="form.evidence_participation_image" @addfile="addParticipationImg"
                        @removefile="removeParticipationImg" :files="files.II" :validator="v$" />
                </section>

                <!-- AFORO -->
                <!-- v-if="form.beneficiaries_capacity == 'aforo' || form.binnacle_id == 'AC'" -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6 mb-8">
                    <BaseFilepond label="DOCUMENTO DEL AFORO *" tooltip="Arrastra un PDF" name="development_activity_image"
                        ref="aforo_file" :to_edit="binnacle_services.data.one.aforo_file" v-model="form.aforo_file"
                        @addfile="addAforoFile" @removefile="removeAforoFile" :accept_only_pdf="true" :files="files.II"
                        :validator="v$" />

                    <BaseInput type="number" label="CAPACIDAD (NÚMERO DE ASISTENTES) *" tooltip="Ingrese la capacidad"
                        placeholder="0..." name="beneficiaries_capacity" v-model="form.beneficiaries_capacity"
                        :validator="v$" />
                </div>
                <div class="flex justify-end">
                    <button :disabled="disabledButton" class="btn btn-primary" type="submit">
                        {{ editing ? "Actualizar" : "Ingresar" }}
                    </button>
                </div>
            </form>
        </fieldset>
    </div>
</template>
