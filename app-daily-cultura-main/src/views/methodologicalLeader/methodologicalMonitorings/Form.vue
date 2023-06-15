<script setup lang="ts">
// [core]
import { storeToRefs } from 'pinia'
import { useMethodologicalMonitorings } from '@/stores/methodologicalMonitorings'
import { useSelectStore } from '@/stores/selects'
import { useVuelidate } from '@vuelidate/core'
import dayjs from 'dayjs'
import es from "dayjs/locale/es";
import localeData from "dayjs/plugin/localeData";
// [components]
import Input from '@/components/base/Input.vue'
import Select from '@/components/base/Select.vue'
import Textarea from '@/components/base/Textarea.vue'
import Filepond from '@/components/base/Filepond.vue'
import BaseManagement from "@/components/base/Management.vue";
// [composables]
import useApi from '@/utils/useApi'
import useSelect, { Select as SelectType } from '@/utils/useSelect'
import Aggregates from '@/components/base/Aggregates.vue'
import { Header } from 'vue3-easy-data-table'
import alerts from '@/utils/alerts'
import { formdataParser } from '@/utils/formdataParser'
import { setLoading } from '@/utils/loading'
import { scroll_top } from '@/utils/scroll'
import permissions from "@/permissions";
import mixins from "@/mixins";
import FormHeader from '@/components/base/FormHeader.vue'
dayjs.locale(es);
dayjs.extend(localeData);
const { getFromDefaults } = useSelect()
const { find, findOne, create, update } = useApi()
const store = useMethodologicalMonitorings()
const { options: { lineaments, cultural_rights: culturalRights, orientations, values, nacs } } = useSelectStore()

const router = useRouter();
const route = useRoute();
const instance = reactive({
    consecutive: '',
    status: '',
    reject_message: ''
})
const { form, form_rules } = storeToRefs(store)
const v$ = useVuelidate(form_rules, form)

const editing = computed(() => {
    return (route.params.id) ? true : false
})

const { state: methodologicalMonitoring } = useAsyncState(async () => {
    v$.value.$reset();
    store.$reset();
    if (editing.value) {
        setLoading(true)
        const response = await findOne<{ items: any }>('methodologicalMonitorings', route.params.id as string)
            .then((response) => {
                const { aggregates, audited, consecutive, created_at, created_by, cultural_right, id, lineament, nac, orientation, reject_message, roles: editRoles, status, user, value, value_id, ...rest } = response.data.items

                Object.assign(instance, {
                    consecutive,
                    status,
                    reject_message
                })

                Object.assign(form.value, {
                    ...rest,
                    consecutive,
                    value: value_id,
                })
                return response.data.items
            })
            .finally(() => {
                setLoading(false)
            })
        return response
    }
    else {
        return null
    }
}, null)
const roles = ref();
onMounted(async () => {
    // const count = await find(`getDataSheet/methodological_monitorings/${form.value.date_realization}`).then((response) => response.data)
    await find('getRole').then((response) => {
        if (response.data.length > 0) {
            roles.value = response.data
        } else {
            form.value.roles = null;
        }

    })
})
// const roles = computedAsync(async () => {
//     return form.value.nac_id
//         ? await findOne<SelectType[]>('getRoles', form.value.nac_id).then((response) => response.data)
//         : []
// }, null)

// watch(roles, () => {
//     if (editing.value) {
//         if (roles.value.length > 0) {
//             if (form.value.nac_id === methodologicalMonitoring.value.nac_id) {
//                 form.value.roles = methodologicalMonitoring.value.roles.map((role) => role.role_id)
//             }
//         }
//     }
// })

const strengthening_types = computedAsync(async () => {
    const response = await getFromDefaults('strengthening_types')
    return response.data
}, null)

const { execute: refreshConsecutive } = useAsyncState(async () => {
    if (!editing.value) {
        const response = await find<string>('consecutive/generate/methodological_monitorings/SML')
        form.value.consecutive = response.data
        instance.consecutive = response.data
        return response.data
    }
}, null)

const files = ref([])



