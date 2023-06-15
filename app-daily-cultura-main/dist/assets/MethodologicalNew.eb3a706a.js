import{m as oe,a0 as le,s as A,b as se,r as ie,H as $,v as N,D as ne,E,f as I,g as M,i as n,w as re,j as de,t as ce,l as e,y as me,k as R,h as l,ad as ue,F as _e,q as pe,a2 as ge,a3 as fe,Q as ve}from"./index.c93647e1.js";import{m as he}from"./methodological.service.26dd505e.js";import{u as be}from"./index.esm.8471a966.js";import{_ as V}from"./Input.d2784565.js";import{_ as U}from"./Select.7b4ad9bd.js";import{_ as Ve}from"./Radio.33857de6.js";import{_}from"./Textarea.8cc31183.js";import{_ as xe,a as O}from"./Management.8f9978fe.js";import{_ as ye}from"./Aggregates.ce7a7b06.js";import{r,d as j,m as Ie}from"./validations.9fff8c5b.js";import{s as we}from"./scroll.6a87917e.js";import{p as L}from"./permissions.c73d0fc1.js";import{c as Se}from"./generals.service.ee73141b.js";import{_ as Ae}from"./FormHeader.f0e8692c.js";import"./useSlug.fdbf14df.js";import"./index.esm.c98934a4.js";import"./StateHeader.5dfd959d.js";import"./get_status.6c591513.js";import"./BaseBackButton.438a649f.js";const $e=oe("methodological",{state:()=>({form:{consecutive:"",place:"",activity_date:"",start_time:"",final_hour:"",expertise_id:"",nac_id:"",goals_met:"",explanation:"",pedagogical_comments:"",technical_practical_comments:"",methodological_comments:"",others_observations:"",place_file1:null,place_file2:null,aggregates:{assistants:[]}},form_rules:{consecutive:{},place:{required:r},activity_date:{required:r},start_time:{required:r,checkMinMaxHours:j("start_time","final_hour")},final_hour:{required:r,checkMinMaxHours:j("start_time","final_hour")},expertise_id:{required:r},nac_id:{required:r},goals_met:{required:r},explanation:{required:r},pedagogical_comments:{required:r},technical_practical_comments:{required:r},methodological_comments:{required:r},others_observations:{required:r},place_file1:{required:r},place_file2:{required:r},aggregates:{assistants:{required:r,minLength:Ie(1)}}},form_options:{goals_met:[{value:"si",text:"Si"},{value:"no",text:"No"}]}}),getters:{getAssistants:d=>d.form.aggregates.assistants,transpiled_data(d){return{...this.form,assistants_id:JSON.stringify(d.form.aggregates.assistants.map(i=>i.id))}}},actions:{push_aggregate(d){this.$patch(i=>{i.form.aggregates.assistants.push(d)})},pop_aggregate(d){this.$patch(i=>{const t=i.form.aggregates.assistants.findIndex(x=>x.id===d);i.form.aggregates.assistants.splice(t,1)})}}});const Ne=d=>(ge("data-v-2858c8ff"),d=d(),fe(),d),Ee={class:"intro-y box mt-5"},Me=["disabled"],Re=["onSubmit"],Ue={id:"general_data",class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8"},Oe={class:"w-full"},je={class:"w-full"},Le={class:"w-full"},De={class:"w-full"},ke={class:"w-full"},Ce={class:"w-full"},Fe={id:"descriptions",class:"mb-8"},He=Ne(()=>l("div",{class:"flex items-center mb-8"},[l("h3",{class:"intro-y text-lg font-medium mr-auto"}," Descripcion de la jornada ")],-1)),Be={class:"grid grid-cols-1 gap-6 justify-evenly"},Pe={class:"w-full"},Te={class:"w-full"},qe={class:"w-full"},ze={class:"w-full"},Ge={class:"w-full"},Je={class:"w-full"},Qe={id:"Images",class:"mb-8"},Xe={class:"flex flex-col lg:grid lg:grid-cols-2 gap-6 justify-evenly"},Ke={key:0,id:"assistants",class:"flex flex-col justify-evenly mb-8"},We={class:"flex justify-center"},Ye=["disabled"],Ze={__name:"MethodologicalNew",setup(d){const i=$e(),{form:t,form_rules:x,form_options:D}=A(i),k=se(),{options:C}=A(k),u=C.value,p=ie(!1),s=be(x,t,{$autoDirty:!0,$lazy:!0}),g=$({I:[],II:[]}),F=(m,a)=>{if(m)return;const{file:o,filename:h}=a;t.value.place_file1={name:"photo1",file:o,filename:h}},H=()=>{t.value.place_file1=null},B=(m,a)=>{if(m)return;const{file:o,filename:h}=a;t.value.place_file2={name:"photo2",file:o,filename:h}},P=()=>{t.value.place_file2=null},w=pe(),c=he(),S=Se(),f=$({status:"",reject_message:""}),v=N(()=>!!w.params.id),y=async()=>await S.getConsecutive("methodological_instructions","AM").then(()=>{t.value.consecutive=S.data.value}),T=async()=>await c.getOne(w.params.id),q=N(()=>{let m=u.monitors_table.filter(a=>a.nac_id==t.value.nac_id);return t.value.nac_id?m:u.monitors_table});ne(async()=>{if(v.value){i.$reset(),await T().catch(()=>{E.not_found_by_id()});const{created_at:m,created_by:a,updated_at:o,id:h,reject_message:J,status:Q,assistants:X,assistants_id:ea,...K}=c.data.one,W=X.map(b=>{const{monitor_fullname:Y,document_number:Z,role_id:ee,nac_id:ae}=u.monitors_table.find(te=>te.id==b.id);return{id:b.id,monitor_fullname:Y,document_number:Z,role_id:ee,nac_id:ae}});i.$patch(b=>{Object.assign(b.form,{...K,aggregates:{assistants:W}})}),f.status=Q,f.reject_message=J}else i.$reset(),await y()});const z=async()=>{await s.value.$validate()?(p.value=!0,v.value?await c.update(c.data.one.id,i.transpiled_data).finally(()=>{p.value=!1}):await y().finally(()=>{c.create(i.transpiled_data).then(async a=>{a.status==200&&(g.I=[],g.II=[],i.$reset(),s.value.$reset(),await y(),we())}).finally(()=>{p.value=!1})})):ve.validation()},G=[{text:"ID",value:"id"},{text:"NOMBRE",value:"monitor_fullname"},{text:"NUMERO DE C\xC9DULA",value:"document_number"},{text:"NAC",value:"nac_id"},{text:"ROL",value:"role_id"}];return(m,a)=>(I(),M(_e,null,[n(Ae,{state:{consecutive:e(t).consecutive,status:e(f).status,reject_message:e(f).reject_message}},{default:re(()=>[de(ce(e(v)?`Edici\xF3n de Instrucci\xF3n Metodol\xF3gica #${e(c).data.one.id}`:"Instrucci\xF3n Metodol\xF3gica"),1)]),_:1},8,["state"]),e(v)&&e(L).methodologicalInstructions.management()?(I(),me(xe,{key:0,class:"mt-5",onSend:a[0]||(a[0]=o=>e(E).methods.send_management("methodological_instructions",e(c).data.one.id,o))})):R("",!0),l("div",Ee,[l("fieldset",{disabled:e(L).methodologicalInstructions.no_edit()},[l("form",{onSubmit:ue(z,["prevent"]),class:"p-5 border-t border-slate-200/60 dark:border-darkmode-400"},[l("section",Ue,[l("div",Oe,[n(V,{type:"text",label:"LUGAR *",tooltip:"Ingrese el Nombre del Lugar",placeholder:"Nombre del lugar",name:"place",modelValue:e(t).place,"onUpdate:modelValue":a[1]||(a[1]=o=>e(t).place=o),validator:e(s)},null,8,["modelValue","validator"])]),l("div",je,[n(V,{type:"date",label:"FECHA *",tooltip:"Ingrese la fecha",placeholder:"Fecha",name:"activity_date",modelValue:e(t).activity_date,"onUpdate:modelValue":a[2]||(a[2]=o=>e(t).activity_date=o),validator:e(s)},null,8,["modelValue","validator"])]),l("div",Le,[n(V,{type:"time",label:"HORA INICIO *",tooltip:"Ingrese la hora de inicio",placeholder:"Hora inicio",name:"start_time",modelValue:e(t).start_time,"onUpdate:modelValue":a[3]||(a[3]=o=>e(t).start_time=o),validator:e(s)},null,8,["modelValue","validator"])]),l("div",De,[n(V,{type:"time",label:"HORA FINAL *",tooltip:"Ingrese la hora final",placeholder:"Hora final",name:"final_hour",min:e(t).start_time,modelValue:e(t).final_hour,"onUpdate:modelValue":a[4]||(a[4]=o=>e(t).final_hour=o),validator:e(s)},null,8,["min","modelValue","validator"])]),l("div",ke,[n(U,{label:"EXPERTICIA *",tooltip:"Ingrese la Experticia",placeholder:"Seleccione",name:"expertise_id",modelValue:e(t).expertise_id,"onUpdate:modelValue":a[5]||(a[5]=o=>e(t).expertise_id=o),options:e(u).expertises,validator:e(s)},null,8,["modelValue","options","validator"])]),l("div",Ce,[n(U,{label:"NAC *",tooltip:"Ingrese el NAC",placeholder:"Seleccione",name:"nac_id",modelValue:e(t).nac_id,"onUpdate:modelValue":a[6]||(a[6]=o=>e(t).nac_id=o),options:e(u).nacs,validator:e(s)},null,8,["modelValue","options","validator"])])]),l("section",Fe,[He,l("div",Be,[l("div",Pe,[n(Ve,{label:"Se cumpli\xF3 el objetivo de la instrucci\xF3n metodol\xF3gica? *",tooltip:"",name:"goals_met",modelValue:e(t).goals_met,"onUpdate:modelValue":a[7]||(a[7]=o=>e(t).goals_met=o),options:e(D).goals_met,validator:e(s)},null,8,["modelValue","options","validator"])]),l("div",Te,[n(_,{label:"Explicaci\xF3n *",tooltip:"Ingrese la explicaci\xF3n",placeholder:"Explicaci\xF3n",name:"explanation",modelValue:e(t).explanation,"onUpdate:modelValue":a[8]||(a[8]=o=>e(t).explanation=o),validator:e(s),rows:"3"},null,8,["modelValue","validator"])]),l("div",qe,[n(_,{label:"Pedag\xF3gico *",tooltip:"Ingrese pedag\xF3gico",placeholder:"Pedag\xF3gico",name:"pedagogical_comments",modelValue:e(t).pedagogical_comments,"onUpdate:modelValue":a[9]||(a[9]=o=>e(t).pedagogical_comments=o),validator:e(s),rows:"3"},null,8,["modelValue","validator"])]),l("div",ze,[n(_,{label:"T\xE9cnico practico *",tooltip:"Ingrese lo t\xE9cnico practico",placeholder:"T\xE9cnico practico",name:"technical_practical_comments",modelValue:e(t).technical_practical_comments,"onUpdate:modelValue":a[10]||(a[10]=o=>e(t).technical_practical_comments=o),validator:e(s),rows:"3"},null,8,["modelValue","validator"])]),l("div",Ge,[n(_,{label:"Metodol\xF3gico *",tooltip:"Ingrese lo metodol\xF3gico",placeholder:"Metodol\xF3gico",name:"methodological_comments",modelValue:e(t).methodological_comments,"onUpdate:modelValue":a[11]||(a[11]=o=>e(t).methodological_comments=o),validator:e(s),rows:"3"},null,8,["modelValue","validator"])]),l("div",Je,[n(_,{label:"Otros y observaciones *",tooltip:"Ingrese otros y observaciones",placeholder:"Otros y observaciones",name:"others_observations",modelValue:e(t).others_observations,"onUpdate:modelValue":a[12]||(a[12]=o=>e(t).others_observations=o),validator:e(s),rows:"3"},null,8,["modelValue","validator"])])])]),l("section",Qe,[l("div",Xe,[n(O,{label:"DESARROLLO DEL DESARROLLO *",tooltip:"Arrastra o selecciona una Imagen",name:"place_file1",ref:"place_file1_ref",to_edit:e(c).data.one.place_file1,modelValue:e(t).place_file1,"onUpdate:modelValue":a[13]||(a[13]=o=>e(t).place_file1=o),onAddfile:F,onRemovefile:H,files:e(g).I,validator:e(s)},null,8,["to_edit","modelValue","files","validator"]),n(O,{label:"EVIDENCIA DE LAS PERSONAS *",tooltip:"Arrastra o selecciona una Imagen",name:"place_file2",ref:"place_file2_ref",to_edit:e(c).data.one.place_file2,modelValue:e(t).place_file2,"onUpdate:modelValue":a[14]||(a[14]=o=>e(t).place_file2=o),onAddfile:B,onRemovefile:P,files:e(g).II,validator:e(s)},null,8,["to_edit","modelValue","files","validator"])])]),e(u).monitors_table?(I(),M("section",Ke,[n(ye,{onPop:a[15]||(a[15]=o=>e(i).pop_aggregate(o)),onPush:a[16]||(a[16]=o=>e(i).push_aggregate(o)),headers:G,aggregates:e(t).aggregates.assistants,options:e(q)||null,validator:e(s),name:"aggregates"},null,8,["aggregates","options","validator"])])):R("",!0),l("div",We,[l("button",{disabled:e(p),type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ",8,Ye)])],40,Re)],8,Me)])],64))}};var Va=le(Ze,[["__scopeId","data-v-2858c8ff"]]);export{Va as default};