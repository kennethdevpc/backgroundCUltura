<script setup>
// Importing Vuelidate & Rules
import { useVuelidate } from "@vuelidate/core";

// Importing Components
import BaseInput from "@/components/base/Input.vue";
import BaseRadio from "@/components/base/Radio.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseBackButton from "../../../components/base/BaseBackButton.vue";

// Store
import { useRoles } from "../../../stores/roles";
import { storeToRefs } from "pinia";

import roleService from "../../../services/role.service";
import permissionService from "../../../services/permission.service";
import permissionRoleService from "../../../services/permissionRole.service";
import mixins from "@/mixins";
import alerts from "@/utils/alerts";
// Extracting Services
const role_service = roleService();
const permission_service = permissionService();
const permissionsGroup = ref([]);
const permissions = ref([]);

// Extracting Store Data
const roles = useRoles();
const { form, form_rules, form_options } = storeToRefs(roles);
// Using Vuelidate (with Store form & form)
const v$ = useVuelidate(form_rules, form, { $autoDirty: true });
// Routes
const route = useRoute();
// Watch General Data for Validate in Store
watch(roles, async () => {
  const valid = await v$.value.$validate();

  if (valid) roles.toggleGeneralData(true);
  else roles.toggleGeneralData(false);
});

const isEditing = computed(() => {
  if (route.params.id) return true;
  else return false;
});

const fetchOne = async () => {
  return await role_service.getOne(route.params.id);
};
const getAllPermissions = async () => {
  await permission_service.get(true);
};

const assignPermissions = async () => {
  const form = {
    permissions: permissions.value,
    role_id: route.params.id,
  };
  const result = await permissionRoleService.store(form);
  if (result.status === 200) {
    alerts.custom('', result.data.message, "success")
  }
};
onMounted(async () => {
  if (route.params.id) {
    roles.$reset();
    await fetchOne().catch(() => {
      mixins.not_found_by_id()
    })
    await getAllPermissions();
    const { id, created_at, updated_at, ...rest } = role_service.dataOne.value;
    if (role_service.permissionsRole.value.length) {
      permissions.value = role_service.permissionsRole.value;
    }

    permissionsGroup.value = permission_service.data.value;

    form.value = { ...rest };
  } else {
    roles.$reset();
  }
});
const onSubmit = async () => {
  if (isEditing.value)
    await role_service.update(role_service.dataOne.value.id, form.value);
  else await role_service.create(form.value);
};
</script>

<template>
  <div class="flex flex-col items-start gap-1 mt-8 intro-y">
    <BaseBackButton />
    <h2 v-if="isEditing" class="mr-auto text-lg font-medium">
      Edicion de Rol: <b>{{ role_service.dataOne.value.name }}</b>
    </h2>
    <h2 v-else class="mr-auto text-lg font-medium">Creación de Rol</h2>
  </div>
  <div class="mt-5 intro-y box">
    <form @submit.prevent="onSubmit" class="px-5 py-10 border-t sm:px-10 border-slate-200/60 dark:border-darkmode-400">
      <section id="generalData"
        class="flex flex-col gap-6 mb-8 lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 justify-evenly">
        <div class="w-full">
          <BaseInput type="text" label="Nombre *" tooltip="Ingrese el nombre" placeholder="Nombre" name="name"
            v-model="form.name" :validator="v$" />
        </div>
        <div class="w-full">
          <BaseInput type="text" label="Slug *" tooltip="Ingrese el slug" placeholder="Slug" name="slug"
            v-model="form.slug" :validator="v$" />
        </div>
        <div class="w-full lg:col-span-2 xl:col-span-2">
          <BaseTextarea label="Descripcion *" tooltip="Ingrese la descripcion" placeholder="Descripcion"
            name="description" v-model="form.description" :validator="v$" rows="3" />
        </div>
        <div class="w-full">
          <BaseRadio label="Publico" tooltip="Sera publico?" name="public" v-model="form.public"
            :options="form_options.public" :validator="v$" />
        </div>
        <div class="w-full">
          <BaseRadio label="Acceso Completo" tooltip="Tendra acceso completo?" name="full-access"
            v-model="form['full-access']" :options="form_options.full_access" :validator="v$" />
        </div>
      </section>
      <div class="flex justify-center">
        <button :disabled="!roles.isGeneralData" type="submit" class="w-24 ml-2 btn btn-primary">
          Ingresar
        </button>
      </div>
    </form>
  </div>
  <section v-if="isEditing" class="mt-5">
    <h2 class="mr-auto text-lg font-medium">Asignar permisos</h2>
    <div v-if="Object.keys(permissionsGroup).length" class="p-5 mt-5 intro-y box">
      <div v-for="(group, key) in permissionsGroup" :key="key" class="flex flex-col mb-5 space-y-2">
        <h3 class="text-lg font-semibold text-gray-900 capitalize">
          {{ key }}
        </h3>
        <div class="grid grid-cols-6 gap-5">
          <div class="col-span-2" v-for="permission in group" :key="permission.id">
            <input :id="permission.name" type="checkbox" class="mr-2 border form-check-input" :value="permission.id"
              v-model="permissions" />
            <label class="font-semibold capitalize cursor-pointer select-none" :for="permission.name">{{
              permission.name
            }}</label>
          </div>
        </div>
      </div>
      <button class="px-8 py-2 font-bold text-white bg-green-500 rounded-md" @click="assignPermissions">
        Guardar permisos
      </button>
    </div>
  </section>
</template>
