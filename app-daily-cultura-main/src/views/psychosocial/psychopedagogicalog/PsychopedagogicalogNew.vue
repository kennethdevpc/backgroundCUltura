<script setup>
import services from "@/services/psychopedagogicalog.service"
import consecutiveService from "@/services/generals.service";
import userService from "@/services/user.service";

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';
import { cedula_valid, phone_valid, only_letters } from '../../../utils/validations'
import { string } from 'yup'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from '@/components/base/Aggregates.vue'
import { scroll_top } from "@/utils/scroll";

// Store
import { usePsychopedagogicalog } from '../../../stores/psychopedagogicalog'
import { useSelectStore } from "../../../stores/selects"
import { storeToRefs } from "pinia";
import permissions from "@/permissions";
import Management from "@/components/base/Management.vue";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import alerts from "@/utils/alerts";
import FormHeader from "@/components/base/FormHeader.vue";

// Extracting Store Data
const psychopedagogicalog_store = usePsychopedagogicalog()
const { form, get_computed_rules: form_rules } = storeToRefs(psychopedagogicalog_store)

// Extracting Select Data
const select_store = useSelectStore()
const { options: lists } = storeToRefs(select_store)
const { monitors, nacs } = lists.value

// Extracting Services
const psychopedagogicalog_services = services()
const consecutive_psychopedagogicalog = consecutiveService()
const user_service = new userService;

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })
const disabledButton = ref(false);
// Images
const filesI = ref([])
const filesII = ref([])

// Images - First Input
const handleAddFirstFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.development_activity_image = {
        name: 'photo1',
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
        name: 'photo2',
        file,
        filename,
    }
}
const handleRemoveSecondFile = () => {
    form.value.evidence_participation_image = null
}

// Editing Logic
const route = useRoute()

const fetchOne = async () => {
    return await psychopedagogicalog_services.getOne(route.params.id)
}

const isEditing = computed(() => {
    return (route.params.id) ? true : false
})

const instance = reactive({
    consecutive: '',
    status: '',
    reject_message: ''
})

const fetch_consecutive = async () => {
    return await consecutive_psychopedagogicalog.getConsecutive("psychopedagogical_logbooks", "BTP").then(() => {
        form.value.consecutive = consecutive_psychopedagogicalog.data.value
        instance.consecutive = consecutive_psychopedagogicalog.data.value
    })
}

