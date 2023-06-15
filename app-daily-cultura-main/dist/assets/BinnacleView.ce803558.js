import{d as b,v as h,r,D as y,f as d,g as A,h as i,l as s,k as L,i as I,M as V,F as T,u as B,q as F,E as a,e as S}from"./index.c93647e1.js";import{_ as M}from"./BaseCrud.3eb21af2.js";import{B as U}from"./binnacles_managers.service.fe412afe.js";import{p as v}from"./permissions.c73d0fc1.js";import"./DatasheetFormat.bb093e19.js";import"./description.c57d405a.js";import"./user.service.5d6ec521.js";import"./useSlug.fdbf14df.js";import"./Input.d2784565.js";import"./Select.7b4ad9bd.js";import"./get_status.6c591513.js";const P={class:"intro-y flex flex-col sm:flex-row items-center mt-8"},w=i("h2",{class:"text-lg font-medium mr-auto"},"Activaci\xF3n cultural",-1),H={key:0,class:"w-full sm:w-auto flex mt-4 sm:mt-0"},k={class:"intro-y box mt-5 p-5"},G={class:"flex items-center justify-center p-2 text-base"},te=b({__name:"BinnacleView",setup(j){const o=U(),C=B(),E=F(),O=h(()=>String(E.name).split(".")[0]),f=()=>{C.push({name:`${O.value}.create`})};async function g(e){o.destroy(e).then(()=>{l()})}const x=[{text:"CONSECUTIVO",value:"consecutive"},{text:"FECHA",value:"activity_date",width:120},{text:"HORA INICIO",value:"start_time"},{text:"HORA FINAL",value:"final_hour"},{text:"BIT\xC1CORA",value:"binnacle_name"},{text:"NAC",value:"nac.name"},{text:"ACTIVIDAD",value:"activity_name"},{text:"LUGAR",value:"place"},{text:"CREADO POR",value:"user.name"},{text:"CREACI\xD3N",value:"created_at"},{text:"ESTADO",value:"status"},{text:"ACCIONES",value:"actions"}],m=r([]);let t=r(1),p=r(0);const u=r({});async function l(){const e=Object.keys(u.value).length?new URLSearchParams(u.value):null;await o.get(t.value,e).then(()=>{m.value=o.data.all,p.value=o.data.count_page})}const D=e=>{const{id:c,consecutive:_}=e;return{id:c,consecutive:_,sections:{general:{title:"Datos Generales",fields:{BIT\u00C1CORA:a.get_option_label("binnacles",e.binnacle_id),ACTIVIDAD:e.activity_name,"MODO DE ACTIVACI\xD3N":e.activation_mode,FECHA:e.activity_date,"HORA INICIO":e.start_time,"HORA FINAL":e.final_hour,LUGAR:e.place,NAC:a.get_option_label("nacs",e.nac_id),EXPERTICIA:a.get_option_label("expertises",e.expertise_id),"DERECHO CULTURAL":a.get_option_label("cultural_rights",e.cultural_right_id),"OBJETIVO VIVENCIAL":e.experiential_objective,LINEAMIENTO:a.get_option_label("lineaments",e.lineament_id),ORIENTACI\u00D3N:a.get_option_label("orientations",e.orientation_id),"SE CUMPLI\xD3 EL OBJETIVO VIVENCIAL":e.goals_met.toUpperCase(),"EL POR QUE":e.explain_goals_met,INICIO:e.start_activity,DESARROLLO:e.activity_development,FINAL:e.end_of_activity,OBSERVACIONES:e.observations_activity,"CAPACIDAD DE BENEFICIARIOS":e.beneficiaries_capacity=="aforo"?"AFORO":"BENEFICIARIO"}},multimedia:{title:"Multimedia",fields:{"IMAGEN (DESARROLLO DE LA JORNADA DE PACTO)":e.development_activity_image,"IMAGEN (EVIDENCIA DE PARTICIPACI\xD3N)":e.evidence_participation_image,"DOCUMENTO (AFORO)":e.aforo_file}},assistants:{title:"Asistentes Agregados",fields:{ASISTENTES:e.assistants.map(n=>({NOMBRE:n.full_name,C\u00C9DULA:n.document_number}))}}}}};function N(e){t.value=1,u.value=e,l()}function R(){l()}return y(async()=>{await l()}),(e,c)=>{const _=S("v-pagination");return d(),A(T,null,[i("div",P,[w,s(v).binnaclesManagers.create()?(d(),A("div",H,[i("button",{class:"btn btn-primary shadow-md mr-2",onClick:f}," Crear una activaci\xF3n cultural ")])):L("",!0)]),i("div",k,[i("div",G,[I(_,{modelValue:s(t),"onUpdate:modelValue":[c[0]||(c[0]=n=>V(t)?t.value=n:t=n),R],pages:s(p),"range-size":1,"active-color":"#DCEDFF"},null,8,["modelValue","pages"])]),I(M,{headers:x,items:s(m),item_see_fnc:D,management_permissions:s(v).binnaclesManagers.crud_management(),label:"la bit\xE1cora",edit_gestor:!0,"on-delete-fnc":g,onChange_filter:N},null,8,["items","management_permissions"])])],64)}}});export{te as default};