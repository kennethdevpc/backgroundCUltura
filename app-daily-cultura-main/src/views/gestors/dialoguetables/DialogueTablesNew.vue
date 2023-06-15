<script setup>
import services from '@/services/dialoguetables.service'
import servicesConsecutive from '@/services/generals.service'

import { cedula_valid, phone_valid, alphas } from '@/utils/validations'
import { string } from 'yup'

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from "@/components/base/Aggregates.vue"

// Store
import { useDialogueTables } from '@/stores/dialoguetables';
import { storeToRefs } from "pinia";
import { useSelectStore } from "@/stores/selects"
import { scroll_top } from "@/utils/scroll";
import Management from "@/components/base/Management.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import Swal from 'sweetalert2';
import FormHeader from '@/components/base/FormHeader.vue';

// Extracting Store Data
const dialoguetables_store = useDialogueTables()
const { form, form_rules } = storeToRefs(dialoguetables_store)

// Extracting Select Data
const useSelect = useSelectStore()
const { options } = storeToRefs(useSelect)
const lists = options.value

// Extracting Services
const dialoguetables_services = services()
const consecutive_services = servicesConsecutive()

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

// Images
const files = reactive({
    I: [],
    II: []
})
const disabledButton = ref(false);
// Images - First Input
const handleAddFirstFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.place_image1 = {
        name: 'photo1',
        file,
        filename,
    }
}
const handleRemoveFirstFile = () => {
    form.value.place_image1 = null
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.place_image2 = {
        name: 'photo2',
        file,
        filename,
    }
}
const handleRemoveSecondFile = () => {
    form.value.place_image2 = null
}

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
            rules: alphas,
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
            rules: alphas,
        },
        {
            name: 'nac_id',
            as: 'select',
            placeholder: 'NAC',
            rules: string().required(),
            children: options.value.nacs.map((nac) => {
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

const route = useRoute()

const instance = reactive({
    status: '',
    reject_message: ''
})

const editing = computed(() => {
    if (route.params.id)
        return true
    else
        return false
})

const fetch_consecutive = async () => {
    return await consecutive_services.getConsecutive('dialogue_tables', 'IMD').then(() => {
        form.value.consecutive = consecutive_services.data.value
    })
}

const fetch_dialoguetable = async () => {
    return await dialoguetables_services.getOne(route.params.id)
}

onMounted(async () => {
    if (editing.value) {
        dialoguetables_store.$reset()
        await fetch_dialoguetable().catch(() => {
            mixins.not_found_by_id()
        })

        const { id, nac, assistants, status, reject_message, ...rest } = dialoguetables_services.data.one

        const assistants_map = assistants.map((item) => {
            const { created_at, deleted_at, id, pivot, updated_at, ...item_rest } = item

            return {
                ...item_rest
            }
        })

        dialoguetables_store.$patch((state) => {
            Object.assign(state.form, {
                ...rest,
                nac_id: nac.id,
                assistants: assistants_map
            })
        })

        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        dialoguetables_store.$reset()
        await fetch_consecutive()
    }
})

const onSubmit = async () => {
    const valid = await v$.value.$validate()
    if (valid) {
        disabledButton.value = true;
        if (editing.value) {
            await dialoguetables_services.update(dialoguetables_services.data.one.id, dialoguetables_store.transpiled_data).finally(() => {
                    disabledButton.value = false
                })
        }
        else {
            await fetch_consecutive().finally(() => {
                dialoguetables_services.create(dialoguetables_store.transpiled_data).then(async (response) => {
                    if (response.data.success) {
                        files.I = []
                        files.II = []
                        dialoguetables_store.$reset()
                        v$.value.$reset()
                        scroll_top()
                        await fetch_consecutive()
                    }
                }).finally(() => {
                    disabledButton.value = false
                })
            })
        }
    }
    else {
        Swal.fire('Validación', 'Por favor valide los campos solicitados.', 'error')
    }
}
</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
        {{ (editing) ? `Edición de Mesa de Diálogo #${dialoguetables_services.data.one.id}` : 'Mesa de Diálogo' }}
    </FormHeader>
    <Management class="mt-5" v-if="(editing) ? permissions.dialoguetables.management() : false"
        @send="(payload) => mixins.methods.send_management('dialogue_tables', dialoguetables_services.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.dialoguetables.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                <section id="GeneralData"
                    class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
                    <div class="w-full">
                        <BaseInput type="date" label="FECHA *" tooltip="Ingrese la fecha" placeholder="Fecha"
                            name="activity_date" v-model="form.activity_date" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA INICIO *" tooltip="Ingrese la hora de inicio"
                            placeholder="Hora inicio" name="start_time" v-model="form.start_time" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseInput type="time" label="HORA FINAL *" tooltip="Ingrese la hora final" placeholder="Hora final"
                            name="final_hour" :min="form.start_time" v-model="form.final_hour" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                            v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
                    </div>
                </section>
                <section id="Descriptions" class="mb-8">
                    <div class="flex items-center mb-8">
                        <h3 class="intro-y text-lg font-medium mr-auto">
                            Descripción de la jornada *
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 justify-evenly">
                        <div class="w-full">
                            <BaseTextarea label="Objetivo de la jornada *" tooltip="Ingrese el objetivo"
                                placeholder="Objetivo" name="target_workday" v-model="form.target_workday" :validator="v$"
                                rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Tema abordado *" tooltip="Ingrese el tema" placeholder="Tema" name="theme"
                                v-model="form.theme" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Agenda del dia *" tooltip="Ingrese agenda" placeholder="Agenda"
                                name="schedule_day" v-model="form.schedule_day" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Descripción de la jornada *" tooltip="Ingrese descripción"
                                placeholder="Descripción" name="workday_description" v-model="form.workday_description"
                                :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Logros y Dificultades *" tooltip="Ingrese logros y dificultades"
                                placeholder="Logros y dificultades" name="achievements_difficulties"
                                v-model="form.achievements_difficulties" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Alertas *" tooltip="Ingrese alertas" placeholder="Alertas" name="alerts"
                                v-model="form.alerts" :validator="v$" rows="3" />
                        </div>
                    </div>
                </section>
                <section id="Images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
                        <BaseFilepond label="DESARROLLO DEL DIÁLOGO CULTURAL *"
                            tooltip="Arrastra o selecciona una Imagen o PDF" name="place_image1" ref="place_image1_ref"
                            v-model="form.place_image1" :to_edit="dialoguetables_services.data.one.place_image1"
                            @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile" :files="files.I"
                            :validator="v$" />
                        <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra o selecciona una Imagen o PDF"
                            name="place_image2" ref="place_image2_ref" v-model="form.place_image2"
                            :to_edit="dialoguetables_services.data.one.place_image2" @addfile="handleAddSecondFile"
                            @removefile="handleRemoveSecondFile" :files="files.II" :validator="v$" />
                    </div>
                </section>
                <section class="flex flex-col justify-start mb-8">
                    <div>
                        <h3 class="intro-y form-label font-bold uppercase">
                            <span>
                                Asistentes Agregados
                            </span>
                            <span>
                                # {{ form.assistants.length }}
                            </span>
                        </h3>
                    </div>
                    <Aggregates @pop="(id) => dialoguetables_store.pop_aggregate(id)"
                        @push="(aggregate) => dialoguetables_store.push_aggregate(aggregate)" :headers="assistants_headers"
                        :aggregates="form.assistants" :add_schema="assistants_schema" :validator="v$" name="assistants">
                    </Aggregates>
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

<style scoped>
.table th {
    border-bottom: 0;
}

.dropdown-menu .dropdown-content {
    @apply pt-14 pb-1.5 px-1.5;
}
</style>