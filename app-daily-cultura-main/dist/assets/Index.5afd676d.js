import{_ as u}from"./Input.d2784565.js";import{u as N}from"./index.esm.8471a966.js";import{d as T,v as p,H as j,r as B,f as i,g as d,h as a,l as t,i as e,k as f,w as n,j as x,F as $,E as w,e as s}from"./index.c93647e1.js";import{r as H}from"./report.services.02e33be8.js";const X=a("div",{class:"intro-y flex flex-col sm:flex-row items-center mt-8"},[a("h2",{class:"text-lg font-medium mr-auto"},"Informe de inicio de sesi\xF3n")],-1),A={class:"intro-y flex flex-col gap-2"},M={class:"flex flex-col gap-3 lg:grid lg:grid-cols-4 lg:items-center"},S={key:0,class:"flex flex-col justify-start h-full"},U=a("br",null,null,-1),G=a("label",{for:"regular-form-2",class:"form-label font-bold min-w-max mr-2"},"FECHA RANGO",-1),L={class:"grid grid-cols-2 gap-1.5 w-full intro-x"},O={key:1,class:"flex justify-center md:justify-end gap-3"},K=T({__name:"Index",props:{show_exports:{type:Boolean,default:!1}},setup(g){const v=p(()=>!!(w.computed.is_role("super.root")||w.computed.is_role("root"))),o=j({date_criteria_start:"",date_criteria_end:""}),h=p(()=>({date_criteria_start:{},date_criteria_end:{}})),c=N(h,o,{$autoDirty:!0}),D=B(!0),y=H(),V=async _=>{await y.exportExcel(_,o.date_criteria_start,o.date_criteria_end)};return(_,r)=>{const m=s("FileTextIcon"),C=s("ChevronDownIcon"),I=s("DropdownToggle"),b=s("DropdownItem"),k=s("DropdownContent"),E=s("DropdownMenu"),F=s("Dropdown");return i(),d($,null,[X,a("div",A,[a("section",M,[t(D)?(i(),d("div",S,[U,G,a("div",L,[e(u,{class:"",type:"date",tooltip:"Desde",name:"date_criteria_start",modelValue:t(o).date_criteria_start,"onUpdate:modelValue":r[0]||(r[0]=l=>t(o).date_criteria_start=l),validator:t(c)},null,8,["modelValue","validator"]),e(u,{class:"",type:"date",tooltip:"Hasta",name:"date_criteria_end",modelValue:t(o).date_criteria_end,"onUpdate:modelValue":r[1]||(r[1]=l=>t(o).date_criteria_end=l),validator:t(c)},null,8,["modelValue","validator"])])])):f("",!0),g.show_exports||t(v)?(i(),d("div",O,[e(F,{class:"sm:w-auto"},{default:n(()=>[e(I,{class:"btn btn-outline-secondary w-full sm:w-auto"},{default:n(()=>[e(m,{class:"w-4 h-4 mr-2"}),x(" Exportar "),e(C,{class:"w-4 h-4 ml-auto sm:ml-2"})]),_:1}),e(E,{class:"w-40"},{default:n(()=>[e(k,null,{default:n(()=>[e(b,{onClick:r[2]||(r[2]=l=>V("sesion"))},{default:n(()=>[e(m,{class:"w-4 h-4 mr-2"}),x(" Exportar a XLSX ")]),_:1})]),_:1})]),_:1})]),_:1})])):f("",!0)])])],64)}}});export{K as default};
