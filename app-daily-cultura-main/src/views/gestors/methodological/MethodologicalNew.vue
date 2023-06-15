<script setup>
import services from '@/services/methodological.service'

// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// Importing Components
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import Aggregates from "@/components/base/Aggregates.vue"

// Store
import { useMethodological } from '@/stores/methodological'
import { useSelectStore } from "@/stores/selects"
import { storeToRefs } from "pinia";
import { scroll_top } from "@/utils/scroll";
import Management from "@/components/base/Management.vue";
import permissions from "@/permissions";
import mixins from "@/mixins";
import { loading } from "@/utils/loading";
import consecutiveService from "@/services/generals.service";
import alerts from '@/utils/alerts';
import FormHeader from '@/components/base/FormHeader.vue';

// Extracting Store Data
const methodological_store = useMethodological();
const { form, form_rules, form_options } = storeToRefs(methodological_store);
// const { usersNoPaginate } = servicesUsers;

// Extracting Select Data
const useSelect = useSelectStore()
const { options } = storeToRefs(useSelect)
const lists = options.value
const disabledButton = ref(false);
// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

// Images
const files = reactive({
    I: [],
    II: [],
})

// Images - First Input
const handleAddFirstFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.place_file1 = {
        name: 'photo1',
        file,
        filename,
    }
}
const handleRemoveFirstFile = () => {
    form.value.place_file1 = null
}

// Images - Second Input
const handleAddSecondFile = (err, val) => {
    if (err) return
    const { file, filename } = val
    form.value.place_file2 = {
        name: 'photo2',
        file,
        filename,
    }
}
const handleRemoveSecondFile = () => {
    form.value.place_file2 = null
}

const route = useRoute()
const methodological_services = services()
const consecutive_services = consecutiveService()

const instance = reactive({
    status: '',
    reject_message: ''
})

const editing = computed(() => {
    return (route.params.id) ? true : false
})

const fetch_consecutive = async () => {
    return await consecutive_services.getConsecutive('methodological_instructions', 'AM').then(() => {
        form.value.consecutive = consecutive_services.data.value
    })
}

const fetch_one = async () => {
    return await methodological_services.getOne(route.params.id)
}

const monitors_comp = computed(() => {
    let filter = lists.monitors_table.filter((monitor) => monitor.nac_id == form.value.nac_id)

    if (form.value.nac_id) {
        return filter
    }
    else {
        return lists.monitors_table
    }
})

onMounted(async () => {
    if (editing.value) {
        methodological_store.$reset()
        await fetch_one().catch(() => {
            mixins.not_found_by_id()
        })

        const { created_at, created_by, updated_at, id, reject_message, status, assistants, assistants_id, ...rest } = methodological_services.data.one

        const _assistants = assistants.map((item) => {
            const { monitor_fullname, document_number, role_id, nac_id } = lists.monitors_table.find(user => user.id == item.id)
            return {
                id: item.id,
                monitor_fullname,
                document_number,
                role_id,
                nac_id
            }
        })

        methodological_store.$patch((state) => {
            Object.assign(state.form, {
                ...rest,
                aggregates: {
                    assistants: _assistants
                },
            })
        })

        instance.status = status
        instance.reject_message = reject_message
    }
    else {
        methodological_store.$reset()
        await fetch_consecutive()
    }
})

const onSubmit = async () => {
    const valid = await v$.value.$validate()
    if (valid) {
        disabledButton.value = true;
        if (editing.value) {
            await methodological_services.update(methodological_services.data.one.id, methodological_store.transpiled_data).finally(() => {
                disabledButton.value = false
            });
        }
        else {

            await fetch_consecutive().finally(() => {
                methodological_services.create(methodological_store.transpiled_data).then(async (response) => {
                    if (response.status == 200) {
                        files.I = []
                        files.II = []
                        methodological_store.$reset()
                        v$.value.$reset()
                        await fetch_consecutive()
                        scroll_top()
                    }
                }).finally(() => {
                    disabledButton.value = false
                });
            });
        }
    }
    else {
        alerts.validation()

    }
}

