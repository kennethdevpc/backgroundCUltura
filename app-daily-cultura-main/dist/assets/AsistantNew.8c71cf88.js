import{m as A,s as h,b as O,v as $,D as C,E,f as _,g as p,h as a,i as l,l as t,j as I,t as U,ad as L,F as R,q as k,u as B,Q as D}from"./index.c93647e1.js";import{a as M}from"./assistants.service.5ca13225.js";import{u as j}from"./index.esm.8471a966.js";import{r,m as F,p as T}from"./validations.9fff8c5b.js";import{_ as d}from"./Input.d2784565.js";import{_ as q}from"./BaseBackButton.438a649f.js";import{_ as G}from"./Select.7b4ad9bd.js";import{e as Q}from"./index.esm.c98934a4.js";const z=A("assistants",{state:()=>({form:{nac_id:"",assistant_name:"",assistant_document_number:"",assistant_position:"",assistant_phone:"",assistant_email:""},form_rules:{nac_id:{required:r},assistant_name:{required:r},assistant_document_number:{required:r,minLength:F(10)},assistant_position:{required:r},assistant_phone:{phone:T,required:r},assistant_email:{required:r,email:Q}}}),getters:{},actions:{}}),H={class:"intro-y flex flex-col items-start gap-1 mt-8"},J={key:0,class:"text-lg font-medium mr-auto"},K={key:1,class:"text-lg font-medium mr-auto"},P={class:"intro-y box mt-5"},W=["onSubmit"],X={class:"grid grid-cols-2 gap-6 justify-evenly mb-8"},Y={class:"w-full"},Z={class:"w-full"},tt={class:"w-full"},et={class:"w-full"},st={class:"w-full"},at={class:"w-full"},ot=a("div",{class:"flex justify-center"},[a("button",{type:"submit",form:"assistant_form",class:"btn btn-primary w-24"}," Confirmar ")],-1),ft={__name:"AsistantNew",setup(nt){const u=k(),f=B(),m=z(),{form:e,form_rules:g}=h(m),c=O(),{options:V}=h(c),y=V.value,n=j(g,e,{$autoDirty:!0}),i=M(),x=async()=>{await i.getOne(u.params.id)},v=$(()=>!!u.params.id);C(async()=>{if(u.params.id){await x().catch(()=>{E.not_found_by_id()});const{id:b,created_at:s,updated_at:o,deleted_at:lt,nac_id:S,...N}=i.dataOne.value;e.value={...N,nac_id:String(S)}}else m.$reset()});const w=async()=>{n.value.$validate()?v.value?await i.update(i.dataOne.value.id,e.value).then(()=>{m.$reset,c.dataSelects(!0),f.push({name:"assistants.index"})}):await i.create(e.value).then(()=>{m.$reset,c.dataSelects(!0),f.push({name:"assistants.index"})}):D.validation()};return(b,s)=>(_(),p(R,null,[a("div",H,[l(q),t(v)?(_(),p("h2",J,[I(" Edici\xF3n de Asistente: "),a("b",null,U(t(i).dataOne.value.id),1)])):(_(),p("h2",K," Crear Asistente "))]),a("div",P,[a("form",{id:"assistant_form",onSubmit:L(w,["prevent"]),class:"px-5 sm:px-10 py-10 border-t border-slate-200/60"},[a("div",X,[a("div",Y,[l(G,{label:"NAC *",tooltip:"Ingrese el NAC",placeholder:"Seleccione",name:"nac_id",modelValue:t(e).nac_id,"onUpdate:modelValue":s[0]||(s[0]=o=>t(e).nac_id=o),options:t(y).nacs,validator:t(n)},null,8,["modelValue","options","validator"])]),a("div",Z,[l(d,{type:"text",label:"NOMBRE *",tooltip:"Ingrese el nombre",placeholder:"Nombre",name:"assistant_name",modelValue:t(e).assistant_name,"onUpdate:modelValue":s[1]||(s[1]=o=>t(e).assistant_name=o),validator:t(n)},null,8,["modelValue","validator"])]),a("div",tt,[l(d,{type:"text",label:"CARGO *",tooltip:"Ingrese el cargo",placeholder:"Cargo",name:"assistant_position",modelValue:t(e).assistant_position,"onUpdate:modelValue":s[2]||(s[2]=o=>t(e).assistant_position=o),validator:t(n)},null,8,["modelValue","validator"])]),a("div",et,[l(d,{type:"text",label:"C\xC9DULA *",tooltip:"Ingrese el numero de c\xE9dula",placeholder:"Numero de c\xE9dula",name:"assistant_document_number",modelValue:t(e).assistant_document_number,"onUpdate:modelValue":s[3]||(s[3]=o=>t(e).assistant_document_number=o),validator:t(n)},null,8,["modelValue","validator"])]),a("div",st,[l(d,{type:"tel",label:"TEL\xC9FONO *",tooltip:"Ingrese el numero de tel\xE9fono",placeholder:"Numero de tel\xE9fono",name:"assistant_phone",modelValue:t(e).assistant_phone,"onUpdate:modelValue":s[4]||(s[4]=o=>t(e).assistant_phone=o),validator:t(n)},null,8,["modelValue","validator"])]),a("div",at,[l(d,{type:"text",label:"EMAIL *",tooltip:"Ingrese el correo electr\xF3nico",placeholder:"Correo electr\xF3nico",name:"assistant_email",modelValue:t(e).assistant_email,"onUpdate:modelValue":s[5]||(s[5]=o=>t(e).assistant_email=o),validator:t(n)},null,8,["modelValue","validator"])])]),ot],40,W)])],64))}};export{ft as default};