import{d as m,v as c,f as o,g as f,a5 as n,aa as v,h as r,t as b,ar as g,l as B,a9 as s,M as V,y as h,ac as k,k as x}from"./index.c93647e1.js";import{B as y}from"./Input.d2784565.js";const p=["for"],w={inheritAttrs:!1},D=m({...w,__name:"Textarea",props:{modelValue:null,label:null,placeholder:null,minlength:null,maxlength:null,disabled:{type:Boolean},tooltip:{type:[String,Boolean],default:!1},name:null,rows:null,cols:null,validator:null},emits:["update:modelValue"],setup(e,{emit:d}){const u=e,a=c({get(){return u.modelValue},set(l){d("update:modelValue",l)}});return(l,t)=>(o(),f("div",null,[n(r("label",{for:e.name,class:"form-label font-bold"},b(e.label),9,p),[[v,e.label]]),n(r("textarea",s(l.$attrs,{"onUpdate:modelValue":t[0]||(t[0]=i=>V(a)?a.value=i:null),class:["form-control",{"border border-danger":e.validator&&e.validator[e.name].$error}]}),null,16),[[g,B(a)]]),e.validator?(o(),h(y,k(s({key:0},{name:e.name,validator:e.validator,tooltip:e.tooltip})),null,16)):x("",!0)]))}});export{D as _};
