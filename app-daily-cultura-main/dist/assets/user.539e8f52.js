import{m as o}from"./index.c93647e1.js";import{f as e}from"./index.esm.c98934a4.js";import{r as s,m as i}from"./validations.9fff8c5b.js";const c=o("user",{state:()=>({form:{email:"",password:"",password_confirm:"",user_id:"",contractor_full_name:"",nac_id:"",document_number:"",role_id:"",gestor_id:"",psychosocial_id:"",methodological_support_id:"",support_tracing_monitoring_id:"",ambassador_leader_id:"",instructor_leader_id:""},form_change_password:{id:"",password:"",password_confirmation:""},form_change_password_rules:{id:{},password:{required:e.withMessage("Se requiere la nueva contrase\xF1a",s),minLength:e.withMessage("Se requiere minimo 6 caracteres",i(6))},password_confirmation:{required:e.withMessage("Se requiere confirmar contrase\xF1a",s),minLength:e.withMessage("Se requiere minimo 6 caracteres",i(6)),sameAs:e.withMessage("Las contrase\xF1as deben ser iguales",(r,a)=>a.password===r)}},form_rules:{email:{required:s},password:{required:e.withMessage("Se requiere la nueva contrase\xF1a",s),minLength:e.withMessage("Se requiere minimo 6 caracteres",i(6))},password_confirm:{required:e.withMessage("Se requiere la nueva contrase\xF1a",s),minLength:e.withMessage("Se requiere minimo 6 caracteres",i(6)),sameAs:e.withMessage("Las contrase\xF1as deben ser iguales",(r,a)=>a.password===r)},user_id:{},contractor_full_name:{required:e.withMessage("Se requiere el nombre completo",s)},nac_id:{required:e.withMessage("Se requiere el nac",s)},document_number:{required:e.withMessage("Se requiere el n\xFAmero de identificaci\xF3n",s),minLength:e.withMessage("Se requiere minimo 6 caracteres",i(6))},role_id:{required:e.withMessage("Se requiere seleccionar un rol",s)},gestor_id:{},psychosocial_id:{},methodological_support_id:{},support_tracing_monitoring_id:{},ambassador_leader_id:{},instructor_leader_id:{}},validator:{}}),getters:{},actions:{clearInputs(){this.$reset()},fillData(r){this.$state.form.name=r==null?void 0:r.name,this.$state.form.email=r==null?void 0:r.email}}});export{c as u};
