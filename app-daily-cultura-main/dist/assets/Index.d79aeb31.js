import{H as P,r as v,u as I,P as o,K as d,Q as p,d as O,v as R,D as A,f as N,g as T,h as l,i as $,l as y,M as b,F,q as S,e as V}from"./index.c93647e1.js";import{_ as L}from"./BaseCrud.3eb21af2.js";import"./DatasheetFormat.bb093e19.js";import"./description.c57d405a.js";import"./user.service.5d6ec521.js";import"./useSlug.fdbf14df.js";import"./Input.d2784565.js";import"./Select.7b4ad9bd.js";import"./get_status.6c591513.js";const _="alerts",m="api/v1";function M(){const n=P({all:[],one:{},no_paginate:[],user_id:null,count_page:0}),f=I(),c=v("");return{data:n,errors:c,get:async a=>{var t;try{o(!0);let e=await d.get(`/${m}/alertsPaginate?page=`+a).finally(()=>{o(!1)});e.status===200&&(n.all=e.data.items,n.count_page=e.data.count_page)}catch(e){p.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},getOne:async a=>{var t;try{o(!0);let e=await d.get(`/${m}/${_}/${a}`).finally(()=>{o(!1)});e.status===200&&(n.one=e.data.items)}catch(e){p.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},update:async(a,t)=>{var e;c.value="";try{o(!0),(await d.put(`/${m}/${_}/${a}`,t).finally(()=>{o(!1)})).status==200&&f.push({name:"alerts.index",params:{}})}catch(r){p.custom_validation((e=r.response.data.error)!=null?e:r.response.data.message)}},create:async a=>{var t;c.value="";try{o(!0);const e=await d.post(`/${m}/${_}`,a).finally(()=>{o(!1)});return e.status===200&&(n.user_id=e.data.items.id),e}catch(e){p.error((t=e.response.data.error)!=null?t:e.response.data.message)}},destroy:async a=>{var t;try{o(!0),(await d.delete(`/${m}/${_}/${a}`).finally(()=>{o(!1)})).status===200&&await f.push({name:"alerts.index"})}catch(e){p.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}}}}const k=l("h2",{class:"text-lg font-medium mr-auto"},"Actividad de la plataforma",-1),B={class:"intro-y box p-5 mt-5"},H={class:"flex items-center justify-center p-2 text-base"},W=O({__name:"Index",setup(n){const f=I(),c=S(),u=M(),x=R(()=>String(c.name).split(".")[0]),C=()=>{f.push({name:`${x.value}.create`})};async function h(s){await u.destroy(s),g()}const w=[{text:"#",value:"id"},{text:"TITULO",value:"title"},{text:"TYPE",value:"type"},{text:"DESCRIPCION",value:"description"},{text:"FECHA DE CREACION",value:"created_at"},{text:"ACCIONES",value:"actions"}],a=v([]),t=s=>{const{id:i}=s;return{id:i,sections:{general:{title:"Datos Generales",fields:{ID:s.id,TITLE:s.title,TYPE:s.type,DESCRIPCION:s.description,"FECHA REGISTRO":s.created_at}}}}};let e=v(1),r=v(0);async function g(){await u.get(e.value),a.value=u.data.all,r.value=u.data.count_page}return A(async()=>{await g(),a.value.map(s=>({...s,actions:"Acciones"}))}),(s,i)=>{const D=V("v-pagination");return N(),T(F,null,[l("div",{class:"intro-y flex flex-col sm:flex-row items-center mt-8"},[k,l("div",{class:"w-full sm:w-auto flex mt-4 sm:mt-0"},[l("button",{class:"btn btn-primary shadow-md mr-2",onClick:C}," Crear Alerta ")])]),l("div",B,[l("div",H,[$(D,{modelValue:y(e),"onUpdate:modelValue":[i[0]||(i[0]=E=>b(e)?e.value=E:e=E),g],pages:y(r),"range-size":1,"active-color":"#DCEDFF"},null,8,["modelValue","pages"])]),$(L,{onChange_status:i[1]||(i[1]=async()=>await g()),headers:w,items:y(a),item_see_fnc:t,label:"Usuarios","on-delete-fnc":h,server_options:{page:1,rowsPerPage:15}},null,8,["items"])])],64)}}});export{W as default};
