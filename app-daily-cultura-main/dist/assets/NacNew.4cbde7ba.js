import{s as y,N as b,v as h,D as x,E as w,f as d,g as c,h as e,i as f,l as o,j as D,t as N,ad as V,F as $,q as k,Q as O}from"./index.c93647e1.js";import{d as E}from"./dropDowns.service.7a5c5bb9.js";import{u as S}from"./index.esm.8471a966.js";import"./validations.9fff8c5b.js";import{_ as B}from"./Input.d2784565.js";import{_ as j}from"./BaseBackButton.438a649f.js";import{u as G}from"./drop-downs.5b8c55a3.js";import"./index.esm.c98934a4.js";const I={class:"intro-y flex flex-col items-start gap-1 mt-8"},M={key:0,class:"text-lg font-medium mr-auto"},R={key:1,class:"text-lg font-medium mr-auto"},A={class:"intro-y box mt-5"},C=["onSubmit"],F={id:"GeneralData",class:"flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8"},T={class:"w-full"},q=e("div",{class:"flex justify-center"},[e("button",{type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ")],-1),X={__name:"NacNew",setup(Q){const n=k(),a=G(),{form:s,form_rules:p}=y(a),t=E(),r=S(p,s,{$autoDirty:!0});b(s.value,async()=>{await r.value.$validate()?a.toggleGeneralData(!0):a.toggleGeneralData(!1)});const _=async()=>await t.getOne("nacs",n.params.id),m=h(()=>!!n.params.id);x(async()=>{if(n.params.id){await _().catch(()=>{w.not_found_by_id()});const{id:l,created_at:i,updated_at:u,...g}=t.dataOne.value;s.value={...g}}else a.$reset()});const v=async()=>{r.value.$validate()?m.value?await t.update("nacs",t.dataOne.value.id,a.transpiledData,"nacs"):await t.create("nacs",a.transpiledData,"nacs"):O.validation()};return(l,i)=>(d(),c($,null,[e("div",I,[f(j),o(m)?(d(),c("h2",M,[D(" Edici\xF3n de nac: "),e("b",null,N(o(t).dataOne.value.id),1)])):(d(),c("h2",R," Ingresar nac "))]),e("div",A,[e("form",{onSubmit:V(v,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[e("section",F,[e("div",T,[f(B,{type:"text",label:"NOMBRE *",tooltip:"Ingrese el nombre del NAC",placeholder:"NAC...",name:"name",modelValue:o(s).name,"onUpdate:modelValue":i[0]||(i[0]=u=>o(s).name=u),validator:o(r)},null,8,["modelValue","validator"])])]),q],40,C)])],64))}};export{X as default};