onMounted(async () => {
    if (isEditing.value) {
        psychopedagogicalog_store.$reset()
        await fetchOne().catch(() => {
            mixins.not_found_by_id()
        })

        const { created_at, id, assistants, monitors, nac, consecutive, date, status, reject_message, ...rest } = psychopedagogicalog_services.data.one

        let monitors_map = reactive([])

        monitors.forEach((monitor) => {
            const record = lists.value.monitors_table.find((monitor_table) => monitor_table.id == monitor.monitor_id)
            monitors_map.push(record)
        })

        psychopedagogicalog_store.$patch((state) => {
            Object.assign(state.form, {
                ...rest,
                date,
                status,
                consecutive,
                aggregates: {
                    assistants: assistants.map(({ nac, ...rest }) => ({ ...rest })),
                    monitors: monitors_map,
                },
            })
        })
        instance.consecutive = consecutive
        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        psychopedagogicalog_store.$reset()
        v$.value.$reset()
        await fetch_consecutive()
    }
});

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
        disabledButton.value = true;
        if (isEditing.value) {
            await psychopedagogicalog_services.update(psychopedagogicalog_services.data.one.id, psychopedagogicalog_store.transpiled_data).finally(() => {
                disabledButton.value = false;

            });
        }
        else {
            await fetch_consecutive().finally(() => {
                psychopedagogicalog_services.create(psychopedagogicalog_store.transpiled_data).then(async (response) => {
                    if (response.status) {
                        if (response.data.success) {
                            psychopedagogicalog_store.$reset();
                            v$.value.$reset();
                            await fetch_consecutive()
                            scroll_top();
                            filesI.value = [];
                            filesII.value = [];
                        }
                    }
                })
            }).finally(() => {
                disabledButton.value = false;

            });
        }
    }
    else {
        alerts.validation()
    }
}
</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
        {{ isEditing ? `Edición de Bitácora Psicopedagógica #${psychopedagogicalog_services.data.one.id}` :
            'Bitácora Psicopedagógica'
        }}
    </FormHeader>
    <Management class="mt-5" v-if="(isEditing) ? permissions.psychopedagogicallogs.management() : false"
        @send="(payload) => mixins.methods.send_management('psychopedagogical_logbooks', psychopedagogicalog_services.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.psychopedagogicallogs.no_edit()">
            <form @submit.prevent="onSubmit"
                class="px-5 sm:px-10 py-10 border-t border-slate-200/60 dark:border-darkmode-400">
                <section id="general_data"
                    class="flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
                    <div class="w-full">
                        <BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha" name="date"
                            v-model="form.date" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                            v-model="form.nac_id" :options="nacs" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA INICIO *" tooltip="Ingrese la hora de inicio"
                            placeholder="Hora inicio" name="start_time" v-model="form.start_time" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA FINAL *" tooltip="Ingrese la hora final" placeholder="Hora final"
                            name="final_time" :min="form.start_time" v-model="form.final_time" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="text" label="NOMBRE PERSONA ATENDIDA *"
                            tooltip="Ingrese el nombre de la persona atendida" placeholder="Nombre de persona"
                            name="person_served_name" v-model="form.person_served_name" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NOMBRE MONITOR CULTURAL *" tooltip="Ingrese el nombre del monitor cultural"
                            placeholder="Seleccione" name="monitor_id" v-model="form.monitor_id" :options="monitors"
                            :validator="v$" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="OBJETIVO *" tooltip="Ingrese el objetivo" placeholder="Objetivo"
                            name="objective" v-model="form.objective" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="DESARROLLO *" tooltip="Ingrese el desarrollo" placeholder="Desarrollo"
                            name="development" v-model="form.development" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="REMISIONES *" tooltip="Ingrese las remisiones" placeholder="Remisiones"
                            name="referrals" v-model="form.referrals" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="CONCLUSIONES, REFLEXIONES Y COMPROMISOS DE LA JORNADA *"
                            tooltip="Ingrese las conclusiones, reflexiones y compromisos de la jornada"
                            placeholder="Remisiones" name="conclusions_reflections_commitments"
                            v-model="form.conclusions_reflections_commitments" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="REPORTE DE ALERTA PARA HACER SEGUIMIENTO *" tooltip="Ingrese el reporte"
                            placeholder="Reporte" name="alert_reporting_tracking" v-model="form.alert_reporting_tracking"
                            :validator="v$" rows="5" />
                    </div>
                </section>
                <section id="images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
                        <BaseFilepond label="EVIDENCIA DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen o PDF"
                            name="development_activity_image" ref="development_activity_image_ref"
                            v-model="form.development_activity_image" :accept_pdf="true"
                            :to_edit="psychopedagogicalog_services.data.one.development_activity_image"
                            @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="filesI"
                            :validator="v$" />
                        <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen o PDF"
                            name="evidence_participation_image" ref="evidence_participation_image_ref"
                            v-model="form.evidence_participation_image" :accept_pdf="true"
                            :to_edit="psychopedagogicalog_services.data.one.evidence_participation_image"
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
                        <Aggregates @pop="(id) => psychopedagogicalog_store.pop_aggregate(id, 'a')"
                            @push="(aggregate) => psychopedagogicalog_store.push_aggregate(aggregate, 'a')"
                            :headers="assistants_headers" :aggregates="form.aggregates.assistants"
                            :add_schema="assistants_schema" name="aggregates" :validator="v$">
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
                        <Aggregates @pop="(id) => psychopedagogicalog_store.pop_aggregate(id, 'm')"
                            @push="(aggregate) => psychopedagogicalog_store.push_aggregate(aggregate, 'm')"
                            :headers="monitors_headers" :aggregates="form.aggregates.monitors"
                            :options="lists.monitors_table" name="aggregates" :validator="v$">
                        </Aggregates>
                    </div>
                    <div>
                    </div>
                </section>
                <div class="flex justify-center">
                    <button :disabled="disabledButton" type="submit" class="btn btn-primary w-24 ml-2">
                        Ingresar
                    </button>
                </div>
            </form>
        </fieldset>
    </div>
</template>
