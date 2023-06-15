import{d as Ae,as as ae,v as $,z as $e,W as Te,S as mn,l as c,D as hn,U as yn,M as Me,N as x,ah as gn,r as J,H as Ye,_ as Ee,V as Rn,ag as pn,at as Tn,f as B,y as tn,w as pe,h as q,g as T,x as ke,t as ce,k as ve,i as be,F as le,a9 as rn,j as ge,a1 as Pn,e as bn,E as ln,n as $n}from"./index.c93647e1.js";import{s as Un}from"./Select.7b4ad9bd.js";/**
  * vee-validate v4.7.4
  * (c) 2023 Abdelrahman Awad
  * @license MIT
  */function Ve(e){return typeof e=="function"}function je(e){return e==null}const me=e=>e!==null&&!!e&&typeof e=="object"&&!Array.isArray(e);function Vn(e){return Number(e)>=0}function zn(e){const n=parseFloat(e);return isNaN(n)?e:n}const Dn={};function Ln(e){return Dn[e]}const Ce=Symbol("vee-validate-form"),Gn=Symbol("vee-validate-field-instance"),Pe=Symbol("Default empty value"),Wn=typeof window!="undefined";function He(e){return Ve(e)&&!!e.__locatorRef}function Ie(e){return!!e&&Ve(e.validate)}function Oe(e){return e==="checkbox"||e==="radio"}function Hn(e){return me(e)||Array.isArray(e)}function On(e){return Array.isArray(e)?e.length===0:me(e)&&Object.keys(e).length===0}function Ue(e){return/^\[.+\]$/i.test(e)}function qn(e){return An(e)&&e.multiple}function An(e){return e.tagName==="SELECT"}function Kn(e,n){const t=![!1,null,void 0,0].includes(n.multiple)&&!Number.isNaN(n.multiple);return e==="select"&&"multiple"in n&&t}function Yn(e,n){return!Kn(e,n)&&n.type!=="file"&&!Oe(n.type)}function _n(e){return Xe(e)&&e.target&&"submit"in e.target}function Xe(e){return e?!!(typeof Event!="undefined"&&Ve(Event)&&e instanceof Event||e&&e.srcElement):!1}function an(e,n){return n in e&&e[n]!==Pe}function z(e,n){if(e===n)return!0;if(e&&n&&typeof e=="object"&&typeof n=="object"){if(e.constructor!==n.constructor)return!1;var t,r,l;if(Array.isArray(e)){if(t=e.length,t!=n.length)return!1;for(r=t;r--!==0;)if(!z(e[r],n[r]))return!1;return!0}if(e instanceof Map&&n instanceof Map){if(e.size!==n.size)return!1;for(r of e.entries())if(!n.has(r[0]))return!1;for(r of e.entries())if(!z(r[1],n.get(r[0])))return!1;return!0}if(un(e)&&un(n))return!(e.size!==n.size||e.name!==n.name||e.lastModified!==n.lastModified||e.type!==n.type);if(e instanceof Set&&n instanceof Set){if(e.size!==n.size)return!1;for(r of e.entries())if(!n.has(r[0]))return!1;return!0}if(ArrayBuffer.isView(e)&&ArrayBuffer.isView(n)){if(t=e.length,t!=n.length)return!1;for(r=t;r--!==0;)if(e[r]!==n[r])return!1;return!0}if(e.constructor===RegExp)return e.source===n.source&&e.flags===n.flags;if(e.valueOf!==Object.prototype.valueOf)return e.valueOf()===n.valueOf();if(e.toString!==Object.prototype.toString)return e.toString()===n.toString();if(l=Object.keys(e),t=l.length,t!==Object.keys(n).length)return!1;for(r=t;r--!==0;)if(!Object.prototype.hasOwnProperty.call(n,l[r]))return!1;for(r=t;r--!==0;){var i=l[r];if(!z(e[i],n[i]))return!1}return!0}return e!==e&&n!==n}function un(e){return Wn?e instanceof File:!1}function on(e,n,t){typeof t.value=="object"&&(t.value=M(t.value)),!t.enumerable||t.get||t.set||!t.configurable||!t.writable||n==="__proto__"?Object.defineProperty(e,n,t):e[n]=t.value}function M(e){if(typeof e!="object")return e;var n=0,t,r,l,i=Object.prototype.toString.call(e);if(i==="[object Object]"?l=Object.create(e.__proto__||null):i==="[object Array]"?l=Array(e.length):i==="[object Set]"?(l=new Set,e.forEach(function(u){l.add(M(u))})):i==="[object Map]"?(l=new Map,e.forEach(function(u,v){l.set(M(v),M(u))})):i==="[object Date]"?l=new Date(+e):i==="[object RegExp]"?l=new RegExp(e.source,e.flags):i==="[object DataView]"?l=new e.constructor(M(e.buffer)):i==="[object ArrayBuffer]"?l=e.slice(0):i.slice(-6)==="Array]"&&(l=new e.constructor(e)),l){for(r=Object.getOwnPropertySymbols(e);n<r.length;n++)on(l,r[n],Object.getOwnPropertyDescriptor(e,r[n]));for(n=0,r=Object.getOwnPropertyNames(e);n<r.length;n++)Object.hasOwnProperty.call(l,t=r[n])&&l[t]===e[t]||on(l,t,Object.getOwnPropertyDescriptor(e,t))}return l||e}function xe(e){return Ue(e)?e.replace(/\[|\]/gi,""):e}function P(e,n,t){return e?Ue(n)?e[xe(n)]:(n||"").split(/\.|\[(\d+)\]/).filter(Boolean).reduce((l,i)=>Hn(l)&&i in l?l[i]:t,e):t}function se(e,n,t){if(Ue(n)){e[xe(n)]=t;return}const r=n.split(/\.|\[(\d+)\]/).filter(Boolean);let l=e;for(let i=0;i<r.length;i++){if(i===r.length-1){l[r[i]]=t;return}(!(r[i]in l)||je(l[r[i]]))&&(l[r[i]]=Vn(r[i+1])?[]:{}),l=l[r[i]]}}function Ge(e,n){if(Array.isArray(e)&&Vn(n)){e.splice(Number(n),1);return}me(e)&&delete e[n]}function Re(e,n){if(Ue(n)){delete e[xe(n)];return}const t=n.split(/\.|\[(\d+)\]/).filter(Boolean);let r=e;for(let i=0;i<t.length;i++){if(i===t.length-1){Ge(r,t[i]);break}if(!(t[i]in r)||je(r[t[i]]))break;r=r[t[i]]}const l=t.map((i,u)=>P(e,t.slice(0,u).join(".")));for(let i=l.length-1;i>=0;i--)if(!!On(l[i])){if(i===0){Ge(e,t[0]);continue}Ge(l[i-1],t[i-1])}}function W(e){return Object.keys(e)}function Je(e,n=void 0){const t=pn();return(t==null?void 0:t.provides[e])||mn(e,n)}function qe(e,n,t){if(Array.isArray(e)){const r=[...e],l=r.findIndex(i=>z(i,n));return l>=0?r.splice(l,1):r.push(n),r}return z(e,n)?t:n}function sn(e,n=0){let t=null,r=[];return function(...l){return t&&window.clearTimeout(t),t=window.setTimeout(()=>{const i=e(...l);r.forEach(u=>u(i)),r=[]},n),new Promise(i=>r.push(i))}}function Xn(e,n){return me(n)&&n.number?zn(e):e}function Ke(e,n){let t;return async function(...l){const i=e(...l);t=i;const u=await i;return i!==t||(t=void 0,n(u,l)),u}}function xn({get:e,set:n}){const t=J(M(e()));return x(e,r=>{z(r,t.value)||(t.value=M(r))},{deep:!0}),x(t,r=>{z(r,e())||n(M(r))},{deep:!0}),t}const ze=(e,n,t)=>n.slots.default?typeof e=="string"||!e?n.slots.default(t()):{default:()=>{var r,l;return(l=(r=n.slots).default)===null||l===void 0?void 0:l.call(r,t())}}:n.slots.default;function We(e){if(Fn(e))return e._value}function Fn(e){return"_value"in e}function Qe(e){if(!Xe(e))return e;const n=e.target;if(Oe(n.type)&&Fn(n))return We(n);if(n.type==="file"&&n.files){const t=Array.from(n.files);return n.multiple?t:t[0]}if(qn(n))return Array.from(n.options).filter(t=>t.selected&&!t.disabled).map(We);if(An(n)){const t=Array.from(n.options).find(r=>r.selected);return t?We(t):n.value}return n.value}function Sn(e){const n={};return Object.defineProperty(n,"_$$isNormalized",{value:!0,writable:!1,enumerable:!1,configurable:!1}),e?me(e)&&e._$$isNormalized?e:me(e)?Object.keys(e).reduce((t,r)=>{const l=Jn(e[r]);return e[r]!==!1&&(t[r]=cn(l)),t},n):typeof e!="string"?n:e.split("|").reduce((t,r)=>{const l=Qn(r);return l.name&&(t[l.name]=cn(l.params)),t},n):n}function Jn(e){return e===!0?[]:Array.isArray(e)||me(e)?e:[e]}function cn(e){const n=t=>typeof t=="string"&&t[0]==="@"?Zn(t.slice(1)):t;return Array.isArray(e)?e.map(n):e instanceof RegExp?[e]:Object.keys(e).reduce((t,r)=>(t[r]=n(e[r]),t),{})}const Qn=e=>{let n=[];const t=e.split(":")[0];return e.includes(":")&&(n=e.split(":").slice(1).join(":").split(",")),{name:t,params:n}};function Zn(e){const n=t=>P(t,e)||t[e];return n.__locatorRef=e,n}function et(e){return Array.isArray(e)?e.filter(He):W(e).filter(n=>He(e[n])).map(n=>e[n])}const nt={generateMessage:({field:e})=>`${e} is not valid.`,bails:!0,validateOnBlur:!0,validateOnChange:!0,validateOnInput:!1,validateOnModelUpdate:!0};let tt=Object.assign({},nt);const Ze=()=>tt;async function wn(e,n,t={}){const r=t==null?void 0:t.bails,l={name:(t==null?void 0:t.name)||"{field}",rules:n,label:t==null?void 0:t.label,bails:r!=null?r:!0,formData:(t==null?void 0:t.values)||{}},u=(await rt(l,e)).errors;return{errors:u,valid:!u.length}}async function rt(e,n){if(Ie(e.rules))return it(n,e.rules,{bails:e.bails});if(Ve(e.rules)||Array.isArray(e.rules)){const u={field:e.label||e.name,name:e.name,label:e.label,form:e.formData,value:n},v=Array.isArray(e.rules)?e.rules:[e.rules],d=v.length,s=[];for(let V=0;V<d;V++){const f=v[V],b=await f(n,u);if(typeof b!="string"&&b)continue;const R=typeof b=="string"?b:En(u);if(s.push(R),e.bails)return{errors:s}}return{errors:s}}const t=Object.assign(Object.assign({},e),{rules:Sn(e.rules)}),r=[],l=Object.keys(t.rules),i=l.length;for(let u=0;u<i;u++){const v=l[u],d=await lt(t,n,{name:v,params:t.rules[v]});if(d.error&&(r.push(d.error),e.bails))return{errors:r}}return{errors:r}}async function it(e,n,t){var r;return{errors:await n.validate(e,{abortEarly:(r=t.bails)!==null&&r!==void 0?r:!0}).then(()=>[]).catch(i=>{if(i.name==="ValidationError")return i.errors;throw i})}}async function lt(e,n,t){const r=Ln(t.name);if(!r)throw new Error(`No such validator '${t.name}' exists.`);const l=at(t.params,e.formData),i={field:e.label||e.name,name:e.name,label:e.label,value:n,form:e.formData,rule:Object.assign(Object.assign({},t),{params:l})},u=await r(n,l,i);return typeof u=="string"?{error:u}:{error:u?void 0:En(i)}}function En(e){const n=Ze().generateMessage;return n?n(e):"Field is invalid"}function at(e,n){const t=r=>He(r)?r(n):r;return Array.isArray(e)?e.map(t):Object.keys(e).reduce((r,l)=>(r[l]=t(e[l]),r),{})}async function ut(e,n){const t=await e.validate(n,{abortEarly:!1}).then(()=>[]).catch(i=>{if(i.name!=="ValidationError")throw i;return i.inner||[]}),r={},l={};for(const i of t){const u=i.errors;r[i.path]={valid:!u.length,errors:u},u.length&&(l[i.path]=u[0])}return{valid:!t.length,results:r,errors:l}}async function ot(e,n,t){const l=W(e).map(async s=>{var V,f,b;const E=(V=t==null?void 0:t.names)===null||V===void 0?void 0:V[s],R=await wn(P(n,s),e[s],{name:(E==null?void 0:E.name)||s,label:E==null?void 0:E.label,values:n,bails:(b=(f=t==null?void 0:t.bailsMap)===null||f===void 0?void 0:f[s])!==null&&b!==void 0?b:!0});return Object.assign(Object.assign({},R),{path:s})});let i=!0;const u=await Promise.all(l),v={},d={};for(const s of u)v[s.path]={valid:s.valid,errors:s.errors},s.valid||(i=!1,d[s.path]=s.errors[0]);return{valid:i,results:v,errors:d}}let dn=0;function st(e,n){const{value:t,initialValue:r,setInitialValue:l}=kn(e,n.modelValue,n.form),{errorMessage:i,errors:u,setErrors:v}=dt(e,n.form),d=ct(t,r,u),s=dn>=Number.MAX_SAFE_INTEGER?0:++dn;function V(f){var b;"value"in f&&(t.value=f.value),"errors"in f&&v(f.errors),"touched"in f&&(d.touched=(b=f.touched)!==null&&b!==void 0?b:d.touched),"initialValue"in f&&l(f.initialValue)}return{id:s,path:e,value:t,initialValue:r,meta:d,errors:u,errorMessage:i,setState:V}}function kn(e,n,t){const r=J(c(n));function l(){return t?P(t.meta.value.initialValues,c(e),c(r)):c(r)}function i(s){if(!t){r.value=s;return}t.stageInitialValue(c(e),s,!0)}const u=$(l);if(!t)return{value:J(l()),initialValue:u,setInitialValue:i};const v=n?c(n):P(t.values,c(e),c(u));return t.stageInitialValue(c(e),v,!0),{value:$({get(){return P(t.values,c(e))},set(s){t.setFieldValue(c(e),s)}}),initialValue:u,setInitialValue:i}}function ct(e,n,t){const r=Ye({touched:!1,pending:!1,valid:!0,validated:!!c(t).length,initialValue:$(()=>c(n)),dirty:$(()=>!z(c(e),c(n)))});return x(t,l=>{r.valid=!l.length},{immediate:!0,flush:"sync"}),r}function dt(e,n){function t(l){return l?Array.isArray(l)?l:[l]:[]}if(!n){const l=J([]);return{errors:l,errorMessage:$(()=>l.value[0]),setErrors:i=>{l.value=t(i)}}}const r=$(()=>n.errorBag.value[c(e)]||[]);return{errors:r,errorMessage:$(()=>r.value[0]),setErrors:l=>{n.setFieldErrorBag(c(e),t(l))}}}function ft(e,n,t){return Oe(t==null?void 0:t.type)?ht(e,n,t):jn(e,n,t)}function jn(e,n,t){const{initialValue:r,validateOnMount:l,bails:i,type:u,checkedValue:v,label:d,validateOnValueUpdate:s,uncheckedValue:V,controlled:f,keepValueOnUnmount:b,modelPropName:E,syncVModel:R,form:H}=vt(c(e),t),ee=f?Je(Ce):void 0,k=H||ee;let D=!1;const{id:h,value:y,initialValue:_,meta:g,setState:A,errors:S,errorMessage:N}=st(e,{modelValue:r,form:k});R&&yt({value:y,prop:E,handleChange:C});const X=()=>{g.touched=!0},te=$(()=>{let m=c(n);const j=c(k==null?void 0:k.schema);return j&&!Ie(j)&&(m=mt(j,c(e))||m),Ie(m)||Ve(m)||Array.isArray(m)?m:Sn(m)});async function re(m){var j,K;return k!=null&&k.validateSchema?(j=(await k.validateSchema(m)).results[c(e)])!==null&&j!==void 0?j:{valid:!0,errors:[]}:wn(y.value,te.value,{name:c(e),label:c(d),values:(K=k==null?void 0:k.values)!==null&&K!==void 0?K:{},bails:i})}const Y=Ke(async()=>(g.pending=!0,g.validated=!0,re("validated-only")),m=>(D&&(m.valid=!0,m.errors=[]),A({errors:m.errors}),g.pending=!1,m)),Z=Ke(async()=>re("silent"),m=>(D&&(m.valid=!0),g.valid=m.valid,m));function U(m){return(m==null?void 0:m.mode)==="silent"?Z():Y()}function C(m,j=!0){const K=Qe(m);y.value=K,!s&&j&&Y()}hn(()=>{if(l)return Y();(!k||!k.validateSchema)&&Z()});function L(m){g.touched=m}let Q,_e=M(y.value);function ue(){Q=x(y,(m,j)=>{if(z(m,j)&&z(m,_e))return;(s?Y:Z)(),_e=M(m)},{deep:!0})}ue();function de(m){var j;Q==null||Q();const K=m&&"value"in m?m.value:_.value;A({value:M(K),initialValue:M(K),touched:(j=m==null?void 0:m.touched)!==null&&j!==void 0?j:!1,errors:(m==null?void 0:m.errors)||[]}),g.pending=!1,g.validated=!1,Z(),Ee(()=>{ue()})}function he(m){y.value=m}function Fe(m){A({errors:Array.isArray(m)?m:[m]})}const oe={id:h,name:e,label:d,value:y,meta:g,errors:S,errorMessage:N,type:u,checkedValue:v,uncheckedValue:V,bails:i,keepValueOnUnmount:b,resetField:de,handleReset:()=>de(),validate:U,handleChange:C,handleBlur:X,setState:A,setTouched:L,setErrors:Fe,setValue:he};if(yn(Gn,oe),Me(n)&&typeof c(n)!="function"&&x(n,(m,j)=>{z(m,j)||(g.validated?Y():Z())},{deep:!0}),!k)return oe;k.register(oe),gn(()=>{D=!0,k.unregister(oe)});const De=$(()=>{const m=te.value;return!m||Ve(m)||Ie(m)||Array.isArray(m)?{}:Object.keys(m).reduce((j,K)=>{const Se=et(m[K]).map(fe=>fe.__locatorRef).reduce((fe,ye)=>{const Be=P(k.values,ye)||k.values[ye];return Be!==void 0&&(fe[ye]=Be),fe},{});return Object.assign(j,Se),j},{})});return x(De,(m,j)=>{if(!Object.keys(m).length)return;!z(m,j)&&(g.validated?Y():Z())}),oe}function vt(e,n){const t=()=>({initialValue:void 0,validateOnMount:!1,bails:!0,label:e,validateOnValueUpdate:!0,keepValueOnUnmount:void 0,modelPropName:"modelValue",syncVModel:!0,controlled:!0});if(!n)return t();const r="valueProp"in n?n.valueProp:n.checkedValue,l="standalone"in n?!n.standalone:n.controlled;return Object.assign(Object.assign(Object.assign({},t()),n||{}),{controlled:l!=null?l:!0,checkedValue:r})}function mt(e,n){if(!!e)return e[n]}function ht(e,n,t){const r=t!=null&&t.standalone?void 0:Je(Ce),l=t==null?void 0:t.checkedValue,i=t==null?void 0:t.uncheckedValue;function u(v){const d=v.handleChange,s=$(()=>{const f=c(v.value),b=c(l);return Array.isArray(f)?f.findIndex(E=>z(E,b))>=0:z(b,f)});function V(f,b=!0){var E;if(s.value===((E=f==null?void 0:f.target)===null||E===void 0?void 0:E.checked)){b&&v.validate();return}let R=Qe(f);r||(R=qe(c(v.value),c(l),c(i))),d(R,b)}return Object.assign(Object.assign({},v),{checked:s,checkedValue:l,uncheckedValue:i,handleChange:V})}return u(jn(e,n,t))}function yt({prop:e,value:n,handleChange:t}){const r=pn();if(!r)return;const l=e||"modelValue",i=`update:${l}`;l in r.props&&(x(n,u=>{z(u,fn(r,l))||r.emit(i,u)}),x(()=>fn(r,l),u=>{if(u===Pe&&n.value===void 0)return;const v=u===Pe?void 0:u;z(v,Xn(n.value,r.props.modelModifiers))||t(v)}))}function fn(e,n){return e.props[n]}const gt=Ae({name:"Field",inheritAttrs:!1,props:{as:{type:[String,Object],default:void 0},name:{type:String,required:!0},rules:{type:[Object,String,Function],default:void 0},validateOnMount:{type:Boolean,default:!1},validateOnBlur:{type:Boolean,default:void 0},validateOnChange:{type:Boolean,default:void 0},validateOnInput:{type:Boolean,default:void 0},validateOnModelUpdate:{type:Boolean,default:void 0},bails:{type:Boolean,default:()=>Ze().bails},label:{type:String,default:void 0},uncheckedValue:{type:null,default:void 0},modelValue:{type:null,default:Pe},modelModifiers:{type:null,default:()=>({})},"onUpdate:modelValue":{type:null,default:void 0},standalone:{type:Boolean,default:!1},keepValue:{type:Boolean,default:void 0}},setup(e,n){const t=ae(e,"rules"),r=ae(e,"name"),l=ae(e,"label"),i=ae(e,"uncheckedValue"),u=ae(e,"keepValue"),{errors:v,value:d,errorMessage:s,validate:V,handleChange:f,handleBlur:b,setTouched:E,resetField:R,handleReset:H,meta:ee,checked:k,setErrors:D}=ft(r,t,{validateOnMount:e.validateOnMount,bails:e.bails,standalone:e.standalone,type:n.attrs.type,initialValue:bt(e,n),checkedValue:n.attrs.value,uncheckedValue:i,label:l,validateOnValueUpdate:!1,keepValueOnUnmount:u}),h=function(N,X=!0){f(N,X),n.emit("update:modelValue",d.value)},y=S=>{Oe(n.attrs.type)||(d.value=Qe(S))},_=function(N){y(N),n.emit("update:modelValue",d.value)},g=$(()=>{const{validateOnInput:S,validateOnChange:N,validateOnBlur:X,validateOnModelUpdate:te}=pt(e),re=[b,n.attrs.onBlur,X?V:void 0].filter(Boolean),Y=[L=>h(L,S),n.attrs.onInput].filter(Boolean),Z=[L=>h(L,N),n.attrs.onChange].filter(Boolean),U={name:e.name,onBlur:re,onInput:Y,onChange:Z};U["onUpdate:modelValue"]=L=>h(L,te),Oe(n.attrs.type)&&k&&(U.checked=k.value);const C=vn(e,n);return Yn(C,n.attrs)&&(U.value=d.value),U});function A(){return{field:g.value,value:d.value,meta:ee,errors:v.value,errorMessage:s.value,validate:V,resetField:R,handleChange:h,handleInput:_,handleReset:H,handleBlur:b,setTouched:E,setErrors:D}}return n.expose({setErrors:D,setTouched:E,reset:R,validate:V,handleChange:f}),()=>{const S=$e(vn(e,n)),N=ze(S,n,A);return S?Te(S,Object.assign(Object.assign({},n.attrs),g.value),N):N}}});function vn(e,n){let t=e.as||"";return!e.as&&!n.slots.default&&(t="input"),t}function pt(e){var n,t,r,l;const{validateOnInput:i,validateOnChange:u,validateOnBlur:v,validateOnModelUpdate:d}=Ze();return{validateOnInput:(n=e.validateOnInput)!==null&&n!==void 0?n:i,validateOnChange:(t=e.validateOnChange)!==null&&t!==void 0?t:u,validateOnBlur:(r=e.validateOnBlur)!==null&&r!==void 0?r:v,validateOnModelUpdate:(l=e.validateOnModelUpdate)!==null&&l!==void 0?l:d}}function bt(e,n){return Oe(n.attrs.type)?an(e,"modelValue")?e.modelValue:void 0:an(e,"modelValue")?e.modelValue:n.attrs.value}const Vt=gt;let Ot=0;function At(e){var n;const t=Ot++,r=new Set;let l=!1;const i=J({}),u=J(!1),v=J(0),d=[],s=Ye(M(c(e==null?void 0:e.initialValues)||{})),{errorBag:V,setErrorBag:f,setFieldErrorBag:b}=St(e==null?void 0:e.initialErrors),E=$(()=>W(V.value).reduce((a,o)=>{const p=V.value[o];return p&&p.length&&(a[o]=p[0]),a},{}));function R(a){const o=i.value[a];return Array.isArray(o)?o[0]:o}function H(a){return!!i.value[a]}const ee=$(()=>W(i.value).reduce((a,o)=>{const p=R(o);return p&&(a[o]={name:c(p.name)||"",label:c(p.label)||""}),a},{})),k=$(()=>W(i.value).reduce((a,o)=>{var p;const O=R(o);return O&&(a[o]=(p=O.bails)!==null&&p!==void 0?p:!0),a},{})),D=Object.assign({},(e==null?void 0:e.initialErrors)||{}),h=(n=e==null?void 0:e.keepValuesOnUnmount)!==null&&n!==void 0?n:!1,{initialValues:y,originalInitialValues:_,setInitialValues:g}=Ft(i,s,e==null?void 0:e.initialValues),A=_t(i,s,_,E),S=$(()=>[...r,...W(i.value)].reduce((a,o)=>{const p=P(s,o);return se(a,o,p),a},{})),N=e==null?void 0:e.validationSchema,X=sn(nn,5),te=sn(nn,5),re=Ke(async a=>await a==="silent"?X():te(),(a,[o])=>{const p=C.fieldsByPath.value||{},O=W(C.errorBag.value);return[...new Set([...W(a.results),...W(p),...O])].reduce((F,w)=>{const G=p[w],ie=(a.results[w]||{errors:[]}).errors,ne={errors:ie,valid:!ie.length};if(F.results[w]=ne,ne.valid||(F.errors[w]=ne.errors[0]),!G)return ue(w,ie),F;if(Q(G,we=>we.meta.valid=ne.valid),o==="silent")return F;const Nn=Array.isArray(G)?G.some(we=>we.meta.validated):G.meta.validated;return o==="validated-only"&&!Nn||Q(G,we=>we.setState({errors:ne.errors})),F},{valid:a.valid,results:{},errors:{}})});function Y(a){return function(p,O){return function(F){return F instanceof Event&&(F.preventDefault(),F.stopPropagation()),j(W(i.value).reduce((w,G)=>(w[G]=!0,w),{})),u.value=!0,v.value++,Ne().then(w=>{const G=M(s);if(w.valid&&typeof p=="function"){const ie=M(S.value);return p(a?ie:G,{evt:F,controlledValues:ie,setErrors:de,setFieldError:ue,setTouched:j,setFieldTouched:m,setValues:Fe,setFieldValue:he,resetForm:Se,resetField:K})}!w.valid&&typeof O=="function"&&O({values:G,evt:F,errors:w.errors,results:w.results})}).then(w=>(u.value=!1,w),w=>{throw u.value=!1,w})}}}const U=Y(!1);U.withControlled=Y(!0);const C={formId:t,fieldsByPath:i,values:s,controlledValues:S,errorBag:V,errors:E,schema:N,submitCount:v,meta:A,isSubmitting:u,fieldArrays:d,keepValuesOnUnmount:h,validateSchema:c(N)?re:void 0,validate:Ne,register:Be,unregister:In,setFieldErrorBag:b,validateField:Le,setFieldValue:he,setValues:Fe,setErrors:de,setFieldError:ue,setFieldTouched:m,setTouched:j,resetForm:Se,resetField:K,handleSubmit:U,stageInitialValue:Cn,unsetInitialValue:Mn,setFieldInitialValue:en,useFieldModel:De};function L(a){return Array.isArray(a)}function Q(a,o){return Array.isArray(a)?a.forEach(o):o(a)}function _e(a){Object.values(i.value).forEach(o=>{!o||Q(o,a)})}function ue(a,o){b(a,o)}function de(a){f(a)}function he(a,o,{force:p}={force:!1}){var O;const I=i.value[a],F=M(o);if(!I){se(s,a,F);return}if(L(I)&&((O=I[0])===null||O===void 0?void 0:O.type)==="checkbox"&&!Array.isArray(o)){const G=M(qe(P(s,a)||[],o,void 0));se(s,a,G);return}let w=F;!L(I)&&I.type==="checkbox"&&!p&&!l&&(w=M(qe(P(s,a),o,c(I.uncheckedValue)))),se(s,a,w)}function Fe(a){W(s).forEach(o=>{delete s[o]}),W(a).forEach(o=>{he(o,a[o])}),d.forEach(o=>o&&o.reset())}function oe(a){const{value:o}=kn(a,void 0,C);return x(o,()=>{H(c(a))||Ne({mode:"validated-only"})},{deep:!0}),r.add(c(a)),o}function De(a){return Array.isArray(a)?a.map(oe):oe(a)}function m(a,o){const p=i.value[a];p&&Q(p,O=>O.setTouched(o))}function j(a){W(a).forEach(o=>{m(o,!!a[o])})}function K(a,o){const p=i.value[a];p&&Q(p,O=>O.resetField(o))}function Se(a){l=!0,_e(p=>p.resetField());const o=a!=null&&a.values?a.values:_.value;g(o),Fe(o),a!=null&&a.touched&&j(a.touched),de((a==null?void 0:a.errors)||{}),v.value=(a==null?void 0:a.submitCount)||0,Ee(()=>{l=!1})}function fe(a,o){const p=Tn(a),O=o;if(!i.value[O]){i.value[O]=p;return}const I=i.value[O];I&&!Array.isArray(I)&&(i.value[O]=[I]),i.value[O]=[...i.value[O],p]}function ye(a,o){const p=o,O=i.value[p];if(!!O){if(!L(O)&&a.id===O.id){delete i.value[p];return}if(L(O)){const I=O.findIndex(F=>F.id===a.id);if(I===-1)return;O.splice(I,1),O.length||delete i.value[p]}}}function Be(a){const o=c(a.name);fe(a,o),Me(a.name)&&x(a.name,async(O,I)=>{await Ee(),ye(a,I),fe(a,O),(E.value[I]||E.value[O])&&(ue(I,void 0),Le(O)),await Ee(),H(I)||Re(s,I)});const p=c(a.errorMessage);p&&(D==null?void 0:D[o])!==p&&Le(o),delete D[o]}function In(a){const o=c(a.name),p=i.value[o],O=!!p&&L(p);ye(a,o),Ee(()=>{var I;const F=(I=c(a.keepValueOnUnmount))!==null&&I!==void 0?I:c(h),w=P(s,o);if(O&&(p===i.value[o]||!i.value[o])&&!F)if(Array.isArray(w)){const ie=w.findIndex(ne=>z(ne,c(a.checkedValue)));if(ie>-1){const ne=[...w];ne.splice(ie,1),he(o,ne,{force:!0})}}else w===c(a.checkedValue)&&Re(s,o);if(!H(o)){if(ue(o,void 0),F||O&&Array.isArray(w)&&!On(w))return;Re(s,o)}})}async function Ne(a){const o=(a==null?void 0:a.mode)||"force";if(o==="force"&&_e(F=>F.meta.validated=!0),C.validateSchema)return C.validateSchema(o);const p=await Promise.all(Object.values(i.value).map(F=>{const w=Array.isArray(F)?F[0]:F;return w?w.validate(a).then(G=>({key:c(w.name),valid:G.valid,errors:G.errors})):Promise.resolve({key:"",valid:!0,errors:[]})})),O={},I={};for(const F of p)O[F.key]={valid:F.valid,errors:F.errors},F.errors.length&&(I[F.key]=F.errors[0]);return{valid:p.every(F=>F.valid),results:O,errors:I}}async function Le(a){const o=i.value[a];return o?Array.isArray(o)?o.map(p=>p.validate())[0]:o.validate():Promise.resolve({errors:[],valid:!0})}function Mn(a){Re(y.value,a)}function Cn(a,o,p=!1){se(s,a,o),en(a,o),p&&!(e!=null&&e.initialValues)&&se(_.value,a,M(o))}function en(a,o){se(y.value,a,M(o))}async function nn(){const a=c(N);return a?Ie(a)?await ut(a,s):await ot(a,s,{names:ee.value,bailsMap:k.value}):{valid:!0,results:{},errors:{}}}const Bn=U((a,{evt:o})=>{_n(o)&&o.target.submit()});return hn(()=>{if(e!=null&&e.initialErrors&&de(e.initialErrors),e!=null&&e.initialTouched&&j(e.initialTouched),e!=null&&e.validateOnMount){Ne();return}C.validateSchema&&C.validateSchema("silent")}),Me(N)&&x(N,()=>{var a;(a=C.validateSchema)===null||a===void 0||a.call(C,"validated-only")}),yn(Ce,C),Object.assign(Object.assign({},C),{handleReset:()=>Se(),submitForm:Bn})}function _t(e,n,t,r){const l={touched:"some",pending:"some",valid:"every"},i=$(()=>!z(n,c(t)));function u(){const d=Object.values(e.value).flat(1).filter(Boolean);return W(l).reduce((s,V)=>{const f=l[V];return s[V]=d[f](b=>b.meta[V]),s},{})}const v=Ye(u());return Rn(()=>{const d=u();v.touched=d.touched,v.valid=d.valid,v.pending=d.pending}),$(()=>Object.assign(Object.assign({initialValues:c(t)},v),{valid:v.valid&&!W(r.value).length,dirty:i.value}))}function Ft(e,n,t){const r=J(M(c(t))||{}),l=J(M(c(t))||{});function i(u,v=!1){r.value=M(u),l.value=M(u),v&&W(e.value).forEach(d=>{const s=e.value[d],V=Array.isArray(s)?s.some(b=>b.meta.touched):s==null?void 0:s.meta.touched;if(!s||V)return;const f=P(r.value,d);se(n,d,M(f))})}return Me(t)&&x(t,u=>{i(u,!0)},{deep:!0}),{initialValues:r,originalInitialValues:l,setInitialValues:i}}function St(e){const n=J({});function t(i){return Array.isArray(i)?i:i?[i]:[]}function r(i,u){if(!u){delete n.value[i];return}n.value[i]=t(u)}function l(i){n.value=W(i).reduce((u,v)=>{const d=i[v];return d&&(u[v]=t(d)),u},{})}return e&&l(e),{errorBag:n,setErrorBag:l,setFieldErrorBag:r}}const wt=Ae({name:"Form",inheritAttrs:!1,props:{as:{type:String,default:"form"},validationSchema:{type:Object,default:void 0},initialValues:{type:Object,default:void 0},initialErrors:{type:Object,default:void 0},initialTouched:{type:Object,default:void 0},validateOnMount:{type:Boolean,default:!1},onSubmit:{type:Function,default:void 0},onInvalidSubmit:{type:Function,default:void 0},keepValues:{type:Boolean,default:!1}},setup(e,n){const t=ae(e,"initialValues"),r=ae(e,"validationSchema"),l=ae(e,"keepValues"),{errors:i,values:u,meta:v,isSubmitting:d,submitCount:s,controlledValues:V,validate:f,validateField:b,handleReset:E,resetForm:R,handleSubmit:H,setErrors:ee,setFieldError:k,setFieldValue:D,setValues:h,setFieldTouched:y,setTouched:_,resetField:g}=At({validationSchema:r.value?r:void 0,initialValues:t,initialErrors:e.initialErrors,initialTouched:e.initialTouched,validateOnMount:e.validateOnMount,keepValuesOnUnmount:l}),A=H((U,{evt:C})=>{_n(C)&&C.target.submit()},e.onInvalidSubmit),S=e.onSubmit?H(e.onSubmit,e.onInvalidSubmit):A;function N(U){Xe(U)&&U.preventDefault(),E(),typeof n.attrs.onReset=="function"&&n.attrs.onReset()}function X(U,C){return H(typeof U=="function"&&!C?U:C,e.onInvalidSubmit)(U)}function te(){return M(u)}function re(){return M(v.value)}function Y(){return M(i.value)}function Z(){return{meta:v.value,errors:i.value,values:u,isSubmitting:d.value,submitCount:s.value,controlledValues:V.value,validate:f,validateField:b,handleSubmit:X,handleReset:E,submitForm:A,setErrors:ee,setFieldError:k,setFieldValue:D,setValues:h,setFieldTouched:y,setTouched:_,resetForm:R,resetField:g,getValues:te,getMeta:re,getErrors:Y}}return n.expose({setFieldError:k,setErrors:ee,setFieldValue:D,setValues:h,setFieldTouched:y,setTouched:_,resetForm:R,validate:f,validateField:b,resetField:g,getValues:te,getMeta:re,getErrors:Y}),function(){const C=e.as==="form"?e.as:$e(e.as),L=ze(C,n,Z);if(!e.as)return L;const Q=e.as==="form"?{novalidate:!0}:{};return Te(C,Object.assign(Object.assign(Object.assign({},Q),n.attrs),{onSubmit:S,onReset:N}),L)}}}),Et=wt;function kt(e){const n=Je(Ce,void 0),t=J([]),r=()=>{},l={fields:t,remove:r,push:r,swap:r,insert:r,update:r,replace:r,prepend:r,move:r};if(!n||!c(e))return l;const i=n.fieldArrays.find(h=>c(h.path)===c(e));if(i)return i;let u=0;function v(){const h=P(n==null?void 0:n.values,c(e),[])||[];t.value=h.map(s),d()}v();function d(){const h=t.value.length;for(let y=0;y<h;y++){const _=t.value[y];_.isFirst=y===0,_.isLast=y===h-1}}function s(h){const y=u++;return{key:y,value:xn({get(){const g=P(n==null?void 0:n.values,c(e),[])||[],A=t.value.findIndex(S=>S.key===y);return A===-1?h:g[A]},set(g){const A=t.value.findIndex(S=>S.key===y);A!==-1&&H(A,g)}}),isFirst:!1,isLast:!1}}function V(h){const y=c(e),_=P(n==null?void 0:n.values,y);if(!_||!Array.isArray(_))return;const g=[..._];g.splice(h,1),n==null||n.unsetInitialValue(y+`[${h}]`),n==null||n.setFieldValue(y,g),t.value.splice(h,1),d()}function f(h){const y=c(e),_=P(n==null?void 0:n.values,y),g=je(_)?[]:_;if(!Array.isArray(g))return;const A=[...g];A.push(h),n==null||n.stageInitialValue(y+`[${A.length-1}]`,h),n==null||n.setFieldValue(y,A),t.value.push(s(h)),d()}function b(h,y){const _=c(e),g=P(n==null?void 0:n.values,_);if(!Array.isArray(g)||!(h in g)||!(y in g))return;const A=[...g],S=[...t.value],N=A[h];A[h]=A[y],A[y]=N;const X=S[h];S[h]=S[y],S[y]=X,n==null||n.setFieldValue(_,A),t.value=S,d()}function E(h,y){const _=c(e),g=P(n==null?void 0:n.values,_);if(!Array.isArray(g)||g.length<h)return;const A=[...g],S=[...t.value];A.splice(h,0,y),S.splice(h,0,s(y)),n==null||n.setFieldValue(_,A),t.value=S,d()}function R(h){const y=c(e);n==null||n.setFieldValue(y,h),v()}function H(h,y){const _=c(e),g=P(n==null?void 0:n.values,_);!Array.isArray(g)||g.length-1<h||(n==null||n.setFieldValue(`${_}[${h}]`,y),n==null||n.validate({mode:"validated-only"}))}function ee(h){const y=c(e),_=P(n==null?void 0:n.values,y),g=je(_)?[]:_;if(!Array.isArray(g))return;const A=[h,...g];n==null||n.stageInitialValue(y+`[${A.length-1}]`,h),n==null||n.setFieldValue(y,A),t.value.unshift(s(h)),d()}function k(h,y){const _=c(e),g=P(n==null?void 0:n.values,_),A=je(g)?[]:[...g];if(!Array.isArray(g)||!(h in g)||!(y in g))return;const S=[...t.value],N=S[h];S.splice(h,1),S.splice(y,0,N);const X=A[h];A.splice(h,1),A.splice(y,0,X),n==null||n.setFieldValue(_,A),t.value=S,d()}const D={fields:t,remove:V,push:f,swap:b,insert:E,update:H,replace:R,prepend:ee,move:k};return n.fieldArrays.push(Object.assign({path:e,reset:v},D)),gn(()=>{const h=n.fieldArrays.findIndex(y=>c(y.path)===c(e));h>=0&&n.fieldArrays.splice(h,1)}),D}Ae({name:"FieldArray",inheritAttrs:!1,props:{name:{type:String,required:!0}},setup(e,n){const{push:t,remove:r,swap:l,insert:i,replace:u,update:v,prepend:d,move:s,fields:V}=kt(ae(e,"name"));function f(){return{fields:V.value,push:t,remove:r,swap:l,insert:i,update:v,replace:u,prepend:d,move:s}}return n.expose({push:t,remove:r,swap:l,insert:i,update:v,replace:u,prepend:d,move:s}),()=>ze(void 0,n,f)}});const jt=Ae({name:"ErrorMessage",props:{as:{type:String,default:void 0},name:{type:String,required:!0}},setup(e,n){const t=mn(Ce,void 0),r=$(()=>t==null?void 0:t.errors.value[e.name]);function l(){return{message:r.value}}return()=>{if(!r.value)return;const i=e.as?$e(e.as):e.as,u=ze(i,n,l),v=Object.assign({role:"alert"},n.attrs);return!i&&(Array.isArray(u)||!u)&&(u==null?void 0:u.length)?u:(Array.isArray(u)||!u)&&!(u!=null&&u.length)?Te(i||"span",v,r.value):Te(i,v,u)}}}),It=jt,Mt=["for"],Ct={key:0,value:"",disabled:"",selected:"",hidden:""},Bt={key:0},Nt={class:"text-left text-danger text-xs"},Rt={type:"submit",class:"btn btn-primary flex"},Tt=Ae({__name:"DynamicForm",props:{schema:null,initial:null},emits:["submit"],setup(e,{emit:n}){const t=e;function r(l,{resetForm:i}){n("submit",l),i()}return(l,i)=>{const u=bn("PlusIcon");return B(),tn(c(Et),{onSubmit:r,class:"grid grid-cols-1 gap-2 lg:grid lg:grid-cols-[1fr_auto] lg:gap-4 lg:justify-between md:items-start"},{default:pe(({})=>[q("div",{class:"flex flex-col gap-2 lg:grid lg:gap-4 lg:justify-evenly lg:items-start",style:Pn(`grid-template-columns: repeat(${t.schema.fields.length}, minmax(0, 1fr))`)},[(B(!0),T(le,null,ke(e.schema.fields,({as:v,name:d,label:s,children:V,...f})=>(B(),T("div",{key:d},[s?(B(),T("label",{key:0,for:d},ce(s),9,Mt)):ve("",!0),be(c(Vt),rn({as:v,id:d,name:d},f,{validateOnInput:"",class:"form-control",autocomplete:"off"}),{default:pe(()=>[V&&V.length?(B(),T(le,{key:0},[v.includes("select")?(B(),T("option",Ct,[f!=null&&f.placeholder?(B(),T("span",Bt,ce(f==null?void 0:f.placeholder),1)):ve("",!0)])):ve("",!0),(B(!0),T(le,null,ke(V,({tag:b,label:E,...R},H)=>(B(),tn($e(b),rn({key:H},R),{default:pe(()=>[ge(ce(E),1)]),_:2},1040))),128))],64)):ve("",!0)]),_:2},1040,["as","id","name"]),be(c(It),{name:d},{default:pe(({message:b})=>[q("p",Nt,ce(b),1)]),_:2},1032,["name"])]))),128))],4),q("button",Rt,[be(u,{class:"w-5 h-5"})])]),_:1})}}}),Pt={class:"grid grid-cols-1 gap-2"},$t={key:0,class:"relative border border-slate-200 rounded"},Ut={key:0,class:"pointer-events-none absolute top-2.5 left-[8px] text-sm z-10 opacity-100"},zt={key:1,class:"p-1"},Dt={class:"overflow-x-auto"},Lt={class:"table"},Gt={class:"table-dark"},Wt={class:"whitespace-nowrap"},Ht=q("th",{class:"whitespace-nowrap"},[q("div",{class:"w-full flex justify-end"}," ACCIONES ")],-1),qt={class:"flex justify-end gap-2"},Kt=["onClick"],Yt={key:1,class:"text-center"},Xt=["colspan"],xt={class:"flex flex-col gap-2"},Zt=Ae({__name:"Aggregates",props:{headers:null,aggregates:null,options:null,add_schema:null,name:null,validator:null},emits:["pop","push"],setup(e,{emit:n}){const t=e,r=J({}),l=J(!0),i=d=>t.options.at(0)[d],u=$(()=>t.options?t.aggregates?t.options.filter(s=>{const V=Object.keys(s)[0];return!t.aggregates.some(f=>f[V]===s[V])}):t.options:[]);x(u,()=>{u.value.length==0?l.value=!1:l.value=!0});const v=$(()=>t.validator[t.name].$errors.length?"text-danger":!1);return(d,s)=>{const V=bn("Trash2Icon");return B(),T("div",Pt,[e.options?(B(),T("div",$t,[c(l)?(B(),T("span",Ut," Selecciona o busca... ")):ve("",!0),be(c(Un),{modelValue:c(r),"onUpdate:modelValue":s[0]||(s[0]=f=>Me(r)?r.value=f:null),placeholder:"Selecciona o busca...",openDirection:"below",disabled:c(u).length===0,label:e.options.length==0?"No hay":i("monitor_fullname")?"monitor_fullname":i("full_name")?"full_name":i("name")?"name":i("assistant_name")?"assistant_name":i("names")?"names":"No hay",showPointer:!1,options:c(u),"option-height":104,searchable:!0,"close-on-select":!0,onOpen:s[1]||(s[1]=f=>l.value=!1),onClose:s[2]||(s[2]=f=>l.value=!0),onSelect:s[3]||(s[3]=f=>{d.$emit("push",f)}),resetAfter:""},{noResult:pe(()=>[ge(" Sin resultados. ")]),noOptions:pe(()=>[ge(" Lista vacia. ")]),_:1},8,["modelValue","disabled","label","options"])])):ve("",!0),e.add_schema?(B(),T("div",zt,[be(Tt,{schema:e.add_schema,onSubmit:s[4]||(s[4]=f=>d.$emit("push",f))},null,8,["schema"])])):ve("",!0),q("div",Dt,[q("table",Lt,[q("thead",Gt,[q("tr",null,[(B(!0),T(le,null,ke(e.headers,f=>(B(),T("th",Wt,ce(f.text),1))),256)),Ht])]),q("tbody",null,[e.aggregates.length?(B(!0),T(le,{key:0},ke(e.aggregates,f=>(B(),T("tr",null,[(B(!0),T(le,null,ke(e.headers,b=>(B(),T("td",null,[b.value=="nac_id"?(B(),T(le,{key:0},[ge(ce(c(ln).get_option_label("nacs",f[b.value])),1)],64)):b.value=="role_id"?(B(),T(le,{key:1},[ge(ce(c(ln).get_option_label("roles_display",f[b.value])),1)],64)):(B(),T(le,{key:2},[ge(ce(f[b.value]),1)],64))]))),256)),q("td",null,[q("div",qt,[q("button",{type:"button",class:"btn btn-danger",onClick:b=>d.$emit("pop",f.id)},[be(V,{class:"w-5 h-5"})],8,Kt)])])]))),256)):(B(),T("tr",Yt,[q("td",{colspan:e.headers.length+1},[q("div",xt,[q("span",{class:$n(c(v))}," No hay agregados ",2)])],8,Xt)]))])])])])}}});export{Zt as _};
