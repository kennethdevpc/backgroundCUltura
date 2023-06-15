import{m as j,P as k,K as E,r as D,s as M,N as A,v as B,D as T,E as q,f as i,g as r,h as s,i as u,l as e,j as z,t as y,ad as F,F as h,x as S,a5 as L,a6 as K,M as Q,k as $,q as H,Q as J}from"./index.c93647e1.js";import{u as W}from"./index.esm.8471a966.js";import{_ as R}from"./Input.d2784565.js";import{_ as N}from"./Radio.33857de6.js";import{_ as X}from"./Textarea.8cc31183.js";import{_ as Y}from"./BaseBackButton.438a649f.js";import{r as f}from"./validations.9fff8c5b.js";import{r as Z}from"./role.service.37d43193.js";import{p as ee}from"./permission.service.43853215.js";import"./index.esm.c98934a4.js";const se=j("roles",{state:()=>({form:{name:"",slug:"",description:"",public:!1,"full-access":""},form_rules:{name:{required:f},slug:{required:f},description:{required:f},public:{required:f},"full-access":{required:f}},form_options:{public:[{value:1,text:"Si"},{value:0,text:"No"}],full_access:[{value:"yes",text:"Si"},{value:"no",text:"No"}]},validator:{generalData:!1}}),getters:{isGeneralData:d=>d.validator.generalData},actions:{toggleGeneralData(d){this.$patch(l=>{l.validator.generalData=d})}}}),ae="api/v1",te="permissionRole";var oe={async store(d){return k(!0),E.post(`/${ae}/${te}`,d).finally(()=>{k(!1)})}};const le={class:"flex flex-col items-start gap-1 mt-8 intro-y"},ie={key:0,class:"mr-auto text-lg font-medium"},re={key:1,class:"mr-auto text-lg font-medium"},ne={class:"mt-5 intro-y box"},de=["onSubmit"],ce={id:"generalData",class:"flex flex-col gap-6 mb-8 lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 justify-evenly"},ue={class:"w-full"},me={class:"w-full"},pe={class:"w-full lg:col-span-2 xl:col-span-2"},fe={class:"w-full"},ve={class:"w-full"},_e={class:"flex justify-center"},ge=["disabled"],be={key:0,class:"mt-5"},xe=s("h2",{class:"mr-auto text-lg font-medium"},"Asignar permisos",-1),ye={key:0,class:"p-5 mt-5 intro-y box"},he={class:"text-lg font-semibold text-gray-900 capitalize"},Ve={class:"grid grid-cols-6 gap-5"},we=["id","value"],ke=["for"],Pe={__name:"RolesNew",setup(d){const l=Z(),V=ee(),b=D([]),m=D([]),n=se(),{form:t,form_rules:G,form_options:w}=M(n),c=W(G,t,{$autoDirty:!0}),v=H();A(n,async()=>{await c.value.$validate()?n.toggleGeneralData(!0):n.toggleGeneralData(!1)});const x=B(()=>!!v.params.id),O=async()=>await l.getOne(v.params.id),U=async()=>{await V.get(!0)},C=async()=>{const _={permissions:m.value,role_id:v.params.id},a=await oe.store(_);a.status===200&&J.custom("",a.data.message,"success")};T(async()=>{if(v.params.id){n.$reset(),await O().catch(()=>{q.not_found_by_id()}),await U();const{id:_,created_at:a,updated_at:o,...g}=l.dataOne.value;l.permissionsRole.value.length&&(m.value=l.permissionsRole.value),b.value=V.data.value,t.value={...g}}else n.$reset()});const I=async()=>{x.value?await l.update(l.dataOne.value.id,t.value):await l.create(t.value)};return(_,a)=>(i(),r(h,null,[s("div",le,[u(Y),e(x)?(i(),r("h2",ie,[z(" Edicion de Rol: "),s("b",null,y(e(l).dataOne.value.name),1)])):(i(),r("h2",re,"Creaci\xF3n de Rol"))]),s("div",ne,[s("form",{onSubmit:F(I,["prevent"]),class:"px-5 py-10 border-t sm:px-10 border-slate-200/60 dark:border-darkmode-400"},[s("section",ce,[s("div",ue,[u(R,{type:"text",label:"Nombre *",tooltip:"Ingrese el nombre",placeholder:"Nombre",name:"name",modelValue:e(t).name,"onUpdate:modelValue":a[0]||(a[0]=o=>e(t).name=o),validator:e(c)},null,8,["modelValue","validator"])]),s("div",me,[u(R,{type:"text",label:"Slug *",tooltip:"Ingrese el slug",placeholder:"Slug",name:"slug",modelValue:e(t).slug,"onUpdate:modelValue":a[1]||(a[1]=o=>e(t).slug=o),validator:e(c)},null,8,["modelValue","validator"])]),s("div",pe,[u(X,{label:"Descripcion *",tooltip:"Ingrese la descripcion",placeholder:"Descripcion",name:"description",modelValue:e(t).description,"onUpdate:modelValue":a[2]||(a[2]=o=>e(t).description=o),validator:e(c),rows:"3"},null,8,["modelValue","validator"])]),s("div",fe,[u(N,{label:"Publico",tooltip:"Sera publico?",name:"public",modelValue:e(t).public,"onUpdate:modelValue":a[3]||(a[3]=o=>e(t).public=o),options:e(w).public,validator:e(c)},null,8,["modelValue","options","validator"])]),s("div",ve,[u(N,{label:"Acceso Completo",tooltip:"Tendra acceso completo?",name:"full-access",modelValue:e(t)["full-access"],"onUpdate:modelValue":a[4]||(a[4]=o=>e(t)["full-access"]=o),options:e(w).full_access,validator:e(c)},null,8,["modelValue","options","validator"])])]),s("div",_e,[s("button",{disabled:!e(n).isGeneralData,type:"submit",class:"w-24 ml-2 btn btn-primary"}," Ingresar ",8,ge)])],40,de)]),e(x)?(i(),r("section",be,[xe,Object.keys(e(b)).length?(i(),r("div",ye,[(i(!0),r(h,null,S(e(b),(o,g)=>(i(),r("div",{key:g,class:"flex flex-col mb-5 space-y-2"},[s("h3",he,y(g),1),s("div",Ve,[(i(!0),r(h,null,S(o,p=>(i(),r("div",{class:"col-span-2",key:p.id},[L(s("input",{id:p.name,type:"checkbox",class:"mr-2 border form-check-input",value:p.id,"onUpdate:modelValue":a[5]||(a[5]=P=>Q(m)?m.value=P:null)},null,8,we),[[K,e(m)]]),s("label",{class:"font-semibold capitalize cursor-pointer select-none",for:p.name},y(p.name),9,ke)]))),128))])]))),128)),s("button",{class:"px-8 py-2 font-bold text-white bg-green-500 rounded-md",onClick:C}," Guardar permisos ")])):$("",!0)])):$("",!0)],64))}};export{Pe as default};