import{d as D,au as h,v as I,r as i,D as L,f,g as A,h as r,l as s,i as p,w as N,j as y,k as U,M as b,F as G,u as P,q as V,E as _,e as w}from"./index.c93647e1.js";import{_ as B}from"./BaseCrud.3eb21af2.js";import{p as F}from"./pecs.service.c1afe54a.js";import{p as v}from"./permissions.c73d0fc1.js";import{_ as S}from"./ToCreate.df149aaf.js";import"./DatasheetFormat.bb093e19.js";import"./description.c57d405a.js";import"./user.service.5d6ec521.js";import"./useSlug.fdbf14df.js";import"./Input.d2784565.js";import"./Select.7b4ad9bd.js";import"./get_status.6c591513.js";import"./index.37cca9d1.js";const T={class:"intro-y flex flex-col sm:flex-row items-center mt-8"},M=r("h2",{class:"text-lg font-medium mr-auto"},"PEC",-1),H={key:0,class:"w-full sm:w-auto flex mt-4 sm:mt-0"},k={class:"intro-y box mt-5 p-5"},j={class:"flex items-center justify-center p-2 text-base"},oe=D({__name:"PecView",setup(q){const o=F();P();const C=V();h(),I(()=>String(C.name).split(".")[0]);async function R(e){await o.destroy(e),n()}const g=[{text:"CONSECUTIVO",value:"consecutive"},{text:"FECHA DE ACTIVIDAD",value:"activity_date"},{text:"HORA INICIO",value:"start_time"},{text:"HORA FINAL",value:"final_hour"},{text:"CREADO POR",value:"user.name"},{text:"ROL CREADOR",value:"role"},{text:"C\xC9DULA",value:"profile.document_number"},{text:"NAC",value:"nac.name"},{text:"BARRIO",value:"neighborhood.name"},{text:"LUGAR",value:"place"},{text:"CREACI\xD3N",value:"created_at"},{text:"ESTADO",value:"status"},{text:"ACCIONES",value:"actions"}],c=i([]),x=e=>{const{id:l,consecutive:m}=e;return{id:l,consecutive:m,sections:{general:{title:"Datos Generales",fields:{"CREADO POR":e.user.name,C\u00C9DULA:e.profile.document_number,NAC:_.get_option_label("nacs",e.nac_id),BARRIO:_.get_option_label("neighborhoods",e.neighborhood_id),LUGAR:e.place,"OTRO LUGAR":e.other_place_type,"DIRECCION DEL LUGAR":e.place_address,FECHA:e.activity_date,"HORA INICIO":e.start_time,"HORA FINAL":e.final_hour,"TIPO DE LUGAR":_.get_option_label("place_types",e.place_type),"BREVE RESE\xD1A DEL LUGAR":e.place_description}},multimedia:{title:"Multimedia",fields:{"IMAGEN DEL LUGAR":e.place_image1,"IMAGEN DEL LUGAR 2":e.place_image2}},aggregates:{title:"Agregados",fields:{"BENEFICIARIOS (TABLA)":e.beneficiaries.map(a=>({"NOMBRE COMPLETO":a.full_name,C\u00C9DULA:a.nuip}))}}}}};let t=i(1),d=i(0);const u=i({});function E(e){t.value=1,u.value=e,n()}function O(){n()}async function n(){const e=Object.keys(u.value).length?new URLSearchParams(u.value):null;await o.get(t.value,e).then(()=>{c.value=o.data.all,d.value=o.data.count_page})}return L(async()=>{await n(),c.value.map(e=>({...e,actions:"Acciones"}))}),(e,l)=>{const m=w("v-pagination");return f(),A(G,null,[r("div",T,[M,s(v).pecs.create()?(f(),A("div",H,[p(S,{to:{name:"pecs.create"}},{default:N(()=>[y(" Crear un PEC ")]),_:1},8,["to"])])):U("",!0)]),r("div",k,[r("div",j,[p(m,{modelValue:s(t),"onUpdate:modelValue":[l[0]||(l[0]=a=>b(t)?t.value=a:t=a),O],pages:s(d),"range-size":1,"active-color":"#DCEDFF"},null,8,["modelValue","pages"])]),p(B,{headers:g,items:s(c),item_see_fnc:x,management_permissions:s(v).pecs.crud_management(),label:"el pec","on-delete-fnc":R,onChange_filter:E},null,8,["items","management_permissions"])])],64)}}});export{oe as default};