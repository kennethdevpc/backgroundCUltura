import{m as ie,s as R,b as ae,r as U,H as oe,v as h,D as le,E as C,N as F,f as _,g as v,i as r,w as se,h as l,t as V,l as e,k as g,y as ne,ad as re,B as de,F as ce,u as me,q as ue,O as pe,a as _e,an as fe}from"./index.c93647e1.js";import"./es.d3f7231d.js";import{s as ve}from"./scroll.6a87917e.js";import{u as ge}from"./index.esm.8471a966.js";import{c as ye}from"./generals.service.ee73141b.js";import{M as be}from"./methodologicalSheetsTwo.service.099bd407.js";import{r as c,l as L}from"./validations.9fff8c5b.js";import{_ as j}from"./Input.d2784565.js";import{_ as P}from"./Select.7b4ad9bd.js";import{_ as he}from"./Radio.33857de6.js";import{_ as D}from"./Textarea.8cc31183.js";import{_ as Ve,a as T}from"./Management.8f9978fe.js";import{_ as xe}from"./Aggregates.ce7a7b06.js";import{p as k}from"./permissions.c73d0fc1.js";import{_ as $e}from"./FormHeader.f0e8692c.js";import"./index.37cca9d1.js";import"./index.esm.c98934a4.js";import"./useSlug.fdbf14df.js";import"./StateHeader.5dfd959d.js";import"./get_status.6c591513.js";import"./BaseBackButton.438a649f.js";const Ie=ie("methodologicalSheetsTwo",{state:()=>({form:{activity_type:"",date_ini:"",date_fin:"",keyactors_participating_community:"",objective_process:"",reached_target:"",sustein:"",development_activity_image:null,evidence_participation_image:null,aforo_pdf:"",number_attendees:"",group_id:"",beneficiaries:[],beneficiaries_or_capacity:""},form_rules:{activity_type:{required:c},date_ini:{required:c,checkMinMaxMonths:L("date_ini","date_fin")},date_fin:{required:c,checkMinMaxMonths:L("date_ini","date_fin")},keyactors_participating_community:{required:c},objective_process:{required:c},reached_target:{required:c},sustein:{required:c},development_activity_image:{required:c},evidence_participation_image:{required:c},aforo_pdf:{},number_attendees:{},group_id:{},beneficiaries:{},beneficiaries_or_capacity:{}},form_options:{reached_target:[{value:1,text:"Si"},{value:0,text:"No"}]},update_instance:{consecutive:"",status:"",reject_message:""},filesOne:{development_activity_image:null,evidence_participation_image:null,aforo_pdf:null}}),getters:{get_form_rules_computed:m=>({...m.form_rules,aforo_pdf:m.form.activity_type==="C"||m.form.activity_type==="R"?{required:c}:{},number_attendees:m.form.activity_type==="C"||m.form.activity_type==="R"?{required:c}:{}})},actions:{clear(){this.$state.form.activity_type="",this.$state.form.date_ini="",this.$state.form.date_fin="",this.$state.form.keyactors_participating_community="",this.$state.form.objective_process="",this.$state.form.reached_target="",this.$state.form.sustein="",this.$state.form.development_activity_image=null,this.$state.form.evidence_participation_image=null,this.$state.form.aforo_pdf=null,this.$state.form.number_attendees="",this.$state.form.beneficiaries=[]},push_aggregate(m){this.$patch(s=>{s.form.beneficiaries.push(m)})},pop_aggregate(m){this.$patch(s=>{const a=s.form.beneficiaries.findIndex(x=>x.id===m);s.form.beneficiaries.splice(a,1)})}}}),Ae={class:"flex"},Oe={key:0,class:"text-lg font-italic mr-auto ml-4"},Ee={class:"intro-y box mt-5"},Se=["disabled"],we=["onSubmit"],Me={class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5"},Ce={class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mb-8"},je={class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5"},De={class:"flex flex-col lg:grid lg:grid-cols-2 xl:grid xl:grid-cols-2 gap-6 justify-evenly mt-5"},Te={class:"flex flex-col lg:grid lg:grid-cols-1 xl:grid xl:grid-cols-1 gap-6 justify-evenly mt-5"},ke={class:"w-full intro-x grid grid-cols-1 md:grid-cols-2 gap-6 justify-evenly mt-5"},Ne={key:0,class:"w-full intro-x grid grid-cols-2 md:grid-cols-2 gap-6 justify-evenly mt-5"},Re={key:1,class:"w-full"},Ue={key:2,class:"flex flex-col justify-start nt-5"},Fe={class:"intro-y form-label font-bold uppercase"},Le=l("span",null," Asistentes Agregados ",-1),Pe={class:"overflow-x-auto overflow-y-hidden"},Be={key:3,class:"flex justify-center mt-6"},qe=["disabled"],pt={__name:"MethodologicalSheetsTwoForm",setup(m){const s=Ie(),{form:a,form_options:x,get_form_rules_computed:B,filesOne:$}=R(s),{computed:{is_role:q}}=C,H=ae(),{options:I}=R(H),J=me(),n=ge(B,a,{$autoDirty:!0,$lazy:!0}),y=be(),{getConsecutive:z,getDataSheet:G,getGroupBeneficiaries:Z}=ye(),A=ue(),O=U(""),u=oe({I:[],II:[],aforo_pdf:[]}),K=[{text:"ID",value:"id"},{text:"NOMBRE",value:"full_name"},{text:"C\xC9DULA",value:"nuip"}],f=h(()=>!!A.params.id),E=async()=>await z("methodological_sheets_two","FP").then(o=>{a.value.consecutive=o.data}),Q=async()=>await y.getOne(A.params.id),S=h(()=>{const o=a.value.activity_type;return o?o==="E"||o==="C"?1:2:0});le(async()=>{f.value?(s.$reset(),await Q().then(()=>{const{created_at:o,id:t,monitor:i,nac:d,orientation:He,status:N,reject_message:te,user_id:Je,...b}=y.data.one;s.$patch(p=>{Object.assign(p.form,{...b,status:N}),p.update_instance.consecutive=b.consecutive,p.update_instance.status=N,p.update_instance.reject_message=te,p.filesOne.development_activity_image=b.development_activity_image,p.filesOne.aforo_pdf=b.aforo_pdf,p.filesOne.evidence_participation_image=b.evidence_participation_image})}).catch(()=>{C.not_found_by_id()})):(s.$reset(),n.value.$reset(),await E())});const W=async()=>{if(await n.value.$validate())if(f.value)(await y.update(y.data.one.id,a.value)).data.items&&J.push({name:"methodologicalsheetstwo.index"});else{const t={...a.value,beneficiaries:JSON.stringify(a.value.beneficiaries.map(i=>i.id))};await E().finally(()=>{y.create({...t,user_id:_e().get_user.id}).then(async i=>{i.data.items&&(u.I=[],u.II=[],u.aforo_pdf=[],O.value="",s.clear(),s.$reset(),n.value.$reset(),await E(),ve())})})}else fe.fire("Validaci\xF3n","Por favor valide los campos solicitados.","error")},w=(o,t,i)=>{if(!o){const{file:d}=t;i==1&&(a.value.development_activity_image=d),i==2&&(a.value.evidence_participation_image=d),i==3&&(a.value.aforo_pdf=d)}},M=o=>{o==1&&(a.value.development_activity_image=null,u.I=[]),o==2&&(a.value.evidence_participation_image=null,u.II=[]),o==3&&(a.value.aforo_pdf=null,u.aforo_pdf=[])},X=U([]);F(h(()=>a.value.date_ini),async()=>{if(a.value.date_ini){const{data:o}=await G("methodological_sheets_two",a.value.date_ini),t=pe(a.value.date_ini).locale("es").format("MMMM");O.value=`Ficha ${o} - ${t}`}});const Y=async o=>{f.value||await Z(o).then(t=>{if(t!=null){let i=t.data.items[0].beneficiaries;X.value=i,s.$patch(d=>{d.form.beneficiaries=i})}})},ee=h(()=>a.value.group_id);return F(ee,async(o,t)=>{o!=""&&o!=null&&o!=t&&await Y(o)}),(o,t)=>(_(),v(ce,null,[r($e,{state:{consecutive:e(a).consecutive,status:e(s).update_instance.status,reject_message:e(s).update_instance.reject_message}},{default:se(()=>[l("div",Ae,[l("span",null,V(e(f)?"Edici\xF3n de Ficha Metodol\xF3gica de Evaluaci\xF3n":"Ficha Metodol\xF3gica de Evaluaci\xF3n"),1),e(q)("instructor")&&!o.id?(_(),v("p",Oe,V(e(O)),1)):g("",!0)])]),_:1},8,["state"]),e(f)&&e(k).pedagogicals.management()?(_(),ne(Ve,{key:0,onSend:t[0]||(t[0]=i=>e(C).methods.send_management("methodological_sheets_two",e(A).params.id,i)),class:"mt-5"})):g("",!0),l("div",Ee,[l("fieldset",{disabled:e(k).pedagogicals.no_edit()},[l("form",{onSubmit:re(W,["prevent"]),class:"p-5 border-t border-slate-200/60"},[l("div",Me,[r(P,{label:"TIPO ACTIVIDAD *",tooltip:"",placeholder:"Tipo de la actividad",options:e(I).activity_type,name:"activity_type",modelValue:e(a).activity_type,"onUpdate:modelValue":t[1]||(t[1]=i=>e(a).activity_type=i),validator:e(n)},null,8,["options","modelValue","validator"]),l("div",Ce,[r(j,{type:"date",label:"FECHA INICIAL *",tooltip:"Ingrese la fecha inicial",placeholder:"Fecha",name:"date_ini",modelValue:e(a).date_ini,"onUpdate:modelValue":t[2]||(t[2]=i=>e(a).date_ini=i),validator:e(n)},null,8,["modelValue","validator"]),r(j,{type:"date",label:"FECHA FINAL *",tooltip:"Ingrese la fecha final",placeholder:"Fecha",name:"date_fin",modelValue:e(a).date_fin,"onUpdate:modelValue":t[3]||(t[3]=i=>e(a).date_fin=i),validator:e(n)},null,8,["modelValue","validator"])])]),l("div",je,[r(D,{label:"ACTORES CLAVES DE LA COMUNIDAD PARTICIPANTE *",tooltip:"",name:"keyactors_participating_community",modelValue:e(a).keyactors_participating_community,"onUpdate:modelValue":t[4]||(t[4]=i=>e(a).keyactors_participating_community=i),validator:e(n)},null,8,["modelValue","validator"]),r(D,{label:"OBJETIVO DEL PROCESO *",tooltip:"",placeholder:"Objetivo del proceso",name:"objective_process",modelValue:e(a).objective_process,"onUpdate:modelValue":t[5]||(t[5]=i=>e(a).objective_process=i),validator:e(n),required:""},null,8,["modelValue","validator"])]),l("div",De,[r(he,{label:"\xBFSE ALCANZ\xD3 EL OBJETIVO? *",tooltip:"",name:"reached_target",modelValue:e(a).reached_target,"onUpdate:modelValue":t[6]||(t[6]=i=>e(a).reached_target=i),options:e(x).reached_target,validator:e(n),required:""},null,8,["modelValue","options","validator"])]),l("div",Te,[r(D,{label:"SUSTENTE *",tooltip:"",placeholder:"Sustentaci\xF3n",name:"sustein",modelValue:e(a).sustein,"onUpdate:modelValue":t[7]||(t[7]=i=>e(a).sustein=i),validator:e(n),required:""},null,8,["modelValue","validator"])]),l("div",ke,[r(T,{label:"FOTO DEL DESARROLLO *",tooltip:"Arrastra un archivo valido",name:"development_activity_image",ref:"development_activity_image",to_edit:e($).development_activity_image,modelValue:e(a).development_activity_image,"onUpdate:modelValue":t[8]||(t[8]=i=>e(a).development_activity_image=i),onAddfile:t[9]||(t[9]=(i,d)=>w(i,d,1)),onRemovefile:t[10]||(t[10]=i=>M(1)),files:e(u).I,validator:e(n)},null,8,["to_edit","modelValue","files","validator"]),r(T,{label:"EVIDENCIA DE PARTICIPACI\xD3N *",tooltip:"Arrastra un archivo valido",name:"evidence_participation_image",to_edit:e($).evidence_participation_image,ref:"evidence_participation_image",modelValue:e(a).evidence_participation_image,"onUpdate:modelValue":t[11]||(t[11]=i=>e(a).evidence_participation_image=i),onAddfile:t[12]||(t[12]=(i,d)=>w(i,d,2)),onRemovefile:t[13]||(t[13]=i=>M(2)),files:e(u).II,validator:e(n)},null,8,["to_edit","modelValue","files","validator"])]),e(S)===1?(_(),v("div",Ne,[r(T,{label:"DOCUMENTO DEL AFORO *",tooltip:"Arrastra un archivo valido",name:"aforo_pdf",ref:"aforo_pdf",modelValue:e(a).aforo_pdf,"onUpdate:modelValue":t[14]||(t[14]=i=>e(a).aforo_pdf=i),to_edit:e($).aforo_pdf,onAddfile:t[15]||(t[15]=(i,d)=>w(i,d,3)),onRemovefile:t[16]||(t[16]=i=>M(3)),files:e(u).aforo_pdf,validator:e(n),accept_only_pdf:""},null,8,["modelValue","to_edit","files","validator"]),r(j,{type:"number",label:"CAPACIDAD (NUMERO DE ASISTENTES) *",tooltip:"",name:"number_attendees",modelValue:e(a).number_attendees,"onUpdate:modelValue":t[17]||(t[17]=i=>e(a).number_attendees=i),validator:e(n)},null,8,["modelValue","validator"])])):g("",!0),e(S)===2?(_(),v("div",Re,[r(P,{label:"GRUPO *",tooltip:"Selecciona un grupo",placeholder:"Seleccione",name:"group_id",modelValue:e(a).group_id,"onUpdate:modelValue":t[18]||(t[18]=i=>e(a).group_id=i),options:e(I).group_beneficiaries,validator:e(n)},null,8,["modelValue","options","validator"])])):g("",!0),e(S)===2?(_(),v("section",Ue,[l("div",null,[l("h3",Fe,[Le,l("span",null," # "+V(e(a).beneficiaries.length),1)])]),l("div",Pe,[r(xe,{onPop:t[19]||(t[19]=i=>e(s).pop_aggregate(i)),onPush:t[20]||(t[20]=i=>e(s).push_aggregate(i)),options:e(I).beneficiaries_table||null,headers:K,aggregates:e(a).beneficiaries,validator:e(n),name:"beneficiaries"},null,8,["options","aggregates","validator"])])])):g("",!0),e(k).sheetsOne.create()?(_(),v("div",Be,[l("button",{disabled:e(de),type:"submit",class:"btn btn-primary w-24 mr-1 mb-2"},V(e(f)?"Actualizar":"Ingresar"),9,qe)])):g("",!0)],40,we)],8,Se)])],64))}};export{pt as default};
