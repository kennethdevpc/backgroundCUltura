<script lang="ts" setup>
import services from "../../../services/inscriptions.service";
import type { Header, Item } from "vue3-easy-data-table";
import { useRouter } from "vue-router";
import permissions from "@/permissions";
import mixins from "@/mixins";
import BaseCrudPDF from "@/components/base/BaseCrudPDF.vue";

const inscription_services = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const createInscription = () => {
  router.push({ name: `${routeName.value}.create` });
};

const editInscription = (id: string | number) => {
  router.push({ name: `${routeName.value}.edit`, params: { id: id } });
};

async function deleteInscription(id: string | number) {
  inscription_services.destroy(id);
  await fetchData();
}

const headers: Header[] = [
 // { text: "#", value: "id" },
  { text: "CONSECUTIVO", value: "consecutive" },
  { text: "BENEFICIARIO", value: "benefiary.full_name" },
  { text: "DOCUMENTO", value: "benefiary.document_number" },
  { text: "ACUDIENTE", value: "attendant.full_name" },
  { text: "DOCUMENTO DE ACUDIENTE", value: "attendant.document_number" },
  { text: "CREADOR POR", value: "user.name" },
  { text: "ROL CREADOR", value: "role" },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ESTADO", value: "status" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const {
    id,
    consecutive,
    benefiary,
    sociodemographic_characterization_benefiary,
    health_data_benefiary,
    attendant,
    sociodemographic_characterization_attendant,
    health_data_attendant,
  } = item;
  return {
    id,
    consecutive,
    sections: {
      beneficiary: {
        title: "Beneficiario",
        fields: {
          "NOMBRE COMPLETO": benefiary.full_name,
          VINCULACIÓN: mixins.get_option_label(
            "linkage_projects",
            benefiary.linkage_project
          ),
          "GRUPO":benefiary.group?.name ?? '',
          "NOMBRE DE REFERIDO": benefiary.referrer_name,
          "INSTITUCIÓN, ENTIDAD O REFERIDO":
            benefiary.institution_entity_referred,
          "TIPO DE DOC": mixins.get_option_label(
            "type_documents",
            benefiary.type_document
          ),
          "NUMERO DE DOC": benefiary.document_number,
          NAC: mixins.get_option_label("nacs", benefiary.nac_id),
          BARRIO: mixins.get_option_label(
            "neighborhoods",
            benefiary.neighborhood_id
          ),
          "OTRO BARRIO": benefiary.neighborhood_new,
          ZONA: mixins.get_option_label("zones", benefiary.zone),
          ESTRATO: mixins.get_option_label("stratums", benefiary.stratum),
          TELÉFONO: benefiary.phone,
          EMAIL: benefiary.email,
          IMAGEN: benefiary.file,
        },
      },
      beneficiary_socio: {
        title: "Beneficiario - Características Sociodemograficas",
        fields: {
          GENERO: mixins.get_option_label(
            "genders",
            sociodemographic_characterization_benefiary.gender
          ),
          EDAD: sociodemographic_characterization_benefiary.age,
          "ACTUALMENTE ESTUDIA":
            sociodemographic_characterization_benefiary.decision_study == 1
              ? "SI"
              : "NO",
          "NIVEL EDUCATIVO ALCANZADO": mixins.get_option_label(
            "educational_levels",
            sociodemographic_characterization_benefiary.educational_level
          ),
          "PRESENTA DISCAPACIDAD":
            sociodemographic_characterization_benefiary.decision_disability == 1
              ? "SI"
              : "NO",
          "TIPO DE DISCAPACIDAD": mixins.get_option_label(
            "disability_types",
            sociodemographic_characterization_benefiary.disability_type
          ),
          "TIPO DE ETNIA": mixins.get_option_label(
            "ethnicities",
            sociodemographic_characterization_benefiary.ethnicity
          ),
          CONDICIÓN: mixins.get_option_label(
            "conditions",
            sociodemographic_characterization_benefiary.condition
          ),
        },
      },
      beneficiary_health_data: {
        title: "Beneficiario - Datos de salud",
        fields: {
          "REGIMEN DE SALUD": mixins.get_option_label(
            "medical_services",
            health_data_benefiary.medical_service
          ),
          EPS: mixins.get_option_label(
            "entity_names",
            health_data_benefiary.entity_name_id
          ),
          "OTRO EPS": health_data_benefiary.other_entity_name,
          "ESTADO DE SALUD": mixins.get_option_label(
            "health_conditions",
            health_data_benefiary.health_condition
          ),
        },
      },
      attendant: {
        title: "Acudiente",
        fields: {
          PARENTESCO: mixins.get_option_label(
            "relationships",
            attendant.relationship
          ),
          "NOMBRE COMPLETO": attendant.full_name,
          "TIPO DE DOC": mixins.get_option_label(
            "type_documents",
            attendant.type_document
          ),
          "NUMERO DE DOC": attendant.document_number,
          ZONA: mixins.get_option_label("zones", attendant.zone),
          TELÉFONO: attendant.phone,
          EMAIL: attendant.email,
        },
      },
      attendant_socio: {
        title: "Acudiente - Características Sociodemograficas",
        fields: {
          GENERO: mixins.get_option_label(
            "genders",
            sociodemographic_characterization_benefiary.gender
          ),
          EDAD: sociodemographic_characterization_attendant.age,
          "ACTUALMENTE ESTUDIA":
            sociodemographic_characterization_attendant.decision_study == 1
              ? "SI"
              : "NO",
          "NIVEL EDUCATIVO ALCANZADO": mixins.get_option_label(
            "educational_levels",
            sociodemographic_characterization_attendant.educational_level
          ),
          "PRESENTA DISCAPACIDAD":
            sociodemographic_characterization_attendant.decision_disability == 1
              ? "SI"
              : "NO",
          "TIPO DE DISCAPACIDAD": mixins.get_option_label(
            "disability_types",
            sociodemographic_characterization_attendant.disability_type
          ),
          "TIPO DE ETNIA": mixins.get_option_label(
            "ethnicities",
            sociodemographic_characterization_attendant.ethnicity
          ),
          CONDICIÓN: mixins.get_option_label(
            "conditions",
            sociodemographic_characterization_attendant.condition
          ),
        },
      },
      attendant_health_data: {
        title: "Acudiente - Datos de salud",
        fields: {
          "REGIMEN DE SALUD": mixins.get_option_label(
            "medical_services",
            health_data_attendant.medical_service
          ),
          EPS: mixins.get_option_label(
            "entity_names",
            health_data_attendant.entity_name_id
          ),
          "OTRO EPS": health_data_benefiary.other_entity_name,
          "ESTADO DE SALUD": mixins.get_option_label(
            "health_conditions",
            health_data_attendant.health_condition
          ),
        },
      },
    },
  };
};
let page = ref(1)
let pageCount = ref(0)
const filter = ref({})

