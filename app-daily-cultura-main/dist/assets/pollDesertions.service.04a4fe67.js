import{H as f,r as y,u as g,P as n,K as r,Q as o,E as $}from"./index.c93647e1.js";const i="pollDesertions",l="api/v1";function D(){const c=f({all:[],one:{},no_paginate:[],count_page:0}),d=g(),p=y("");return{data:c,errors:p,get:async(a,s=null)=>{var e;try{n(!0);let t=Boolean(s)?`?page=${a}&${s}`:`?page=${a}`,u=await r.get(`/${l}/${i}${t}`).finally(()=>{n(!1)});return u.status===200&&(c.all=u.data.items,c.count_page=u.data.count_page),u}catch(t){o.custom_validation((e=t.response.data.error)!=null?e:t.response.data.message)}},getOne:async a=>{var s;try{n(!0);let e=await r.get(`/${l}/${i}/${a}`).finally(()=>{n(!1)});return e.status===200&&(c.one=e.data.items),e}catch(e){e.response.status===404?$.not_found_by_id():o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}},update:async(a,s)=>{var e;p.value="";try{n(!0);const t=await r.put(`/${l}/${i}/${a}`,s).finally(()=>{n(!1)});return t.status===200&&(o.update(),d.push({name:"pollDesertions.index"})),t}catch(t){o.custom_validation((e=t.response.data.error)!=null?e:t.response.data.message)}},create:async(a,s)=>{var e;p.value="";try{n(!0);const t=await r.post(`/${l}/${i}`,a,s).finally(()=>{n(!1)});return t.status===200&&o.create(),t}catch(t){loading.value=!1,o.custom_validation((e=t.response.data.error)!=null?e:t.response.data.message)}},destroy:async a=>{var s;try{n(!0),(await r.delete(`/${l}/${i}/${a}`).finally(()=>{n(!1)})).status==200&&o.destroy("Encuesta de Deserci\xF3n",a)}catch(e){o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}}}}export{D as p};
