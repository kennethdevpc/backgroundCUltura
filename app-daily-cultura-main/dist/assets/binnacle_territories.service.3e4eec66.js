import{H as _,r as v,u as $,P as s,K as l,Q as o,E as w}from"./index.c93647e1.js";const u="binnacle_territories",p="api/v1";function U(){const d=_({all:[],one:{},no_paginate:[],count_page:0}),g=$(),m=v("");return{data:d,errors:m,get:async(a,e=null)=>{var t;try{s(!0);let r=Boolean(e)?`?page=${a}&${e}`:`?page=${a}`,i=await l.get(`/${p}/${u}${r}`).finally(()=>{s(!1)});i.status===200&&(d.all=i.data.items,d.count_page=i.data.count_page)}catch(r){o.custom_validation((t=r.response.data.error)!=null?t:r.response.data.message)}},getOne:async a=>{var e;try{s(!0);let t=await l.get(`/${p}/${u}/${a}`).finally(()=>{s(!1)});return t.status===200&&(d.one=t.data.items),t}catch(t){t.response.status===404?w.not_found_by_id():o.custom_validation((e=t.response.data.error)!=null?e:t.response.data.message)}},update:async(a,e)=>{var n;m.value="";const t=new FormData,{file_1:r,file_2:i,...f}=e;for(const[c,y]of Object.entries(f))t.append(c,y);t.append("development_activity_image",r),t.append("evidence_participation_image",i);try{s(!0),(await l.post(`/${p}/${u}/${a}`,t).finally(()=>{s(!1)})).status===200&&(o.update(),g.push({name:"coordinadores.index"}))}catch(c){o.custom_validation((n=c.response.data.error)!=null?n:c.response.data.message)}},create:async a=>{var f;m.value="";const e=new FormData,{file_1:t,file_2:r,...i}=a;for(const[n,c]of Object.entries(i))e.append(n,c);e.append("development_activity_image",t),e.append("evidence_participation_image",r);try{s(!0);const n=await l.post(`/${p}/${u}`,e).finally(()=>{s(!1)});return n.status===200&&n.data.success&&o.create(),n}catch(n){o.custom_validation((f=n.response.data.error)!=null?f:n.response.data.message)}},destroy:async a=>{var e;try{s(!0),(await l.delete(`/${p}/${u}/${a}`).finally(()=>{s(!1)})).status==200&&o.destroy("Visita a territorio",a)}catch(t){o.custom_validation((e=t.response.data.error)!=null?e:t.response.data.message)}},getAllByUserId:async()=>{var a;try{s(!0);let e=await l.get(`/${p}/getAllByUserLogged`).finally(()=>{s(!1)});e.status===200&&(d.all=e.data.items)}catch(e){o.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message)}}}}export{U as b};