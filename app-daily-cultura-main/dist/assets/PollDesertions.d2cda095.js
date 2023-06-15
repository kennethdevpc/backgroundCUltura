import{d as I,v as N,r,D as R,f as y,g as h,h as n,i as _,l as m,M as b,F,u as O,q as S,E as T,e as P}from"./index.c93647e1.js";import{_ as w}from"./BaseCrud.3eb21af2.js";import{p as B}from"./pollDesertions.service.04a4fe67.js";import"./DatasheetFormat.bb093e19.js";import"./description.c57d405a.js";import"./user.service.5d6ec521.js";import"./useSlug.fdbf14df.js";import"./Input.d2784565.js";import"./Select.7b4ad9bd.js";import"./get_status.6c591513.js";const V=n("h2",{class:"text-lg font-medium mr-auto"},"Encuesta de Deserci\xF3n",-1),k={class:"intro-y box mt-5 p-5"},G={class:"flex items-center justify-center p-2 text-base"},K=I({__name:"PollDesertions",setup(H){const o=B(),f=O(),d=S(),x=N(()=>String(d.name).split(".")[0]),C=()=>{f.push({name:`${x.value}.create`})};async function v(e){await o.destroy(e),s()}const E=[{text:"BENEFICIARIO",value:"beneficiary.full_name"},{text:"FECHA",value:"date"},{text:"NAC",value:"nac.name"},{text:"FACTOR DE DESERCI\xD3N",value:"beneficiary_attrition_factors"},{text:"OTRO FACTOR",value:"beneficiary_attrition_factor_other"},{text:"DESINTER\xC9S Y APAT\xCDA",value:"disinterest_apathy"},{text:"REINTEGRACI\xD3N",value:"reintegration"},{text:"CREACI\xD3N",value:"created_at"},{text:"ACCIONES",value:"actions"}],i=r([]);let t=r(1),p=r(0);const l=r({});async function s(){const e=Object.keys(l.value).length?new URLSearchParams(l.value):null;await o.get(t.value,e),i.value=o.data.all,p.value=o.data.count_page}function A(e){t.value=1,l.value=e,s()}function D(){s()}const g=e=>{const{id:a,consecutive:c}=e;return{id:a,consecutive:c,sections:{general:{title:"Datos Generales",fields:{BENEFICIARIO:e.beneficiary.full_name,FECHA:e.date,NAC:e.nac.name,"FACTOR DE DESERCI\xD3N":T.get_option_label("beneficiary_attrition_factors",e.beneficiary_attrition_factors),"OTRO FACTOR":e.beneficiary_attrition_factor_other,"A. DESINTER\xC9S Y APAT\xCDA":e.disinterest_apathy==1?"SI":"NO","A. EXPLICACI\xD3N":e.disinterest_apathy_explanation,"B. REINTEGRACI\xD3N":e.reintegration==1?"SI":"NO","B. EXPLICACI\xD3N":e.reintegration_explanation}}}}};return R(async()=>{await s(),i.value.map(e=>({...e,actions:"Acciones"}))}),(e,a)=>{const c=P("v-pagination");return y(),h(F,null,[n("div",{class:"intro-y flex flex-col sm:flex-row items-center mt-8"},[V,n("div",{class:"w-full sm:w-auto flex mt-4 sm:mt-0"},[n("button",{class:"btn btn-primary shadow-md mr-2",onClick:C}," Hacer encuesta de deserci\xF3n ")])]),n("div",k,[n("div",G,[_(c,{modelValue:m(t),"onUpdate:modelValue":[a[0]||(a[0]=u=>b(t)?t.value=u:t=u),D],pages:m(p),"range-size":1,"active-color":"#DCEDFF"},null,8,["modelValue","pages"])]),_(w,{headers:E,items:m(i),item_see_fnc:g,label:"la Encuesta de Deserci\xF3n","on-delete-fnc":v,onChange_status:a[1]||(a[1]=u=>s()),onChange_filter:A,server_options:{page:1,rowsPerPage:15}},null,8,["items"])])],64)}}});export{K as default};