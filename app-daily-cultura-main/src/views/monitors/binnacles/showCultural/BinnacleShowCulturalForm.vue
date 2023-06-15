<script setup lang="ts">
// Importing Vuelidate & Rules
import { useVuelidate } from '@vuelidate/core';

// store
import { storeToRefs } from "pinia";
import { useBinnacleShowCultural } from '@/stores/binnacleShowCultural'

// Importing component
import BaseInput from '@/components/base/Input.vue'
import BaseTextarea from '@/components/base/Textarea.vue'
import BaseRadio from '@/components/base/Radio.vue'
import BaseFilepond from '@/components/base/Filepond.vue'

// Import services
import service from '@/services/binnacle_show_cultural.service'
import serviceGlobal from '@/services/generals.service';
import alerts from '@/utils/alerts';
import { scroll_top } from '@/utils/scroll';
import Management from '@/components/base/Management.vue';
import permissions from '@/permissions';
import mixins from '@/mixins';
import FormHeader from '@/components/base/FormHeader.vue';

// Extracting Store Data
const binnacleshowcultual_store = useBinnacleShowCultural()
const { create, update, getOne } = service();
const { getConsecutive } = serviceGlobal();
const { params: { id } } = useRoute();
const router = useRouter();

const { form, form_rules, form_options, filesOne } = storeToRefs(binnacleshowcultual_store)
const disabledButton = ref(false);
// Editing logic
const instance = reactive({
    consecutive: '',
    status: '',
    reject_message: '',
});

const files = reactive({
    I: [],
    II: [],
    III: []
})

const fetch_consecutive = async () => {
    const { data } = await getConsecutive('binnacle_cultural_show', 'BCS');
    form.value.consecutive = data;
}

// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true, $lazy: true })

onBeforeMount(async () => {
    if (id) {
        const { data: { items: data } } = await getOne(id);
        instance.reject_message = data.reject_message;
        instance.status = data.status;
        form.value = data;
        filesOne.value.aforo_pdf = data.aforo_pdf;
        filesOne.value.development_activity_image = data.development_activity_image;
        filesOne.value.evidence_participation_image = data.evidence_participation_image;
    } else {
        await fetch_consecutive();
    }
})

const onSubmit = async () => {
    const valid = await v$.value.$validate();
    if (valid) {
        disabledButton.value = true;
        if (id) {
            const response = await update(id, form.value).finally(() => {
                disabledButton.value = false
            })
            if (response.data) {
                router.push({ name: "binnacleculturalshow.index" });
            }
        } else {
            await fetch_consecutive().finally(() => {
                create(form.value).then((response) => {
                    if (response.data.success) {
                        binnacleshowcultual_store.$reset();
                        v$.value.$reset();
                        fetch_consecutive();
                        scroll_top();
                        files.I = [];
                        files.II = [];
                        files.III = [];
                    }
                }).finally(() => {
                    disabledButton.value = false
                })
            });
        }
    } else {
        alerts.validation()
    }
}


const addDevActivityImg = (err, val) => {
    if (err) return
    const { file } = val
    form.value.development_activity_image = file;
}

const addParticipationImg = (err, val) => {
    if (err) return
    const { file } = val
    form.value.evidence_participation_image = file;
}

const addAforoFile = (err, val) => {
    if (err) return
    const { file } = val
    form.value.aforo_pdf = file;
}

const removeDevActivityImg = () => {
    form.value.development_activity_image = null;
}

const removeParticipationImg = () => {
    form.value.evidence_participation_image = null;
}

const removeAforoFile = () => {
    form.value.aforo_pdf = null;
}

</script>

<template>
    <FormHeader
        :state="{ consecutive: form.consecutive, status: instance.status, reject_message: instance.reject_message }">
        Bitacora show cultural
    </FormHeader>

    <Management class="mt-5" v-if="id ? permissions.binnacleculturalshow.management() : false"
        @send="(payload) => mixins.methods.send_management('binnacle_cultural_show', id, payload)" />

    <div class="intro-y box mt-5">
        <fieldset :disabled="permissions.binnacleculturalshow.no_edit()">
            <form @submit.prevent="onSubmit" class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                <section id="general_data" class=" mb-8">
                    <div class="w-full">
                        <BaseInput type="date" label="FECHA *" placeholder="Fecha" name="date_range"
                            v-model="form.date_range" :validator="v$" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="ACTIVIDAD *" placeholder="ingrese Actividad" name="activity"
                            v-model="form.activity" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="EXPERTICIA ARTISTICA *" placeholder="Ingrese experticia artistica "
                            name="expertise" v-model="form.expertise" :validator="v$" rows="5" />
                    </div>
                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="ESPECÍFICA TU PARTICIPACIÓN ARTISTICA EN EL EVENTO *"
                            placeholder="Ingrese experticia artistica " name="artistic_participation"
                            v-model="form.artistic_participation" :validator="v$" rows="5" />
                    </div>

                    <div class="lg:col-span-1 xl:col-span-2">

                        <BaseRadio label="¿FUE EXITOSA TU PARTICIPACIÓN? *" tooltip="" name="reached_target"
                            v-model="form.reached_target" :options="form_options.reached_target" :validator="v$" required />
                    </div>

                    <div class="lg:col-span-1 xl:col-span-2">
                        <BaseTextarea label="SUSTENTE : *" placeholder="Sustente " name="sustein" v-model="form.sustein"
                            :validator="v$" rows="5" />
                    </div>
                </section>
                <section id="Images" class="mb-8">
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">

                        <BaseFilepond label="FOTO DEL DESARROLLO *" name="development_activity_image"
                            v-model="form.development_activity_image" :validator="v$" @addfile="addDevActivityImg"
                            @removefile="removeDevActivityImg" :files="files.I"
                            :to_edit="filesOne.development_activity_image" />

                        <BaseFilepond label="EVIDENCIA DE PARTICIPACIÓN *" name="evidence_participation_image"
                            v-model="form.evidence_participation_image" :validator="v$" @addfile="addParticipationImg"
                            @removefile="removeParticipationImg" :files="files.II"
                            :to_edit="filesOne.evidence_participation_image" />

                    </div>
                    <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly">

                        <BaseFilepond label="DOCUMENTO DEL AFORO *" name="aforo_pdf" v-model="form.aforo_pdf"
                            :validator="v$" @addfile="addAforoFile" @removefile="removeAforoFile" :files="files.III"
                            accept_only_pdf :to_edit="filesOne.aforo_pdf" />

                        <BaseInput type="number" label="CANTIDAD (NUMERO DE ASISTENTES) *" placeholder="Ingrese cantidad"
                            name="number_attendees" v-model="form.number_attendees" :validator="v$" />

                    </div>
                </section>
                <div class="flex justify-center">
                    <button type="submit" :disabled="disabledButton" class="btn btn-primary w-24 ml-2">
                        {{ id ? "Actualizar" : "Ingresar" }}
                    </button>
                </div>


            </form>
        </fieldset>
    </div>
</template>


<style></style>