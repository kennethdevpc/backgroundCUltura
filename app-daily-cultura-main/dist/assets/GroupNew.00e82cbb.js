import{s as b,N as h,v as x,D as w,E as D,f as d,g as u,h as e,i as p,l as s,j as O,t as _,ad as V,F as $,q as k,Q as N}from"./index.c93647e1.js";import{d as E}from"./dropDowns.service.7a5c5bb9.js";import{u as G}from"./index.esm.8471a966.js";import"./validations.9fff8c5b.js";import{_ as S}from"./Input.d2784565.js";import{_ as B}from"./BaseBackButton.438a649f.js";import{u as j}from"./drop-downs.5b8c55a3.js";import"./index.esm.c98934a4.js";const I={class:"intro-y flex flex-col items-start gap-1 mt-8"},M={key:0,class:"text-lg font-medium mr-auto"},R={key:1,class:"text-lg font-medium mr-auto"},F={class:"intro-y box mt-5"},T=["onSubmit"],q={id:"GeneralData",class:"flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mb-8"},z={class:"w-full"},A={class:"flex justify-center"},L={type:"submit",class:"btn btn-primary w-24 ml-2"},Y={__name:"GroupNew",setup(Q){const r=k(),t=j(),{form:o,form_rules:f}=b(t),a=E(),i=G(f,o,{$autoDirty:!0});h(o.value,async()=>{await i.value.$validate()?t.toggleGeneralData(!0):t.toggleGeneralData(!1)});const g=async()=>await a.getOne("groups",r.params.id),c=x(()=>!!r.params.id);w(async()=>{if(r.params.id){await g().catch(()=>{D.not_found_by_id()});const{id:l,created_at:n,updated_at:m,...y}=a.dataOne.value;o.value={...y}}else t.$reset()});const v=async()=>{i.value.$validate()?c.value?await a.update("groups",a.dataOne.value.id,t.transpiledData,"groups"):await a.create("groups",t.transpiledData,"groups"):N.validation()};return(l,n)=>(d(),u($,null,[e("div",I,[p(B),s(c)?(d(),u("h2",M,[O(" Edici\xF3n de grupo: "),e("b",null,_(s(a).dataOne.value.id),1)])):(d(),u("h2",R," Ingresar grupo "))]),e("div",F,[e("form",{onSubmit:V(v,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[e("section",q,[e("div",z,[p(S,{type:"text",label:"NOMBRE *",tooltip:"Ingrese el nombre deL grupo",placeholder:"Grupo...",name:"name",modelValue:s(o).name,"onUpdate:modelValue":n[0]||(n[0]=m=>s(o).name=m),validator:s(i)},null,8,["modelValue","validator"])])]),e("div",A,[e("button",L,_(s(a).dataOne.value.id?"Actualizar":"Ingresar"),1)])],40,T)])],64))}};export{Y as default};
