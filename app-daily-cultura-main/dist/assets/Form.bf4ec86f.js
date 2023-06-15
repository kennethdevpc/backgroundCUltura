import{d as C,b as j,s as E,H as v,v as g,r as D,c as I,E as y,f as b,g as B,i as d,w as N,j as k,t as A,l as e,y as M,k as O,h as i,ad as U,B as T,F as q,q as H,Q as P}from"./index.c93647e1.js";import{s as z}from"./scroll.6a87917e.js";import{p as h}from"./permissions.c73d0fc1.js";import{c as L}from"./generals.service.ee73141b.js";import{m as Q}from"./monitoring_report.service.39dc3e50.js";import{u as W}from"./index.esm.8471a966.js";import{_ as X}from"./Input.d2784565.js";import{_ as G}from"./Textarea.8cc31183.js";import{_ as J,a as K}from"./Management.8f9978fe.js";import{r as c}from"./validations.9fff8c5b.js";import{_ as Y}from"./FormHeader.f0e8692c.js";import"./Select.7b4ad9bd.js";import"./useSlug.fdbf14df.js";import"./index.esm.c98934a4.js";import"./StateHeader.5dfd959d.js";import"./get_status.6c591513.js";import"./BaseBackButton.438a649f.js";const Z={class:"intro-y box mt-5"},ee=["disabled"],te=["onSubmit"],se={class:"flex flex-col md:grid md:grid-cols-2 gap-6 justify-evenly"},ae={class:"w-full"},oe={class:"w-full"},ie={class:"w-full"},ne={class:"flex justify-end"},re=["disabled"],Re=C({__name:"Form",setup(le){const w=j();E(w);const s=Q(),m=L(),t=v({consecutive:"",date:"",description:"",file:null}),V=()=>{t.consecutive="",t.date="",t.file=null,t.description=""},x=g(()=>({consecutive:{required:c},date:{required:c},description:{required:c},file:{required:c}})),r=W(x,t,{$autoDirty:!0,$lazy:!0}),p=D([]),F=(f,a)=>{if(f)return;const{file:o}=a;t.file=o},$=()=>{t.file=null},_=H(),n=v({status:"",consecutive:"",reject_message:""}),l=g(()=>!!_.params.id),u=async()=>await m.getConsecutive("monitoring_reports","MR").then(()=>{t.consecutive=m.data.value,n.consecutive=m.data.value}),R=async()=>await s.getOne(_.params.id);I(async()=>{l.value?(await R().catch(()=>{y.not_found_by_id()}),s.data.one&&(n.status=s.data.one.status,n.consecutive=s.data.one.consecutive,n.reject_message=s.data.one.reject_message,t.consecutive=s.data.one.consecutive,t.date=s.data.one.date,t.description=s.data.one.description,t.file=s.data.one.file)):await u()});const S=async()=>{await r.value.$validate()?l.value?await s.update(s.data.one.id,t):await u().finally(()=>{s.create(t).then(async a=>{a.data.success&&(p.value=[],V(),r.value.$reset(),z(),await u())})}):P.validation()};return(f,a)=>(b(),B(q,null,[d(Y,{state:{consecutive:e(n).consecutive,status:e(n).status,reject_message:e(n).reject_message}},{default:N(()=>[k(A(e(l)?`Edici\xF3n de Informe de seguimiento #${e(s).data.one.id}`:"Informe de seguimiento"),1)]),_:1},8,["state"]),e(l)&&e(h).monitoringReports.management()?(b(),M(J,{key:0,class:"mt-5",onSend:a[0]||(a[0]=o=>e(y).methods.send_management("monitoring_reports",e(s).data.one.id,o))})):O("",!0),i("div",Z,[i("fieldset",{disabled:e(h).monitoringReports.no_edit()},[i("form",{onSubmit:U(S,["prevent"]),class:"p-5 border-t border-slate-200/60 space-y-8"},[i("section",se,[i("div",ae,[d(X,{type:"date",label:"FECHA *",tooltip:"Ingrese la fecha",placeholder:"Fecha",name:"date",modelValue:e(t).date,"onUpdate:modelValue":a[1]||(a[1]=o=>e(t).date=o),validator:e(r)},null,8,["modelValue","validator"])]),i("div",oe,[d(K,{label:"DOCUMENTO *",tooltip:"Arrastra o selecciona un documento WORD, PDF o EXCEL",name:"file",ref:"file_ref",to_edit:e(s).data.one.file,modelValue:e(t).file,"onUpdate:modelValue":a[2]||(a[2]=o=>e(t).file=o),onAddfile:F,onRemovefile:$,files:e(p),accept_docs:!0,validator:e(r)},null,8,["to_edit","modelValue","files","validator"])]),i("div",ie,[d(G,{label:"DESCRIPCI\xD3N *",placeholder:"Ingrese descripci\xF3n",name:"description",modelValue:e(t).description,"onUpdate:modelValue":a[3]||(a[3]=o=>e(t).description=o),validator:e(r),rows:"5"},null,8,["modelValue","validator"])])]),i("div",ne,[i("button",{disabled:e(T),type:"submit",class:"btn btn-primary w-24 ml-2"}," Ingresar ",8,re)])],40,te)],8,ee)])],64))}});export{Re as default};