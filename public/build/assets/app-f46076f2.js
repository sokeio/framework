var $=Object.defineProperty;var E=(e,t,i)=>t in e?$(e,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[t]=i;var u=(e,t,i)=>(E(e,typeof t!="symbol"?t+"":t,i),i);const y={render(){return console.log("render"),`
        <div>
            <h1>hello world</h1>
            [sokeio::demo2 /]
        </div>
        `}},k={state:{tesst:"<h1>hello world</h1>test"},render(){return`
        <div>
            <h1>hello world</h1>
            <div so-text="tesst">dfdfdfdf</div>
            <div so-html="tesst">dfdfdfdf</div>
            <input so-model="tesst" />
            <select so-model="tesst">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
            <textarea so-model="tesst" ></textarea>
            <button so-on:click="tesst=new Date().getTime();">click</button>
            <button so-on:click="this.tesst=new Date().getTime();">click2</button>
        </div>
        `}};function O({component:e,el:t,name:i,method:r,value:s}){s&&(e.watch(s,()=>{t.innerHTML=e[s]}),t.innerHTML=e[s])}function j({component:e,el:t,name:i,method:r,value:s}){s&&(e.watch(s,()=>{t.value=e[s]}),t.value=e[s],t.addEventListener("input",n=>{e[s]=n.target.value}))}function C(e){const t=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;return[...e.matchAll(t)].map(i=>{const[r,s,n,,o]=i;return{component:r,tag:s,attrs:n,content:o}})}const P="############$$$$$$$$############";function T(...e){console.log(...e)}function L(e){const t=(o,d)=>(Object.getOwnPropertyDescriptor(o,d)||{}).get,i=(o,d)=>typeof o[d]=="function",r=o=>o&&o!==Object.prototype&&Object.getOwnPropertyNames(o).filter(d=>t(o,d)||i(o,d)).concat(r(Object.getPrototypeOf(o))||[]),s=o=>Array.from(new Set(r(o)));return(o=>s(o).filter(d=>d!=="constructor"&&!~d.indexOf("__")))(e)}function R(e){let t={};return Reflect.ownKeys(e).forEach(i=>{Object.defineProperty(t,i,{get(){return e[i]},set(r){e[i]=r},enumerable:!0,configurable:!0})}),t}function D(e,t,i){return new Function("$event","$obj",`with($obj) {  ${e}}`).apply(i,[t,R(i)])}function F(e,t,i){if(!t||!e)return;let r=t.split(".");if(r.length===1)return e[t]=i,e[t];let s=r.shift(),n=r.join(".");s&&(e[s]===void 0&&(e[s]={}),this.dataSet(e[s],n,i))}function M(e,t){return t===""||!t?e:t.split(".").reduce((i,r)=>{if(i!==void 0)return i[r]},e)}function H(e){const t=document.createElement("template");return t.innerHTML=e,t.content.firstChild}const c={getComponentsFromText:C,LOG:T,tagSplit:P,runFunction:D,getMethods:L,dataSet:F,dataGet:M,convertHtmlToElement:H};function S({component:e,el:t,name:i,method:r,value:s}){s&&t.addEventListener(r,n=>{c.runFunction(s,n,e)})}function V({component:e,el:t,name:i,method:r,value:s}){s&&(e.watch(s,()=>{t.innerText=e[s]}),t.innerText=e[s])}const l={html:O,model:j,text:V,on:S},f="so-";function x(e){setTimeout(()=>{e.$el.querySelectorAll("*").forEach(t=>{[...t.attributes].forEach(i=>{if(i.name.startsWith(f)){let r=i.name.split(":"),s=r[0].replace(f,""),n=r[1],o="";n&&(n=n.split("."),n.length>1&&(o=n[1],n=n[0])),l[s]?l[s]({component:e,el:t,name:s,method:n,method2:o,value:i.value}):console.error("feature not found: "+s)}})})})}class h{constructor(t=void 0){u(this,"listeners",{});u(this,"removeListeners",[]);u(this,"data",{});this.data=t??{}}getValue(t){return this.data[t]}setValue(t,i){const r=this.data[t];setTimeout(()=>{this.changeProperty(t,i,r)},1),this.data[t]=i}watch(t,i){this.listeners[t]||(this.listeners[t]=[]),this.listeners[t].push(i)}changeProperty(t,i,r){this.listeners[t]&&this.listeners[t].forEach(s=>s(i,r))}cleanup(){this.removeListeners.forEach(t=>t()),this.removeListeners=[],this.listeners={}}check(t){return this.data[t]!==void 0}getKeys(){return Object.keys(this.data)}}let B=new Date().getTime(),a={},I=(e,t,i)=>!e.startsWith("_")&&i.indexOf(e)===t;function A(e,t){a[e]=t}function G(e,t,i=null){return a[e]?m(a[e],t,i):(console.error("Component not found: "+e),null)}function K(e){let t=e.$el.innerHTML,r=c.getComponentsFromText(t).map(s=>(t=t.split(s.component).join(c.tagSplit),G(s.tag,s.attrs,e)));if(r.length){let s="";t.split(c.tagSplit).forEach((n,o)=>{s+=n,r[o]&&(s+='<span id="sokeio-component-'+r[o].getId()+'">'+r[o].getId()+"</span>")}),t=s,e.$children=r}return e.$el.innerHTML=t,e}function m(e,t,i=null){let r={...e,$parent:i,$children:[],$id:0,$el:null,$manager:null},s=Object.keys(r).concat(Object.keys(e.state??{})).concat(Object.keys(t)).concat(c.getMethods(r)).filter(I).concat(["getId","watch","cleanup","doBoot","doRender","doUpdate","doDestroy","doReady","__data__","__props__"]);return Object.defineProperty(r,"getId",{value:function(){return this.$id||(this.$id=++B),this.$id}}),Object.defineProperty(r,"__data__",{value:new h(e.state??{})}),Object.defineProperty(r,"__props__",{value:new h(t)}),Object.defineProperty(r,"watch",{value:function(n,o){this.__data__.check(n)&&this.__data__.watch(n,o),this.__props__.check(n)&&this.__props__.watch(n,o)}}),Object.defineProperty(r,"cleanup",{value:function(n,o){this.__data__.cleanup(n,o)}}),Object.defineProperty(r,"doBoot",{value:function(){let n=this.render?this.render():"<div></div>";n=n.trim(),this.$el=c.convertHtmlToElement(n),K(this),x(this),this.$children&&this.$children.forEach(o=>{o.doBoot(),o.boot&&o.boot()})}}),Object.defineProperty(r,"doRender",{value:function(){this.$children&&this.$children.forEach(n=>{let o=this.$el.querySelector("#sokeio-component-"+n.getId());o.parentNode.insertBefore(n.$el,o),o.remove(),n.doRender()})}}),Object.defineProperty(r,"doReady",{value:function(){this.$children&&this.$children.forEach(n=>{n.doReady()})}}),Object.defineProperty(r,"doDestroy",{value:function(){this.$children&&this.$children.forEach(n=>{n.doDestroy(),n.destroy&&n.destroy()})}}),new Proxy(r,{ownKeys:()=>s,set:(n,o,d)=>n.__data__.check(o)?(n.__data__.setValue(o,d),!0):n.__props__.check(o)?(n.__props__.setValue(o,d),!0):n[o]!==void 0?(n[o]=d,!0):!1,get:(n,o)=>n.__data__.check(o)?n.__data__.getValue(o):n.__props__.check(o)?n.__props__.getValue(o):(n[o]!==void 0,n[o])})}let p=!1,_=!1;function N(e){document.addEventListener("sokeio::register",e)}function U(e){document.addEventListener("sokeio::boot",e)}function W(e){p?e():document.addEventListener("sokeio::ready",e)}function z(e){document.addEventListener("sokeio::destroy",e)}function g(){document.dispatchEvent(new CustomEvent("sokeio::register",{detail:{registerComponent:A}}))}function v(e){e&&e.doBoot(),document.dispatchEvent(new CustomEvent("sokeio::boot"))}function w(e){e&&e.doRender(),document.dispatchEvent(new CustomEvent("sokeio::render"))}function b(e){e&&e.doReady(),document.dispatchEvent(new CustomEvent("sokeio::ready")),p=!0}function J(e){e&&e.doDestroy(),document.dispatchEvent(new CustomEvent("sokeio::destroy"))}function Q(e={},t=null){_||(g(),_=!0),document.dispatchEvent(new CustomEvent("sokeio::run"));let i=new m(e,{});return v(i),w(i),b(i),t||(t=document.body),typeof t=="string"&&(t=document.querySelector(t)),t&&t.appendChild(i.$el),i}const X=Object.freeze(Object.defineProperty({__proto__:null,boot:v,destroy:J,onBoot:U,onDestroy:z,onReady:W,onRegister:N,ready:b,register:g,render:w,run:Q},Symbol.toStringTag,{value:"Module"}));document.addEventListener("sokeio::register",({detail:{registerComponent:e}})=>{e("demo::test",y),e("sokeio::demo2",k)});window.sokeio={Application:X};
