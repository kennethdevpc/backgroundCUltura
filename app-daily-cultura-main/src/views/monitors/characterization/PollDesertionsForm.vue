<script setup>
import { ref } from 'vue'
import { useVuelidate } from '@vuelidate/core'
// Importing Store
import { storeToRefs } from 'pinia'
import { PollDesertions } from '@/stores/pollDesertions'
import PollDesertionsServices from '@/services/pollDesertions.service'
import servicesConsecutive from '@/services/generals.service'

// Importing Options Store
import { useSelectStore } from '@/stores/selects'

// Importing Components 
import BaseInput from '@/components/base/Input.vue'
import BaseSelect from '@/components/base/Select.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseBackButton from "../../../components/base/BaseBackButton.vue";
import BaseTextarea from '@/components/base/Textarea.vue'
import Swal from 'sweetalert2';
import { scroll_top } from '@/utils/scroll'
import { loading } from '@/utils/loading'
import StateHeader from '@/components/base/StateHeader.vue'
import mixins from '@/mixins'

const route = useRoute()

// Extracting Store Data
const poll_desertions = PollDesertions()
const consecutive_services = servicesConsecutive()
const poll_desertions_services = PollDesertionsServices()

const { form, get_form_rules_computed: form_rules, form_options } = storeToRefs(poll_desertions)

// Extracting Store Data
const select_store = useSelectStore()
const { options } = storeToRefs(select_store)

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true })

const editing = computed(() => {
    return (route.params.id) ? true : false
})

const fetch_consecutive = async () => {
    return await consecutive_services.getConsecutive('polls_desertion', 'ED').then(() => {
        form.value.consecutive = consecutive_services.data.value
    })
}

const fetch_poll = async () => {
    return await poll_desertions_services.getOne(route.params.id)
}

const filter_beneficiaries = ref([])

// options.beneficiaries
onBeforeMount(async () => {
    if (!editing.value) {
        await poll_desertions_services.get().then(() => {
            const actives = poll_desertions_services.data.all.map(({ beneficiary_id }) => (beneficiary_id))
            const inactives = options.value.beneficiaries.filter(({ id: inactive_id }) => {
                return !actives.some(active_id => active_id == inactive_id)
            })

            filter_beneficiaries.value = inactives
        })
    }
})

onMounted(async () => {
    if (editing.value) {
        poll_desertions.$reset()
        await fetch_poll().then(async () => {
            const { id, nac, user_id, beneficiary, created_at, ...rest } = poll_desertions_services.data.one

            poll_desertions.$patch((state) => {
                Object.assign(state.form, rest)
            })

            await poll_desertions_services.get().then(() => {
                const actives = poll_desertions_services.data.all.map(({ beneficiary_id }) => (beneficiary_id))

                const inactives = options.value.beneficiaries.filter(({ id: inactive_id }) => {
                    return !actives.some(active_id => {
                        if (active_id == inactive_id && active_id != beneficiary.id) {
                            return true
                        }
                    })
                })

                filter_beneficiaries.value = inactives
            })
        }).catch(() => {
            mixins.not_found_by_id()
        })
    }
    else {
        poll_desertions.$reset()
        v$.value.$reset()
        await fetch_consecutive()
    }
})

const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        if (editing.value) {
            await poll_desertions_services.update(poll_desertions_services.data.one.id, form.value)
        }
        else {
            await fetch_consecutive().finally(() => {
                poll_desertions_services.create(form.value).then(async (response) => {
                    if (response.status == 200) {
                        poll_desertions.$reset()
                        v$.value.$reset()
                        scroll_top()
                        await fetch_consecutive()
                    }
                })
            })
        }
    } else {
        Swal.fire('Validación', 'Por favor valide los campos solicitados.', 'error')
    }
}
</script>

<template>
    <div class="max-w-screen-full mx-auto">
        <div class="intro-y flex justify-between items-start md:items-end mt-5">
            <div class="intro-y flex flex-col items-start gap-1">
                <BaseBackButton />
                <h2 class="text-lg font-medium mr-auto">
                    {{ (editing) ? `Edición de Encuesta de Deserción #${poll_desertions_services.data.one.id}` :
                        'Encuesta de Deserción'
                    }}
                </h2>
            </div>
            <StateHeader :consecutive="form.consecutive" />
        </div>
        <div>
            <div class="intro-y box p-5 my-5">
                <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div v-if="filter_beneficiaries.length">
                        <BaseSelect label="BENEFICIARIO *" tooltip="" name="beneficiary_id" v-model="form.beneficiary_id"
                            :options="filter_beneficiaries || []" :validator="v$" required />
                    </div>

                    <div>
                        <BaseInput type="date" label="FECHA *" tooltip="" placeholder="" name="date" v-model="form.date"
                            :validator="v$" />
                    </div>
                    <div>
                        <BaseSelect label="NAC" @change="nac($event)" tooltip="" name="nac_id" v-model="form.nac_id"
                            :options="options.nacs" :validator="v$" required />
                    </div>
                    <template :class="(form.beneficiary_attrition_factors === 'OTRO') ? 'grid-cols-2' : 'grid-cols-1'"
                        class="grid gap-3">
                        <div>
                            <BaseSelect label="FACTOR DE DESERCIÓN DEL BENEFICIARIO" tooltip="" placeholder="Seleccione"
                                name="beneficiary_attrition_factors" v-model="form.beneficiary_attrition_factors"
                                :options="options.beneficiary_attrition_factors" :validator="v$" required />
                        </div>
                        <div v-if="form.beneficiary_attrition_factors === 'OTRO'" class="flex items-end intro-x">
                            <BaseInput type="text" label="¿CUAL ES EL OTRO FACTOR?" tooltip="" placeholder="Factor..."
                                name="beneficiary_attrition_factor_other" v-model="form.beneficiary_attrition_factor_other"
                                :validator="v$" />
                        </div>
                    </template>
                </div>
                <div class="mx-auto mt-10 grid grid-cols-1 gap-4">
                    <div>
                        <BaseRadio
                            label="¿CREE USTED QUE HUBO DESINTERÉS Y APATÍA POR PARTE DEL PARTICIPANTE EN SEGUIR PARTICIPANDO EN LAS EXPRESIONES ARTÍSTICAS?"
                            tooltip="" name="disinterest_apathy" v-model="form.disinterest_apathy"
                            :options="form_options.disinterest_apathy" :validator="v$" required />
                    </div>
                    <div>
                        <BaseTextarea label="EXPLICACIÓN" tooltip="" placeholder="Explicación..."
                            name="disinterest_apathy_explanation" v-model="form.disinterest_apathy_explanation"
                            :validator="v$" rows="3" required />
                    </div>
                    <div>
                        <BaseRadio
                            label="¿CREE USTED QUE EL PARTICIPANTE PUEDE REINTEGRARSE AL PROGRAMA RED DE MONITORES CULTURALES? *"
                            tooltip="" name="reintegration" v-model="form.reintegration"
                            :options="form_options.reintegration" :validator="v$" required />
                    </div>
                    <div>
                        <BaseTextarea label="EXPLICACIÓN" tooltip="" placeholder="Explicación..."
                            name="reintegration_explanation" v-model="form.reintegration_explanation" :validator="v$"
                            rows="3" required />
                    </div>
                </div>
            </div>
            <div class="mx-auto mt-10 flex justify-center">
                <button :disabled="loading" class="btn btn-primary w-24 mr-1 mb-2" @click="onSubmit">Ingresar</button>
            </div>
        </div>

    </div>
</template>