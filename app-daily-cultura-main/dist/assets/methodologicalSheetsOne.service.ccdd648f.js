import{H as g,r as m,u as f,P as s,K as n,E as y}from"./index.c93647e1.js";const r="methodologicalsheetsone",i="api/v1";function C(){const l=g({all:[],one:{},no_paginate:[],user_id:null,count_page:0}),d=m([]),p=f(),c=m("");return{get:async(a,t=null)=>{var e;try{s(!0);let o=Boolean(t)?`?page=${a}&${t}`:`?page=${a}`,u=await n.get(`/${i}/${r}${o}`).finally(()=>{s(!1)});return u.status===200&&(l.value=u.data.items,l.count_page=u.data.count_page),u}catch(o){alerts.custom_validation((e=o.response.data.error)!=null?e:o.response.data.message)}},data:l,dataOne:d,errors:c,getOne:async a=>{var t;try{s(!0);let e=await n.get(`/${i}/${r}/${a}`).finally(()=>{s(!1)});return e.status===200&&(d.value=e.data.items),e}catch(e){e.response.status===404?y.not_found_by_id():alerts.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},update:async(a,t)=>{var e;c.value="";try{s(!0),(await n.put(`/${i}/${r}/${a}`,t).finally(()=>{s(!1)})).status===200&&p.push({name:"methodologicalsheetsone.index"})}catch(o){alerts.custom_validation((e=o.response.data.error)!=null?e:o.response.data.message)}},create:async a=>{var t;c.value="";try{return s(!0),await n.post(`/${i}/${r}`,a).finally(()=>{s(!1)})}catch(e){alerts.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},destroy:async a=>{var t;try{s(!0),(await n.delete(`/${i}/${r}/${a}`).finally(()=>{s(!1)})).status===200&&await p.push({name:"methodologicalsheetsone.index"})}catch(e){alerts.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},getCountLimit:async()=>{var a;try{s(!0);let t=await n.get(`/${i}/getCountLimit/${r}`).finally(()=>{s(!1)});return t.status===200&&(l.all=t.data.items),t}catch(t){alerts.custom_validation((a=t.response.data.error)!=null?a:t.response.data.message)}}}}export{C as u};
