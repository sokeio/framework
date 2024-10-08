var K=Object.defineProperty;var N=(e,t,i)=>t in e?K(e,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[t]=i;var f=(e,t,i)=>(N(e,typeof t!="symbol"?t+"":t,i),i);function B({component:e,el:t,name:i,method:o,value:r}){r&&(e.watch(r,()=>{t.innerHTML=e[r]}),t.innerHTML=e[r])}function U({component:e,el:t,name:i,method:o,value:r}){r&&(e.watch(r,()=>{t.value=e[r]}),t.value=e[r],t.addEventListener("input",n=>{e[r]=n.target.value}))}function G(e){const t=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;return[...e.matchAll(t)].map(i=>{const[o,r,n,,s]=i;return{component:o,tag:r,attrs:n,content:s}})}const W="############$$$$$$$$############";function X(...e){console.log(...e)}function Q(e){const t=(s,a)=>(Object.getOwnPropertyDescriptor(s,a)||{}).get,i=(s,a)=>typeof s[a]=="function",o=s=>s&&s!==Object.prototype&&Object.getOwnPropertyNames(s).filter(a=>t(s,a)||i(s,a)).concat(o(Object.getPrototypeOf(s))||[]),r=s=>Array.from(new Set(o(s)));return(s=>r(s).filter(a=>a!=="constructor"&&!~a.indexOf("__")))(e)}function Y(e){let t={};return Reflect.ownKeys(e).forEach(i=>{Object.defineProperty(t,i,{get(){return e[i]},set(o){e[i]=o},enumerable:!0,configurable:!0})}),t}function J(e,t,i){return new Function("$event","$obj",`with($obj) {  ${e}}`).apply(i,[t,Y(i)])}function Z(e,t,i){if(!t||!e)return;let o=t.split(".");if(o.length===1)return e[t]=i,e[t];let r=o.shift(),n=o.join(".");r&&(e[r]===void 0&&(e[r]={}),this.dataSet(e[r],n,i))}function ee(e,t){return t===""||!t?e:t.split(".").reduce((i,o)=>{if(i!==void 0)return i[o]},e)}function te(e){const t=document.createElement("template");return t.innerHTML=e,t.content.firstChild}let ie=function(e){if(!e)return;Array.isArray(e)||(e=[e]);const t=document.getElementsByTagName("script")[0];e.forEach(i=>{let o=document.createElement("script");o.src=i,t.parentNode.insertBefore(o,t)})},re=function(e){if(!e)return;Array.isArray(e)||(e=[e]);const t=document.getElementsByTagName("link")[0];e.forEach(i=>{let o=document.createElement("link");o.rel="stylesheet",o.href=i,t.parentNode.insertBefore(o,t)})};const d={getComponentsFromText:G,LOG:X,tagSplit:W,runFunction:J,getMethods:Q,dataSet:Z,dataGet:ee,convertHtmlToElement:te,addScriptToWindow:ie,addStyleToWindow:re};function ne({component:e,el:t,name:i,method:o,value:r}){r&&t.addEventListener(o,n=>{(ignore=t.getAttribute("so-on:ignore"))&&n.target.closest(ignore)||d.runFunction(r,n,e)})}function oe({component:e,el:t,name:i,method:o,value:r}){r&&(e.watch(r,()=>{t.innerText=e[r]}),t.innerText=e[r])}const F={html:B,model:U,text:oe,on:ne},C="so-",O=(e,t)=>{[...e.attributes].forEach(i=>{if(i.name.startsWith(C)){let o=i.name.split(":"),r=o[0].replace(C,""),n=o[1],s="";n&&(n=n.split("."),n.length>1&&(s=n[1],n=n[0])),F[r]?F[r]({component:t,el:e,name:r,method:n,method2:s,value:i.value}):console.error("feature not found: "+r)}})};function I(e){setTimeout(()=>{e.$el&&(e.$el.querySelectorAll("*").forEach(t=>{O(t,e)}),O(e.$el,e))})}function x(){return document.querySelector('meta[name="csrf-token"]')?document.querySelector('meta[name="csrf-token"]').getAttribute("content"):document.querySelector('input[name="_token"]')?document.querySelector('input[name="_token"]').getAttribute("value"):""}function se(e,t=void 0,i={},o="GET"){return fetch(e,{headers:{Accept:"application/json","Content-Type":"application/json","X-CSRF-TOKEN":x(),"X-SOKEIO":""},...i,method:o,body:t})}const ae={fetch(e,t=void 0,i={},o="GET"){return se(e,t,i,o)},get(e,t=void 0,i={}){return this.fetch(e,t,i,"GET")},post(e,t={},i={}){return this.fetch(e,t,i,"POST")},put(e,t={},i={}){return this.fetch(e,t,i,"PUT")},delete(e,t={},i={}){return this.fetch(e,t,i,"DELETE")},patch(e,t={},i={}){return this.fetch(e,t,i,"PATCH")},upload(e,t,i={}){let o=i.headers||{},r=i.progress||!1;return new Promise((n,s)=>{const a=new XMLHttpRequest;a.open("POST",e),Object.entries(o).forEach(([l,c])=>{a.setRequestHeader(l,c)}),a.responseType="json",a.setRequestHeader("X-SOKEIO",""),a.setRequestHeader("X-CSRF-TOKEN",x()),a.upload.addEventListener("progress",l=>{l.detail={},l.detail.progress=Math.floor(l.loaded*100/l.total),r&&r(l)}),a.onload=()=>{a.status===200?n(a.response):s(a.response)},a.onerror=()=>{s(a.response)},a.send(t)})}};class q{constructor(t=void 0){f(this,"listeners",{});f(this,"removeListeners",[]);f(this,"data",{});this.data=t??{}}getValue(t){return this.data[t]}setValue(t,i){const o=this.data[t];setTimeout(()=>{this.changeProperty(t,i,o)},1),this.data[t]=i}watch(t,i){this.listeners[t]||(this.listeners[t]=[]),this.listeners[t].push(i)}changeProperty(t,i,o){this.listeners[t]&&this.listeners[t].forEach(r=>r(i,o))}cleanup(){this.removeListeners.forEach(t=>t()),this.removeListeners=[],this.listeners={}}check(t){return this.data[t]!==void 0}getKeys(){return Object.keys(this.data)}}const P=ae;let le=new Date().getTime(),h={},de=(e,t,i)=>!e.startsWith("_")&&i.indexOf(e)===t;function ce(e,t){h[e]=t}function ue(e,t,i=null){return!h[e]&&!(i!=null&&i.components[e])?(console.error("Component not found: "+e),null):i!=null&&i.components[e]?w(i==null?void 0:i.components[e],t,i):w(h[e],t,i)}function fe(e){let t=e.$el.innerHTML,o=d.getComponentsFromText(t).map(r=>(t=t.split(r.component).join(d.tagSplit),ue(r.tag,r.attrs,e)));if(o.length){let r="";t.split(d.tagSplit).forEach((n,s)=>{r+=n,o[s]&&(r+='<span id="sokeio-component-'+o[s].getId()+'">'+o[s].getId()+"</span>")}),t=r,e.$children=o}return e.$el.innerHTML=t,e}function g(e){let t=e.render?e.render():"<div></div>";t=t.trim(),e.$el=d.convertHtmlToElement(t),fe(e),I(e),e.$children&&e.$children.forEach(i=>{g(i)}),e.boot&&e.boot()}function b(e){if(e.$children&&e.$children.forEach(i=>{let o=e.$el.querySelector("#sokeio-component-"+i.getId());o.parentNode.insertBefore(i.$el,o),o.remove(),b(i)}),e.$el){e.$el.setAttribute("data-sokeio-id",e.getId()),e.$el._sokeio=e;var t=new MutationObserver(function(i){I(e)});t.observe(document.documentElement,{childList:!0,subtree:!0}),e.cleanup(()=>{t.disconnect()})}}function k(e){e.$children&&e.$children.forEach(t=>{k(t)}),e.ready&&e.ready(),e.$hookReady&&e.$hookReady.forEach(t=>{t.bind(e)()})}function m(e){var t;e.$children&&e.$children.forEach(i=>{m(i)}),e.destroy&&e.destroy(),e.$hookDestroy&&e.$hookDestroy.forEach(i=>{i.bind(e)()}),(t=e.$el)==null||t.remove(),e.$hookDestroy=[],e.$hookReady=[],e.$children=[],e.$el=null}function w(e,t,i=null){let o={...e,$parent:i,$children:[],$id:0,$el:null,$hookDestroy:[],$hookReady:[]},r=Object.keys(o).concat(Object.keys(e.state??{})).concat(Object.keys(t)).concat(d.getMethods(o)).filter(de).concat(["getId","watch","cleanup","boot","ready","delete","destroy","onReady","reRender","querySelectorAll","on","$request","__data__","__props__"]).filter(function(n,s,a){return a.indexOf(n)===s});return Object.defineProperty(o,"$request",{value:P}),Object.defineProperty(o,"getId",{value:function(){return this.$id||(this.$id=++le),this.$id}}),Object.defineProperty(o,"__data__",{value:new q(e.state??{})}),Object.defineProperty(o,"__props__",{value:new q(t)}),Object.defineProperty(o,"querySelectorAll",{value:function(n,s){this.onReady(function(){if(this.$el){let a=[...this.$el.querySelectorAll(n)];return s&&s.bind(this)(a),a}})}}),Object.defineProperty(o,"on",{value:function(n,s,a){this.querySelectorAll(n,l=>{l.forEach(c=>{console.log(s,c,a),c.addEventListener(s,a),this.$hookDestroy.push(()=>{c.removeEventListener(s,a)})})})}}),Object.defineProperty(o,"watch",{value:function(n,s){this.__data__.check(n)&&this.__data__.watch(n,s),this.__props__.check(n)&&this.__props__.watch(n,s)}}),Object.defineProperty(o,"delete",{value:function(){m(this)}}),Object.defineProperty(o,"cleanup",{value:function(n){n&&this.$hookDestroy.push(n)}}),Object.defineProperty(o,"onReady",{value:function(n){n&&this.$hookReady.push(n)}}),Object.defineProperty(o,"reRender",{value:function(){let n=this.$el.parentNode,s=this.$el.nextSibling;m(this),g(this),b(this),k(this),s?n.insertBefore(this.$el,s):n.appendChild(this.$el)}}),new Proxy(o,{ownKeys:()=>r,set:(n,s,a)=>n.__data__.check(s)?(n.__data__.setValue(s,a),!0):n.__props__.check(s)?(n.__props__.setValue(s,a),!0):n[s]!==void 0?(n[s]=a,!0):!1,get:(n,s)=>n.__data__.check(s)?n.__data__.getValue(s):n.__props__.check(s)?n.__props__.getValue(s):(n[s]!==void 0,n[s])})}let R=!1,L=!1;const me=P;function he(e){document.addEventListener("sokeio::register",e)}function we(e){document.addEventListener("sokeio::boot",e)}function pe(e){R?e():document.addEventListener("sokeio::ready",e)}function ye(e){document.addEventListener("sokeio::destroy",e)}function M(){document.dispatchEvent(new CustomEvent("sokeio::register",{detail:{registerComponent:ce}}))}function H(e){g(e),document.dispatchEvent(new CustomEvent("sokeio::boot"))}function D(e){b(e),document.dispatchEvent(new CustomEvent("sokeio::render"))}function V(e){k(e),document.dispatchEvent(new CustomEvent("sokeio::ready")),R=!0}function ge(e){m(e),document.dispatchEvent(new CustomEvent("sokeio::destroy"))}function be(e={},t={}){var n;L||(M(),L=!0);let i=t.selector,o=t.init===void 0?!0:t.init;document.dispatchEvent(new CustomEvent("sokeio::run")),(n=t==null?void 0:t.props)!=null&&n.wireId&&(t.props={...t.props,wireId:t.props.wireId,$wire:window.Livewire.find(t.props.wireId)});let r=new w(e,t.props??{});return o&&(H(r),D(r),V(r),i||(i=document.body),typeof i=="string"&&(i=document.querySelector(i)),i&&i.appendChild(r.$el)),r}const ke=Object.freeze(Object.defineProperty({__proto__:null,$request:me,boot:H,destroy:ge,onBoot:we,onDestroy:ye,onReady:pe,onRegister:he,ready:V,register:M,render:D,run:be},Symbol.toStringTag,{value:"Module"}));window.sokeioUI=ke;const ve={render(){return`
                <div class="so-file-manager__header">
                       demo
                </div>
        `}},_e={components:{"so-filemanager::header":ve},boot(){document.body.classList.add("fm-body-wrapper"),this.cleanup(function(){})},showFileManager(e,t="file"){},render(){return`<div class="so-fm">
        <div class="so-fm-overlay"></div>
        <div class="so-fm-application">
          [so-filemanager::header /]
        </div>
    </div>`}};window.showFileManager=function(e,t="file"){window.sokeioUI.run(_e).showFileManager(e,t)};function v(e="",t="",i="",o=""){o.indexOf("<")===-1&&(o=`<i class="${o}"></i>`),o=o.replace('class="','class="so-modal-icon '),i===void 0&&(i=` <div class="so-modal-header">${o}
                            <h3 class="so-modal-title" so-text="title"></h3>
                        </div>`),$content=i,!t&&!$content?$content=e:$content=`${$conten}<div class="so-modal-body">${e}</div> ${t?`<div class="so-modal-footer">${t}</div>`:""}`;let r='<a class="so-modal-close" so-on:click="this.delete()"></a>',n='so-on:click="this.delete()" so-on:ignore=".so-modal-dialog"',s=d.convertHtmlToElement(e);(s.querySelector(".skip-show-close")||s.getAttribute("data-hide-close"))&&(r="");let a=s.getAttribute("data-modal-size");!a&&s.querySelector("[data-modal-size]")&&(a=s.querySelector("[data-modal-size]").getAttribute("data-modal-size"));let l=s.getAttribute("data-skip-overlay");return!l&&s.querySelector("[data-skip-overlay]")&&(l=s.querySelector("[data-skip-overlay]").getAttribute("data-skip-overlay")),l&&(n=""),a||(a="lg"),`<div class="so-modal so-modal-size-${a}" tabindex="-1" aria-modal="true" ${n} >
                <div class="so-modal-dialog">
                    <div class="so-modal-content">
                        ${$content}
                    </div>
                    ${r}
                </div>
            </div>`}function _(){let e=d.convertHtmlToElement('<div class="so-modal-overlay"></div>'),t=document.body.querySelector(".so-modal-overlay");return t?t.parentNode.insertBefore(e,t):document.body.appendChild(e),e}const $e={state:{html:"",loading:!0},boot(){if(!this.skipLoading||!this.loading){let e=_();this.cleanup(function(){document.body.removeChild(e)})}this.html||this.$request.get(this.url).then(async e=>{e.ok?this.html=await e.text():this.html=`<div class="so-modal-content-error"><h3>${e.statusText}</h3><button class="btn btn-primary" so-on:click="this.delete()">Close</button></div>`,this.loading=!1,this.reRender()})},ready(){},render(){return this.skipLoading&&this.loading?"<template></template>":v(this.html||'<div class="so-modal-loader" data-hide-close="true"><span class="loader"></span></div>',"","",this.icon)}};window.showModal=function(e="",t={url:void 0,template:void 0,templateId:void 0,component:void 0,elTarget:void 0,data:{},callback:()=>{}}){if(t.templateId&&(t.template=document.getElementById(t.templateId).innerHTML.replace("export default","return "),delete t.templateId,t.component=new Function(t.template)(),delete t.template),t.component){let i=_();window.sokeioUI.run({...t.component,render:function(){var o,r,n,s,a,l;return v((r=(o=t.component).render)==null?void 0:r.call(o),(s=(n=t.component).footer)==null?void 0:s.call(n),(l=(a=t.component).header)==null?void 0:l.call(a),t.component.icon)}},{props:{title:e,...t}}).cleanup(function(){document.body.removeChild(i)});return}window.sokeioUI.run($e,{props:{title:e,...t}})};const Ae={checkFirst:()=>window.ApexCharts!==void 0,local:{js:["/platform/modules/sokeio/apexcharts/dist/apexcharts.min.js"],css:["/platform/modules/sokeio/apexcharts/dist/apexcharts.css"]},cdn:{js:["https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"],css:["https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css"]},init:({el:e,directive:t,component:i,cleanup:o})=>{}},Ee={checkFirst:()=>window.bootstrap.Carousel!==void 0,local:{js:[],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{e.$sokeio_carousel=window.bootstrap.Carousel.getOrCreateInstance(e,r)}},je={checkFirst:()=>window.Clipboard!==void 0,local:{js:["/platform/modules/sokeio/clipboard/dist/clipboard.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{e.$sokeio_clipboard=new window.Clipboard(e,r)}},Te={checkFirst:()=>window.CountUp!==void 0,local:{js:["/platform/modules/sokeio/count-up/dist/count-up.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{let n=e.getAttribute("wire:countup");(!n||n=="")&&(n=d.dataGet(i.$wire,e.getAttribute("wire:countup.model"))),e.$sokeio_countup=new window.CountUp(e,n,r),e.$sokeio_countup.error?console.error(e.$sokeio_countup.error):e.$sokeio_countup.start()}};window.shortcodePattern=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/s;function Se(e){let t=e.toLowerCase();return t=t.replace("dd","99"),t=t.replace("d","99"),t=t.replace("mm","99"),t=t.replace("m","99"),t=t.replace("yyyy","9999"),t=t.replace("yy","9999"),t=t.replace("y","9999"),t}function Fe(e){let t=e.toLowerCase();return t=t.replace("hh","99"),t=t.replace("h","99"),t=t.replace("ii","99"),t=t.replace("i","99"),t=t.replace("ss","99"),t=t.replace("s","99"),t}function Ce(e){return Fe(Se(e))}function Oe(e){return e.getAttribute("wire:id")??e.closest("[wire:id]").getAttribute("wire:id")}const qe={checkFirst:()=>window.flatpickr!==void 0,local:{js:["/platform/modules/sokeio/flatpickr/dist/flatpickr.min.js"],css:["/platform/modules/sokeio/flatpickr/dist/flatpickr.min.css"]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{let n=e.getAttribute("wire:model"),s=(r==null?void 0:r.dateFormat)??(r!=null&&r.enableTime?"Y/m/d H:i:S":"Y/m/d"),a=Ce(s);e.$sokeio_flatpickr=new window.flatpickr(e,{allowInput:!0,allowInvalidPreload:!0,dateFormat:s,...r,onChange:(l,c,u)=>{d.dataSet(i.$wire,n,l)}}),setTimeout(async()=>{Alpine.$data(e).maskFormat=a},10)}},Le={checkFirst:()=>!0,init:({el:e,directive:t,component:i,cleanup:o})=>{let r=e.getAttribute("wire:model"),n=e.getAttribute("wire:get-value"),s=e.getAttribute("wire:get-value-parent")??i.$wire.soRefId;if(!s)return;let a=window.Livewire.find(s);if(!a)return;let l=d.dataGet(a,n);d.dataSet(i.$wire,r,l)}},Ie={checkFirst:()=>window.Masonry!==void 0,local:{js:["/platform/modules/sokeio/masonry/dist/masonry.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{e.$sokeio_masonry=new Masonry(e,r);let n=()=>{e.$sokeio_masonry_timer&&(e.$sokeio_masonry.layout(),clearTimeout(e.$sokeio_masonry_timer),e.$sokeio_masonry_timer=null),e.$sokeio_masonry_timer=setTimeout(()=>{e.$sokeio_masonry.layout(),e.$sokeio_masonry_timer=null},100)};window.addEventListener("resize",n),o(()=>{window.removeEventListener("resize",n)})}},xe={checkFirst:()=>!0,local:{js:[],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o})=>{let r={icon:'<i class="ti ti-alarm"></i>',templateId:"",template:"",url:"",elTarget:e},n=e.getAttribute("wire:modal.title");e.hasAttribute("wire:modal.url")&&(r.url=e.getAttribute("wire:modal.url")),e.hasAttribute("wire:modal.size")&&(r.size=e.getAttribute("wire:modal.size")),e.hasAttribute("wire:modal.icon")&&(r.icon=e.getAttribute("wire:modal.icon")),e.hasAttribute("wire:modal.template-id")&&(r.templateId=e.getAttribute("wire:modal.template-id")),e.hasAttribute("wire:modal.template")&&(r.template=e.getAttribute("wire:modal.template"));let s=function(){window.showModal(n,r)};e.addEventListener("click",s),o(()=>{e.removeEventListener("click",s)}),console.log("init")}},Pe={checkFirst:()=>window.QRCode!==void 0,local:{js:["/platform/modules/sokeio/easyqrcodejs/dist/easy.qrcode.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{let n={titleFont:"normal normal bold 18px Arial",titleColor:"#004284",titleBackgroundColor:"#ccc",titleHeight:40,titleTop:25,...r};(n.text==null||n.text=="")&&e.getAttribute("wire:qrcode")!=""&&(n.text=i.$wire[e.getAttribute("wire:qrcode")]),e.hasAttribute("wire:qrcode.text")&&(n.text=e.getAttribute("wire:qrcode.text")),e.hasAttribute("wire:qrcode.title")&&(n.title=e.getAttribute("wire:qrcode.title")),!e.$sokeio_qrcode&&(e.$sokeio_qrcode=new window.QRCode(e,n))}},Re={checkFirst:()=>window.Sortable!==void 0,local:{js:["/platform/modules/sokeio/sortable/sortable.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{e.$sokeio_sortable||(e.$sokeio_sortable=window.Sortable.create(e,{animation:150,...r,draggable:"[wire\\:sortable\\.item]",handle:e.querySelector("[wire\\:sortable\\.handle]")?"[wire\\:sortable\\.handle]":null,sort:!0,dataIdAttr:"data-sortable-id",group:{animation:150,...(r==null?void 0:r.group)??{},name:e.getAttribute("wire:sortable"),pull:!1,put:!1},store:{...(r==null?void 0:r.store)??{},set:function(n){var a;let s=n.toArray().map((l,c)=>({order:c+1,value:l}));t.expression?i.$wire.call(t.expression,s):(a=Alpine.$data(e).onSortable)==null||a.call(e,s)}}}))}},Me={checkFirst:()=>window.Sortable!==void 0,local:{js:["/platform/modules/sokeio/sortable/sortable.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{t.modifiers.includes("item-group")&&(e.$sokeio_sortable_group=window.Sortable.create(e,{animation:150,...r,draggable:"[wire\\:sortable-group\\.item]",handle:e.querySelector("[wire\\:sortable-group\\.handle]")?"[wire\\:sortable-group\\.handle]":null,sort:!0,dataIdAttr:"wire:sortable-group.item",group:{...(r==null?void 0:r.group)??{},name:e.closest("[wire\\:sortable-group]").getAttribute("wire:sortable-group"),pull:!0,put:!0},onSort:()=>{let n=e.closest("[wire\\:sortable-group]");if(!n)return;let s=Array.from(n.querySelectorAll("[wire\\:sortable-group\\.item-group]")).map((a,l)=>({order:l+1,value:a.getAttribute("wire:sortable-group.item-group"),items:a.$sokeio_sortable_group.toArray().map((c,u)=>({order:u+1,value:c}))}));i.$wire.call(n.getAttribute("wire:sortable-group"),s)}}))}},He={checkFirst:()=>window.Tagify!==void 0,local:{js:["/platform/modules/sokeio/tagify/dist/tagify.min.js"],css:["/platform/modules/sokeio/tagify/dist/tagify.css"]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{r.templates&&(r.templates=new Function(`return ${r.templates};`)()),r.originalInputValueFormat&&(r.originalInputValueFormat=new Function(`return ${r.originalInputValueFormat};`)()),r.validate&&(r.validate=new Function(`return ${r.validate};`)()),r.transformTag&&(r.transformTag=new Function(`return ${r.transformTag};`)()),r.callbacks&&(r.callbacks=new Function(`return ${r.callbacks};`)()),r.hooks&&(r.hooks=new Function(`return ${r.hooks};`)());let n=e.getAttribute("wire:model");const s=l=>{if(!e.$sokeio_tagify)return;let c=l.detail.value;e.$sokeio_tagify.loading(!0),i.$wire.callActionUI(r.whitelistAction,c).then(function(u){e.$sokeio_tagify.whitelist=u,e.$sokeio_tagify.loading(!1).dropdown.show(c)})},a=l=>{Utils.dataSet(i.$wire,n,l.detail.value)};e.$sokeio_tagify=new window.Tagify(e,r),r.whitelistAction&&e.$sokeio_tagify.on("input",s),e.$sokeio_tagify.on("change",a),o(()=>{var l;e!=null&&e.$sokeio_tagify&&((l=e.$sokeio_tagify)!=null&&l.destroy&&e.$sokeio_tagify.destroy(),e.$sokeio_tagify=null),e.removeEventListener("input",s),e.removeEventListener("change",a)})}},De={checkFirst:()=>window.tinymce!==void 0,local:{js:["/platform/modules/sokeio/tinymce/tinymce.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:o,options:r})=>{if(e.$sokeio_tinymce)return;e.hasAttribute("wire:tinymce")&&(r=new Function(`return ${e.getAttribute("wire:tinymce")};`)()),o(()=>{e.$sokeio_tinymce&&e.$sokeio_tinymce.remove&&(e.$sokeio_tinymce.remove(),e.$sokeio_tinymce=null)});let n=e.getAttribute("wire:tinymce-model")??e.getAttribute("wire:model"),s={};e.hasAttribute("wire:tinymce-skip")&&(s={}),e.$sokeio_tinymce=window.tinymce.init({...s??{},...r,promotion:!1,target:e,setup:function(a){a.on("init",function(){a.setContent(e.value),a.undoManager.dispatchChange()}),a.on("input",function(l){d.dataSet(i.$wire,n,a.getContent())}),a.on("ExecCommand",l=>{["mceFocus"].includes(l.command)||d.dataSet(i.$wire,n,a.getContent())})},file_picker_callback:function(a,l,c){window.showFileManager(function(u){a(u[0].url)},{type:"image",value:l})}})}},p={apexcharts:Ae,"get-value":Le,qrcode:Pe,carousel:Ee,clipboard:je,countup:Te,flatpickr:qe,masonry:Ie,sortable:Re,"sortable-group":Me,tagify:He,tinymce:De,modal:xe};let z=(e,t,i)=>{if(!e.checkFirst||e.checkFirst()){e.init(t);return}i>50||setTimeout(()=>{z(e,t,i+1)},50)};function Ve(e){Object.keys(p).forEach(function(t){let i=p[t];e.directive(t,o=>{var n,s,a,l,c,u,$,A,E,j,T,S;if(o.length===0||o.directive.modifiers.length>0||o.el[`$$sokeio_${t.replace(/\./g,"_").replace(/\//g,"_").replace(/-/g,"_")}`])return;let r={};(r=o.el.getAttribute(`wire:${t}.options`))&&(r=new Function(`return ${r};`)()),i.checkFirst&&!i.checkFirst()&&((n=i==null?void 0:i.cdn)!=null&&n.js&&Array.isArray((s=i==null?void 0:i.cdn)==null?void 0:s.js)&&((a=i==null?void 0:i.cdn)==null?void 0:a.js.length)>0?d.addScriptToWindow((l=i==null?void 0:i.cdn)==null?void 0:l.js):(c=i==null?void 0:i.local)!=null&&c.js&&d.addScriptToWindow((u=i==null?void 0:i.local)==null?void 0:u.js),($=i==null?void 0:i.cdn)!=null&&$.css&&Array.isArray((A=i==null?void 0:i.cdn)==null?void 0:A.css)&&((E=i==null?void 0:i.cdn)==null?void 0:E.css.length)>0?d.addStyleToWindow((j=i==null?void 0:i.cdn)==null?void 0:j.css):(T=i==null?void 0:i.local)!=null&&T.css&&d.addStyleToWindow((S=i==null?void 0:i.local)==null?void 0:S.css)),z(i,{...o,options:r},0)})})}const ze={directive:p,install:Ve},Ke={state:{html:"",loading:!0},boot(){let e=_();this.cleanup(function(){document.body.removeChild(e)}),console.log(this.$wire)},ready(){},render(){return v("<div style='min-height:100px' so-html='message'></div>","",this.icon)}};function Ne(e){window.sokeioUI.run(Ke,{props:{...e}})}const y={sokeio_message:Ne};function Be(e){e.type&&y[e.type]&&y[e.type](e.payload)}const Ue={dispatch:y,install:Be};document.addEventListener("livewire:init",()=>{ze.install(window.Livewire)});document.addEventListener("sokeio::dispatch",e=>{let t=e.detail[0];Ue.install({type:t.type,payload:{...t.payload,wireId:Oe(e.target)}})});
