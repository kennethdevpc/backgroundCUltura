import Swal, { SweetAlertIcon } from "sweetalert2"

const alerts = {
  validation: () => {
    Swal.fire('Validación', 'Por favor verifique los campos marcados en naranja.', 'error')
  },
  custom_validation: (message: string) => {
    Swal.fire('Validación', message, 'error')
  },
  inaccesible: () => {
    Swal.fire('Inaccesible', 'No puedes acceder a esta pagina.', 'warning')
  },
  create: () => {
    Swal.fire('', 'Creación exitosa!', 'success')
  },
  update: () => {
    Swal.fire('', 'Edición exitosa!', 'success')
  },
  destroy: (module, id) => {
    Swal.fire('', `${module} #${id} eliminad@!`, 'info')
  },
  saved_permissions: () => {
    Swal.fire('', 'Permisos guardados!', 'success')
  },
  send_failed: (message: string) => {
    Swal.fire('Error en Servidor', message, 'warning')
  },
  custom: (title: string, html: string, icon: SweetAlertIcon) => {
    Swal.fire(title, html, icon)
  },
  general: (message: string) => {
    Swal.fire('Éxito', message, 'success')
  },
  info: (message: string) => {
    Swal.fire('!Aviso', message, 'info')
  },
  error: (message: string) => {
    Swal.fire('!Aviso', message, 'error')
  },
  default: (message: string) => {
    Swal.fire('!Aviso', message, 'error')
  },
  personalized: (message: string,icon: SweetAlertIcon) => {
    Swal.fire('!Aviso', message, icon)
  },
  
}

export default alerts