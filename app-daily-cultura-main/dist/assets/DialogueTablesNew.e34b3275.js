import{m as W,a0 as X,s as A,b as Y,H as D,r as Z,v as ee,D as ae,E as k,f as N,g as te,i as n,w as se,j as le,t as O,l as e,y as oe,k as ie,h as l,ad as ne,F as de,q as re,a2 as ce,a3 as ue,an as me}from"./index.c93647e1.js";import{d as _e}from"./dialoguetables.service.31eedb5d.js";import{c as pe}from"./generals.service.ee73141b.js";import{r as d,d as j,m as fe,h as E,i as ve,j as S,k as ge}from"./validations.9fff8c5b.js";import{u as he}from"./index.esm.8471a966.js";import{_ as w}from"./Input.d2784565.js";import{_ as ye}from"./Select.7b4ad9bd.js";import{_ as m}from"./Textarea.8cc31183.js";import{_ as be,a as $}from"./Management.8f9978fe.js";import{_ as we}from"./Aggregates.ce7a7b06.js";import{s as Ve}from"./scroll.6a87917e.js";import{p as R}from"./permissions.c73d0fc1.js";import{_ as Ie}from"./FormHeader.f0e8692c.js";import"./index.esm.c98934a4.js";import"./useSlug.fdbf14df.js";import"./StateHeader.5dfd959d.js";import"./get_status.6c591513.js";import"./BaseBackButton.438a649f.js";const xe=W("dialoguetables",{state:()=>({form:{consecutive:"",nac_id:"",activity_date:"",start_time:"",final_hour:"",target_workday:"",theme:"",schedule_day:"",workday_description:"",achievements_difficulties:"",alerts:"",place_image1:{},place_image2:{},assistants:[]},form_rules:{consecutive:{},nac_id:{required:d},activity_date:{required:d},start_time:{required:d,checkMinMaxHours:j("start_time","final_hour")},final_hour:{required:d,checkMinMaxHours:j("start_time","final_hour")},target_workday:{required:d},theme:{required:d},schedule_day:{required:d},workday_description:{required:d},achievements_difficulties:{required:d},alerts:{required:d},place_image1:{required:d},place_image2:{required:d},assistants:{required:d,minLength:fe(1)}}}),getters:{transpiled_data(){const{assistants:r,...o}=this.form;return{...o,asistents:r}}},actions:{push_aggregate(r){this.$patch(o=>{o.form.assistants.push(r)})},pop_aggregate(r){this.$patch(o=>{const t=o.form.assistants.findIndex(h=>h.id===r);o.form.assistants.splice(t,1)})}}});const U=r=>(ce("data-v-495cfc0c"),r=r(),ue(),r),Ae={class:"intro-y box mt-5"},De=["disabled"],ke=["onSubmit"],Ne={id:"GeneralData",class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8"},Oe={class:"w-full"},je={class:"w-full"},Ee={class:"w-full"},Se={class:"w-full"},$e={id:"Descriptions",class:"mb-8"},Re=U(()=>l("div",{class:"flex items-center mb-8"},[l("h3",{class:"intro-y text-lg font-medium mr-auto"}," Descripci\xF3n de la jornada * ")],-1)),Ue={class:"grid grid-cols-1 gap-6 justify-evenly"},Ce={class:"w-full"},Le={class:"w-full"},Me={class:"w-full"},Fe={class:"w-full"},Te={class:"w-full"},He={class:"w-full"},Be={id:"Images",class:"mb-8"},Pe={class:"flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly"},Ge={class:"flex flex-col justify-start mb-8"},qe={class:"intro-y form-label font-bold uppercase"},ze=U(()=>l("span",null," Asistentes Agregados ",-1)),Je={class:"flex justify-center"},Ke=["disabled"],Qe={__name:"DialogueTablesNew",setup(r){const o=xe(),{form:t,form_rules:h}=A(o),C=Y(),{options:V}=A(C),L=V.value,c=_e(),I=pe(),i=he(h,t,{$autoDirty:!0,$lazy:!0}),p=D({I:[],II:[]}),f=Z(!1),M=(u,a)=>{if(u)return;const{file:s,filename:_}=a;t.value.place_image1={name:"photo1",file:s,filename:_}},F=()=>{t.value.place_image1=null},T=(u,a)=>{if(u)return;const{file:s,filename:_}=a;t.value.place_image2={name:"photo2",file:s,filename:_}},H=()=>{t.value.place_image2=null},B=[{text:"NOMBRE",value:"assistant_name"},{text:"NUMERO DE C\xC9DULA",value:"assistant_document_number"},{text:"CARGO",value:"assistant_position"},{text:"NAC",value:"nac_id"},{text:"TEL\xC9FONO",value:"assistant_phone"},{text:"EMAIL",value:"assistant_email"}],P={fields:[{name:"assistant_name",as:"input",type:"text",placeholder:"NOMBRE",rules:E},{name:"assistant_document_number",as:"input",type:"text",placeholder:"NUMERO DE C\xC9DULA",rules:ve},{name:"assistant_position",as:"input",type:"text",placeholder:"CARGO",rules:E},{name:"nac_id",as:"select",placeholder:"NAC",rules:S().required(),children:V.value.nacs.map(u=>({tag:"option",value:u.value,label:u.label}))},{name:"assistant_phone",as:"input",type:"tel",placeholder:"TEL\xC9FONO",rules:ge},{name:"assistant_email",as:"input",type:"text",placeholder:"EMAIL",rules:S().email()}]},x=re(),v=D({status:"",reject_message:""}),g=ee(()=>!!x.params.id),y=async()=>await I.getConsecutive("dialogue_tables","IMD").then(()=>{t.value.consecutive=I.data.value}),G=async()=>await c.getOne(x.params.id);ae(async()=>{if(g.value){o.$reset(),await G().catch(()=>{k.not_found_by_id()});const{id:u,nac:a,assistants:s,status:_,reject_message:z,...J}=c.data.one,K=s.map(b=>{const{created_at:We,deleted_at:Xe,id:Ye,pivot:Ze,updated_at:ea,...Q}=b;return{...Q}});o.$patch(b=>{Object.assign(b.form,{...J,nac_id:a.id,assistants:K})}),v.status=_,v.reject_message=z}else o.$reset(),await y()});const q=async()=>{await i.value.$validate()?(f.value=!0,g.value?await c.update(c.data.one.id,o.transpiled_data).finally(()=>{f.value=!1}):await y().finally(()=>{c.create(o.transpiled_data).then(async a=>{a.data.success&&(p.I=[],p.II=[],o.$reset(),i.value.$reset(),Ve(),await y())}).finally(()=>{f.value=!1})})):me.fire("Validaci\xF3n","Por favor valide los campos solicitados.","error")};return(u,a)=>(N(),te(de,null,[n(Ie,{state:{consecutive:e(t).consecutive,status:e(v).status,reject_message:e(v).reject_message}},{default:se(()=>[le(O(e(g)?`Edici\xF3n de Mesa de Di\xE1logo #${e(c).data.one.id}`:"Mesa de Di\xE1logo"),1)]),_:1},8,["state"]),e(g)&&e(R).dialoguetables.management()?(N(),oe(be,{key:0,class:"mt-5",onSend:a[0]||(a[0]=s=>e(k).methods.send_management("dialogue_tables",e(c).data.one.id,s))})):ie("",!0),l("div",Ae,[l("fieldset",{disabled:e(R).dialoguetables.no_edit()},[l("form",{onSubmit:ne(q,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[l("section",Ne,[l("div",Oe,[n(w,{type:"date",label:"FECHA *",tooltip:"Ingrese la fecha",placeholder:"Fecha",name:"activity_date",modelValue:e(t).activity_date,"onUpdate:modelValue":a[1]||(a[1]=s=>e(t).activity_date=s),validator:e(i)},null,8,["modelValue","validator"])]),l("div",je,[n(w,{type:"time",label:"HORA INICIO *",tooltip:"Ingrese la hora de inicio",placeholder:"Hora inicio",name:"start_time",modelValue:e(t).start_time,"onUpdate:modelValue":a[2]||(a[2]=s=>e(t).start_time=s),validator:e(i)},null,8,["modelValue","validator"])]),l("div",Ee,[n(w,{type:"time",label:"HORA FINAL *",tooltip:"Ingrese la hora final",placeholder:"Hora final",name:"final_hour",min:e(t).start_time,modelValue:e(t).final_hour,"onUpdate:modelValue":a[3]||(a[3]=s=>e(t).final_hour=s),validator:e(i)},null,8,["min","modelValue","validator"])]),l("div",Se,[n(ye,{label:"NAC *",tooltip:"Ingrese el NAC",placeholder:"Seleccione",name:"nac_id",modelValue:e(t).nac_id,"onUpdate:modelValue":a[4]||(a[4]=s=>e(t).nac_id=s),options:e(L).nacs,validator:e(i)},null,8,["modelValue","options","validator"])])]),l("section",$e,[Re,l("div",Ue,[l("div",Ce,[n(m,{label:"Objetivo de la jornada *",tooltip:"Ingrese el objetivo",placeholder:"Objetivo",name:"target_workday",modelValue:e(t).target_workday,"onUpdate:modelValue":a[5]||(a[5]=s=>e(t).target_workday=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])]),l("div",Le,[n(m,{label:"Tema abordado *",tooltip:"Ingrese el tema",placeholder:"Tema",name:"theme",modelValue:e(t).theme,"onUpdate:modelValue":a[6]||(a[6]=s=>e(t).theme=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])]),l("div",Me,[n(m,{label:"Agenda del dia *",tooltip:"Ingrese agenda",placeholder:"Agenda",name:"schedule_day",modelValue:e(t).schedule_day,"onUpdate:modelValue":a[7]||(a[7]=s=>e(t).schedule_day=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])]),l("div",Fe,[n(m,{label:"Descripci\xF3n de la jornada *",tooltip:"Ingrese descripci\xF3n",placeholder:"Descripci\xF3n",name:"workday_description",modelValue:e(t).workday_description,"onUpdate:modelValue":a[8]||(a[8]=s=>e(t).workday_description=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])]),l("div",Te,[n(m,{label:"Logros y Dificultades *",tooltip:"Ingrese logros y dificultades",placeholder:"Logros y dificultades",name:"achievements_difficulties",modelValue:e(t).achievements_difficulties,"onUpdate:modelValue":a[9]||(a[9]=s=>e(t).achievements_difficulties=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])]),l("div",He,[n(m,{label:"Alertas *",tooltip:"Ingrese alertas",placeholder:"Alertas",name:"alerts",modelValue:e(t).alerts,"onUpdate:modelValue":a[10]||(a[10]=s=>e(t).alerts=s),validator:e(i),rows:"3"},null,8,["modelValue","validator"])])])]),l("section",Be,[l("div",Pe,[n($,{label:"DESARROLLO DEL DI\xC1LOGO CULTURAL *",tooltip:"Arrastra o selecciona una Imagen o PDF",name:"place_image1",ref:"place_image1_ref",modelValue:e(t).place_image1,"onUpdate:modelValue":a[11]||(a[11]=s=>e(t).place_image1=s),to_edit:e(c).data.one.place_image1,onAddfile:M,onRemovefile:F,files:e(p).I,validator:e(i)},null,8,["modelValue","to_edit","files","validator"]),n($,{label:"EVIDENCIA DE PARTICIPACI\xD3N *",tooltip:"Arrastra o selecciona una Imagen o PDF",name:"place_image2",ref:"place_image2_ref",modelValue:e(t).place_image2,"onUpdate:modelValue":a[12]||(a[12]=s=>e(t).place_image2=s),to_edit:e(c).data.one.place_image2,onAddfile:T,onRemovefile:H,files:e(p).II,validator:e(i)},null,8,["modelValue","to_edit","files","validator"])])]),l("section",Ge,[l("div",null,[l("h3",qe,[ze,l("span",null," # "+O(e(t).assistants.length),1)])]),n(we,{onPop:a[13]||(a[13]=s=>e(o).pop_aggregate(s)),onPush:a[14]||(a[14]=s=>e(o).push_aggregate(s)),headers:B,aggregates:e(t).assistants,add_schema:P,validator:e(i),name:"assistants"},null,8,["aggregates","validator"])]),l("div",Je,[l("button",{disabled:e(f),type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ",8,Ke)])],40,ke)],8,De)])],64))}};var ya=X(Qe,[["__scopeId","data-v-495cfc0c"]]);export{ya as default};