/**
 * Watch the changes form date_range input and obtains his month in long format
 * --> put in the token value Ficha ${split of consecutive obtaining her number} - the month
 */
watch(computed(() => form.value.date_realization), async () => {
    if (form.value.date_realization) {
        form.value.datasheet = 'Cargando...'
        const count = await find(`getDataSheet/methodological_monitorings/${form.value.date_realization}`).then((response) => response.data)
        const month = dayjs(form.value.date_realization).format("MMMM");
        const dataSheet = computed(() => (`Seguimiento ${count} - ${month}`))
        form.value.datasheet = dataSheet.value
    }
})

const aggregatesHeaders: Header[] = [
    {
        text: 'ID',
        value: 'id',
    },
    {
        text: 'NOMBRE',
        value: 'full_name',
    },
    {
        text: 'CÉDULA',
        value: 'nuip',
    }
]

var aggregatesOptions =  ref([]);
// console.log('aggregatesOptions', aggregatesOptions);

const aggregatesOptionsFilter = ref([]);
watch(computed(() => form.value.roles), async () => {

    if (form.value.roles) {

        aggregatesOptions.value = await create('getRolesUsers', { 'roles': form.value.roles }).then((response) => Object.entries(response.data).map(([key, user]) => {
        return {
            value: user.id,
            id: user.id,
            full_name: user.monitor_fullname,
            nuip: user.document_number,
            roleId: user.role_id
        }
    }));
    }

    // await find<Array<any>>('getRolesUsers',form.value.roles).then((response) => console.log('response.data',response.data));
    if (form.value.roles.length && aggregatesOptions.value.length) {

        aggregatesOptionsFilter.value = aggregatesOptions.value.filter((item) => form.value.roles.includes(item.roleId));
    } else {
        aggregatesOptionsFilter.value = [];
        form.value.aggregates = [];
    }
});

// watch(aggregatesOptions, () => {
//     if (editing.value) {
//         if (aggregatesOptions.value.length > 0) {
//             if (form.value.nac_id === methodologicalMonitoring.value.nac_id) {
//                 aggregatesOptions.value.map((aggregate) => {
//                     methodologicalMonitoring.value.aggregates.map(({ aggregate_id }) => {
//                         if (aggregate.id === aggregate_id) {
//                             form.value.aggregates.push(aggregate)
//                         }
//                     })
//                 })
//             }
//         }
//     }
// })

watch(computed(() => form.value.roles), () => {
    //console.log(form.value.roles)
})

