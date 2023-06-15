import{H as g,r as y,u as f,P as t,K as n,Q as o}from"./index.c93647e1.js";const l="users",c="api/v1";function S(){const u=g({all:[],one:{},no_paginate:[],user_id:null,count_page:0}),d=f(),i=y("");return{data:u,errors:i,get:async(a,s=null)=>{var e;try{t(!0);let r=Boolean(s)?`?page=${a}&${s}`:`?page=${a}`,p=await n.get(`/${c}/${l}${r}`).finally(()=>{t(!1)});p.status===200&&(u.all=p.data.items,u.count_page=p.data.count_page)}catch(r){o.custom_validation((e=r.response.data.error)!=null?e:r.response.data.message)}},getOne:async a=>{var s;try{t(!0);let e=await n.get(`/${c}/${l}/${a}`).finally(()=>{t(!1)});e.status===200&&(u.one=e.data.items)}catch(e){o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}},update:async(a,s)=>{var e;i.value="";try{t(!0),(await n.put(`/${c}/${l}/${a}`,s).finally(()=>{t(!1)})).status==200&&d.push({name:"users.index",params:{}})}catch(r){o.custom_validation((e=r.response.data.error)!=null?e:r.response.data.message)}},create:async a=>{var s;i.value="";try{t(!0);const e=await n.post(`/${c}/${l}`,a).finally(()=>{t(!1)});return e.status===200&&(u.user_id=e.data.items.id),e}catch(e){o.error((s=e.response.data.error)!=null?s:e.response.data.message)}},destroy:async a=>{var s;try{t(!0),(await n.delete(`/${c}/${l}/${a}`).finally(()=>{t(!1)})).status===200&&await d.push({name:"users.index"})}catch(e){o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}},usersNoPaginate:async()=>{var a;try{t(!0);let s=await n.get(`/${c}/usersNoPaginate`).finally(()=>{t(!1)});s.status===200&&(u.no_paginate=s.data.items)}catch(s){o.custom_validation((a=s.response.data.error)!=null?a:s.response.data.message)}},changePassword:async a=>{var s;i.value="";try{return t(!0),await n.post(`/${c}/users/changePassword`,a).finally(()=>{t(!1)})}catch(e){o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}},changeStatus:async a=>{var s;i.value="";try{return t(!0),await n.post(`/${c}/users/changeStatus`,a).finally(()=>{t(!1)})}catch(e){o.custom_validation((s=e.response.data.error)!=null?s:e.response.data.message)}}}}export{S as u};
