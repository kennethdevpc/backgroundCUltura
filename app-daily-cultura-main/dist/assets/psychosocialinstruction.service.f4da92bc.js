import{H as $,r as w,u as h,P as n,K as p,Q as o,E as O}from"./index.c93647e1.js";const d="psychosocialinstructions",l="api/v1";function j(){const u=$({all:[],one:{},no_paginate:[],count_page:0}),g=h(),f=w("");return{data:u,errors:f,get:async(t,a=null)=>{var e;try{n(!0);let i=Boolean(a)?`?page=${t}&${a}`:`?page=${t}`,r=await p.get(`/${l}/${d}${i}`).finally(()=>{n(!1)});r.status===200&&(u.all=r.data.items,u.count_page=r.data.count_page)}catch(i){o.custom_validation((e=i.response.data.error)!=null?e:i.response.data.message)}},getOne:async t=>{var a;try{n(!0);let e=await p.get(`/${l}/${d}/${t}`).finally(()=>{n(!1)});return e.status===200&&(u.one=e.data.items),e}catch(e){e.response.status===404?O.not_found_by_id():o.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message)}},update:async(t,a)=>{var s;f.value="";const e=new FormData,{development_activity_image:i,evidence_participation_image:r,added_wizards:_,assistance_monitors:y,...m}=a;for(const[c,v]of Object.entries(m))e.append(c,v);e.append("added_wizards",JSON.stringify(_)),e.append("assistance_monitors",JSON.stringify(y)),i.file&&e.append("development_activity_image",i.file),r.file&&e.append("evidence_participation_image",r.file);try{n(!0),(await p.post(`/${l}/${d}/${t}`,e,{"Content-Type":"multipart/form-data"}).finally(()=>{n(!1)})).status==200&&(o.update(),g.push({name:"psychosocialinstructions.index"}))}catch(c){o.custom_validation(c.response.data.message),o.custom_validation((s=c.response.data.error)!=null?s:c.response.data.message)}},create:async t=>{var m;f.value="";const a=new FormData,{development_activity_image:e,evidence_participation_image:i,added_wizards:r,assistance_monitors:_,...y}=t;for(const[s,c]of Object.entries(y))a.append(s,c);a.append("development_activity_image",e.file),a.append("evidence_participation_image",i.file),a.append("added_wizards",JSON.stringify(r)),a.append("assistance_monitors",JSON.stringify(_));try{n(!0);const s=await p.post(`/${l}/${d}`,a,{"Content-Type":"multipart/form-data"}).finally(()=>{n(!1)});return s.data.success===!0&&o.create(),s}catch(s){o.custom_validation((m=s.response.data.error)!=null?m:s.response.data.message)}},destroy:async t=>{var a;try{n(!0),(await p.delete(`/${l}/${d}/${t}`).finally(()=>{n(!1)})).status===200&&o.destroy("Instrucci\xF3n Psicosocial",t)}catch(e){o.custom_validation((a=e.response.data.error)!=null?a:e.response.data.message)}}}}export{j as p};
