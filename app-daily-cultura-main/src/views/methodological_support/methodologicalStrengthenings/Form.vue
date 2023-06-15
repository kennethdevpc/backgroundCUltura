<script lang="ts" setup>
// Importing Overall librearies / Dependencies
import { storeToRefs } from "pinia";
import { useVuelidate } from "@vuelidate/core";
import { loading } from "@/utils/loading";
import services from "@/services/methodologicalStrengthenings.services"
// Testing both imports
import permissions from "@/permissions";
import mixins from "@/mixins";

import { methodologicalStrengthenings } from "@/stores/methodologicalStrengthenings";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseFilepond from "@/components/base/Filepond.vue";
import BaseSelect from '@/components/base/Select.vue'
import BaseManagement from "@/components/base/Management.vue";
import Aggregates from '@/components/base/Aggregates.vue'
import servicesConsecutive from '@/services/generals.service'
import { scroll_top } from "@/utils/scroll";
import alerts from '@/utils/alerts';
import FormHeader from "@/components/base/FormHeader.vue";
const consecutive_services = servicesConsecutive()

// [Using Store] => ['methodologicalStrengthenings']
const methodologicalStrengthenings_store = methodologicalStrengthenings();
const methodological_strengthening_services = services()
// [Extracting Store] => ['methodologicalStrengthenings']
const { form, form_options, instance, get_computed_rules: form_rules  } = storeToRefs(methodologicalStrengthenings_store);
//   
// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, {
    $autoDirty: true,
    $lazy: true,
});

// [Computed] => Getting Edit Status
const route = useRoute();
const isEditing = computed(() => (route.params.id ? true : false));

// [Ref] => Declaring Edit Image
const files = ref({
    development_activity_image: [],
    evidence_participation_image: [],
});


