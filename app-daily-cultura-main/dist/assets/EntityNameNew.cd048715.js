import{s as y,N as b,v as h,D as x,E as w,f as d,g as c,h as e,i as f,l as o,j as D,t as E,ad as N,F as V,q as $,Q as k}from"./index.c93647e1.js";import{d as O}from"./dropDowns.service.7a5c5bb9.js";import{u as S}from"./index.esm.8471a966.js";import"./validations.9fff8c5b.js";import{_ as B}from"./Input.d2784565.js";import{_ as j}from"./BaseBackButton.438a649f.js";import{u as G}from"./drop-downs.5b8c55a3.js";import"./index.esm.c98934a4.js";const I={class:"intro-y flex flex-col items-start gap-1 mt-8"},M={key:0,class:"text-lg font-medium mr-auto"},R={key:1,class:"text-lg font-medium mr-auto"},F={class:"intro-y box mt-5"},T=["onSubmit"],q={id:"GeneralData",class:"flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8"},Q={class:"w-full"},U=e("div",{class:"flex justify-center"},[e("button",{type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ")],-1),X={__name:"EntityNameNew",setup(z){const i=$(),t=G(),{form:s,form_rules:p}=y(t),a=O(),n=S(p,s,{$autoDirty:!0});b(s.value,async()=>{await n.value.$validate()?t.toggleGeneralData(!0):t.toggleGeneralData(!1)});const _=async()=>await a.getOne("entities",i.params.id),m=h(()=>!!i.params.id);x(async()=>{if(i.params.id){await _().catch(()=>{w.not_found_by_id()});const{id:r,created_at:l,updated_at:u,...g}=a.dataOne.value;s.value={...g}}else t.$reset()});const v=async()=>{n.value.$validate()?m.value?await a.update("entities",a.dataOne.value.id,t.transpiledData,"entities"):await a.create("entities",t.transpiledData,"entities"):k.validation()};return(r,l)=>(d(),c(V,null,[e("div",I,[f(j),o(m)?(d(),c("h2",M,[D(" Edici\xF3n de entidad: "),e("b",null,E(o(a).dataOne.value.id),1)])):(d(),c("h2",R," Ingresar entidad "))]),e("div",F,[e("form",{onSubmit:N(v,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[e("section",q,[e("div",Q,[f(B,{type:"text",label:"NOMBRE *",tooltip:"Ingrese el nombre de la entidad",placeholder:"Entidad...",name:"name",modelValue:o(s).name,"onUpdate:modelValue":l[0]||(l[0]=u=>o(s).name=u),validator:o(n)},null,8,["modelValue","validator"])])]),U],40,T)])],64))}};export{X as default};