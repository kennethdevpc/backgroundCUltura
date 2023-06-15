<script lang="ts" setup>
import BaseCrud from "../../../components/base/BaseCrud.vue";
import type { Header, Item } from "vue3-easy-data-table";
import axios from "axios";
import services from "../../../services/polls.service";
import mixins from "@/mixins";
const poll_services = services();

const router = useRouter();
const route = useRoute();

const routeName = computed(() => {
  return String(route.name).split(".")[0];
});

const isAdmin = computed(
  () => mixins.computed.is_role("root") || mixins.computed.is_role("super.root")
);

const create = () => {
  router.push({ name: `${routeName.value}.create` });
};

async function destroy(id: string | number) {
  await poll_services.destroy(id);
  fetchData();
}

const headers: Header[] = [
  { text: "#", value: "id" },
  { text: "BARRIO", value: "neighborhood" },
  { text: "OTRO BARRIO", value: "other_neighborhoods" },
  { text: "ENTIDAD", value: "entity_name.name" },
  { text: "GÉNERO", value: "gender" },
  { text: "EDAD", value: "age" },
  { text: "FECHA DE NACIMIENTO", value: "birth_date" },
  { text: "CREACIÓN", value: "created_at" },
  { text: "ACCIONES", value: "actions" },
];

const items = ref<Item[]>([]);

const item_map = (item: { [key: string]: any }) => {
  const { id } = item;
  let consecutive = `Encuesta #${id}`;
  // console.log('item.neighborhood',item?.neighborhood?.name ?? '');
  return {
    id,
    consecutive,
    sections: {
      general: {
        title: "Datos Generales",
        fields: {
          GENERO: mixins.get_option_label("genders", item.gender),
          "FECHA DE NACIMIENTO": item.birth_date,
          EDAD: item.age,
          "ESTADO CIVIL": mixins.get_option_label(
            "marital_status",
            item.marital_state
          ),
          ESTRATO: item.stratum,
          BARRIO: item?.neighborhood?.name,
          "OTRO BARRIO": item.other_neighborhoods ?? "",
          TELÉFONO: item.phone,
          "CORREO ELECTRÓNICO": item.email,
          "NUMERO DE HIJOS": item.number_children,
          "PERSONAS A CARGO": item.dependents,
          "PARENTESCO CON JEFE DE HOGAR": mixins.get_option_label(
            "relationship_households",
            item.relationship_head_household
          ),
          "ETNIA QUE LE REPRESENTA": mixins.get_option_label(
            "ethnicities",
            item.ethnicity
          ),
          "VICTIMA DE CONFLICTO ARMADO":
            item.victim_armed_conflict == 1 ? "SI" : "NO",
          "REGISTRO ÚNICO DE VICTIMAS": mixins.get_option_label(
            "single_registry_victims",
            item.single_registry_victims
          ),
          "ESTUDIA ACTUALMENTE": item.study == 1 ? "SI" : "NO",
          "EDUCACIÓN ALCANZADA": mixins.get_option_label(
            "educational_levels",
            item.educational_level
          ),
          "REGIMEN DE SALUD": mixins.get_option_label(
            "medical_services",
            item.medical_service
          ),
          EPS: item.entity_name.name,
          "ESTADO DE SALUD": mixins.get_option_label(
            "health_conditions",
            item.health_condition
          ),
          "PADECE ENFERMEDAD": item.suffers_disease == 1 ? "SI" : "NO",
          "TIPO DE ENFERMEDAD": mixins.get_option_label(
            "disease_types",
            item.type_disease
          ),
          "OTRO TIPO DE ENFERMEDAD": item.other_disease_type,
          "PADECE DISCAPACIDAD": item.disability == 1 ? "SI" : "NO",
          "TIPO DE DISCAPACIDAD": mixins.get_option_label(
            "disability_types",
            item.disability_type
          ),
          "TOMA MEDICAMENTOS": item.takes_medication == 1 ? "SI" : "NO",
          "DISCAPACIDAD HA SIDO VALORADA":
            item.assessed_disability == 1 ? "SI" : "NO",
          "RECIBE TERAPIA": item.receives_therapy == 1 ? "SI" : "NO",
          "ARTE QUE DESEMPEÑA": mixins.get_option_label(
            "expertises",
            item.expertises
          ),
          "Experiencia en el sector artístico (Años)": item.artistic_experience,
          "PERTENECE A UN GRUPO ARTÍSTICO":
            item.artistic_group == 1 ? "SI" : "NO",
          "NOMBRE DEL GRUPO": item.artistic_group_name,
          "ROL EN EL GRUPO": item.role_artistic_group,
          ESTADO: item.status,
          "MENSAJE DE RECHAZO": item.reject_message,
        },
      },
    },
  };
};

