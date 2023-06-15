import{f as d,y as c,$ as h,i as o,ad as r,a5 as S,aa as V,F as D,x as T,t as v,ae as g,k as b,T as E,w,j as y,d as K,v as P,g as A,h as O,a9 as M,l as $,M as N,ac as R}from"./index.c93647e1.js";import{B as C}from"./Input.d2784565.js";function B(e){return e===0?!1:Array.isArray(e)&&e.length===0?!0:!e}function j(e){return(...t)=>!e(...t)}function z(e,t){return e===void 0&&(e="undefined"),e===null&&(e="null"),e===!1&&(e="false"),e.toString().toLowerCase().indexOf(t.trim())!==-1}function F(e,t,i,s){return t?e.filter(p=>z(s(p,i),t)).sort((p,n)=>s(p,i).length-s(n,i).length):e}function q(e){return e.filter(t=>!t.$isLabel)}function k(e,t){return i=>i.reduce((s,p)=>p[e]&&p[e].length?(s.push({$groupLabel:p[t],$isLabel:!0}),s.concat(p[e])):s,[])}function I(e,t,i,s,p){return n=>n.map(l=>{if(!l[i])return console.warn("Options passed to vue-multiselect do not contain groups, despite the config."),[];const u=F(l[i],e,t,p);return u.length?{[s]:l[s],[i]:u}:[]})}const H=(...e)=>t=>e.reduce((i,s)=>s(i),t);var U={data(){return{search:"",isOpen:!1,preferredOpenDirection:"below",optimizedHeight:this.maxHeight}},props:{internalSearch:{type:Boolean,default:!0},options:{type:Array,required:!0},multiple:{type:Boolean,default:!1},trackBy:{type:String},label:{type:String},searchable:{type:Boolean,default:!0},clearOnSelect:{type:Boolean,default:!0},hideSelected:{type:Boolean,default:!1},placeholder:{type:String,default:"Select option"},allowEmpty:{type:Boolean,default:!0},resetAfter:{type:Boolean,default:!1},closeOnSelect:{type:Boolean,default:!0},customLabel:{type:Function,default(e,t){return B(e)?"":t?e[t]:e}},taggable:{type:Boolean,default:!1},tagPlaceholder:{type:String,default:"Press enter to create a tag"},tagPosition:{type:String,default:"top"},max:{type:[Number,Boolean],default:!1},id:{default:null},optionsLimit:{type:Number,default:1e3},groupValues:{type:String},groupLabel:{type:String},groupSelect:{type:Boolean,default:!1},blockKeys:{type:Array,default(){return[]}},preserveSearch:{type:Boolean,default:!1},preselectFirst:{type:Boolean,default:!1},preventAutofocus:{type:Boolean,default:!1}},mounted(){!this.multiple&&this.max&&console.warn("[Vue-Multiselect warn]: Max prop should not be used when prop Multiple equals false."),this.preselectFirst&&!this.internalValue.length&&this.options.length&&this.select(this.filteredOptions[0])},computed:{internalValue(){return this.modelValue||this.modelValue===0?Array.isArray(this.modelValue)?this.modelValue:[this.modelValue]:[]},filteredOptions(){const e=this.search||"",t=e.toLowerCase().trim();let i=this.options.concat();return this.internalSearch?i=this.groupValues?this.filterAndFlat(i,t,this.label):F(i,t,this.label,this.customLabel):i=this.groupValues?k(this.groupValues,this.groupLabel)(i):i,i=this.hideSelected?i.filter(j(this.isSelected)):i,this.taggable&&t.length&&!this.isExistingOption(t)&&(this.tagPosition==="bottom"?i.push({isTag:!0,label:e}):i.unshift({isTag:!0,label:e})),i.slice(0,this.optionsLimit)},valueKeys(){return this.trackBy?this.internalValue.map(e=>e[this.trackBy]):this.internalValue},optionKeys(){return(this.groupValues?this.flatAndStrip(this.options):this.options).map(t=>this.customLabel(t,this.label).toString().toLowerCase())},currentOptionLabel(){return this.multiple?this.searchable?"":this.placeholder:this.internalValue.length?this.getOptionLabel(this.internalValue[0]):this.searchable?"":this.placeholder}},watch:{internalValue(){this.resetAfter&&this.internalValue.length&&(this.search="",this.$emit("update:modelValue",this.multiple?[]:null))},search(){this.$emit("search-change",this.search)}},emits:["open","search-change","close","select","update:modelValue","remove","tag"],methods:{getValue(){return this.multiple?this.internalValue:this.internalValue.length===0?null:this.internalValue[0]},filterAndFlat(e,t,i){return H(I(t,i,this.groupValues,this.groupLabel,this.customLabel),k(this.groupValues,this.groupLabel))(e)},flatAndStrip(e){return H(k(this.groupValues,this.groupLabel),q)(e)},updateSearch(e){this.search=e},isExistingOption(e){return this.options?this.optionKeys.indexOf(e)>-1:!1},isSelected(e){const t=this.trackBy?e[this.trackBy]:e;return this.valueKeys.indexOf(t)>-1},isOptionDisabled(e){return!!e.$isDisabled},getOptionLabel(e){if(B(e))return"";if(e.isTag)return e.label;if(e.$isLabel)return e.$groupLabel;const t=this.customLabel(e,this.label);return B(t)?"":t},select(e,t){if(e.$isLabel&&this.groupSelect){this.selectGroup(e);return}if(!(this.blockKeys.indexOf(t)!==-1||this.disabled||e.$isDisabled||e.$isLabel)&&!(this.max&&this.multiple&&this.internalValue.length===this.max)&&!(t==="Tab"&&!this.pointerDirty)){if(e.isTag)this.$emit("tag",e.label,this.id),this.search="",this.closeOnSelect&&!this.multiple&&this.deactivate();else{if(this.isSelected(e)){t!=="Tab"&&this.removeElement(e);return}this.multiple?this.$emit("update:modelValue",this.internalValue.concat([e])):this.$emit("update:modelValue",e),this.$emit("select",e,this.id),this.clearOnSelect&&(this.search="")}this.closeOnSelect&&this.deactivate()}},selectGroup(e){const t=this.options.find(i=>i[this.groupLabel]===e.$groupLabel);if(!!t){if(this.wholeGroupSelected(t)){this.$emit("remove",t[this.groupValues],this.id);const i=this.internalValue.filter(s=>t[this.groupValues].indexOf(s)===-1);this.$emit("update:modelValue",i)}else{let i=t[this.groupValues].filter(s=>!(this.isOptionDisabled(s)||this.isSelected(s)));this.max&&i.splice(this.max-this.internalValue.length),this.$emit("select",i,this.id),this.$emit("update:modelValue",this.internalValue.concat(i))}this.closeOnSelect&&this.deactivate()}},wholeGroupSelected(e){return e[this.groupValues].every(t=>this.isSelected(t)||this.isOptionDisabled(t))},wholeGroupDisabled(e){return e[this.groupValues].every(this.isOptionDisabled)},removeElement(e,t=!0){if(this.disabled||e.$isDisabled)return;if(!this.allowEmpty&&this.internalValue.length<=1){this.deactivate();return}const i=typeof e=="object"?this.valueKeys.indexOf(e[this.trackBy]):this.valueKeys.indexOf(e);if(this.multiple){const s=this.internalValue.slice(0,i).concat(this.internalValue.slice(i+1));this.$emit("update:modelValue",s)}else this.$emit("update:modelValue",null);this.$emit("remove",e,this.id),this.closeOnSelect&&t&&this.deactivate()},removeLastElement(){this.blockKeys.indexOf("Delete")===-1&&this.search.length===0&&Array.isArray(this.internalValue)&&this.internalValue.length&&this.removeElement(this.internalValue[this.internalValue.length-1],!1)},activate(){this.isOpen||this.disabled||(this.adjustPosition(),this.groupValues&&this.pointer===0&&this.filteredOptions.length&&(this.pointer=1),this.isOpen=!0,this.searchable?(this.preserveSearch||(this.search=""),this.preventAutofocus||this.$nextTick(()=>this.$refs.search&&this.$refs.search.focus())):this.preventAutofocus||typeof this.$el!="undefined"&&this.$el.focus(),this.$emit("open",this.id))},deactivate(){!this.isOpen||(this.isOpen=!1,this.searchable?typeof this.$refs.search!="undefined"&&this.$refs.search.blur():typeof this.$el!="undefined"&&this.$el.blur(),this.preserveSearch||(this.search=""),this.$emit("close",this.getValue(),this.id))},toggle(){this.isOpen?this.deactivate():this.activate()},adjustPosition(){if(typeof window=="undefined")return;const e=this.$el.getBoundingClientRect().top,t=window.innerHeight-this.$el.getBoundingClientRect().bottom;t>this.maxHeight||t>e||this.openDirection==="below"||this.openDirection==="bottom"?(this.preferredOpenDirection="below",this.optimizedHeight=Math.min(t-40,this.maxHeight)):(this.preferredOpenDirection="above",this.optimizedHeight=Math.min(e-40,this.maxHeight))}}},J={data(){return{pointer:0,pointerDirty:!1}},props:{showPointer:{type:Boolean,default:!0},optionHeight:{type:Number,default:40}},computed:{pointerPosition(){return this.pointer*this.optionHeight},visibleElements(){return this.optimizedHeight/this.optionHeight}},watch:{filteredOptions(){this.pointerAdjust()},isOpen(){this.pointerDirty=!1},pointer(){this.$refs.search&&this.$refs.search.setAttribute("aria-activedescendant",this.id+"-"+this.pointer.toString())}},methods:{optionHighlight(e,t){return{"multiselect__option--highlight":e===this.pointer&&this.showPointer,"multiselect__option--selected":this.isSelected(t)}},groupHighlight(e,t){if(!this.groupSelect)return["multiselect__option--disabled",{"multiselect__option--group":t.$isLabel}];const i=this.options.find(s=>s[this.groupLabel]===t.$groupLabel);return i&&!this.wholeGroupDisabled(i)?["multiselect__option--group",{"multiselect__option--highlight":e===this.pointer&&this.showPointer},{"multiselect__option--group-selected":this.wholeGroupSelected(i)}]:"multiselect__option--disabled"},addPointerElement({key:e}="Enter"){this.filteredOptions.length>0&&this.select(this.filteredOptions[this.pointer],e),this.pointerReset()},pointerForward(){this.pointer<this.filteredOptions.length-1&&(this.pointer++,this.$refs.list.scrollTop<=this.pointerPosition-(this.visibleElements-1)*this.optionHeight&&(this.$refs.list.scrollTop=this.pointerPosition-(this.visibleElements-1)*this.optionHeight),this.filteredOptions[this.pointer]&&this.filteredOptions[this.pointer].$isLabel&&!this.groupSelect&&this.pointerForward()),this.pointerDirty=!0},pointerBackward(){this.pointer>0?(this.pointer--,this.$refs.list.scrollTop>=this.pointerPosition&&(this.$refs.list.scrollTop=this.pointerPosition),this.filteredOptions[this.pointer]&&this.filteredOptions[this.pointer].$isLabel&&!this.groupSelect&&this.pointerBackward()):this.filteredOptions[this.pointer]&&this.filteredOptions[0].$isLabel&&!this.groupSelect&&this.pointerForward(),this.pointerDirty=!0},pointerReset(){!this.closeOnSelect||(this.pointer=0,this.$refs.list&&(this.$refs.list.scrollTop=0))},pointerAdjust(){this.pointer>=this.filteredOptions.length-1&&(this.pointer=this.filteredOptions.length?this.filteredOptions.length-1:0),this.filteredOptions.length>0&&this.filteredOptions[this.pointer].$isLabel&&!this.groupSelect&&this.pointerForward()},pointerSet(e){this.pointer=e,this.pointerDirty=!0}}},G={name:"vue-multiselect",mixins:[U,J],props:{name:{type:String,default:""},modelValue:{type:null,default(){return[]}},selectLabel:{type:String,default:"Press enter to select"},selectGroupLabel:{type:String,default:"Press enter to select group"},selectedLabel:{type:String,default:"Selected"},deselectLabel:{type:String,default:"Press enter to remove"},deselectGroupLabel:{type:String,default:"Press enter to deselect group"},showLabels:{type:Boolean,default:!0},limit:{type:Number,default:99999},maxHeight:{type:Number,default:300},limitText:{type:Function,default:e=>`and ${e} more`},loading:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1},openDirection:{type:String,default:""},showNoOptions:{type:Boolean,default:!0},showNoResults:{type:Boolean,default:!0},tabindex:{type:Number,default:0}},computed:{hasOptionGroup(){return this.groupValues&&this.groupLabel&&this.groupSelect},isSingleLabelVisible(){return(this.singleValue||this.singleValue===0)&&(!this.isOpen||!this.searchable)&&!this.visibleValues.length},isPlaceholderVisible(){return!this.internalValue.length&&(!this.searchable||!this.isOpen)},visibleValues(){return this.multiple?this.internalValue.slice(0,this.limit):[]},singleValue(){return this.internalValue[0]},deselectLabelText(){return this.showLabels?this.deselectLabel:""},deselectGroupLabelText(){return this.showLabels?this.deselectGroupLabel:""},selectLabelText(){return this.showLabels?this.selectLabel:""},selectGroupLabelText(){return this.showLabels?this.selectGroupLabel:""},selectedLabelText(){return this.showLabels?this.selectedLabel:""},inputStyle(){return this.searchable||this.multiple&&this.modelValue&&this.modelValue.length?this.isOpen?{width:"100%"}:{width:"0",position:"absolute",padding:"0"}:""},contentStyle(){return this.options.length?{display:"inline-block"}:{display:"block"}},isAbove(){return this.openDirection==="above"||this.openDirection==="top"?!0:this.openDirection==="below"||this.openDirection==="bottom"?!1:this.preferredOpenDirection==="above"},showSearchInput(){return this.searchable&&(this.hasSingleSelectedSlot&&(this.visibleSingleValue||this.visibleSingleValue===0)?this.isOpen:!0)}}};const Q={ref:"tags",class:"multiselect__tags"},W={class:"multiselect__tags-wrap"},X={class:"multiselect__spinner"},Y={key:0},Z={class:"multiselect__option"},x={class:"multiselect__option"},_=y("No elements found. Consider changing the search query."),ee={class:"multiselect__option"},te=y("List is empty.");function ie(e,t,i,s,p,n){return d(),c("div",{tabindex:e.searchable?-1:i.tabindex,class:[{"multiselect--active":e.isOpen,"multiselect--disabled":i.disabled,"multiselect--above":n.isAbove,"multiselect--has-options-group":n.hasOptionGroup},"multiselect"],onFocus:t[14]||(t[14]=l=>e.activate()),onBlur:t[15]||(t[15]=l=>e.searchable?!1:e.deactivate()),onKeydown:[t[16]||(t[16]=g(r(l=>e.pointerForward(),["self","prevent"]),["down"])),t[17]||(t[17]=g(r(l=>e.pointerBackward(),["self","prevent"]),["up"]))],onKeypress:t[18]||(t[18]=g(r(l=>e.addPointerElement(l),["stop","self"]),["enter","tab"])),onKeyup:t[19]||(t[19]=g(l=>e.deactivate(),["esc"])),role:"combobox","aria-owns":"listbox-"+e.id},[h(e.$slots,"caret",{toggle:e.toggle},()=>[o("div",{onMousedown:t[1]||(t[1]=r(l=>e.toggle(),["prevent","stop"])),class:"multiselect__select"},null,32)]),h(e.$slots,"clear",{search:e.search}),o("div",Q,[h(e.$slots,"selection",{search:e.search,remove:e.removeElement,values:n.visibleValues,isOpen:e.isOpen},()=>[S(o("div",W,[(d(!0),c(D,null,T(n.visibleValues,(l,u)=>h(e.$slots,"tag",{option:l,search:e.search,remove:e.removeElement},()=>[(d(),c("span",{class:"multiselect__tag",key:u},[o("span",{textContent:v(e.getOptionLabel(l))},null,8,["textContent"]),o("i",{tabindex:"1",onKeypress:g(r(a=>e.removeElement(l),["prevent"]),["enter"]),onMousedown:r(a=>e.removeElement(l),["prevent"]),class:"multiselect__tag-icon"},null,40,["onKeypress","onMousedown"])]))])),256))],512),[[V,n.visibleValues.length>0]]),e.internalValue&&e.internalValue.length>i.limit?h(e.$slots,"limit",{key:0},()=>[o("strong",{class:"multiselect__strong",textContent:v(i.limitText(e.internalValue.length-i.limit))},null,8,["textContent"])]):b("v-if",!0)]),o(E,{name:"multiselect__loading"},{default:w(()=>[h(e.$slots,"loading",{},()=>[S(o("div",X,null,512),[[V,i.loading]])])]),_:3}),e.searchable?(d(),c("input",{key:0,ref:"search",name:i.name,id:e.id,type:"text",autocomplete:"off",spellcheck:"false",placeholder:e.placeholder,style:n.inputStyle,value:e.search,disabled:i.disabled,tabindex:i.tabindex,onInput:t[2]||(t[2]=l=>e.updateSearch(l.target.value)),onFocus:t[3]||(t[3]=r(l=>e.activate(),["prevent"])),onBlur:t[4]||(t[4]=r(l=>e.deactivate(),["prevent"])),onKeyup:t[5]||(t[5]=g(l=>e.deactivate(),["esc"])),onKeydown:[t[6]||(t[6]=g(r(l=>e.pointerForward(),["prevent"]),["down"])),t[7]||(t[7]=g(r(l=>e.pointerBackward(),["prevent"]),["up"])),t[9]||(t[9]=g(r(l=>e.removeLastElement(),["stop"]),["delete"]))],onKeypress:t[8]||(t[8]=g(r(l=>e.addPointerElement(l),["prevent","stop","self"]),["enter"])),class:"multiselect__input","aria-controls":"listbox-"+e.id},null,44,["name","id","placeholder","value","disabled","tabindex","aria-controls"])):b("v-if",!0),n.isSingleLabelVisible?(d(),c("span",{key:1,class:"multiselect__single",onMousedown:t[10]||(t[10]=r((...l)=>e.toggle&&e.toggle(...l),["prevent"]))},[h(e.$slots,"singleLabel",{option:n.singleValue},()=>[y(v(e.currentOptionLabel),1)])],32)):b("v-if",!0),n.isPlaceholderVisible?(d(),c("span",{key:2,class:"multiselect__placeholder",onMousedown:t[11]||(t[11]=r((...l)=>e.toggle&&e.toggle(...l),["prevent"]))},[h(e.$slots,"placeholder",{},()=>[y(v(e.placeholder),1)])],32)):b("v-if",!0)],512),o(E,{name:"multiselect"},{default:w(()=>[S(o("div",{class:"multiselect__content-wrapper",onFocus:t[12]||(t[12]=(...l)=>e.activate&&e.activate(...l)),tabindex:"-1",onMousedown:t[13]||(t[13]=r(()=>{},["prevent"])),style:{maxHeight:e.optimizedHeight+"px"},ref:"list"},[o("ul",{class:"multiselect__content",style:n.contentStyle,role:"listbox",id:"listbox-"+e.id},[h(e.$slots,"beforeList"),e.multiple&&e.max===e.internalValue.length?(d(),c("li",Y,[o("span",Z,[h(e.$slots,"maxElements",{},()=>[y("Maximum of "+v(e.max)+" options selected. First remove a selected option to select another.",1)])])])):b("v-if",!0),!e.max||e.internalValue.length<e.max?(d(!0),c(D,{key:1},T(e.filteredOptions,(l,u)=>(d(),c("li",{class:"multiselect__element",key:u,id:e.id+"-"+u,role:l&&(l.$isLabel||l.$isDisabled)?null:"option"},[l&&(l.$isLabel||l.$isDisabled)?b("v-if",!0):(d(),c("span",{key:0,class:[e.optionHighlight(u,l),"multiselect__option"],onClick:r(a=>e.select(l),["stop"]),onMouseenter:r(a=>e.pointerSet(u),["self"]),"data-select":l&&l.isTag?e.tagPlaceholder:n.selectLabelText,"data-selected":n.selectedLabelText,"data-deselect":n.deselectLabelText},[h(e.$slots,"option",{option:l,search:e.search,index:u},()=>[o("span",null,v(e.getOptionLabel(l)),1)])],42,["onClick","onMouseenter","data-select","data-selected","data-deselect"])),l&&(l.$isLabel||l.$isDisabled)?(d(),c("span",{key:1,"data-select":e.groupSelect&&n.selectGroupLabelText,"data-deselect":e.groupSelect&&n.deselectGroupLabelText,class:[e.groupHighlight(u,l),"multiselect__option"],onMouseenter:r(a=>e.groupSelect&&e.pointerSet(u),["self"]),onMousedown:r(a=>e.selectGroup(l),["prevent"])},[h(e.$slots,"option",{option:l,search:e.search,index:u},()=>[o("span",null,v(e.getOptionLabel(l)),1)])],42,["data-select","data-deselect","onMouseenter","onMousedown"])):b("v-if",!0)],8,["id","role"]))),128)):b("v-if",!0),S(o("li",null,[o("span",x,[h(e.$slots,"noResult",{search:e.search},()=>[_])])],512),[[V,i.showNoResults&&e.filteredOptions.length===0&&e.search&&!i.loading]]),S(o("li",null,[o("span",ee,[h(e.$slots,"noOptions",{},()=>[te])])],512),[[V,i.showNoOptions&&(e.options.length===0||n.hasOptionGroup===!0&&e.filteredOptions.length===0)&&!e.search&&!i.loading]]),h(e.$slots,"afterList")],12,["id"])],36),[[V,e.isOpen]])]),_:3})],42,["tabindex","aria-owns"])}G.render=ie;const le={class:"BaseSelect"},se=["for"],ne=["onClick"],ae=O("svg",{class:"-mr-0.5 ml-1.5 h-3 w-3 text-primary/50",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},[O("path",{fill:"none",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M18 6L6 18M6 6l12 12"})],-1),re={inheritAttrs:!1,components:{Validator:C}},he=K({...re,__name:"Select",props:{allowEmpty:{type:Boolean,default:!0},disabled:{type:Boolean},hideSelected:{type:Boolean},label:null,modelValue:null,multiple:{type:Boolean,default:!1},name:null,onRemove:null,onSelect:null,options:null,placeholder:null,tooltip:null,validator:null},emits:["update:modelValue","searchValue"],setup(e,{emit:t}){const i=e;function s(a,m){return typeof a.value=="number"&&typeof m.value=="number"?a.value-m.value:a.label.toString().localeCompare(m.label.toString())}const p=P(()=>{const{options:a}=i;return(a!=null?a:[]).sort(s).map(f=>f.value)}),n=a=>{var f;const{options:m}=i;return m!=null&&((f=m.find(L=>L.value==a))==null?void 0:f.label)||"Sin opci\xF3n"},l=a=>{t("searchValue",a)},u=P({get(){return i.modelValue},set(a){t("update:modelValue",a)}});return(a,m)=>(d(),A("div",le,[e.label?(d(),A("label",{key:0,for:e.name,class:"form-label font-bold"},v(e.label),9,se)):b("",!0),o($(G),M({modelValue:$(u),"onUpdate:modelValue":m[0]||(m[0]=f=>N(u)?u.value=f:null),placeholder:e.placeholder?e.placeholder:"Selecciona",class:["box-border border rounded",[{"border-danger":e.validator&&e.validator[e.name].$error}]],options:$(p),"max-height":600,"close-on-select":!0,"clear-on-select":!1,"custom-label":f=>n(f),allowEmpty:e.allowEmpty,disabled:e.disabled,hideSelected:e.hideSelected,multiple:e.multiple,openDirection:"bottom",selectedLabel:"",deselectLabel:"",selectLabel:"",onSelect:e.onSelect,onRemove:e.onRemove,onSearchChange:l},a.$attrs),{noResult:w(()=>[y(" No se encontraron elementos. ")]),noOptions:w(()=>[y(" La lista esta vacia. ")]),tag:w(({option:f,remove:L})=>[O("span",{onClick:()=>L(f),class:"inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary cursor-pointer"},[O("p",null,v(n(f)),1),ae],8,ne)]),_:1},16,["modelValue","placeholder","class","options","custom-label","allowEmpty","disabled","hideSelected","multiple","onSelect","onRemove"]),e.validator?(d(),c(C,R(M({key:1},{name:e.name,validator:e.validator,tooltip:e.tooltip})),null,16)):b("",!0)]))}});export{he as _,G as s};
