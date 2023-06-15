<script setup>
import services from "@/services/parentschool.service"
import consecutiveService from "@/services/generals.service";

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';
import { cedula_valid, only_letters, phone_valid } from '@/utils/validations'
import { string } from 'yup'

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from '@/components/base/Aggregates.vue'

// Store
import { useParentSchoolStore } from '@/stores/parentschool'
import { useSelectStore } from "@/stores/selects"
import { storeToRefs } from "pinia";
import Management from "@/components/base/Management.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import { scroll_top } from "@/utils/scroll";
import alerts from "@/utils/alerts";
import FormHeader from "@/components/base/FormHeader.vue";

// Extracting Store Data
const parentschool_store = useParentSchoolStore()
const { form, get_computed_rules: form_rules } = storeToRefs(parentschool_store)

// Extracting Select Data
const select_store = useSelectStore()
const { options: lists } = storeToRefs(select_store)

// Extracting Services
const parentschool_services = services()
const consecutive_parentschool = consecutiveService()

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
    return await parentschool_services.getOne(route.params.id)
}

const editing = computed(() => {
    return (route.params.id) ? true : false
})

const instance = reactive({
    consecutive: '',
    status: '',
    reject_message: '',
})

// Consecutivo
const fetch_consecutive = async () => {
    return await consecutive_parentschool.getConsecutive("parent_schools", "EP").then(() => {
        form.value.consecutive = consecutive_parentschool.data.value
        instance.consecutive = consecutive_parentschool.data.value
    })
}

onMounted(async () => {
    if (editing.value) {
        parentschool_store.$reset()
        v$.value.$reset()
        await fetchOne().catch(() => {
            mixins.not_found_by_id()
        })
        // console.log('pro',parentschool_services.data.one)
        const { added_wizards, assistance_monitors, created_at, id, monitor, reject_message, status, updated_at, user_psychoso_coordinator_id, consecutive, start_time, final_time, ...rest_data } = parentschool_services.data.one
        /*  console.log('inicio',start_time)
          console.log('final_time',start_time)*/
        let monitors_map = reactive([])

        assistance_monitors.forEach((monitor) => {
            const record = lists.value.monitors_table.find((monitor_table) => monitor_table.id == monitor.monitor_id)
            monitors_map.push(record)
        })

        // var st = dayjs(start_time, ['h:mm A']).format('HH:mm')
        // var ft = dayjs(final_time, ['h:mm A']).format('HH:mm')

        parentschool_store.$patch((state) => {
            Object.assign(state.form, {
                ...rest_data,
                consecutive,
                start_time: start_time,
                final_time: final_time
            })

            state.form.aggregates.assistants = added_wizards.map(({ nac, ...rest_wizard }) => ({ ...rest_wizard }))
            state.form.aggregates.monitors = monitors_map
        })

        instance.consecutive = consecutive
        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        parentschool_store.$reset()
        v$.value.$reset()
        await fetch_consecutive()
    }
})

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
            type: 'text',
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
        if (editing.value) {
            await parentschool_services.update(parentschool_services.data.one.id, parentschool_store.transpiled_data)
        }
        else {
            await fetch_consecutive().finally(() => {
                 parentschool_services.create(parentschool_store.transpiled_data)
                    .then(async (response) => {
                        if (response.status == 200) {
                            filesI.value = []
                            filesII.value = []
                            parentschool_store.$reset()
                            v$.value.$reset()
                            scroll_top()
                            await fetch_consecutive()
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
        {{ editing ? `Edición de Escuela de Padres #${parentschool_services.data.one.id}` : `Escuela de Padres` }}
    </FormHeader>
    <Management class="mt-5" v-if="(editing) ? permissions.parentschools.management() : false"
        @send="(payload) => mixins.methods.send_management('parent_schools', parentschool_services.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.parentschools.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                <section id="general_data" class="grid grid-cols-1 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
                    <div class="w-full">
                        <BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha" name="date"
                            v-model="form.date" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NOMBRE MONITOR CULTURAL *" tooltip="Ingrese el nombre del monitor cultural"
                            placeholder="Seleccione" name="monitor_id" v-model="form.monitor_id"
                            :options="lists.monitors_parentschools" :validator="v$" />
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
                        <BaseInput type="text" label="LUGAR ATENCIÓN *" tooltip="Ingrese el nombre del lugar de atención"
                            placeholder="Lugar Atención" name="place_attention" v-model="form.place_attention"
                            :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="text" label="CONTACTO *" tooltip="Ingrese el nombre del contacto"
                            placeholder="Contacto" name="contact" v-model="form.contact" :validator="v$" />
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
                        <BaseTextarea label="CONCLUSIONES *" tooltip="Ingrese las Conclusiones" placeholder="Conclusiones"
                            name="conclusions" v-model="form.conclusions" :validator="v$" rows="5" />
                    </div>
                </section>
                <section id="images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
                        <BaseFilepond label=" EVIDENCIAS DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen"
                            name="development_activity_image" ref="development_activity_image_ref"
                            v-model="form.development_activity_image" :accept_pdf="true"
                            :to_edit="parentschool_services.data.one.development_activity_image"
                            @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="filesI"
                            :validator="v$" />
                        <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen"
                            name="evidence_participation_image" ref="evidence_participation_image_ref"
                            v-model="form.evidence_participation_image" :accept_pdf="true"
                            :to_edit="parentschool_services.data.one.evidence_participation_image"
                            @addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile" :files="filesII"
                            :validator="v$" />
                    </div>
                </section>
                <section class="flex flex-col justify-evenly mb-8">
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
                    <Aggregates @pop="(id) => parentschool_store.pop_aggregate(id, 'a')"
                        @push="(aggregate) => parentschool_store.push_aggregate(aggregate, 'a')"
                        :headers="assistants_headers" :aggregates="form.aggregates.assistants"
                        :add_schema="assistants_schema" name="aggregates" :validator="v$">
                    </Aggregates>
                </section>
                <section v-if="lists.monitors_table" class="flex flex-col justify-evenly mb-8">
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
                    <Aggregates @pop="(id) => parentschool_store.pop_aggregate(id, 'm')"
                        @push="(aggregate) => parentschool_store.push_aggregate(aggregate, 'm')" :headers="monitors_headers"
                        :aggregates="form.aggregates.monitors" :options="lists.monitors_table || null" name="aggregates"
                        :validator="v$">
                    </Aggregates>
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