const users_headers = [
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

const editing = computed(() => {
    return (route.params.id) ? true : false
})
const fetch_methodological_strengthening = async () => {
    return await methodological_strengthening_services.getOne(route.params.id)
}
const fetch_consecutive = async () => {
    return await consecutive_services.getConsecutive('methodological_strengthenings', 'FM').then(() => {
        form.value.consecutive = consecutive_services.data.value
    })
}

onBeforeMount(async () => {
    await fetch_consecutive()
    // await select_store.dataSelects()

    if (editing.value) {
        methodologicalStrengthenings_store.$reset()
        v$.value.$reset()
        await fetch_methodological_strengthening().then(() => {
            const { created_at, id, assistants, status, reject_message, ...rest } = methodological_strengthening_services.data.one

            // const record = state.form.aggregates.assistants.findIndex((item) => item.id === payload)
            // state.form.aggregates.assistants.splice(record, 1)
            methodologicalStrengthenings_store.$patch((state) => {
                Object.assign(state.form, {
                    ...rest,
                    aggregates: {
                        assistants: assistants.map(({ pivot, ...rest }) => {
                            return {
                                ...rest
                            }
                        })
                    }
                })
            })

            instance.value.status = status
            instance.value.reject_message = reject_message
        })
            .catch(() => {
                mixins.not_found_by_id()
            })
    }
    else {
        methodologicalStrengthenings_store.$reset()
        v$.value.$reset()
        await fetch_consecutive()

    }
});

// [Submit] => Checking Payload before adding or editing
const onSubmit = async () => {
    const valid = await v$.value.$validate()

    if (valid) {
        if (editing.value) {
            await methodological_strengthening_services.update(methodological_strengthening_services.data.one.id, methodologicalStrengthenings_store.transpiled_data)
        }
        else {
            await fetch_consecutive().finally(() => {
            methodological_strengthening_services.create(methodologicalStrengthenings_store.transpiled_data).then(async (response) => {
                if (response.data.success) {
                    files.value.development_activity_image = []
                    files.value.evidence_participation_image = []
                    methodologicalStrengthenings_store.$reset()
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
};

const addFile = (err, val, name) => {
    if (err) return;
    const { file, filename } = val;
    form.value[name] = {
        name: "photo1",
        file,
        filename,
    };
};

const removeFile = (name) => {
    form.value[name] = null;
};

// Creating Overall composables
const getSingleLabel = (currOption, allOptions) => {
    if (allOptions.length > 0) {
        return allOptions.find((opt) => opt.value == currOption).label;
    } else {
        return "Sin Datos";
    }
};

const getSingleOption = (options) => {
    if (options.length > 0) {
        return options.map((opt) => opt.value);
    } else {
        return [];
    }
};

const assistants_table = ref([]);
const get_nac = computed(() => {
    return form.value.nac_id
})

watch(get_nac, async (new_val, old_val) => {
    if (new_val != '' && new_val != null) {
        if (new_val != old_val) {
            await get_profile_role_user_nac(new_val)
        }
    }
})


const get_profile_role_user_nac = async (id) => {
    await consecutive_services.getProfileRoleUserNac(id).then((response) => {
        if (response != undefined) {
            let assistants_data = response.data.items.original.assistants

            if (assistants_data.length > 0) {
                assistants_table.value = assistants_data
            } else {
                alerts.personalized('El nac seleccionado no poseé asistentes.', 'info')
                assistants_table.value = [];
                form.value.aggregates.assistants = [];
            }

        }
    })
}


</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
                {{
                    isEditing
                    ? `Edición de Fortalecimiento metodológico #${route.params.id}`
                    : `Fortalecimiento metodológico`
                }}
    </FormHeader>
    <BaseManagement v-if="isEditing ? permissions.methodologicalStrengthenings.management() : false" @send="
        (payload) =>
            mixins.methods.send_management(
                'methodological_strengthenings',
                route.params.id,
                payload
            )
    " class="mt-5" />
    <!-- Form Data -->
    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.methodologicalStrengthenings.no_edit()">
            <!-- <pre>{{ form }}</pre> -->
            <form @submit.prevent="onSubmit" class="grid gap-3 grid-cols-1 p-5 border-t border-slate-200/60">
                <!-- #1 -->
                <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
                    <BaseInput type="date" label="FECHA *" name="date" placeholder="Fecha" v-model="form.date"
                        :validator="v$" />
                    <BaseSelect label="NAC *" tooltip="Ingrese el NAC" placeholder="Seleccione" name="nac_id"
                        v-model="form.nac_id" :options="form_options.nacs" :validator="v$" />

                </section>

                <!-- #5 -->
                <section class="grid gap-5 grid-cols-1 md:grid-cols-2">
                    <BaseSelect label="DERECHO CULTURAL*" tooltip="Ingrese el derecho cultural" placeholder="Seleccione"
                        name="cultural_right_id" v-model="form.cultural_right_id" :options="form_options.cultural_rights"
                        :validator="v$" />

                    <BaseSelect label="LINEAMIENTOS *" tooltip="Ingrese el lineamientos" placeholder="Seleccione"
                        name="lineament_id" v-model="form.lineament_id" :options="form_options.lineaments"
                        :validator="v$" />

                    <BaseSelect label="ORIENTACIONES *" tooltip="Ingrese el orientación" placeholder="Seleccione"
                        name="orientation_id" v-model="form.orientation_id" :options="form_options.orientations"
                        :validator="v$" />

                    <BaseSelect label="VALOR *" tooltip="Ingrese el valor" placeholder="Seleccione" name="value"
                        v-model="form.value" :options="form_options.values" :validator="v$" />

                    <BaseTextarea class="col-span-full" label="OBSERVACIÓN *" placeholder="OBSERVACIÓN" name="comments"
                        v-model="form.comments" :validator="v$" rows="3" />
                </section>

                <!-- #6 -->
                <section class="grid gap-5 grid-cols-1 md:grid-cols-2">


                    <BaseFilepond label="FOTO DEL DESARROLLO *" tooltip="Arrastra una imagen JPG/PNG"
                        name="development_activity_image" ref="development_activity_image"
                        :files="files.development_activity_image" :validator="v$"
                        @addfile="(err, val) => addFile(err, val, 'development_activity_image')"
                        @removefile="removeFile('development_activity_image')"
                        :to_edit="methodological_strengthening_services.data.one.development_activity_image"
                        v-model="form.development_activity_image" />

                    <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" tooltip="Arrastra una imagen JPG/PNG"
                        name="evidence_participation_image" ref="evidence_participation_image"
                        :files="files.evidence_participation_image" :validator="v$" @addfile="
                            (err, val) => addFile(err, val, 'evidence_participation_image')
                        " @removefile="removeFile('evidence_participation_image')"
                        :to_edit="methodological_strengthening_services.data.one.evidence_participation_image"
                        v-model="form.evidence_participation_image" />

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
                        <Aggregates @pop="(id) => methodologicalStrengthenings_store.pop_aggregate(id)"
                            @push="(aggregate) => methodologicalStrengthenings_store.push_aggregate(aggregate)"
                            :headers="users_headers" :aggregates="form.aggregates.assistants" :options="assistants_table"
                            :validator="v$" name="aggregates">
                        </Aggregates>
                    </div>
                </section>
                <div class="flex justify-center">
                    <button type="submit" class="btn btn-primary w-24 mr-1 mb-2" :disabled="loading">
                        Ingresar
                    </button>
                </div>
            </form>
        </fieldset>
    </div>
</template>
