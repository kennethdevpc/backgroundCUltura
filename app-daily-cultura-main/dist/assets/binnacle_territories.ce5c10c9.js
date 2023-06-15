import{d as R,au as f,a as x,v as n,r as N,D as v,f as c,g as l,h as i,l as s,i as m,w as D,j as T,k as L,F as P,u as g,q as h,E as A}from"./index.c93647e1.js";import{_ as y}from"./BaseCrud.3eb21af2.js";import{p as V}from"./permissions.c73d0fc1.js";import{b}from"./binnacle_territories.service.3e4eec66.js";import{_ as w}from"./ToCreate.df149aaf.js";import"./DatasheetFormat.bb093e19.js";import"./description.c57d405a.js";import"./user.service.5d6ec521.js";import"./useSlug.fdbf14df.js";import"./Input.d2784565.js";import"./Select.7b4ad9bd.js";import"./get_status.6c591513.js";const F={class:"intro-y flex flex-col sm:flex-row items-center mt-8"},M=i("h2",{class:"text-lg font-medium mr-auto"},"Visita a territorio",-1),U={key:0,class:"w-full sm:w-auto flex mt-4 sm:mt-0"},B={class:"intro-y box mt-5 p-5"},ee=R({__name:"binnacle_territories",setup(G){const t=b(),{hasPermission:u}=f(),{get_user:H,is_role:_}=x();g();const d=h();n(()=>String(d.name).split(".")[0]);async function E(e){await t.destroy(e),o()}const I=[{text:"CONSECUTIVO",value:"consecutive"},{text:"USUARIO",value:"user_id"},{text:"FECHA",value:"activity_date",width:120},{text:"HORA INICIO",value:"start_time"},{text:"HORA FINAL",value:"final_hour"},{text:"NAC",value:"nac.name"},{text:"CREACI\xD3N",value:"created_at"},{text:"ESTADO",value:"status"},{text:"ACCIONES",value:"actions"}],a=N([]),C=e=>{const{id:r,consecutive:p,roles:O}=e,S=n(()=>O[0].name);return{id:r,consecutive:p,sections:{general:{title:"Datos Generales",fields:{NAC:e.nac.name,ROL:S.value,USUARIO:A.get_option_label("users_table",e.user.id),FECHA:e.activity_date,"HORA INICIO":e.start_time,"HORA FINAL":e.final_hour,LUGAR:e.place}},development:{title:"DESARROLLO DE LA VISITA",fields:{"OBJETIVOS ESTRAT\xC9GICOS DEL \xC1REA":e.strategic_objectives_area,"PROP\xD3SITO DE LA VISITA":e.purpose_visit}},diagnostic:{title:"DIAGN\xD3STICO INICIAL",fields:{"TEM\xC1TICAS ABORDADAS":e.topics_covered,"PERCEPCI\xD3N DE LOS PARTICIPANTES FRENTE A LAS ACTIVIDADES DESARROLLADAS POR EL \xC1REA":e.participants_perception,"DIFICULTADES O PROBLEM\xC1TICAS IDENTIFICADAS":e.problems_identified,"RECOMENDACIONES Y ACCIONES DE MEJORA PROPUESTAS POR LOS PARTICIPANTES ":e.recommendations_actions,"PERCEPCIONES/COMENTARIOS/AN\xC1LISIS FRENTE AL AVANCE DEL PROCESO":e.comments_analysis}},multimedia:{title:"Multimedia",fields:{"IMAGEN DESARROLLO":e.development_activity_image,"IMAGEN EVIDENCIA DE PARTICIPACI\xD3N":e.evidence_participation_image}}}}};async function o(){_("subdireccion")||A.computed.is_admin()?await t.get().then(()=>{a.value=t.data.all}):await t.getAllByUserId().then(()=>{a.value=t.data.all})}return v(async()=>{await o(),a.value.map(e=>({...e,actions:"Acciones"}))}),(e,r)=>(c(),l(P,null,[i("div",F,[M,s(u)("coordinadores.create")?(c(),l("div",U,[m(w,{to:{name:"coordinadores.create"}},{default:D(()=>[T(" Crear Visita a Territorio ")]),_:1},8,["to"])])):L("",!0)]),i("div",B,[m(y,{headers:I,items:s(a),item_see_fnc:C,management_permissions:s(V).coord_superv.crud_management(),label:"la bit\xE1cora","on-delete-fnc":E},null,8,["items","management_permissions"])])],64))}});export{ee as default};