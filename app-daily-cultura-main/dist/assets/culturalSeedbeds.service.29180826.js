import{H as y,r as $,u as w,P as r,K as c,Q as i,E as b}from"./index.c93647e1.js";const p="culturalSeedbeds",d="api/v1";function F(){const u=y({all:[],one:{},no_paginate:[],count_page:0}),g=w(),m=$("");return{data:u,errors:m,get:async(s,t=null)=>{var e;try{r(!0);let a=Boolean(t)?`?page=${s}&${t}`:`?page=${s}`,o=await c.get(`/${d}/${p}${a}`).finally(()=>{r(!1)});return o.status===200&&(u.all=o.data.items,u.count_page=o.data.count_page),o}catch(a){i.custom_validation((e=a.response.data.error)!=null?e:a.response.data.message)}},getOne:async s=>{var t;try{r(!0);let e=await c.get(`/${d}/${p}/${s}`).finally(()=>{r(!1)});return e.status===200&&(u.one=e.data.items),e}catch(e){e.response.status===404?b.not_found_by_id():i.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}},update:async(s,t)=>{var e;m.value="";try{const a=new FormData,{development_activity_image:o,evidence_participation_image:l,...n}=t;for(const[f,v]of Object.entries(n))a.append(f,v);a.append("development_activity_image",o),a.append("evidence_participation_image",l),r(!0),(await c.post(`/${d}/${p}/${s}`,a).finally(()=>{r(!1)})).status===200&&(i.update(),g.push({name:"culturalSeedbeds.index"}))}catch(a){i.custom_validation((e=a.response.data.error)!=null?e:a.response.data.message)}},create:async s=>{var l;const t=new FormData,{development_activity_image:e,evidence_participation_image:a,...o}=s;for(const[n,_]of Object.entries(o))t.append(n,_);t.append("development_activity_image",e),t.append("evidence_participation_image",a);try{const n=await c.post(`/${d}/${p}`,t);return n.status===200&&n.data.success&&i.create(),n}catch(n){i.custom_validation((l=n.response.data.error)!=null?l:n.response.data.message)}},destroy:async s=>{var t;try{r(!0),(await c.delete(`/${d}/${p}/${s}`).finally(()=>{r(!1)})).status===200&&i.destroy("Seguimiento",s)}catch(e){i.custom_validation((t=e.response.data.error)!=null?t:e.response.data.message)}}}}export{F as c};
