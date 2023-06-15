import { defineStore } from "pinia"
import onboardingService from "../services/onboarding.service"
import { roles } from "@/mixins"
import Swal from "sweetalert2"

const serializer = {
  read: (v: any) => v ? JSON.parse(v) : null,
  write: (v: any) => JSON.stringify(v),
}

interface roleT {
  id: number
  name: string
  slug: string
  description: string
  'full-access': string
  public: number
  created_at: string
  updated_at: string
  deleted_at: string
  pivot: {
    user_id: number
    role_id: number
  }
  permissions: Array<{
    id: number
    name: string
    slug: string
    controller: string,
    description: string,
  }>
}

interface userT {
  id: number
  name: string
  document_number: string
  profile_photo_path: string
  profile?: {
    contractor_full_name: string
  }
  email: string
  email_verified_at: string
  two_factor_secret: string
  two_factor_recovery_codes: string
  status: number
  created_at: string
  updated_at: string
  deleted_at: string
  roles: roleT[]
}

interface stateT {
  user: userT
  roles: Object
  isAuth: boolean
}

export const useOnboardingStore = defineStore("onboarding", {
  state: () => ({
    user: {} as userT,
    isAuth: false,
  }),
  getters: {
    get_user: (state) => {
      return state.user
    },
    get_user_role: (state) => {
      if (state.user.roles) {
        return state.user?.roles[0]
      }
      return "";
    },
    is_role: (state) => {
      return (roleSlug: roles) => {
        if (state.user.roles) {
          const validate = state.user.roles.find((role) => role.slug === roleSlug)
          return (validate != undefined) ? true : false
        } else {
          return false
        }
      }
    }
  },
  actions: {
    async login(form) {
      try {
        const result = await onboardingService.login(form);

        // Si el usuario no tiene perfil
        if (!result?.data?.user?.profile) {
          Swal.fire('Oops!', "El usuario no tiene perfil, por favor comunicarse con un administrador, para que te asigne un perfil", 'warning')
          return null;
        }
        if (result.status == 200) {
          this.set_auth(result.data.user)
          // localStorage.setItem("user", JSON.stringify(result.data.user));
          // localStorage.setItem("roles", JSON.stringify(result.data.data.roles));
          // localStorage.setItem("isAuth", JSON.stringify(true));

          // this.user = result.data.user;
          // this.roles = result.data.data.roles;
          // this.isAuth = true;
        }
        return result
      } catch (error) {
        return error.response
      }
    },
    async logout() {
      try {
        const result = await onboardingService.logout();
        this.unset_auth()
        return result
      } catch (error) {

        return error.response
      }
    },
    set_auth(user: userT) {
      this.$patch(state => {
        state.user = user,
          state.isAuth = true
      })
    },
    unset_auth() {
      this.$patch(state => {
        state.user = {},
          state.isAuth = false
      })
    },
  },
  persist: true
});