let page = ref(1);
let pageCount = ref(0);
const filter = ref({});

async function fetchData() {
  const searchParams = Object.keys(filter.value).length
    ? new URLSearchParams(filter.value)
    : null;
  await poll_services.get(page.value, searchParams);
  items.value = poll_services.data.all;
  pageCount.value = poll_services.data.count_page;
}

function updateData(values) {
  page.value = 1;
  filter.value = values;
  fetchData();
}

function updatePage() {
  fetchData();
}

const to_one = computed(() => {
  if (poll_services.data.all.at(0)) {
    return item_map(poll_services.data.all.at(0));
  } else {
    return null;
  }
});

onBeforeMount(async () => {
  if (isAdmin.value) {
    await fetchData();
    items.value.map((item) => {
      return {
        ...item,
        actions: "Acciones",
      };
    });
  } else {
    await poll_services.get().then(() => {
      items.value = poll_services.data.all;
      //  console.log(poll_services.data.all)
    });
  }
});
</script>

<template>
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Encuesta</h2>
    <!-- <div v-if="!mixins.computed.is_role('gestores_culturales')" class="w-full sm:w-auto flex mt-4 sm:mt-0"> -->
    <template v-if="isAdmin || (!isAdmin && poll_services.data.all.length < 1)">
      <button class="btn btn-primary shadow-md mr-2" @click="create">
        Hacer encuesta
      </button>
    </template>
    <!-- </div> -->
  </div>
  <div class="intro-y box mt-5 p-5">
    <div class="flex items-center justify-center p-2 text-base">
      <v-pagination v-model="page" :pages="pageCount" :range-size="1" active-color="#DCEDFF"
        @update:modelValue="updatePage" />
    </div>
    <template v-if="isAdmin">
      <BaseCrud :headers="headers" :items="items" :item_see_fnc="item_map" label="la encuesta" :on-delete-fnc="destroy"
        @change_status="fetchData()" @change_filter="updateData" :server_options="{ page: 1, rowsPerPage: 15 }" />
    </template>
    <template v-else>
      <div class="flex justify-center">
        <template v-if="poll_services.data.all.length < 1">
          <p class="text-center">Haz tu encuesta para visualizar aqui...</p>
        </template>
        <template v-else>
          <div class="grid grid-cols-1 gap-6 divide-dashed divide-y-2 divide-slate-200">
            <template v-if="to_one">
              <template v-for="section in to_one.sections">
                <section class="flex flex-col gap-2 justify-evenly py-3 divide-dashed divide-y-2 divide-slate-200">
                  <h3 class="font-bold uppercase text-center">
                    {{ section.title }}
                  </h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 justify-evenly pt-6">
                    <template v-for="key in Object.keys(section.fields)">
                      <template v-if="section.fields[key] == '' ||
                        section.fields[key] == null ||
                        section.fields[key] == 'No encontrad@'
                        " />
                      <template v-else>
                        <div class="flex flex-col gap-1 justify-start">
                          <h3 class="text-xs opacity-80">
                            {{ key }}
                          </h3>
                          <p class="font-semibold text-sm text-left break-words">
                            {{ section.fields[key] }}
                          </p>
                        </div>
                      </template>
                    </template>
                  </div>
                </section>
              </template>
            </template>
            <template v-else>
              <p class="text-center">Error en la consulta de datos...</p>
            </template>
          </div>
        </template>
      </div>
    </template>
  </div>
</template>
