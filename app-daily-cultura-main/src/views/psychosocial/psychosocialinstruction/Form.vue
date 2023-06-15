<script setup>
import services from "@/services/psychosocialinstruction.service"
import consecutive_services from '@/services/generals.service'

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core'
import { cedula_valid, phone_valid, only_letters } from '@/utils/validations'
import { string } from 'yup'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from '@/components/base/Aggregates.vue'
import BaseRadio from "@/components/base/Radio.vue";
// Store
import { usePsychosocialInstruction } from '@/stores/psychosocialinstruction'
import { useSelectStore } from "@/stores/selects"
import { storeToRefs } from "pinia"
import Management from "@/components/base/Management.vue"
import permissions from "@/permissions"
import mixins from "@/mixins"
import { loading } from "@/utils/loading"
import { scroll_top } from "@/utils/scroll"
import alerts from "@/utils/alerts"
import FormHeader from "@/components/base/FormHeader.vue"

// Extracting Store Data
const psychosocialInstructionStore = usePsychosocialInstruction()
const { form, get_computed_rules: form_rules } = storeToRefs(psychosocialInstructionStore)

// Extracting Select Data
const select_store = useSelectStore()
const { options: lists } = storeToRefs(select_store)
const { monitors, nacs, assistants } = lists.value

// Extracting Services
const psychosocialInstructionServices = services()
const consecutiveServices = consecutive_services()

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

// Images
const filesI = ref([])
const filesII = ref([])

// Images - First Input
const handleAddFirstFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.development_activity_image = {
        name: 'development_activity_image',
        file,
        filename,
    }
}
const handleRemoveFirstFile = () => {
    form.value.development_activity_image = null
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.evidence_participation_image = {
        name: 'evidence_participation_image',
        file,
        filename,
    }
}
const handleRemoveSecondFile = () => {
    form.value.evidence_participation_image = null
}

// Editing Logic
const route = useRoute()
const instance = reactive({
    consecutive: '',
    status: '',
    reject_message: '',
})

const fetchOne = async () => {
    return await psychosocialInstructionServices.getOne(route.params.id)
}

const fetch_consecutive = async () => {
    return await consecutiveServices.getConsecutive('psychosocial_instructions', 'IP').then((response) => {
        psychosocialInstructionStore.form.consecutive = consecutiveServices.data.value
        instance.consecutive = consecutiveServices.data.value
    })
}

const isEditing = computed(() => {
    return (route.params.id) ? true : false
})

onMounted(async () => {
    if (isEditing.value) {
        psychosocialInstructionStore.$reset()
        await fetchOne().catch(() => {
            mixins.not_found_by_id()
        })

        const { assistants, created_at, id, monitors, nac, consecutive, status, reject_message, ...rest } = psychosocialInstructionServices.data.one

        const _assistants = assistants.map((assistant) => {
            const { created_at, deleted_at, id, pivot, updated_at, ...rest_assistant } = assistant
            return {
                ...rest_assistant
            }
        })

        const _monitors = monitors.map(({ id }) => {
            const record = lists.value.monitors_table.find((monitor) => monitor.id == id)

            return {
                ...record
            }
        })

        psychosocialInstructionStore.$patch((state) => {
            Object.assign(state.form, {
                ...rest,
                consecutive,
                aggregates: {
                    assistants: _assistants,
                    monitors: _monitors,
                },
            })
        })

        instance.consecutive = consecutive
        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        psychosocialInstructionStore.$reset()
        await fetch_consecutive()
    }
})

//const storagePath = import.meta.env.VITE_BASE_URL

const assistants_headers = [
    {
        text: 'NOMBRE',
        value: 'assistant_name',
    },
    {
        text: 'NUMERO DE CÉDULA',
        value: 'assistant_document_number',
    },
    {
        text: 'CARGO',
        value: 'assistant_position',
    },
    {
        text: 'NAC',
        value: 'nac_id',
    },
    {
        text: 'TELÉFONO',
        value: 'assistant_phone',
    },
    {
        text: 'EMAIL',
        value: 'assistant_email',
    },
]

const assistants_schema = {
    fields: [
        {
            name: 'assistant_name',
            as: 'input',
            type: 'text',
            placeholder: 'NOMBRE',
            rules: only_letters,
        },
        {
            name: 'assistant_document_number',
            as: 'input',
            type: 'text',
            placeholder: 'NUMERO DE CÉDULA',
            rules: cedula_valid,
        },
        {
            name: 'assistant_position',
            as: 'input',
            type: 'text',
            placeholder: 'CARGO',
            rules: only_letters,
        },
        {
            name: 'nac_id',
            as: 'select',
            placeholder: 'NAC',
            rules: string().required(),
            children: lists.value.nacs.map((nac) => {
                return {
                    tag: 'option',
                    value: nac.value,
                    label: nac.label
                }
            })
        },
        {
            name: 'assistant_phone',
            as: 'input',
            type: 'tel',
            placeholder: 'TELÉFONO',
            rules: phone_valid,
        },
        {
            name: 'assistant_email',
            as: 'input',
            type: 'text',
            placeholder: 'EMAIL',
            rules: string().email(),
        },
    ]
}

const monitors_headers = [
    {
        text: 'ID',
        value: 'id',
    },
    {
        text: 'NOMBRE COMPLETO',
        value: 'monitor_fullname',
    },
    {
        text: 'NUMERO DE CÉDULA',
        value: 'document_number',
    },
]