async function onSubmit() {
    const valid = await v$.value.$validate()

    if (valid) {
        setLoading(true)
        const fd = formdataParser(store.transpiledData)

        if (editing.value) {
            await update<any>('methodologicalMonitorings', route.params.id, fd)
                .then((response) => {
                    if (response.status >= 200 && response.status <= 300) {
                        alerts.update()
                        files.value = []
                        store.$reset()
                        v$.value.$reset()
                        router.go(-1)
                    }
                })
        }
        else {
            await refreshConsecutive().finally(() => {
                create('methodologicalMonitorings', fd)
                    .then(async (response) => {
                        if (response.status >= 200 && response.status <= 300) {
                            alerts.create()
                            files.value = []
                            store.$reset()
                            v$.value.$reset()
                            await refreshConsecutive().finally(() => {
                                setLoading(false)
                                scroll_top()
                            })
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
        {{ (editing) ? `Edición de Seguimiento Metodológico #${route.params.id}` : `Seguimiento Metodológico` }}
    </FormHeader>

    <BaseManagement v-if="editing ? permissions.methodologicalMonitorings.management() : false" @send="
        (payload) =>
            mixins.methods.send_management(
                'methodological_monitorings',
                route.params.id as string,
                payload
            )
    " class="mt-5" />
    <div class="p-5 mt-5 intro-y box">
        <fieldset :disabled="permissions.methodologicalMonitorings.no_edit()">
            <form @submit.prevent="onSubmit" class="space-y-8 divide-y divide-slate-200">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Datos Generales
                    </h3>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <Input name="date_realization" type="date" label="FECHA *" :validator="v$"
                            v-model="form.date_realization" />
                        <Input name="datasheet" type="text" label="FICHA NO. *" disabled :validator="v$"
                            v-model="form.datasheet" />
                        <Select name="nac_id" label="NAC *" :options="nacs" :validator="v$" v-model="form.nac_id" />
                        <Select name="roles" label="ROL *" placeholder="Roles relacionados al NAC" multiple hideSelected
                            :options="roles" :validator="v$" v-model="form.roles" />
                        <div class="col-span-1">
                            <label for="initial" class="form-label font-bold">
                                FECHA RANGO PLANEACIÓN *
                            </label>
                            <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-x-2">
                                <Input name="date_planning_ini" type="date" :validator="v$"
                                    v-model="form.date_planning_ini" />
                                <span class="mb-1">
                                    A
                                </span>
                                <Input name="date_planning_fin" type="date" :validator="v$"
                                    v-model="form.date_planning_fin" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Componente Metodológico
                    </h3>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <Select name="cultural_right_id" label="DERECHO CULTURAL *" :options="culturalRights"
                            :validator="v$" v-model="form.cultural_right_id" />
                        <Select name="lineament_id" label="LINEAMIENTO *" :options="lineaments" :validator="v$"
                            v-model="form.lineament_id" />
                        <Select name="orientation_id" label="ORIENTACIÓN *" :options="orientations" :validator="v$"
                            v-model="form.orientation_id" />
                        <Select name="value" label="VALOR *" :options="values" :validator="v$" v-model="form.value" />
                        <Textarea name="objective_process" label="OBJETIVO DEL PROCESO *" placeholder="Objetivo" rows="5"
                            v-model="form.objective_process" :validator="v$" />
                        <Textarea name="comments" label="OBSERVACIONES *" placeholder="Observación" rows="5"
                            v-model="form.comments" :validator="v$" />
                        <div class="col-span-1 sm:col-span-2">
                            <Select name="strengthening_type" label="TIPO DE FORTALECIMIENTO *"
                                :options="strengthening_types" :validator="v$" v-model="form.strengthening_type" />
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <Textarea name="strengthening_comments" label="COMENTARIOS DEL FORTALECIMIENTO *"
                                placeholder="Comentario" rows="5" v-model="form.strengthening_comments" :validator="v$" />
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <Textarea name="topics_to_strengthened" label="TEMÁTICAS A FORTALECER SEGÚN ROL *"
                                placeholder="Temática" rows="5" v-model="form.topics_to_strengthened" :validator="v$" />
                        </div>
                        <Filepond name="development_activity_image" label="FOTO DEL DESARROLLO *" :validator="v$"
                            @addfile="(_err, value) => { form.development_activity_image = value.file }"
                            @removefile="_$event => { form.development_activity_image = null }"
                            :to_edit="editing && methodologicalMonitoring != null ? methodologicalMonitoring.development_activity_image : null"
                            :files="files" />
                        <Filepond name="evidence_participation_image" label="EVIDENCIA DE PARTICIPACIÓN *" :validator="v$"
                            @addfile="(_err, value) => { form.evidence_participation_image = value.file }"
                            @removefile="_$event => { form.evidence_participation_image = null }"
                            :to_edit="editing && methodologicalMonitoring != null ? methodologicalMonitoring.evidence_participation_image : null"
                            :files="files" />
                    </div>
                </div>
                <div class="pt-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Asistentes Agregados
                    </h3>
                    <div class="mt-6 grid grid-cols-1">
                        <Aggregates name="aggregates" :headers="aggregatesHeaders" :aggregates="form.aggregates"
                            :options="aggregatesOptionsFilter" :validator="v$"
                            @push="$event => form.aggregates.push($event)" @pop="$event => {
                                form.aggregates.splice(form.aggregates.indexOf($event), 1)
                            }" />
                    </div>
                </div>
                <div class="pt-5">
                    <div class="flex justify-end gap-x-4">
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</template>
