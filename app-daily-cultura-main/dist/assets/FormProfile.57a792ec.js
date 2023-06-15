import{m as M,d as B,b as P,s as C,C as x,X as j,f as _,g,i,w as $,j as q,h as d,ad as F,l as e,F as b,k as f,y as v,u as Y,q as z,an as G,Q as c}from"./index.c93647e1.js";import{_ as J}from"./FormHeader.f0e8692c.js";import{u as Q}from"./useApi.3f746542.js";import{_ as V}from"./Input.d2784565.js";import{_ as s}from"./Select.7b4ad9bd.js";import{u as X}from"./profile.service.e6258c5c.js";import{u as H}from"./index.esm.8471a966.js";import{r as n,f as D,m as K}from"./index.esm.c98934a4.js";import"./StateHeader.5dfd959d.js";import"./get_status.6c591513.js";import"./BaseBackButton.438a649f.js";const W=M("profile",{state:()=>({form:{user_id:"",contractor_full_name:"",nac_id:"",document_number:"",email:"",role_id:null,gestor_id:null,psychosocial_id:null,methodological_support_id:null,support_tracing_monitoring_id:null,ambassador_leader_id:null,instructor_leader_id:null}}),getters:{form_rules(p){return{user_id:{},contractor_full_name:{required:n},nac_id:{required:n},email:{required:n},document_number:{required:D.withMessage("Se requiere el n\xFAmero de identificaci\xF3n",n),minLength:D.withMessage("Se requiere minimo 6 caracteres",K(6))},role_id:{required:n},gestor_id:{},psychosocial_id:p.form.role_id==="gestores_culturales"?{required:n}:{},methodological_support_id:p.form.role_id==="gestores_culturales"?{required:n}:{},ambassador_leader_id:p.form.role_id==="embajador"?{required:n}:{},instructor_leader_id:p.form.role_id==="instructor"?{required:n}:{},support_tracing_monitoring_id:{}}}},actions:{}}),Z={class:"intro-y box p-5 mt-5"},ee=["onSubmit"],oe={class:"grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2"},ae=d("button",{type:"submit",class:"btn btn-primary"}," Actualizar ",-1),ge=B({__name:"FormProfile",setup(p){const y=X(),h=P(),{options:O}=C(h),S=W(),{form:r,form_rules:A}=C(S),t=H(A,r),R=Y(),E=z(),{find:T,findOne:I,create:N,delete:re}=Q(),m=(l,a)=>a.filter(o=>o.roles[0].slug===l).map(o=>({label:o.name,value:o.id})),{state:u}=x(async()=>await T("basicUsers").then(l=>{const a=l.data;return{users:a,ambassadors:m("lider_embajador",a),instructors:m("lider_instructor",a),gestors:m("gestores_culturales",a),psychosocials:m("psicosocial",a),methodologicalSupports:m("apoyo_metodologico",a),trackingMonitoringSupports:m("apoyo_seguimiento_monitoreo",a)}}),null),{state:U}=x(async()=>{if(E.params.id)return await I("users",E.params.id).then(l=>{const{email:a,profile:o,id:L,...te}=l.data.items;return Object.assign(r.value,{email:a,user_id:L,contractor_full_name:o.contractor_full_name,nac_id:o.nac_id,document_number:o.document_number,role_id:o.role.slug,gestor_id:o.gestor_id,support_tracing_monitoring_id:o.support_tracing_monitoring_id,psychosocial_id:o.psychosocial_id,ambassador_leader_id:o.ambassador_leader_id,instructor_leader_id:o.instructor_leader_id,methodological_support_id:o.methodological_support_id}),l.data.items})},null);j(()=>{t.value.$reset(),S.$reset()});function k(){G.fire({icon:"question",html:"<p>Desea eliminar el perfil del usuario ?</p>",cancelButtonText:"No",confirmButtonText:"Si",showDenyButton:!0}).then(async l=>{l.isConfirmed})}async function w(){if(await t.value.$validate())try{if(!U.value.profile)N("profiles",r.value),await y.create(r.value);else{const a=await y.update(U.value.profile.id,r.value);if(a.status>=200&&a.status<=300&&(c.custom("","Perfil actualizado!","success"),R.push({name:"users.index"})),a.status==422){let o=a.data.items;o.email?c.custom("Error al Crear Usuario",o.email.at(0),"error"):o.document_number?c.custom("Error al Crear Usuario",o.document_number.at(0),"error"):c.custom("Error al Crear Usuario","Desconocido?","error")}}}catch{c.custom("","Error al crear el perfil","error")}else c.validation()}return(l,a)=>(_(),g(b,null,[i(J,null,{default:$(()=>[q(" Actualizaci\xF3n de Usuario ")]),_:1}),d("div",Z,[d("form",{onSubmit:F(w,["prevent"]),class:"space-y-8 divide-y divide-slate-200"},[d("div",null,[d("div",oe,[i(V,{type:"text",name:"contractor_full_name",label:"NOMBRE Y APELLIDO DEL CONTRATISTA *",modelValue:e(r).contractor_full_name,"onUpdate:modelValue":a[0]||(a[0]=o=>e(r).contractor_full_name=o),validator:e(t)},null,8,["modelValue","validator"]),i(V,{type:"number",name:"document_number",label:"N\xDAMERO DE IDENTIDAD",modelValue:e(r).document_number,"onUpdate:modelValue":a[1]||(a[1]=o=>e(r).document_number=o),validator:e(t)},null,8,["modelValue","validator"]),i(V,{type:"text",name:"email",label:"CORREO",modelValue:e(r).email,"onUpdate:modelValue":a[2]||(a[2]=o=>e(r).email=o),validator:e(t)},null,8,["modelValue","validator"]),i(s,{name:"nac_id",label:"NAC",modelValue:e(r).nac_id,"onUpdate:modelValue":a[3]||(a[3]=o=>e(r).nac_id=o),options:e(O).nacs,validator:e(t)},null,8,["modelValue","options","validator"]),i(s,{name:"role_id",label:"ROL",modelValue:e(r).role_id,"onUpdate:modelValue":a[4]||(a[4]=o=>e(r).role_id=o),options:e(O).roles,validator:e(t)},null,8,["modelValue","options","validator"]),e(r).role_id=="gestores_culturales"?(_(),g(b,{key:0},[i(s,{name:"psychosocial_id",label:"PSICOSOCIAL",modelValue:e(r).psychosocial_id,"onUpdate:modelValue":a[5]||(a[5]=o=>e(r).psychosocial_id=o),options:e(u).psychosocials,validator:e(t)},null,8,["modelValue","options","validator"]),i(s,{label:"APOYO METODOL\xD3GICO",name:"methodological_support_id",modelValue:e(r).methodological_support_id,"onUpdate:modelValue":a[6]||(a[6]=o=>e(r).methodological_support_id=o),options:e(u).methodologicalSupports,validator:e(t)},null,8,["modelValue","options","validator"])],64)):f("",!0),e(r).role_id==="embajador"?(_(),v(s,{key:1,name:"ambassador_leader_id",label:"L\xCDDER EMBAJADOR",modelValue:e(r).ambassador_leader_id,"onUpdate:modelValue":a[7]||(a[7]=o=>e(r).ambassador_leader_id=o),options:e(u).ambassadors,validator:e(t)},null,8,["modelValue","options","validator"])):f("",!0),e(r).role_id==="instructor"?(_(),v(s,{key:2,label:"L\xCDDER INSTRUCTOR",name:"instructor_leader_id",modelValue:e(r).instructor_leader_id,"onUpdate:modelValue":a[8]||(a[8]=o=>e(r).instructor_leader_id=o),options:e(u).instructors,validator:e(t)},null,8,["modelValue","options","validator"])):f("",!0),e(r).role_id==="monitor_cultural"?(_(),g(b,{key:3},[i(s,{label:"GESTOR",name:"gestor_id",modelValue:e(r).gestor_id,"onUpdate:modelValue":a[9]||(a[9]=o=>e(r).gestor_id=o),options:e(u).gestors,validator:e(t)},null,8,["modelValue","options","validator"]),i(s,{label:"PSICOSOCIAL",name:"psychosocial_id",modelValue:e(r).psychosocial_id,"onUpdate:modelValue":a[10]||(a[10]=o=>e(r).psychosocial_id=o),options:e(u).psychosocials,validator:e(t)},null,8,["modelValue","options","validator"])],64)):f("",!0),e(r).role_id=="monitor_cultural"||e(r).role_id==="embajador"||e(r).role_id==="instructor"||e(r).role_id==="gestores_culturales"?(_(),v(s,{key:4,label:"APOYO AL SEGUIMIENTO Y MONITOREO",name:"support_tracing_monitoring_id",modelValue:e(r).support_tracing_monitoring_id,"onUpdate:modelValue":a[11]||(a[11]=o=>e(r).support_tracing_monitoring_id=o),options:e(u).trackingMonitoringSupports,validator:e(t)},null,8,["modelValue","options","validator"])):f("",!0)])]),d("div",{class:"pt-5"},[d("div",{class:"flex justify-end gap-x-4"},[ae,d("button",{onClick:k,type:"button",class:"btn btn-danger"}," Eliminar ")])])],40,ee)])],64))}});export{ge as default};