// Submiting Form
const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        if (isEditing.value) {
            const res = await psychosocialInstructionServices.update(psychosocialInstructionServices.data.one.id, psychosocialInstructionStore.transpiled_data)
            if (res.status == 200) {
                psychosocialInstructionStore.$reset()
                v$.value.$reset()
                filesI.value = []
                filesII.value = []
                scroll_top()
            }
        }
        else {
            await fetch_consecutive().finally(() => {
                psychosocialInstructionServices.create(psychosocialInstructionStore.transpiled_data).then(async (response) => {
                    if (response.status == 200) {
                        psychosocialInstructionStore.$reset()
                        v$.value.$reset()
                        filesI.value = []
                        filesII.value = []
                        await fetch_consecutive()
                        scroll_top()
                    }
                })
            })
        }
    }
    else {
        alerts.validation()
    }
}
</script>

<template>
    <FormHeader
        :state="{ consecutive: instance.consecutive, status: instance.status, reject_message: instance.reject_message }">
        {{ (isEditing) ? `Edición de Instrucción Psicosocial #${psychosocialInstructionServices.data.one.id}` :
            'Instrucción Psicosocial' }}
    </FormHeader>
    <Management class="mt-5" v-if="(isEditing) ? permissions.psychosocialinstructions.management() : false"
        @send="(payload) => mixins.methods.send_management('psychosocial_instructions', psychosocialInstructionServices.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.psychosocialinstructions.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60">
                <section id="general_data"
                    class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
                    <div class="w-full">
                        <BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha"
                            name="activity_date" v-model="form.activity_date" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                            v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA INICIO *" tooltip="Ingrese la hora de inicio"
                            placeholder="Hora inicio" name="start_time" v-model="form.start_time" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA FINAL *" tooltip="Ingrese la hora final" placeholder="Hora final"
                            name="final_hour" :min="form.start_time" v-model="form.final_hour" :validator="v$" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="OBJETIVO DE LA JORNADA *" tooltip="Ingrese el objetivo de la jornada"
                            placeholder="Objetivo de la jornada" name="objective_day" v-model="form.objective_day"
                            :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="TEMAS A TRATAR *" tooltip="Ingrese los temas a tratar"
                            placeholder="Temas a tratar" name="themes_day" v-model="form.themes_day" :validator="v$"
                            rows="5" />
                    </div>

                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="DESARROLLO DE LOS TEMAS *" tooltip="Ingrese el desarrollo de los temas"
                            placeholder="Desarrollo de los temas" name="development_themes"
                            v-model="form.development_themes" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="CONCLUSIONES Y REFLEXIONES Y COMPROMISOS DE LA JORNADA *"
                            tooltip="Ingrese las conclusiones y reflexiones y compromisos de la jornada"
                            placeholder="Conclusiones y reflexiones y compromisos de la jornada"
                            name="conclusions_reflections_commitments" v-model="form.conclusions_reflections_commitments"
                            :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseRadio label="¿REPORTE ALERTAS PARA HACER SEGUIMIENTO? *" tooltip=""
                            name="report_followup_alerts" v-model="form.report_followup_alerts"
                            :options="lists.decisions || null" :validator="v$" />
                    </div>
                </section>
                <section id="images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
                        <BaseFilepond label="EVIDENCIA DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen o PDF"
                            name="development_activity_image" ref="development_activity_image_ref" :accept_pdf="true"
                            v-model="form.development_activity_image"
                            :to_edit="psychosocialInstructionServices.data.one.development_activity_image"
                            @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="filesI"
                            :validator="v$" />
                        <BaseFilepond label="EVIDENCIA DE ACTIVIDAD *" tooltip="Arrastra o selecciona una Imagen o PDF"
                            name="evidence_participation_image" ref="evidence_participation_image_ref" :accept_pdf="true"
                            v-model="form.evidence_participation_image"
                            :to_edit="psychosocialInstructionServices.data.one.evidence_participation_image"
                            @addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile" :files="filesII"
                            :validator="v$" />
                    </div>
                </section>
                <section id="asistentes" class="flex flex-col justify-evenly mb-8">
                    <div>
                        <h3 class="intro-y form-label font-bold uppercase">
                            <span>
                                Asistentes Agregados
                            </span>
                            <span>
                                # {{ form.aggregates.assistants.length }}
                            </span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto overflow-y-hidden">
                        <Aggregates @pop="(id) => psychosocialInstructionStore.pop_aggregate(id, 'a')"
                            @push="(aggregate) => psychosocialInstructionStore.push_aggregate(aggregate, 'a')"
                            :headers="assistants_headers" :aggregates="form.aggregates.assistants"
                            :add_schema="assistants_schema" :validator="v$" name="aggregates">
                        </Aggregates>
                    </div>
                </section>
                <section id="monitors" class="flex flex-col justify-evenly mb-8">
                    <div>
                        <h3 class="intro-y form-label font-bold uppercase">
                            <span>
                                Monitores Agregados
                            </span>
                            <span>
                                # {{ form.aggregates.monitors.length }}
                            </span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto overflow-y-hidden">
                        <Aggregates @pop="(id) => psychosocialInstructionStore.pop_aggregate(id, 'm')"
                            @push="(aggregate) => psychosocialInstructionStore.push_aggregate(aggregate, 'm')"
                            :headers="monitors_headers" :aggregates="form.aggregates.monitors"
                            :options="lists.monitors_table" :validator="v$" name="aggregates">
                        </Aggregates>
                    </div>
                    <div>
                    </div>
                </section>
                <div class="flex justify-center">
                    <button :disabled="loading" type="submit" class="btn btn-primary w-24 ml-2">
                        Ingresar
                    </button>
                </div>
            </form>
        </fieldset>
    </div>
</template>
