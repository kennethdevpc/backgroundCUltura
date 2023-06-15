<script lang="ts" setup>
// @ts-ignore => Ignore TS erros in the line below
import vueFilePond, { setOptions } from "vue-filepond";
import { Validation } from "@vuelidate/core";
import BaseInput from "@/components/base/Input.vue";

// [Filepond] => Import FilePond styles & mage preview plugin styles
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// [Filepond] => Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// [TypeScript] => Defining Props
interface Props {
  name: string;
  label?: string;
  tooltip?: string;
  modelValue?: string | number | object | boolean;
  to_edit?: string;
  accept_pdf?: boolean;
  accept_only_pdf?: boolean;
  accept_word?: boolean;
  accept_docs?: boolean;
  validator?: Validation;
}

const props = withDefaults(defineProps<Props>(), {
  accept_pdf: false,
  accept_only_pdf: false,
  accept_word: false,
  accept_docs: false,
});

// [TypeScript] => Defining Emits
interface Emits {
  (e: "update:modelValue", value: string): void;
}

const emit = defineEmits<Emits>();

// [Filepond] => Creating Component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

// [Filepond] => Setting Global Options
setOptions({
  credits: false,
  imagePreviewMinHeight: "150",
  imagePreviewMaxHeight: "150",
  labelIdle: "Arrastra el archivo aquí",
  labelInvalidField: "Formato de archivo inválido",
  labelFileLoading: "Cargando",
  labelFileLoadError: "Error durante la carga",
  labelFileProcessingError: "Error durante la carga",
  labelFileProcessing: "Subiendo",
  labelTapToRetry: "Presiona para intentar de nuevo",
  labelTapToCancel: "Presiona para cancelar",
  labelButtonRemoveItem: "Eliminar",
  labelMaxFileSizeExceeded: "El archivo es muy grande",
  labelFileTypeNotAllowed: "Extensión del archivo inválida",
});

const accepted_files = computed(() => {
  return props.accept_pdf
    ? ["application/pdf", "image/png", "image/jpeg", "image/webp", "image/jpg"]
    : props.accept_word
      ? [
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      ]
      : props.accept_only_pdf
        ? ["application/pdf"]
        : props.accept_docs
          ? [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          ]
          : ["image/png", "image/jpeg", "image/webp", "image/jpg"];
});

// Detecting v-model changes.
const onInput = (e) => {
  emit("update:modelValue", e.target.value);
};

const preview_edit = computed(() => {
  // return `${import.meta.env.VITE_BASE_URL}${props.to_edit}`
  //VITE_BASE_URL
  return props.to_edit.includes("http")
    ? props.to_edit
    : `${import.meta.env.VITE_BASE_IMAGE_URL}/storage/${props.to_edit}`;
});
</script>

<template>
  <div class="BaseFilepond">
    <!-- Label -->
    <label v-if="label" :for="name" class="form-label font-bold">{{
      label
    }}</label>
    <!-- Component -->
    <div :class="to_edit ? 'grid-cols-1 sm:grid-cols-[1fr_auto]' : 'grid-cols-1'" class="grid gap-2 items-start">
      <file-pond :accepted-file-types="accepted_files" v-bind="$attrs" />
      <div v-if="to_edit" class="relative intro-x flex justify-center w-full h-48 rounded select-none">
        <p class="absolute top-2 right-2 z-30 bg-primary text-white p-2 rounded">
          A reemplazar
        </p>
        <div class="absolute z-0 bg-stripes rounded w-full h-full opacity-50" />
        <template v-if="preview_edit.includes('pdf')">
          <div class="grid place-content-center w-48 h-48 relative rounded-lg z-10">
            <a :href="preview_edit" target="_blank"
              class="flex flex-col items-center p-5 bg-slate-200 fill-primary active:bg-primary active:fill-slate-200 rounded-lg transition">
              <svg width="32" height="32" viewBox="0 0 256 256">
                <path
                  d="M48 136a8 8 0 0 0 8-8V40h88v48a8 8 0 0 0 8 8h48v32a8 8 0 0 0 16 0V88a7.7 7.7 0 0 0-2.4-5.7l-55.9-56A8.1 8.1 0 0 0 152 24H56a16 16 0 0 0-16 16v88a8 8 0 0 0 8 8Zm112-84.7L188.7 80H160ZM64 160H48a8 8 0 0 0-8 8v48a8 8 0 0 0 16 0v-8h8a24 24 0 0 0 0-48Zm0 32h-8v-16h8a8 8 0 0 1 0 16Zm132-16v12h16a8 8 0 0 1 0 16h-16v12a8 8 0 0 1-16 0v-48a8 8 0 0 1 8-8h28a8 8 0 0 1 0 16Zm-68-16h-14a8 8 0 0 0-8 8v48a8 8 0 0 0 8 8h14a32 32 0 0 0 0-64Zm0 48h-6v-32h6a16 16 0 0 1 0 32Z" />
              </svg>
            </a>
          </div>
        </template>
        <template v-else>
          <img :src="preview_edit" class="object-center object-contain w-48 h-48 relative rounded-lg z-10" alt="" />
        </template>
      </div>
    </div>
    <!-- [Component] => Avoiding DRY: Don't Repeat Yourself -->
    <template v-if="validator">
      <BaseInput type="hidden" :name="name" :tooltip="tooltip" :model-value="modelValue" :validator="validator"
        @input="onInput" />
    </template>
  </div>
</template>
