import{H as m,r as g,u as f,P as n,K as r,Q as o,E as y}from"./index.c93647e1.js";const i="managermonitorings",c="api/v1";function O(){const u=m({all:[],one:{},no_paginate:[],count_page:0}),d=f(),l=g("");return{data:u,errors:l,get:async(s,t=null)=>{var e;try{n(!0);let a=Boolean(t)?`?page=${s}&${t}`:`?page=${s}`,p=await r.get(`/${c}/${i}${a}`).finally(()=>{n(!1)});p.status===200&&(u.all=p.data.items,u.count_page=p.data.count_page)}catch(a){o.custom_validation((e=a.response.data.error)!=null?e:a.response.data.message)}},getOne:async s=>{var t;try{n(!0);let e=await r.get(`/${c}/${i}/${s}`).finally(()=>{n(!1)});return e.status===200&&(u.one=e.data.items),e}catch(e){e.response.status===404?y.not_found_by_id():o.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},update:async(s,t)=>{var e;l.value="";try{n(!0),(await r.put(`/${c}/${i}/${s}`,t).finally(()=>{n(!1)})).status===200&&(o.update(),d.push({name:"managermonitorings.index",params:{}}))}catch(a){o.custom_validation((e=a.response.data.error)!=null?e:a.response.data.message)}},create:async(s,t)=>{var e;l.value="";try{n(!0);const a=await r.post(`/${c}/${i}`,s,t).finally(()=>{n(!1)});return a.status===201&&o.create(),a}catch(a){o.custom_validation((e=a.response.data.error)!=null?e:a.response.data.message)}},destroy:async s=>{var t;try{n(!0),(await r.delete(`/${c}/${i}/${s}`).finally(()=>{n(!1)})).status===200&&o.destroy("Seguimiento",s)}catch(e){o.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}}}}export{O as m};