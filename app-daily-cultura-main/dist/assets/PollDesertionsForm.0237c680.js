import{m as L,s as S,b as q,v as B,r as F,c as k,D as M,E as j,f as b,g,h as o,i as r,t as G,l as e,k as C,n as X,B as H,q as Q,an as z}from"./index.c93647e1.js";import{u as Y}from"./index.esm.8471a966.js";import{r as s}from"./validations.9fff8c5b.js";import{p as J}from"./pollDesertions.service.04a4fe67.js";import{c as K}from"./generals.service.ee73141b.js";import{_ as I}from"./Input.d2784565.js";import{_ as x}from"./Select.7b4ad9bd.js";import{_ as T}from"./Radio.33857de6.js";import{_ as W}from"./BaseBackButton.438a649f.js";import{_ as D}from"./Textarea.8cc31183.js";import{s as Z}from"./scroll.6a87917e.js";import{_ as ee}from"./StateHeader.5dfd959d.js";import"./index.esm.c98934a4.js";import"./get_status.6c591513.js";const te=L("pollDesertions",{state:()=>({form:{beneficiary_id:"",consecutive:"",date:"",nac_id:"",beneficiary_attrition_factors:"",beneficiary_attrition_factor_other:"",disinterest_apathy:"",disinterest_apathy_explanation:"",reintegration:"",reintegration_explanation:""},form_rules:{beneficiary_id:{required:s},date:{required:s},consecutive:{},nac_id:{required:s},beneficiary_attrition_factors:{required:s},beneficiary_attrition_factor_other:{},disinterest_apathy:{required:s},disinterest_apathy_explanation:{required:s},reintegration:{required:s},reintegration_explanation:{required:s}},form_options:{disinterest_apathy:[{value:1,text:"Si"},{value:0,text:"No"}],reintegration:[{value:1,text:"Si"},{value:0,text:"No"}]}}),getters:{get_form_rules_computed:p=>({...p.form_rules,beneficiary_attrition_factor_other:p.form.beneficiary_attrition_factors==="OTRO"?{required:s}:{}})},actions:{}}),ae={class:"max-w-screen-full mx-auto"},ie={class:"intro-y flex justify-between items-start md:items-end mt-5"},oe={class:"intro-y flex flex-col items-start gap-1"},ne={class:"text-lg font-medium mr-auto"},re={class:"intro-y box p-5 my-5"},le={class:"mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4"},se={key:0},de={key:0,class:"flex items-end intro-x"},ce={class:"mx-auto mt-10 grid grid-cols-1 gap-4"},ue={class:"mx-auto mt-10 flex justify-center"},me=["disabled"],Ie={__name:"PollDesertionsForm",setup(p){const V=Q(),d=te(),R=K(),l=J(),{form:t,get_form_rules_computed:O,form_options:h}=S(d),N=q(),{options:u}=S(N),n=Y(O,t,{$autoDirty:!0}),m=B(()=>!!V.params.id),f=async()=>await R.getConsecutive("polls_desertion","ED").then(()=>{t.value.consecutive=R.data.value}),U=async()=>await l.getOne(V.params.id),_=F([]);k(async()=>{m.value||await l.get().then(()=>{const c=l.data.all.map(({beneficiary_id:i})=>i),a=u.value.beneficiaries.filter(({id:i})=>!c.some(v=>v==i));_.value=a})}),M(async()=>{m.value?(d.$reset(),await U().then(async()=>{const{id:c,nac:a,user_id:i,beneficiary:v,created_at:_e,...w}=l.data.one;d.$patch(y=>{Object.assign(y.form,w)}),await l.get().then(()=>{const y=l.data.all.map(({beneficiary_id:E})=>E),$=u.value.beneficiaries.filter(({id:E})=>!y.some(A=>{if(A==E&&A!=v.id)return!0}));_.value=$})}).catch(()=>{j.not_found_by_id()})):(d.$reset(),n.value.$reset(),await f())});const P=async()=>{await n.value.$validate()?m.value?await l.update(l.data.one.id,t.value):await f().finally(()=>{l.create(t.value).then(async a=>{a.status==200&&(d.$reset(),n.value.$reset(),Z(),await f())})}):z.fire("Validaci\xF3n","Por favor valide los campos solicitados.","error")};return(c,a)=>(b(),g("div",ae,[o("div",ie,[o("div",oe,[r(W),o("h2",ne,G(e(m)?`Edici\xF3n de Encuesta de Deserci\xF3n #${e(l).data.one.id}`:"Encuesta de Deserci\xF3n"),1)]),r(ee,{consecutive:e(t).consecutive},null,8,["consecutive"])]),o("div",null,[o("div",re,[o("div",le,[_.value.length?(b(),g("div",se,[r(x,{label:"BENEFICIARIO *",tooltip:"",name:"beneficiary_id",modelValue:e(t).beneficiary_id,"onUpdate:modelValue":a[0]||(a[0]=i=>e(t).beneficiary_id=i),options:_.value||[],validator:e(n),required:""},null,8,["modelValue","options","validator"])])):C("",!0),o("div",null,[r(I,{type:"date",label:"FECHA *",tooltip:"",placeholder:"",name:"date",modelValue:e(t).date,"onUpdate:modelValue":a[1]||(a[1]=i=>e(t).date=i),validator:e(n)},null,8,["modelValue","validator"])]),o("div",null,[r(x,{label:"NAC",onChange:a[2]||(a[2]=i=>c.nac(i)),tooltip:"",name:"nac_id",modelValue:e(t).nac_id,"onUpdate:modelValue":a[3]||(a[3]=i=>e(t).nac_id=i),options:e(u).nacs,validator:e(n),required:""},null,8,["modelValue","options","validator"])]),o("template",{class:X([e(t).beneficiary_attrition_factors==="OTRO"?"grid-cols-2":"grid-cols-1","grid gap-3"])},[o("div",null,[r(x,{label:"FACTOR DE DESERCI\xD3N DEL BENEFICIARIO",tooltip:"",placeholder:"Seleccione",name:"beneficiary_attrition_factors",modelValue:e(t).beneficiary_attrition_factors,"onUpdate:modelValue":a[4]||(a[4]=i=>e(t).beneficiary_attrition_factors=i),options:e(u).beneficiary_attrition_factors,validator:e(n),required:""},null,8,["modelValue","options","validator"])]),e(t).beneficiary_attrition_factors==="OTRO"?(b(),g("div",de,[r(I,{type:"text",label:"\xBFCUAL ES EL OTRO FACTOR?",tooltip:"",placeholder:"Factor...",name:"beneficiary_attrition_factor_other",modelValue:e(t).beneficiary_attrition_factor_other,"onUpdate:modelValue":a[5]||(a[5]=i=>e(t).beneficiary_attrition_factor_other=i),validator:e(n)},null,8,["modelValue","validator"])])):C("",!0)],2)]),o("div",ce,[o("div",null,[r(T,{label:"\xBFCREE USTED QUE HUBO DESINTER\xC9S Y APAT\xCDA POR PARTE DEL PARTICIPANTE EN SEGUIR PARTICIPANDO EN LAS EXPRESIONES ART\xCDSTICAS?",tooltip:"",name:"disinterest_apathy",modelValue:e(t).disinterest_apathy,"onUpdate:modelValue":a[6]||(a[6]=i=>e(t).disinterest_apathy=i),options:e(h).disinterest_apathy,validator:e(n),required:""},null,8,["modelValue","options","validator"])]),o("div",null,[r(D,{label:"EXPLICACI\xD3N",tooltip:"",placeholder:"Explicaci\xF3n...",name:"disinterest_apathy_explanation",modelValue:e(t).disinterest_apathy_explanation,"onUpdate:modelValue":a[7]||(a[7]=i=>e(t).disinterest_apathy_explanation=i),validator:e(n),rows:"3",required:""},null,8,["modelValue","validator"])]),o("div",null,[r(T,{label:"\xBFCREE USTED QUE EL PARTICIPANTE PUEDE REINTEGRARSE AL PROGRAMA RED DE MONITORES CULTURALES? *",tooltip:"",name:"reintegration",modelValue:e(t).reintegration,"onUpdate:modelValue":a[8]||(a[8]=i=>e(t).reintegration=i),options:e(h).reintegration,validator:e(n),required:""},null,8,["modelValue","options","validator"])]),o("div",null,[r(D,{label:"EXPLICACI\xD3N",tooltip:"",placeholder:"Explicaci\xF3n...",name:"reintegration_explanation",modelValue:e(t).reintegration_explanation,"onUpdate:modelValue":a[9]||(a[9]=i=>e(t).reintegration_explanation=i),validator:e(n),rows:"3",required:""},null,8,["modelValue","validator"])])])]),o("div",ue,[o("button",{disabled:e(H),class:"btn btn-primary w-24 mr-1 mb-2",onClick:P},"Ingresar",8,me)])])]))}};export{Ie as default};