import{s as g,N as y,v as b,D as h,E as w,f as d,g as c,h as e,i as p,l as o,j as D,t as E,ad as V,F as $,q as k,Q as N}from"./index.c93647e1.js";import{d as O}from"./dropDowns.service.7a5c5bb9.js";import{u as S}from"./index.esm.8471a966.js";import"./validations.9fff8c5b.js";import{_ as B}from"./Input.d2784565.js";import{_ as j}from"./BaseBackButton.438a649f.js";import{u as G}from"./drop-downs.5b8c55a3.js";import"./index.esm.c98934a4.js";const I={class:"intro-y flex flex-col items-start gap-1 mt-8"},M={key:0,class:"text-lg font-medium mr-auto"},R={key:1,class:"text-lg font-medium mr-auto"},F={class:"intro-y box mt-5"},T=["onSubmit"],q={id:"GeneralData",class:"flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8"},Q={class:"w-full"},U=e("div",{class:"flex justify-center"},[e("button",{type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ")],-1),X={__name:"ExpertiseNew",setup(z){const i=k(),t=G(),{form:s,form_rules:f}=g(t),a=O(),r=S(f,s,{$autoDirty:!0});y(s.value,async()=>{await r.value.$validate()?t.toggleGeneralData(!0):t.toggleGeneralData(!1)});const _=async()=>await a.getOne("expertises",i.params.id),m=b(()=>!!i.params.id);h(async()=>{if(i.params.id){await _().catch(()=>{w.not_found_by_id()});const{id:l,created_at:n,updated_at:u,...x}=a.dataOne.value;s.value={...x}}else t.$reset()});const v=async()=>{r.value.$validate()?m.value?await a.update("expertises",a.dataOne.value.id,t.transpiledData,"expertises"):await a.create("expertises",t.transpiledData,"expertises"):N.validation()};return(l,n)=>(d(),c($,null,[e("div",I,[p(j),o(m)?(d(),c("h2",M,[D(" Edici\xF3n de experticia: "),e("b",null,E(o(a).dataOne.value.id),1)])):(d(),c("h2",R," Ingresar experticia "))]),e("div",F,[e("form",{onSubmit:V(v,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[e("section",q,[e("div",Q,[p(B,{type:"text",label:"NOMBRE *",tooltip:"Ingrese el nombre de la Experticia",placeholder:"Experticia...",name:"name",modelValue:o(s).name,"onUpdate:modelValue":n[0]||(n[0]=u=>o(s).name=u),validator:o(r)},null,8,["modelValue","validator"])])]),U],40,T)])],64))}};export{X as default};