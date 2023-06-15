<script lang="ts" setup>
    import BaseInput from '@/components/base/Input.vue'
    import BaseSelect from '@/components/base/Select.vue'
    import { useVuelidate } from '@vuelidate/core'
    import mixins from '@/mixins'
    import services from '@/services/report.services'
    import { useSelectStore } from "@/stores/selects"
    import { storeToRefs } from "pinia"

    const select_store = useSelectStore()
    const { options: lists } = storeToRefs(select_store)
    const { monitors } = lists.value

    const props = withDefaults(defineProps<{
        show_exports?: boolean
    }>(), {
        show_exports: false,
    })

    const conditions_role_admin = computed(() => {
        return (mixins.computed.is_role('super.root') || mixins.computed.is_role('root')) ? true : false
    })
    const filters = reactive({
        date_criteria_start: '',
        date_criteria_end: '',
        monitor_id:''
    })

    const filters_rules = computed(() => ({
        date_criteria_start: {},
        date_criteria_end: {},
        monitor_id:''
    }))
    const v$ = useVuelidate(filters_rules, filters, { $autoDirty: true })

    const show_date = ref(true)

    const services_report = services();

    const exportPDF = async (type,report) => {
        if (filters.monitor_id == '') {
            filters.monitor_id = '@'
        }
        await services_report.exportPdf(type,report,filters.date_criteria_start,filters.date_criteria_end,filters.monitor_id)
    }
    
</script>
<template>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Informe de escuela de padres</h2>
    </div>

    <div class="intro-y flex flex-col gap-2">
        <section class="flex flex-col gap-3 lg:grid lg:grid-cols-4 lg:items-center">
            <div v-if="show_date" class="flex flex-col justify-start h-full">
                <br>
                <label for="regular-form-2" class="form-label font-bold min-w-max mr-2">FECHA RANGO</label>
                <div class="grid grid-cols-2 gap-1.5 w-full intro-x">
                    <BaseInput class="" type="date" tooltip="Desde" name="date_criteria_start"
                        v-model="filters.date_criteria_start" :validator="v$" />
                    <BaseInput class="" type="date" tooltip="Hasta" name="date_criteria_end"
                        v-model="filters.date_criteria_end" :validator="v$" />
                </div>
            </div>
            <div v-if="show_date" class="flex flex-col justify-start h-full">
                <br>
                <div class="grid grid-cols-2 gap-1.5 w-full intro-x">
                    <BaseSelect label="Monitor" tooltip="Ingrese el Monitor" placeholder="Seleccione" name="monitor_id"
                        :options="lists.monitors_parentschools" v-model="filters.monitor_id" :validator="v$" />
                </div>
            </div>
            <div class="flex justify-center md:justify-end gap-3" v-if="show_exports || conditions_role_admin">
                <br>
                <button @click="exportPDF('pdf','escuela_padres')" class="btn btn-primary">
                    <PrinterIcon class="w-4 h-4 mr-2" />
                    <span>
                        Imprimir
                    </span>
                </button>
            </div>
        </section>
    </div>

</template>