async function fetchData() {
  const searchParams = Object.keys(filter.value).length 
  ? new URLSearchParams(filter.value)
  : null
  await inscription_services.get(page.value, searchParams).then(() => {
    items.value = inscription_services.data.all;
    pageCount.value = inscription_services.data.count_page
  });
}
function updateData(values) {
  page.value = 1
  filter.value = values
  fetchData()
}
function updatePage()  {
  fetchData()
}
onMounted(async () => {
  await fetchData();
  items.value.map((item) => {
    return {
      ...item,
      actions: "Acciones",
    };
  });
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Inscripciones</h2>
    <div
      v-if="permissions.inscriptions.create()"
      class="w-full sm:w-auto flex mt-4 sm:mt-0"
    >
      <button class="btn btn-primary shadow-md mr-2" @click="createInscription">
        Hacer una inscripción
      </button>
    </div>
  </div>
  <div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-center p-2 text-base">
			<v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
				@update:modelValue="updatePage" />
		</div>
    <BaseCrudPDF
      module="inscriptions"
      :headers="headers"
      :items="items"
      :item_see_fnc="item_map"
      label="la inscripción"
      :management_permissions="permissions.inscriptions.crud_management()"
      :on-delete-fnc="deleteInscription"
      @change_filter="updateData"
    />
  </div>
</template>
