import{H as g,r as f,u as h,P as t,K as i,Q as u}from"./index.c93647e1.js";const c="neighborhoods",l="api/v1";function b(){const n=g({all:[],one:{},no_paginate:[],user_id:null,count_page:0}),d=h(),r=f("");return{data:n,errors:r,get:async(s,a=null)=>{var e;try{t(!0);let o=Boolean(a)?`?page=${s}&${a}`:`?page=${s}`,p=await i.get(`/${l}/${c}${o}`).finally(()=>{t(!1)});p.status===200&&(n.all=p.data.items,n.count_page=p.data.count_page)}catch(o){u.custom_validation((e=o.response)!=null?e:o.response)}},getOne:async s=>{var a;try{t(!0);let e=await i.get(`/${l}/${c}/${s}`).finally(()=>{t(!1)});e.status===200&&(n.one=e.data.items)}catch(e){u.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message)}},update:async(s,a)=>{var e;r.value="";try{t(!0),(await i.put(`/${l}/${c}/${s}`,a).finally(()=>{t(!1)})).status==200&&d.push({name:"neighborhoods.index",params:{}})}catch(o){u.custom_validation((e=o.response.data.error)!=null?e:o.response.data.message),r.value=o.response.data.errors}},create:async s=>{var a;r.value="";try{t(!0);const e=await i.post(`/${l}/${c}`,s).finally(()=>{t(!1)});return e.status===200&&(n.user_id=e.data.items.id,d.push({name:"neighborhoods.index"})),e}catch(e){u.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message),r.value=e.response.data.errors}},destroy:async s=>{var a;try{t(!0),(await i.delete(`/${l}/${c}/${s}`).finally(()=>{t(!1)})).status===200&&await d.push({name:"neighborhoods.index"})}catch(e){u.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message),r.value=e.response.data.errors}}}}export{b as n};