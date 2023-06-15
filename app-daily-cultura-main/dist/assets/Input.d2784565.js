import{a0 as b,d as v,f as a,g as t,F as x,x as h,t as o,k as r,v as g,e as V,a5 as c,aa as B,h as m,n as u,i as k,ab as $,l as w,M as C,y as I,ac as S,a9 as D}from"./index.c93647e1.js";const N={class:"BaseValidator"},P={key:1,class:"text-gray-400"},z=v({__name:"Validator",props:{validator:null,name:null,tooltip:{type:[String,Boolean]}},setup(e){return(s,d)=>(a(),t("div",N,[e.validator[e.name].$error?(a(!0),t(x,{key:0},h(e.validator[e.name].$errors,(l,n)=>(a(),t("div",{key:n,class:"text-xs text-danger"},o(l.$message),1))),128)):e.tooltip?(a(),t("small",P,o(e.tooltip),1)):r("",!0)]))}});var F=b(z,[["__scopeId","data-v-75865f29"]]);const M={class:"BaseInput w-full relative"},j=["for"],E=["name","type","min","max","placeholder","disabled","onfocus"],R=v({__name:"Input",props:{type:null,modelValue:null,label:null,placeholder:null,min:null,max:null,disabled:{type:Boolean},tooltip:{type:[String,Boolean],default:!1},name:null,validator:null},emits:["update:modelValue"],setup(e,{emit:s}){const d=e,l=g({get(){return d.modelValue},set(n){s("update:modelValue",n)}});return(n,i)=>{const f=V("CalendarIcon");return a(),t("div",M,[c(m("label",{for:e.name,class:"form-label font-bold"},o(e.label),9,j),[[B,e.label]]),e.type==="date"?(a(),t("div",{key:0,class:u(["absolute right-0 box-border rounded-r w-10 h-[42px] pointer-events-none flex items-center justify-center bg-slate-100 border text-slate-500",[{"border-danger":e.validator&&e.validator[e.name].$error},e.label?"top-7":"top-0"]])},[k(f,{class:"w-4 h-4"})],2)):r("",!0),c(m("input",{autocomplete:"off",name:e.name,type:e.type,min:e.min,max:e.max,placeholder:e.placeholder,disabled:e.disabled,"onUpdate:modelValue":i[0]||(i[0]=y=>C(l)?l.value=y:null),onfocus:e.type==="time"||e.type==="date"?"this.showPicker()":"",class:u(["form-control py-[10px]",{"border-danger":e.validator&&e.validator[e.name].$error}])},null,10,E),[[$,w(l)]]),e.validator?(a(),I(F,S(D({key:1},{validator:e.validator,name:e.name,tooltip:e.tooltip})),null,16)):r("",!0)])}}});export{F as B,R as _};