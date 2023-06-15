import{V as ne,r as V,v,U,d as M,D as B,X as K,l as h,S as W,a7 as w,N as re,W as A,F as O,b as ie,H as de,E as ue,f as C,g as I,h as r,i as E,w as H,j as $,x as ce,n as fe,t as pe,M as me,k,u as ve,O as he,e as ge}from"./index.c93647e1.js";import{_ as j}from"./Input.d2784565.js";import{r as xe}from"./report.services.02e33be8.js";import{u as ye}from"./index.esm.8471a966.js";import{m as R,t as N,H as T,M as Y,I as be,o as P,f as _e,K as we,a as Ce,g as Ie,E as fa,d as z,O as D,N as F,T as Z}from"./description.c57d405a.js";function Ee({container:e,accept:t,walk:o,enabled:s}){ne(()=>{let u=e.value;if(!u||s!==void 0&&!s.value)return;let f=R(e);if(!f)return;let c=Object.assign(b=>t(b),{acceptNode:t}),i=f.createTreeWalker(u,NodeFilter.SHOW_ELEMENT,c,!1);for(;i.nextNode();)o(i.currentNode)})}function q(e={},t=null,o=[]){for(let[s,u]of Object.entries(e))X(o,J(t,s),u);return o}function J(e,t){return e?e+"["+t+"]":t}function X(e,t,o){if(Array.isArray(o))for(let[s,u]of o.entries())X(e,J(t,s.toString()),u);else o instanceof Date?e.push([t,o.toISOString()]):typeof o=="boolean"?e.push([t,o?"1":"0"]):typeof o=="string"?e.push([t,o]):typeof o=="number"?e.push([t,`${o}`]):o==null?e.push([t,""]):q(o,t,e)}function Ve(e){var t;let o=(t=e==null?void 0:e.form)!=null?t:e.closest("form");if(o){for(let s of o.elements)if(s.tagName==="INPUT"&&s.type==="submit"||s.tagName==="BUTTON"&&s.type==="submit"||s.nodeName==="INPUT"&&s.type==="image"){s.click();return}}}function He(e,t,o){let s=V(o==null?void 0:o.value),u=v(()=>e.value!==void 0);return[v(()=>u.value?e.value:s.value),function(f){return u.value||(s.value=f),t==null?void 0:t(f)}]}let Q=Symbol("LabelContext");function ee(){let e=W(Q,null);if(e===null){let t=new Error("You used a <Label /> component, but it is not inside a parent.");throw Error.captureStackTrace&&Error.captureStackTrace(t,ee),t}return e}function ae({slot:e={},name:t="Label",props:o={}}={}){let s=V([]);function u(f){return s.value.push(f),()=>{let c=s.value.indexOf(f);c!==-1&&s.value.splice(c,1)}}return U(Q,{register:u,slot:e,name:t,props:o}),v(()=>s.value.length>0?s.value.join(" "):void 0)}let ze=M({name:"Label",props:{as:{type:[Object,String],default:"label"},passive:{type:[Boolean],default:!1},id:{type:String,default:()=>`headlessui-label-${N()}`}},setup(e,{slots:t,attrs:o}){let s=ee();return B(()=>K(s.register(e.id))),()=>{let{name:u="Label",slot:f={},props:c={}}=s,{id:i,passive:b,...g}=e,_={...Object.entries(c).reduce((p,[y,x])=>Object.assign(p,{[y]:h(x)}),{}),id:i};return b&&(delete _.onClick,delete g.onClick),T({ourProps:_,theirProps:g,slot:f,attrs:o,slots:t,name:u})}}});function Se(e,t){return e===t}let le=Symbol("RadioGroupContext");function te(e){let t=W(le,null);if(t===null){let o=new Error(`<${e} /> is missing a parent <RadioGroup /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(o,te),o}return t}let ke=M({name:"RadioGroup",emits:{"update:modelValue":e=>!0},props:{as:{type:[Object,String],default:"div"},disabled:{type:[Boolean],default:!1},by:{type:[String,Function],default:()=>Se},modelValue:{type:[Object,String,Number,Boolean],default:void 0},defaultValue:{type:[Object,String,Number,Boolean],default:void 0},name:{type:String,optional:!0},id:{type:String,default:()=>`headlessui-radiogroup-${N()}`}},inheritAttrs:!1,setup(e,{emit:t,attrs:o,slots:s,expose:u}){let f=V(null),c=V([]),i=ae({name:"RadioGroupLabel"}),b=Y({name:"RadioGroupDescription"});u({el:f,$el:f});let[g,_]=He(v(()=>e.modelValue),a=>t("update:modelValue",a),v(()=>e.defaultValue)),p={options:c,value:g,disabled:v(()=>e.disabled),firstOption:v(()=>c.value.find(a=>!a.propsRef.disabled)),containsCheckedOption:v(()=>c.value.some(a=>p.compare(w(a.propsRef.value),w(e.modelValue)))),compare(a,n){if(typeof e.by=="string"){let d=e.by;return(a==null?void 0:a[d])===(n==null?void 0:n[d])}return e.by(a,n)},change(a){var n;if(e.disabled||p.compare(w(g.value),w(a)))return!1;let d=(n=c.value.find(l=>p.compare(w(l.propsRef.value),w(a))))==null?void 0:n.propsRef;return d!=null&&d.disabled?!1:(_(a),!0)},registerOption(a){c.value.push(a),c.value=be(c.value,n=>n.element)},unregisterOption(a){let n=c.value.findIndex(d=>d.id===a);n!==-1&&c.value.splice(n,1)}};U(le,p),Ee({container:v(()=>P(f)),accept(a){return a.getAttribute("role")==="radio"?NodeFilter.FILTER_REJECT:a.hasAttribute("role")?NodeFilter.FILTER_SKIP:NodeFilter.FILTER_ACCEPT},walk(a){a.setAttribute("role","none")}});function y(a){if(!f.value||!f.value.contains(a.target))return;let n=c.value.filter(d=>d.propsRef.disabled===!1).map(d=>d.element);switch(a.key){case z.Enter:Ve(a.currentTarget);break;case z.ArrowLeft:case z.ArrowUp:if(a.preventDefault(),a.stopPropagation(),D(n,F.Previous|F.WrapAround)===Z.Success){let d=c.value.find(l=>{var m;return l.element===((m=R(f))==null?void 0:m.activeElement)});d&&p.change(d.propsRef.value)}break;case z.ArrowRight:case z.ArrowDown:if(a.preventDefault(),a.stopPropagation(),D(n,F.Next|F.WrapAround)===Z.Success){let d=c.value.find(l=>{var m;return l.element===((m=R(l.element))==null?void 0:m.activeElement)});d&&p.change(d.propsRef.value)}break;case z.Space:{a.preventDefault(),a.stopPropagation();let d=c.value.find(l=>{var m;return l.element===((m=R(l.element))==null?void 0:m.activeElement)});d&&p.change(d.propsRef.value)}break}}let x=v(()=>{var a;return(a=P(f))==null?void 0:a.closest("form")});return B(()=>{re([x],()=>{if(!x.value||e.defaultValue===void 0)return;function a(){p.change(e.defaultValue)}return x.value.addEventListener("reset",a),()=>{var n;(n=x.value)==null||n.removeEventListener("reset",a)}},{immediate:!0})}),()=>{let{disabled:a,name:n,id:d,...l}=e,m={ref:f,id:d,role:"radiogroup","aria-labelledby":i.value,"aria-describedby":b.value,onKeydown:y};return A(O,[...n!=null&&g.value!=null?q({[n]:g.value}).map(([S,L])=>A(_e,we({features:Ce.Hidden,key:S,as:"input",type:"hidden",hidden:!0,readOnly:!0,name:S,value:L}))):[],T({ourProps:m,theirProps:{...o,...Ie(l,["modelValue","defaultValue"])},slot:{},attrs:o,slots:s,name:"RadioGroup"})])}}});var Fe=(e=>(e[e.Empty=1]="Empty",e[e.Active=2]="Active",e))(Fe||{});let Oe=M({name:"RadioGroupOption",props:{as:{type:[Object,String],default:"div"},value:{type:[Object,String,Number,Boolean]},disabled:{type:Boolean,default:!1},id:{type:String,default:()=>`headlessui-radiogroup-option-${N()}`}},setup(e,{attrs:t,slots:o,expose:s}){let u=te("RadioGroupOption"),f=ae({name:"RadioGroupLabel"}),c=Y({name:"RadioGroupDescription"}),i=V(null),b=v(()=>({value:e.value,disabled:e.disabled})),g=V(1);s({el:i,$el:i}),B(()=>u.registerOption({id:e.id,element:i,propsRef:b})),K(()=>u.unregisterOption(e.id));let _=v(()=>{var l;return((l=u.firstOption.value)==null?void 0:l.id)===e.id}),p=v(()=>u.disabled.value||e.disabled),y=v(()=>u.compare(w(u.value.value),w(e.value))),x=v(()=>p.value?-1:y.value||!u.containsCheckedOption.value&&_.value?0:-1);function a(){var l;!u.change(e.value)||(g.value|=2,(l=i.value)==null||l.focus())}function n(){g.value|=2}function d(){g.value&=-3}return()=>{let{id:l,value:m,disabled:S,...L}=e,oe={checked:y.value,disabled:p.value,active:Boolean(g.value&2)},se={id:l,ref:i,role:"radio","aria-checked":y.value?"true":"false","aria-labelledby":f.value,"aria-describedby":c.value,"aria-disabled":p.value?!0:void 0,tabIndex:x.value,onClick:p.value?void 0:a,onFocus:p.value?void 0:n,onBlur:p.value?void 0:d};return T({ourProps:se,theirProps:L,slot:oe,attrs:t,slots:o,name:"RadioGroupOption"})}}}),G=ze;const Re=r("div",{class:"intro-y flex items-center mt-8"},[r("h2",{class:"text-lg font-medium mr-auto"},"Reportes")],-1),Me={class:"intro-y box p-5 mt-5"},Le={class:"flex flex-col lg:grid lg:grid-cols-6 xl:grid xl:grid-cols-6 gap-6 justify-evenly"},Be={class:"flex flex-col justify-start h-ful lg:col-span-2 xl:col-span-2"},Ne=r("label",{for:"regular-form-2",class:"form-label font-bold min-w-max mr-4"},"FECHA RANGO",-1),Te={class:"grid grid-cols-2 gap-6 w-full intro-x"},Ae=r("label",{for:"regular-form-2",class:"form-label font-bold min-w-max"},"ACCIONES",-1),$e={class:"intro-y box p-5 mt-5"},je={class:"flex justify-center"},Pe={class:"grid grid-cols-1 gap-3 sm:grid-cols-6"},De={class:"mt-6"},Ze=["innerHTML"],Ge={class:"flex justify-end gap-x-4"},Ue=["onClick"],Ke=r("svg",{xmlns:"http://www.w3.org/2000/svg",width:"32",height:"32",viewBox:"0 0 256 256"},[r("path",{fill:"currentColor",d:"M48 136a8 8 0 0 0 8-8V40h88v48a8 8 0 0 0 8 8h48v32a8 8 0 0 0 16 0V88a7.7 7.7 0 0 0-2.4-5.7l-55.9-56A8.1 8.1 0 0 0 152 24H56a16 16 0 0 0-16 16v88a8 8 0 0 0 8 8Zm112-84.7L188.7 80H160ZM64 160H48a8 8 0 0 0-8 8v48a8 8 0 0 0 16 0v-8h8a24 24 0 0 0 0-48Zm0 32h-8v-16h8a8 8 0 0 1 0 16Zm132-16v12h16a8 8 0 0 1 0 16h-16v12a8 8 0 0 1-16 0v-48a8 8 0 0 1 8-8h28a8 8 0 0 1 0 16Zm-68-16h-14a8 8 0 0 0-8 8v48a8 8 0 0 0 8 8h14a32 32 0 0 0 0-64Zm0 48h-6v-32h6a16 16 0 0 1 0 32Z"})],-1),We=r("span",{class:"ml-2"}," Pdf ",-1),Ye=[Ke,We],qe=["onClick"],Je=r("svg",{xmlns:"http://www.w3.org/2000/svg",width:"32",height:"32",viewBox:"0 0 100 100"},[r("path",{fill:"currentColor",d:"M89.148 32.927c.001-.037.011-.07.011-.107a3.972 3.972 0 0 0-1.016-2.642l.02-.011l-7.87-13.627a2.53 2.53 0 0 0-2.468-1.914c-.083 0-.161.016-.242.024v-.024H22.219v.004c-.013 0-.026-.004-.039-.004a2.42 2.42 0 0 0-2.17 1.315l-.008-.005l-8.212 14.231l.015.008a4.068 4.068 0 0 0-.963 2.642c0 .047.012.091.014.138v48.211c-.002.048-.014.093-.014.142c0 2.284 1.817 4.069 4.095 4.066c.043 0 .083-.011.125-.012h69.87c.043.001.083.012.126.012c2.283 0 4.1-1.782 4.1-4.062c0-.036-.01-.068-.011-.104V32.927zM63.413 57.492l-12.391 17.43c-.226.318-.59.505-.98.507h-.004c-.386 0-.751-.187-.977-.503L36.59 57.494a1.201 1.201 0 0 1-.091-1.251c.208-.401.62-.654 1.071-.654h5.833l.001-15.654c0-.667.538-1.205 1.203-1.205h10.789c.665 0 1.204.539 1.204 1.204v15.655h5.83a1.206 1.206 0 0 1 .983 1.903zM18.376 28.733l5.263-9.119h52.67l5.266 9.119H18.376z"})],-1),Xe=r("span",{class:"ml-2"}," Zip ",-1),Qe=[Je,Xe],ea=["onClick"],aa=r("svg",{xmlns:"http://www.w3.org/2000/svg",width:"32",height:"32",viewBox:"0 0 256 256"},[r("path",{fill:"currentColor",d:"M200 24H72a16 16 0 0 0-16 16v24H40a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h16v24a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16V40a16 16 0 0 0-16-16Zm-40 80h40v48h-40Zm40-16h-40v-8a16 16 0 0 0-16-16V40h56ZM72 40h56v24H72ZM40 80h104v96H40Zm32 112h56v24H72Zm72 24v-24a16 16 0 0 0 16-16v-8h40v48Zm-76.4-68.8L82 128l-14.4-19.2a8 8 0 1 1 12.8-9.6L92 114.7l11.6-15.5a8 8 0 0 1 12.8 9.6L102 128l14.4 19.2a8 8 0 0 1-1.6 11.2a7.7 7.7 0 0 1-4.8 1.6a8 8 0 0 1-6.4-3.2L92 141.3l-11.6 15.5A8 8 0 0 1 74 160a7.7 7.7 0 0 1-4.8-1.6a8 8 0 0 1-1.6-11.2Z"})],-1),la=r("span",{class:"ml-2"}," Excel ",-1),ta=[aa,la],oa=["onClick"],sa=r("svg",{width:"32",height:"32",viewBox:"0 0 56 56",fill:"none",xmlns:"http://www.w3.org/2000/svg"},[r("path",{d:"M15.037 38.3705C15.3808 38.0266 15.8471 37.8335 16.3333 37.8335H25.6667C26.1529 37.8335 26.6192 38.0266 26.963 38.3705C27.3068 38.7143 27.5 39.1806 27.5 39.6668C27.5 40.1531 27.3068 40.6194 26.963 40.9632C26.6192 41.307 26.1529 41.5002 25.6667 41.5002H16.3333C15.8471 41.5002 15.3808 41.307 15.037 40.9632C14.6932 40.6194 14.5 40.1531 14.5 39.6668C14.5 39.1806 14.6932 38.7143 15.037 38.3705Z",fill:"#363B64",stroke:"#363B64"}),r("path",{d:"M31.5216 9.82736L31.7576 10.095L32.0874 9.95886C32.2737 9.88197 32.4724 9.83945 32.6738 9.83333H46.6667C47.1529 9.83333 47.6192 10.0265 47.963 10.3703C48.3068 10.7141 48.5 11.1804 48.5 11.6667V46.6667C48.5 47.1529 48.3068 47.6192 47.963 47.963C47.6192 48.3068 47.1529 48.5 46.6667 48.5H39.6667C39.1804 48.5 38.7141 48.3068 38.3703 47.963C38.0265 47.6192 37.8333 47.1529 37.8333 46.6667C37.8333 46.1804 38.0265 45.7141 38.3703 45.3703C38.7141 45.0265 39.1804 44.8333 39.6667 44.8333H44.3333H44.8333V44.3333V21V20.5H44.3333H11.6667H11.1667V21V44.3333V44.8333H11.6667H30.3333C30.8196 44.8333 31.2859 45.0265 31.6297 45.3703C31.9735 45.7141 32.1667 46.1804 32.1667 46.6667C32.1667 47.1529 31.9735 47.6192 31.6297 47.963C31.2859 48.3068 30.8196 48.5 30.3333 48.5H9.33333C8.8471 48.5 8.38079 48.3068 8.03697 47.963C7.69315 47.6192 7.5 47.1529 7.5 46.6667V9.33333C7.5 8.8471 7.69315 8.38079 8.03697 8.03697C8.38079 7.69315 8.8471 7.5 9.33333 7.5H26.3666C27.3418 7.50004 28.3058 7.70759 29.1946 8.10886C30.0834 8.51013 30.8767 9.09594 31.5216 9.82736ZM11.1667 16.3333V16.8333H11.6667H29.68H30.3218L30.1648 16.211L29.5115 13.621L29.5113 13.6205C29.3339 12.9207 28.9288 12.2998 28.3598 11.8556C27.7908 11.4114 27.0902 11.169 26.3683 11.1667H26.3667H11.6667H11.1667V11.6667V16.3333ZM34.0016 16.4546L34.0963 16.8333H34.4867H44.3333H44.8333V16.3333V14V13.5H44.3333H33.9033H33.2629L33.4183 14.1213L34.0016 16.4546Z",fill:"#363B64",stroke:"#363B64"})],-1),na=[sa],pa=M({__name:"Index",setup(e){const t=ve(),o=xe();ie();const s=V(1),u=[{name:"Monitores",value:1},{name:"Gestores",value:2},{name:"Embajadores",value:3},{name:"Instructores",value:4},{name:"Psicosocial",value:5},{name:"Metod\xF3logo",value:6},{name:"Supervisi\xF3n",value:7},{name:"Apoyo al seguimiento",value:8},{name:"Coordinadores",value:9},{name:"Otros",value:10},{name:"Cortes",value:11}],f=[{text:"#",value:"id"},{text:"NOMBRE",value:"name"},{text:"ACCIONES",value:"actions"}],c=v(()=>{switch(s.value){case 1:return[{id:1,name:"Informe de Beneficiarios",type:"beneficiariesMonitor",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe de PECS",type:"pecs",pdf:!1,excel:!0,zip:!1,download:!1},{id:3,name:"Informe de Fichas Pedag\xF3gicas",type:"pedagogicals",pdf:!1,excel:!0,zip:!1,download:!1},{id:4,name:"Informe de Encuestas de Deserci\xF3n",type:"pollDesertions",pdf:!1,excel:!0,zip:!1,download:!1},{id:5,name:"Informe de Bit\xE1coras Pacto",type:"binnacles_monitor",pdf:!1,excel:!0,zip:!1,download:!1}];case 2:return[{id:1,name:"Informe Mesa de Dialogo",type:"dialogueTables",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe Instrucci\xF3n Metodol\xF3gica",type:"methodologicalInstructionModels",pdf:!1,excel:!0,zip:!1,download:!1},{id:3,name:"Informe Seguimiento de Gesti\xF3n Cultural",type:"managerMonitorings",pdf:!1,excel:!0,zip:!1,download:!1},{id:4,name:"Informe Activaci\xF3n cultural",type:"binnacleManagers",pdf:!1,excel:!0,zip:!1,download:!1}];case 3:return[{id:1,name:"Informe de Bit\xE1cora show cultural",type:"ambassador",pdf:!1,excel:!0,zip:!1,download:!1}];case 4:return[{id:1,name:"Informe de Beneficiarios",type:"beneficiariesInstructor",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe de ficha metod\xF3logica de planeaci\xF3n",type:"methodologicalSheetsOne",pdf:!1,excel:!0,zip:!1,download:!1},{id:3,name:"Informe de ficha metodologica de evaluaci\xF3n",type:"methodologicalsheetstwo",pdf:!1,excel:!0,zip:!1,download:!1},{id:4,name:"Informe de Bit\xE1cora Ensamble Cultural",type:"culturalEnsembles",pdf:!1,excel:!0,zip:!1,download:!1},{id:5,name:"Informe de Bit\xE1cora Circulaci\xF3n Cultural",type:"culturalCirculations",pdf:!1,excel:!0,zip:!1,download:!1},{id:6,name:"Informe de Bit\xE1cora Semilleros Cultural",type:"culturalSeedbeds",pdf:!1,excel:!0,zip:!1,download:!1}];case 5:return[{id:1,name:"Informe Escuela de Padres",type:"parentschools",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe Bit\xE1coras Psicopedag\xF3gicas",type:"psychopedagogicallogs",pdf:!1,excel:!0,zip:!1,download:!1},{id:3,name:"Informe Instrucci\xF3n Psicosocial",type:"psychosocialInstructions",pdf:!1,excel:!0,zip:!1,download:!1}];case 6:return[{id:1,name:"Informe Seguimiento metodol\xF3gico",type:"methodologicalMonitorings",pdf:!0,excel:!0,zip:!1,download:!1},{id:2,name:"Informe Acompa\xF1amiento metodol\xF3gico",type:"methodologicalAccompaniments",pdf:!0,excel:!0,zip:!1,download:!1},{id:3,name:"Informe de Fortalecimiento metodologico",type:"methodologicalStrengthening",pdf:!1,excel:!0,zip:!1,download:!1}];case 7:return[{id:1,name:"Informe Fortalecimiento a la supervisi\xF3n monitores e instructores",type:"strengtheningSuperMonIns",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe Visita de supervisi\xF3n de gestor",type:"managerSupervisionvisit",pdf:!1,excel:!0,zip:!1,download:!1}];case 8:return[{id:1,name:"Informe Fortalecimiento seguimiento",type:"strengtheningOfMonitorings",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe de seguimiento",type:"monitoringReport",pdf:!1,excel:!0,zip:!1,download:!1}];case 9:return[{id:1,name:"Informe de Visita territorio",type:"binnacleTerritorie",pdf:!1,excel:!0,zip:!1,download:!1}];case 10:return[{id:1,name:"Informe de Encuestas",type:"polls",pdf:!1,excel:!0,zip:!1,download:!1},{id:2,name:"Informe de Usuarios",type:"users",pdf:!1,excel:!0,zip:!1,download:!1},{id:3,name:"Informe Variables",type:"variables",pdf:!1,excel:!0,zip:!1,download:!1},{id:4,name:"Informe de Sesi\xF3n",type:"sesion",pdf:!1,excel:!0,zip:!1,download:!1},{id:5,name:"Historial de Entradas",type:"input_history",pdf:!1,excel:!0,zip:!1,download:!1},{id:6,name:"Informe de Acudientes",type:"attendats",pdf:!1,excel:!0,zip:!1,download:!1},{id:7,name:"Bit\xE1cora Impacto",type:"binnacleImpacts",pdf:!1,excel:!1,zip:!0,download:!1},{id:8,name:"Informe Grupos",type:"groups",pdf:!1,excel:!0,zip:!1,download:!1},{id:6,name:"Informe de Revisiones",type:"revisions",pdf:!1,excel:!0,zip:!1,download:!1}];case 11:return[{id:1,name:"Generar corte de informe beneficiarios monitor",type:"beneficiariesMoni",pdf:!1,excel:!1,zip:!1,download:!0},{id:2,name:"Generar corte de informe impacto",type:"binnacleImpact",pdf:!1,excel:!1,zip:!1,download:!0}]}}),i=de({nac_id:null,date_start:null,date_end:null,status:null,type:null,user_id:null,rol_id:null,data:!1}),b=v(()=>({nac_id:{},date_start:{},date_end:{},status:{},type:{},user_id:{},rol_id:{},data:{}})),g=ye(b,i,{$autoDirty:!0}),_=()=>{i.nac_id=null,i.date_start=null,i.date_end=null,i.status=null,i.type=null,i.user_id=null,i.rol_id=null,i.data=!1,o.count.value=0,g.value.$reset()},p=v(()=>he().format("YYYY-MM-DD")),{is_role:y}=ue.computed,x=(a,n)=>{const{data:d,type:l,...m}=i;let S=t.resolve({name:"reports.download",query:{format:n,type:a,...m}});window.open(S.href,"_blank","menubar=no")};return(a,n)=>{const d=ge("DataTable");return C(),I(O,null,[Re,r("div",Me,[r("section",Le,[r("div",Be,[Ne,r("div",Te,[E(j,{class:"",type:"date",tooltip:"Desde",name:"date_start",max:h(p),modelValue:h(i).date_start,"onUpdate:modelValue":n[0]||(n[0]=l=>h(i).date_start=l),validator:h(g)},null,8,["max","modelValue","validator"]),E(j,{class:"",type:"date",tooltip:"Hasta",name:"date_end",max:h(p),modelValue:h(i).date_end,"onUpdate:modelValue":n[1]||(n[1]=l=>h(i).date_end=l),validator:h(g)},null,8,["max","modelValue","validator"])])]),r("div",{class:"flex flex-col justify-start h-full"},[Ae,r("div",{class:"grid grid-cols-2 gap-6 w-full intro-x"},[r("button",{class:"btn w-full",type:"button",onClick:_}," Limpiar ")])])])]),r("div",$e,[r("div",je,[E(h(ke),{modelValue:h(s),"onUpdate:modelValue":n[2]||(n[2]=l=>me(s)?s.value=l:null),class:"mt-2"},{default:H(()=>[E(h(G),{class:"sr-only"},{default:H(()=>[$(" Elige un tipo de Reporte ")]),_:1}),r("div",Pe,[(C(),I(O,null,ce(u,l=>E(h(Oe),{as:"template",key:l.name,value:l.value},{default:H(({checked:m})=>[r("div",{class:fe([m?"bg-primary border-transparent text-white hover:bg-primary/90":"border-slate-200 hover:bg-slate-50","btn py-2 px-2 flex items-center justify-center text-sm font-medium uppercase sm:flex-1"])},[E(h(G),{as:"span"},{default:H(()=>[$(pe(l.name),1)]),_:2},1024)],2)]),_:2},1032,["value"])),64))])]),_:1},8,["modelValue"])]),r("div",De,[E(d,{headers:f,items:h(c),"hide-footer":""},{"header-actions":H(({text:l})=>[r("div",{class:"flex justify-end",innerHTML:l},null,8,Ze)]),"item-actions":H(l=>[r("div",Ge,[l.pdf&&h(y)("direccion")==!1?(C(),I(O,{key:0},[h(y)("direccion")?(C(),I("button",{key:0,class:"btn",onClick:m=>x(l.type,"pdf")},Ye,8,Ue)):k("",!0)],64)):k("",!0),l.zip?(C(),I("button",{key:1,class:"btn",onClick:m=>x(l.type,"zip")},Qe,8,qe)):k("",!0),l.excel?(C(),I("button",{key:2,class:"btn",onClick:m=>x(l.type,"excel")},ta,8,ea)):k("",!0),l.download?(C(),I("button",{key:3,class:"btn",onClick:m=>x(l.type,"generator")},na,8,oa)):k("",!0)])]),_:1},8,["items"])])])],64)}}});export{pa as default};
