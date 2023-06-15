<script setup lang="ts">
import { required } from '@/utils/validations'
import { useVuelidate } from '@vuelidate/core'
import alerts from '@/utils/alerts';
import BaseSelect from '@/components/base/Select.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseFilepond from '@/components/base/Filepond.vue'
import useSlug from '@/utils/composables/useSlug';
import mixins from '@/mixins'
import servicesSelects from "@/services/selects.service"

// Extracting Services
const select_service = servicesSelects()

const { isSlug } = useSlug()
const route = useRoute()

const status = computedAsync(async () => {
	return await select_service.getStatus().then((response) => select_service.status.all)
},)

const statuses = computedAsync(() => {
    let handle_status = status.value
    const { is_role } = mixins.computed
    if (is_role('apoyo_seguimiento_monitoreo')) {
        if (isSlug('binnacles')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
        else if (isSlug('binnacleManagers')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
        else if (isSlug('binnacleculturalshow')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
        else if (isSlug('culturalEnsembles')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
        else if (isSlug('culturalSeedbeds')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
        else if (isSlug('inscriptions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalCirculations')) {
            return handle_status.filter(status => status.value === 'REV' || status.value === 'REC')
        }
    }
    else if (is_role('gestores_culturales') || is_role('lider_instructor')) {
        if (isSlug('pecs')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('pedagogicals')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalEnsembles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalSeedbeds')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalsheetsone')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalsheetstwo')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalCirculations')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }

    }
    else if (is_role('lider_embajador')) {
        if (isSlug('binnacles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacleculturalshow')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('apoyo_metodologico')) {
        if (isSlug('dialoguetables')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalInstructions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacleManagers')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('managermonitorings')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('coordinador_psicosocial')) {
        if (isSlug('parentschools')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('psychosocialinstructions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('psychopedagogicallogs')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('subdireccion')) {
        if (isSlug('binnacle_territories')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('reportsTerritories')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('direccion')) {
        if (isSlug('reportsTerritories')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('monthly_monitoring_reports')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('groups')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('pedagogicals')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('pollDesertions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('dialoguetables')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalInstructions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('managermonitorings')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('binnacleManagers')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('inscriptions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('pecs')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalsheetsone')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('methodologicalsheetstwo')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalEnsembles')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalCirculations')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalSeedbeds')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('culturalShows')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('psychosocialinstructions')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('parentschools')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        else if (isSlug('psychopedagogicallogs')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('coordinador_supervision')) {
        if (isSlug('strengtheningSuperMonIns')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
        if (isSlug('strengtheningSupervisionMans')) {
            return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
        }
    }
    else if (is_role('coordinador_metodologico')) {
        return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
    }
    else if (is_role('coordinador_seguimiento')) {
        return handle_status.filter(status => status.value === 'APRO' || status.value === 'REC')
    }
    else {
        return handle_status
    }
})

const is_files = computed(() => {
    return (isSlug('inscriptions') && form.status == 'APRO') ? true : false
})

const emit = defineEmits([
    'send'
])

const form = reactive({
    status: '',
    reject_message: '',
    file_1: '',
    file_2: '',
})

const form_rules = computed(() => ({
    status: { required },
    reject_message: (form.status == 'REC') ? { required } : {},
    file_1: (mixins.computed.is_slug('inscriptions', route) && form.status == 'APRO') ? { required } : {},
    file_2: (mixins.computed.is_slug('inscriptions', route) && form.status == 'APRO' && props.age <= 17) ? { required } : {},
}))

const v$ = useVuelidate(form_rules, form, { $scope: false, $stopPropagation: true })

const files = reactive({
    I: [],
    II: []
})

const addFile = (err, val, number) => {
    if (err) {
        return
    }
    else {
        const { file, filename } = val
        if (number == 1) {
            form.file_1 = file
        }
        if (number == 2) {
            form.file_2 = file
        }
    }
}

const removeFile = (number: number) => {
    if (number == 1) {
        form.file_1 = null
        files.I = []
    }
    if (number == 2) {
        form.file_1 = null
        files.II = []
    }
}

const on_submit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        emit('send', { management: { ...form } })
    }
    else {
        alerts.validation()
    }
}
const props = withDefaults(defineProps<{
    age?: Integer
}>(), {
    age: 1
})

</script>

<template>
    <div class="box p-5">
        <form @submit.prevent="on_submit" id="gestor_message" class="flex flex-col items-center gap-6 justify-evenly"
            method="POST">
            <div :class="form.status === 'REC' ? 'grid-cols-1 lg:grid-cols-[25%_1fr]' : 'grid-cols-1'"
                class="grid gap-6 justify-evenly w-full">
                <div class="w-full">
                    <BaseSelect label="ESTADO *" tooltip="Selecciona un Estado" placeholder="Seleccione" name="status"
                        v-model="form.status" :options="statuses || null" :validator="v$" />
                </div>
                <div class="w-full intro-x" v-if="form.status === 'REC'">
                    <BaseTextarea rows="3" label="MENSAJE DE RECHAZO " tooltip="Explique el ¿Por qué?"
                        placeholder="Porque..." name="reject_message" v-model="form.reject_message" :validator="v$" />
                </div>
                <div class="w-full intro-x grid grid-cols-1 md:grid-cols-2 gap-6 justify-evenly" v-if="is_files">
                    <BaseFilepond label="VALIDACIÓN DEL PARTICIPANTE (PDF) *" tooltip="Arrastra un pdf" name="file_1"
                        ref="file_ref" v-model="form.file_1" @addfile="(err, val) => addFile(err, val, 1)"
                        @removefile="removeFile(1)" :files="files.I" accept_only_pdf :validator="v$" />
                    <BaseFilepond label="VALIDACIÓN DEL ACUDIENTE (PDF) *" tooltip="Arrastra un pdf" name="file_2"  v-if="props.age <= 17"
                        ref="file_ref" v-model="form.file_2" @addfile="(err, val) => addFile(err, val, 2)"
                        @removefile="removeFile(2)" :files="files.II" accept_only_pdf :validator="v$" />
                </div>
            </div>
            <div class="w-full flex justify-end col-span-full">
                <button type="submit" form="gestor_message" class="btn btn-primary w-24 ml-2">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</template>