const assistants_headers = [
    {
        text: 'ID',
        value: 'id',
    },
    {
        text: 'NOMBRE',
        value: 'monitor_fullname',
    },
    {
        text: 'NUMERO DE CÉDULA',
        value: 'document_number',
    },
    {
        text: 'NAC',
        value: 'nac_id',
    },
    {
        text: 'ROL',
        value: 'role_id',
    },
]
</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
        {{ (editing) ? `Edición de Instrucción Metodológica #${methodological_services.data.one.id}` : 'Instrucción Metodológica' }}
    </FormHeader>
    <Management class="mt-5" v-if="(editing) ? permissions.methodologicalInstructions.management() : false"
        @send="(payload) => mixins.methods.send_management('methodological_instructions', methodological_services.data.one.id, payload)" />
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.methodologicalInstructions.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                <section id="general_data"
                    class="flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8">
                    <div class="w-full">
                        <BaseInput type="text" label="LUGAR *" tooltip="Ingrese el Nombre del Lugar"
                            placeholder="Nombre del lugar" name="place" v-model="form.place" :validator="v$" />
                    </div>
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
                        <BaseSelect label="EXPERTICIA *" tooltip="Ingrese la Experticia" placeholder="Seleccione"
                            name="expertise_id" v-model="form.expertise_id" :options="lists.expertises" :validator="v$" />
                    </div>
                    <div class="w-full">
                        <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                            v-model="form.nac_id" :options="lists.nacs" :validator="v$" />
                    </div>
                </section>
                <section id="descriptions" class="mb-8">
                    <div class="flex items-center mb-8">
                        <h3 class="intro-y text-lg font-medium mr-auto">
                            Descripcion de la jornada
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 justify-evenly">
                        <div class="w-full">
                            <BaseRadio label="Se cumplió el objetivo de la instrucción metodológica? *" tooltip=""
                                name="goals_met" v-model="form.goals_met" :options="form_options.goals_met"
                                :validator="v$" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Explicación *" tooltip="Ingrese la explicación" placeholder="Explicación"
                                name="explanation" v-model="form.explanation" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Pedagógico *" tooltip="Ingrese pedagógico" placeholder="Pedagógico"
                                name="pedagogical_comments" v-model="form.pedagogical_comments" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Técnico practico *" tooltip="Ingrese lo técnico practico"
                                placeholder="Técnico practico" name="technical_practical_comments"
                                v-model="form.technical_practical_comments" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Metodológico *" tooltip="Ingrese lo metodológico"
                                placeholder="Metodológico" name="methodological_comments"
                                v-model="form.methodological_comments" :validator="v$" rows="3" />
                        </div>
                        <div class="w-full">
                            <BaseTextarea label="Otros y observaciones *" tooltip="Ingrese otros y observaciones"
                                placeholder="Otros y observaciones" name="others_observations"
                                v-model="form.others_observations" :validator="v$" rows="3" />
                        </div>
                    </div>
                </section>
                <section id="Images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">
                        <BaseFilepond label="DESARROLLO DEL DESARROLLO *" tooltip="Arrastra o selecciona una Imagen"
                            name="place_file1" ref="place_file1_ref" :to_edit="methodological_services.data.one.place_file1"
                            v-model="form.place_file1" @addfile="handleAddFirstFile" @removefile="handleRemoveFirstFile"
                            :files="files.I" :validator="v$" />
                        <BaseFilepond label="EVIDENCIA DE LAS PERSONAS *" tooltip="Arrastra o selecciona una Imagen"
                            name="place_file2" ref="place_file2_ref" :to_edit="methodological_services.data.one.place_file2"
                            v-model="form.place_file2" @addfile="handleAddSecondFile" @removefile="handleRemoveSecondFile"
                            :files="files.II" :validator="v$" />
                    </div>
                </section>
                <section v-if="lists.monitors_table" id="assistants" class="flex flex-col justify-evenly mb-8">
                    <Aggregates @pop="(id) => methodological_store.pop_aggregate(id)"
                        @push="(aggregate) => methodological_store.push_aggregate(aggregate)" :headers="assistants_headers"
                        :aggregates="form.aggregates.assistants" :options="monitors_comp || null" :validator="v$"
                        name="aggregates">
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
</style>