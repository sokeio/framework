var K=Object.defineProperty;var B=(e,t,i)=>t in e?K(e,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[t]=i;var w=(e,t,i)=>(B(e,typeof t!="symbol"?t+"":t,i),i);function G(e){const t=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;return[...e.matchAll(t)].map(i=>{const[s,r,o,,n]=i;return{component:s,tag:r,attrs:o,content:n}})}const V="############$$$$$$$$############";function z(...e){console.log(...e)}function W(e){const t=(n,l)=>(Object.getOwnPropertyDescriptor(n,l)||{}).get,i=(n,l)=>typeof n[l]=="function",s=n=>n&&n!==Object.prototype&&Object.getOwnPropertyNames(n).filter(l=>t(n,l)||i(n,l)).concat(s(Object.getPrototypeOf(n))||[]),r=n=>Array.from(new Set(s(n)));return(n=>r(n).filter(l=>l!=="constructor"&&!~l.indexOf("__")))(e)}function J(e){let t={};return Reflect.ownKeys(e).forEach(i=>{Object.defineProperty(t,i,{get(){return e[i]},set(s){e[i]=s},enumerable:!0,configurable:!0})}),t}function X(e,t,i){return new Function("$event","$obj",`with($obj) {  ${e}}`).apply(i,[t,J(i)])}function Q(e,t,i){if(!t||!e)return;let s=t.split(".");if(s.length===1)return e[t]=i,e[t];let r=s.shift(),o=s.join(".");r&&(e[r]===void 0&&(e[r]={}),this.dataSet(e[r],o,i))}function Y(e,t){return t===""||!t?e:t.split(".").reduce((i,s)=>{if(i!==void 0)return i[s]},e)}function Z(e){const t=document.createElement("template");return t.innerHTML=e,t.content.firstChild}let ee=function(e){if(!e)return;Array.isArray(e)||(e=[e]);const t=document.getElementsByTagName("script")[0];e.forEach(i=>{let s=document.createElement("script");s.src=i,t.parentNode.insertBefore(s,t)})},te=function(e){if(!e)return;Array.isArray(e)||(e=[e]);const t=document.getElementsByTagName("link")[0];e.forEach(i=>{let s=document.createElement("link");s.rel="stylesheet",s.href=i,t.parentNode.insertBefore(s,t)})};const h=(...e)=>{window.SOKEIO_DEBUG&&console.log(...e)};function ie(){let e=c.convertHtmlToElement('<div class="so-modal-overlay"></div>'),t=document.body.querySelector(".so-modal-overlay");return t?t.parentNode.insertBefore(e,t):document.body.appendChild(e),e}function g(e,t){let i=t;return t.split(".").forEach(s=>{if(s.startsWith("$")){if(e[s]===void 0||e[s]===null)return;e=e[s]}else i=s}),[i,e]}const c={getComponentsFromText:G,LOG:z,tagSplit:V,runFunction:X,getMethods:W,dataSet:Q,dataGet:Y,convertHtmlToElement:Z,addScriptToWindow:ee,addStyleToWindow:te};function se({component:e,el:t,name:i,method:s,value:r}){if(r){let[o,n]=g(e,r);n.watch(o,()=>{t.innerHTML=n[o]}),t.innerHTML=n[o]}}function re({component:e,el:t,name:i,method:s,value:r}){if(r){let[o,n]=g(e,r);n.watch(o,()=>{t.value=n[o]}),t.value=n[o],t.addEventListener("input",l=>{n[o]=l.target.value})}}function oe({component:e,el:t,name:i,method:s,value:r}){r&&(t["__SOKEIO_ON__"+s]&&t.removeEventListener(s,t["__SOKEIO_ON__"+s]),t["__SOKEIO_ON__"+s]=o=>{(ignore=t.getAttribute("so-on:ignore"))&&o.target.closest(ignore)||c.runFunction(r,o,e)},t.addEventListener(s,t["__SOKEIO_ON__"+s]))}function ne({component:e,el:t,name:i,method:s,value:r}){if(r){let[o,n]=g(e,r);n.watch(o,()=>{t.innerText=n[o]}),t.innerText=n[o]}}const T={html:se,model:re,text:ne,on:oe},O="so-",C=(e,t)=>{[...e.attributes].forEach(i=>{if(i.name.startsWith(O)){let s=i.name.split(":"),r=s[0].replace(O,""),o=s[1],n="";o&&(o=o.split("."),o.length>1&&(n=o[1],o=o[0])),T[r]?T[r]({component:t,el:e,name:r,method:o,method2:n,value:i.value}):console.error("feature not found: "+r)}})};function le(e){setTimeout(()=>{e.$el&&(e.$el.querySelectorAll("*").forEach(t=>{C(t,e)}),C(e.$el,e))})}function x(){return document.querySelector('meta[name="csrf_token"]')?document.querySelector('meta[name="csrf_token"]').getAttribute("value"):document.querySelector('meta[name="csrf-token"]')?document.querySelector('meta[name="csrf-token"]').getAttribute("value"):document.querySelector('input[name="_token"]')?document.querySelector('input[name="_token"]').getAttribute("value"):""}function ae(e,t=void 0,i={},s="GET"){console.log(e,t,i,s);let r=x();return h("fetch::content",r),fetch(e,{headers:{Accept:"application/json","Content-Type":"application/json","X-CSRF-TOKEN":r,"X-SOKEIO":""},...i,method:s,body:JSON.stringify(t)})}const de={fetch(e,t=void 0,i={},s="GET"){return ae(e,t,i,s)},get(e,t=void 0,i={}){return this.fetch(e,t,i,"GET")},post(e,t={},i={}){return this.fetch(e,t,i,"POST")},put(e,t={},i={}){return this.fetch(e,t,i,"PUT")},delete(e,t={},i={}){return this.fetch(e,t,i,"DELETE")},patch(e,t={},i={}){return this.fetch(e,t,i,"PATCH")},upload(e,t,i={}){let s=i.headers||{},r=i.progress||!1;return new Promise((o,n)=>{const l=new XMLHttpRequest;l.open("POST",e),Object.entries(s).forEach(([a,d])=>{l.setRequestHeader(a,d)}),l.responseType="json",l.setRequestHeader("X-SOKEIO",""),l.setRequestHeader("X-CSRF-TOKEN",x()),l.upload.addEventListener("progress",a=>{a.detail={},a.detail.progress=Math.floor(a.loaded*100/a.total),r&&r(a)}),l.onload=()=>{l.status===200?o(l.response):n(l.response)},l.onerror=()=>{n(l.response)},l.send(t)})}};class I{constructor(t=void 0){w(this,"listeners",{});w(this,"removeListeners",[]);w(this,"data",{});this.data=t??{}}getValue(t){return this.data[t]}setValue(t,i){const s=this.data[t];setTimeout(()=>{this.changeProperty(t,i,s)},1),this.data[t]=i}watch(t,i){this.listeners[t]||(this.listeners[t]=[]),this.listeners[t].push(i)}changeProperty(t,i,s){this.listeners[t]&&this.listeners[t].forEach(r=>r(i,s))}cleanup(){this.removeListeners.forEach(t=>t()),this.removeListeners=[],this.listeners={}}check(t){return this.data[t]!==void 0}getKeys(){return Object.keys(this.data)}}const L=de;let ce=new Date().getTime(),y={},ue=(e,t,i)=>!e.startsWith("_")&&i.indexOf(e)===t;function he(e,t){y[e]=t}function fe(e,t,i=null){var s,r,o;return!y[e]&&!(i!=null&&i.components[e])&&!((s=i==null?void 0:i.$root)!=null&&s.components[e])?(console.error("Component not found: "+e),null):i!=null&&i.components[e]?v(i==null?void 0:i.components[e],t,i):(r=i==null?void 0:i.$root)!=null&&r.components[e]?v((o=i==null?void 0:i.$root)==null?void 0:o.components[e],t,i):v(y[e],t,i)}function me(e){let t=e.$el.innerHTML,s=c.getComponentsFromText(t).map(r=>{t=t.split(r.component).join(c.tagSplit);let o=fe(r.tag,r.attrs,e);return o&&q(o),o});if(s.length){let r="";t.split(c.tagSplit).forEach((o,n)=>{r+=o,s[n]&&(r+='<span id="sokeio-component-'+s[n].getId()+'">'+s[n].getId()+"</span>")}),t=r,e.$children=s}return e.$el.innerHTML=t,e}function q(e){h("doRegister",e),e.register&&e.register()}function $(e){h("doBoot",e),e.boot&&e.boot();let t=e.render?e.render():"<div></div>";t=t.trim(),e.$el?e.$el.innerHTML=t:e.$el=c.convertHtmlToElement(t),le(e),me(e),e.$children&&e.$children.forEach(i=>{$(i)})}function _(e){h("doRender",e),e.$children&&e.$children.forEach(t=>{let i=e.$el.querySelector("#sokeio-component-"+t.getId());i.parentNode.insertBefore(t.$el,i),i.remove(),_(t)}),e.$el&&(e.$el.setAttribute("data-sokeio-id",e.getId()),e.$el._sokeio=e)}function A(e){h("doReady",e),e.$children&&e.$children.forEach(t=>{A(t)}),e.ready&&e.ready(),e.$hookReady&&e.$hookReady.forEach(t=>{t.bind(e)()})}function S(e){var t;h("doDestroy",e),e.$children&&e.$children.forEach(i=>{S(i)}),e.destroy&&e.destroy(),e.$hookDestroy&&e.$hookDestroy.forEach(i=>{i.bind(e)()}),(t=e.$el)==null||t.remove(),e.$hookDestroy=[],e.$hookReady=[],e.$children=[],e.$el=null,e.state={},e=void 0}function v(e,t,i=null){e.state||(e.state={});let s={...e,$initState:{...JSON.parse(JSON.stringify(e.state))},$parent:i,$children:[],$id:0,$el:null,$hookDestroy:[],$hookReady:[],$root:i&&i.$root},r=Object.keys(s).concat(Object.keys(e.state)).concat(Object.keys(t)).concat(c.getMethods(s)).filter(ue).concat(["getId","watch","cleanup","show","boot","ready","delete","destroy","onReady","reRender","querySelectorAll","on","$request","$root","__data__","__props__"]).filter(function(o,n,l){return l.indexOf(o)===n});return Object.defineProperty(s,"$request",{value:L}),Object.defineProperty(s,"getId",{value:function(){return this.$id||(this.$id=++ce),this.$id}}),Object.defineProperty(s,"__data__",{value:new I(e.state??{})}),Object.defineProperty(s,"__props__",{value:new I(t)}),s.sokeAppSelector&&Object.defineProperty(s,"show",{value:function(){if(this.$el){if(this.overlay){document.querySelector(".so-modal-overlay")||(document.body.classList.add("so-modal-open"),document.body.style.overflow="hidden");let o=ie();document.body.appendChild(o),this.cleanup(()=>{document.body.removeChild(o),document.querySelector(".so-modal-overlay")||(document.body.classList.remove("so-modal-open"),document.body.style.overflow="auto")})}s.sokeAppSelector.appendChild(this.$el)}}}),Object.defineProperty(s,"querySelectorAll",{value:function(o,n){this.onReady(function(){if(this.$el){let l=[...this.$el.querySelectorAll(o)];return n&&n.bind(this)(l),l}})}}),Object.defineProperty(s,"on",{value:function(o,n,l){this.querySelectorAll(o,a=>{a.forEach(d=>{h(n,d,l),d.addEventListener(n,l),this.$hookDestroy.push(()=>{d.removeEventListener(n,l)})})})}}),Object.defineProperty(s,"watch",{value:function(o,n){return this.__data__.check(o)&&this.__data__.watch(o,n.bind(this)),this.__props__.check(o)&&this.__props__.watch(o,n.bind(this)),this}}),Object.defineProperty(s,"delete",{value:function(){S(this)}}),Object.defineProperty(s,"cleanup",{value:function(o){return o&&this.$hookDestroy.push(o),this}}),Object.defineProperty(s,"onReady",{value:function(o){return o&&this.$hookReady.push(o),this}}),Object.defineProperty(s,"reRender",{value:function(){let o=this.$el.parentNode,n=this.$el.nextSibling;return this.$el.remove(),this.$el=null,h("reRender",this),$(this),_(this),A(this),n?o.insertBefore(this.$el,n):o.appendChild(this.$el),this}}),new Proxy(s,{ownKeys:()=>r,set:(o,n,l)=>o.__data__.check(n)?(o.__data__.setValue(n,l),!0):o.__props__.check(n)?(o.__props__.setValue(n,l),!0):o[n]!==void 0?(o[n]=l,!0):!1,get:(o,n)=>o.__data__.check(n)?o.__data__.getValue(n):o.__props__.check(n)?o.__props__.getValue(n):(o[n]!==void 0,o[n])})}const pe=L;function we(e){document.addEventListener("sokeio::register",e)}function ve(e){document.addEventListener("sokeio::boot",e)}function ye(e){document.addEventListener("sokeio::ready",e)}function be(e){document.addEventListener("sokeio::destroy",e)}function R(e){q(e),document.dispatchEvent(new CustomEvent("sokeio::register",{detail:{registerComponent:he,component:e}}))}function N(e){$(e),document.dispatchEvent(new CustomEvent("sokeio::boot",{detail:{component:e}}))}function P(e){_(e),document.dispatchEvent(new CustomEvent("sokeio::render",{detail:{component:e}}))}function M(e){A(e),document.dispatchEvent(new CustomEvent("sokeio::ready",{detail:{component:e}}))}function ke(e){S(e),document.dispatchEvent(new CustomEvent("sokeio::destroy",{detail:{component:e}}))}function ge(e={},t={}){var o;let i=t.selector;i||(i=document.body),typeof i=="string"&&(i=document.querySelector(i));let s={...e,sokeAppSelector:i,state:JSON.parse(JSON.stringify(e.state??{}))};t.components&&(typeof t.components=="string"&&(t.components=JSON.parse(t.components)),s.components=s.components??{},s.components={...s.components,...t.components}),h("templateCopy",s),t.init===void 0||t.init,document.dispatchEvent(new CustomEvent("sokeio::run")),(o=t==null?void 0:t.props)!=null&&o.wireId&&(t.props={...t.props,wireId:t.props.wireId,$wire:window.Livewire.find(t.props.wireId)});let r=new v(s,t.props??{});return r.$root=r,R(r),h("run",r),N(r),P(r),M(r),r.hide||r.show(),r}const $e=Object.freeze(Object.defineProperty({__proto__:null,$request:pe,boot:N,destroy:ke,onBoot:ve,onDestroy:be,onReady:ye,onRegister:we,ready:M,register:R,render:P,run:ge},Symbol.toStringTag,{value:"Module"}));window.sokeioUI=$e;const _e={diskRender(){let e=' <select so-model="$parent.disk" class="form-select mt-1 p-2">';return Object.keys(this.$parent.disks).forEach(t=>{e+=`<option value="${t}" ${this.$parent.disk==t?"selected":""}>${t}</option>`}),e+="</select>",`<div class="row justify-content-center align-items-center"><div class="col-auto ps-4 fw-bold">Disks</div><div class="col">${e}</div></div>`},checkItemActive(e){return e.path==this.$parent.path},openFolder(e){this.$parent.openFolder(e),this.reRender()},itemRender(e){var i;let t=`<li > <div so-on:click="openFolder('${e.path}')" class="so-fm-folder-item ${this.checkItemActive(e)?"active":""}" style="padding-left:${e.level*20}px">${e.name}</div>`;return e!=null&&e.children&&((i=e==null?void 0:e.children)==null?void 0:i.length)>0&&(t+=this.treeRender(e.children)),`${t}</li>`},treeRender(e){let t="";return Array.isArray(e)?e.forEach(i=>{t+=this.itemRender(i)}):t+=this.itemRender(e),`<ul class="so-fm-folder-list">${t}</ul>`},render(){return`<div class="so-fm-folder-box-wrapper">${this.diskRender()} <div class="so-fm-folder-wrapper"> ${this.treeRender(this.$parent.folders)}
    </div></div>`}},Ae={render(){return`
        <div class="so-fm-header">
            <div class="so-fm-header-title">
                <a href="https://sokeio.com" class="logo-large" target="_blank">
                    Sokeio FM V1.0
                </a>
                <a href="https://sokeio.com" class="logo-small" target="_blank">
                    SFM1.0
                </a>
            </div>
            <div class="so-fm-header-control">
                <div class="so-fm-header-control-item" so-on:click="$parent.createFolder()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-folder-plus"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">New Folder</div>
                </div>
                <div class="so-fm-header-control-item" so-on:click="$parent.uploadFile()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-upload"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">Upload File</div>
                </div>
               
                <div class="so-fm-header-control-item" so-on:click="$parent.refreshSelected()">
                    <div class="so-fm-header-control-item-icon">
                        <i class="ti ti-refresh"></i>
                    </div>
                    <div class="so-fm-header-control-item-text">Refresh</div>
                </div>
            </div>
        </div>
        `}},Se={render(){return`
                <div class="so-fm-footer">
                        Footer
                </div>
        `}},Ee={state:{title:"abc",name:"abc",current:"",path:"",isHide:!0},register(){this.$parent.$modalNewFolder=this},cancel(){this.isHide=!0,this.reRender()},open(e,t,i,s){this.name=e,this.title=t,this.current=i,this.path=s,this.isHide=!1,this.reRender()},ok(){this.isHide=!0,this.reRender(),this.$parent.changeFolder({name:this.name,current:this.current,path:this.path})},render(){return this.isHide?"<div style='display:none'></div>":`
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 so-text="title">New Folder</h3>
                              </div>
                              <div class="so-fm-modal-body">
                                  <input type="text" so-model="name"  class="form-control">
                              </div>
                              <div class="so-fm-modal-footer pt-1">
                                  <button class="btn btn-danger" so-on:click="cancel()">Cancel</button>
                                  <button class="btn btn-primary">OK</button>
                              </div>
                          </div>
                      </div>
                  </div>
          `}},je={state:{title:"abc",name:"abc",current:"",isHide:!0},register(){this.$parent.$modalUpload=this},cancel(){this.isHide=!0,this.reRender()},open(e,t,i){this.name=e,this.title=t,this.current=i,this.isHide=!1,this.reRender()},render(){return this.isHide?"<div style='display:none'></div>":`
                  <div class="so-fm-modal">
                      <div class="so-fm-modal-dialog">
                          <div class="so-fm-modal-content">
                              <div class="so-fm-modal-header">
                                  <h3 so-text="title">Upload</h3>
                              </div>
                              <div class="so-fm-modal-body">
                                  <input type="file"   style="display:none"/>
                              <div class="so-dropzone">
                              Upload file
                                  </div>
                              </div>
                              <div class="so-fm-modal-footer pt-1">
                                  <button class="btn btn-danger" so-on:click="cancel()">Cancel</button>
                                  <button class="btn btn-primary">OK</button>
                              </div>
                          </div>
                      </div>
                  </div>
          `}},Fe={itemRender(e){return`
           <div class="so-fm-item-box">
                ${e.name}
                              </div>
          `},bodyGridRender(){let e="";return this.$parent.files.forEach(t=>{e+=this.itemRender(t)}),e},render(){return`
            <div class="so-fm-body-grid">
                ${this.bodyGridRender()}
            </div>
          `}},D={components:{"so-fm::header":Ae,"so-fm::folder-box":_e,"so-fm::grid-file":Fe,"so-fm::footer":Se,"so-fm::new-folder":Ee,"so-fm::upload":je},state:{path:"/",files:[],folders:[],disks:[],disk:"public",$modalNewFolder:null,$modalUpload:null},register(){this.refreshSelected(),this.watch("disk",function(e,t){e!=t&&(this.path="/",this.refreshSelected())})},boot(){this.cleanup(function(){})},fmAction(e,t={}){this.$request.post("/platform/file-manager",{action:e,payload:t,disk:this.disk,path:this.path}).then(i=>i.json()).then(i=>{this.files=i.files??this.files,this.folders=i.folders??this.folders,this.disks=i.disks??this.disks,this.disk=i.disk??this.disk,this.path=i.path??this.path,this.path==""&&(this.path="/"),this.reRender()})},createFolder(){this.$modalNewFolder.open("New Folder","New Folder",this.path)},uploadFile(){this.$modalUpload.open("Upload","Upload",this.path)},deleteSelected(){alert("delete selected")},renameSelected(){this.$modalNewFolder.open("New Folder","New Folder",this.path)},refreshSelected(){this.fmAction("list")},openFolder(e){this.path=e,this.reRender(),this.fmAction("list")},render(){return` <div class="so-fm-wrapper">
          [so-fm::header /]
          <div class="so-fm-body">
              <div class="so-fm-folder-box">
              [so-fm::folder-box /]
              </div>
              <div class="so-fm-body-list">
                  [so-fm::grid-file /]
              </div>
          </div>
          [so-fm::footer /]
          [so-fm::new-folder /]
          [so-fm::upload /]
        </div>`}};window.showFileManager=function(e,t="file"){window.showModal("File Manager",{type:t,fnCallback:e,template:D,modalSize:"xxl",skipOverlayClose:!0})};function H(e="",t="",i="",s="",r=""){var u,f,m;s.indexOf("<")===-1&&(s=`<i class="${s}"></i>`),s=s.replace('class="','class="so-modal-icon '),i===void 0&&(i=` <div class="so-modal-header">${s}
                            <h3 class="so-modal-title" so-text="title"></h3>
                        </div>`),$content=i,!t&&!$content?$content=e:$content=`${$conten}<div class="so-modal-body">${e}</div> ${t?`<div class="so-modal-footer">${t}</div>`:""}`;let o='<a class="so-modal-close" so-on:click="this.delete()"></a>',n='so-on:click="this.delete()" so-on:ignore=".so-modal-dialog"',l=c.convertHtmlToElement(e),a=(r==null?void 0:r.skipOverlayClose)??!1,d=(r==null?void 0:r.modalSize)??"lg";return l&&l.getAttribute&&l.querySelector&&(((u=l.querySelector)!=null&&u.call(l,".hide-show-button-close")||l.getAttribute("data-hide-button-close"))&&(o=""),d=l.getAttribute("data-modal-size"),!d&&((f=l.querySelector)!=null&&f.call(l,"[data-modal-size]"))&&(d=l.querySelector("[data-modal-size]").getAttribute("data-modal-size")),a=l.getAttribute("data-skip-overlay-close"),!a&&((m=l.querySelector)!=null&&m.call(l,"[data-skip-overlay-close]"))&&(a=l.querySelector("[data-skip-overlay-close]").getAttribute("data-skip-overlay-close"))),a&&(n=""),d||(d="lg"),`<div class="so-modal so-modal-size-${d}" tabindex="-1" aria-modal="true" ${n} >
                <div class="so-modal-dialog">
                    <div class="so-modal-content card">
                        ${$content}
                    </div>
                    ${o}
                </div>
            </div>`}const Te={state:{html:"",loading:!0},boot(){var t;if(h("modal.components",this.components),this.htmlComponent&&(h("modal.htmlComponent",this.htmlComponent),this.loading=!1,this.html=this.htmlComponent),this.html)return;let e="";this.elTarget&&(this.elTarget.hasAttribute("wire:id")?e=this.elTarget.getAttribute("wire:id"):e=(t=this.elTarget.closest("[wire\\:id]"))==null?void 0:t.getAttribute("wire:id")),this.url&&(this.url.includes("?")?this.url=this.url+"&refId="+e+"&_time="+new Date().getTime():this.url=this.url+"?refId="+e+"&_time="+new Date().getTime(),h("modal.url",this.url),this.$request.get(this.url).then(async i=>{i.ok?this.html=await i.text():this.html=`<div class="so-modal-content-error"><h3>${i.statusText}</h3><button class="btn btn-primary" so-on:click="this.delete()">Close</button></div>`,this.loading=!1,this.reRender()}))},ready(){},render(){return h("modal.render",this.html),this.skipLoading&&this.loading?"<template></template>":H(this.html||'<div class="so-modal-loader" data-hide-close="true"><span class="loader"></span></div>',"","",this.icon,this)}};window.showModal=function(e="",t={url:void 0,template:void 0,templateId:void 0,elTarget:void 0,data:{},callback:()=>{},hide:!1,overlay:!0}){if(t.templateId&&(t.template=document.getElementById(t.templateId).innerHTML.replace("export default","return "),delete t.templateId),t.template){let i=t.template;typeof i=="string"&&(i=new Function(i)()),t={...t,components:{"sokeio::modal::template":i},htmlComponent:"[sokeio::modal::template][/sokeio::modal::template]"},delete t.template}return window.sokeioUI.run(Te,{props:{title:e,overlay:!0,...t}})};const Oe={checkFirst:()=>window.ApexCharts!==void 0,local:{js:["/platform/module/sokeio/apexcharts/dist/apexcharts.min.js"],css:["/platform/module/sokeio/apexcharts/dist/apexcharts.css"]},cdn:{js:["https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"],css:["https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css"]},init:({el:e,directive:t,component:i,cleanup:s})=>{}},Ce={checkFirst:()=>window.bootstrap.Carousel!==void 0,local:{js:[],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{e.$sokeio_carousel=window.bootstrap.Carousel.getOrCreateInstance(e,r)}},Ie={checkFirst:()=>window.Clipboard!==void 0,local:{js:["/platform/module/sokeio/clipboard/dist/clipboard.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{e.$sokeio_clipboard=new window.Clipboard(e,r)}},xe={checkFirst:()=>window.CountUp!==void 0,local:{js:["/platform/module/sokeio/count-up/dist/count-up.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{let o=e.getAttribute("wire:countup");(!o||o=="")&&(o=c.dataGet(i.$wire,e.getAttribute("wire:countup.model"))),e.$sokeio_countup=new window.CountUp(e,o,r),e.$sokeio_countup.error?console.error(e.$sokeio_countup.error):e.$sokeio_countup.start()}};window.shortcodePattern=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/s;function Le(e){let t=e.toLowerCase();return t=t.replace("dd","99"),t=t.replace("d","99"),t=t.replace("mm","99"),t=t.replace("m","99"),t=t.replace("yyyy","9999"),t=t.replace("yy","9999"),t=t.replace("y","9999"),t}function qe(e){let t=e.toLowerCase();return t=t.replace("hh","99"),t=t.replace("h","99"),t=t.replace("ii","99"),t=t.replace("i","99"),t=t.replace("ss","99"),t=t.replace("s","99"),t}function Re(e){return qe(Le(e))}function Ne(e){return e.getAttribute("wire:id")??e.closest("[wire:id]").getAttribute("wire:id")}const Pe={checkFirst:()=>window.flatpickr!==void 0,local:{js:["/platform/module/sokeio/flatpickr/dist/flatpickr.min.js"],css:["/platform/module/sokeio/flatpickr/dist/flatpickr.min.css"]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{let o=e.getAttribute("wire:model"),n=(r==null?void 0:r.dateFormat)??(r!=null&&r.enableTime?"Y/m/d H:i:S":"Y/m/d"),l=Re(n);e.$sokeio_flatpickr=new window.flatpickr(e,{allowInput:!0,allowInvalidPreload:!0,dateFormat:n,...r,onChange:(a,d,u)=>{c.dataSet(i.$wire,o,a)}}),setTimeout(async()=>{Alpine.$data(e).maskFormat=l},10)}},Me={checkFirst:()=>!0,init:({el:e,directive:t,component:i,cleanup:s})=>{let r=e.getAttribute("wire:model"),o=e.getAttribute("wire:get-value"),n=e.getAttribute("wire:get-value-parent")??i.$wire.soRefId;if(!n)return;let l=window.Livewire.find(n);if(!l)return;let a=c.dataGet(l,o);c.dataSet(i.$wire,r,a)}},De={checkFirst:()=>window.Masonry!==void 0,local:{js:["/platform/module/sokeio/masonry/dist/masonry.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{e.$sokeio_masonry=new Masonry(e,r);let o=()=>{e.$sokeio_masonry_timer&&(e.$sokeio_masonry.layout(),clearTimeout(e.$sokeio_masonry_timer),e.$sokeio_masonry_timer=null),e.$sokeio_masonry_timer=setTimeout(()=>{e.$sokeio_masonry.layout(),e.$sokeio_masonry_timer=null},100)};window.addEventListener("resize",o),s(()=>{window.removeEventListener("resize",o)})}},He={checkFirst:()=>!0,local:{js:[],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s})=>{let r={icon:'<i class="ti ti-alarm"></i>',templateId:"",template:"",url:"",elTarget:e,hide:!0},o=e.getAttribute("wire:modal.title");e.hasAttribute("wire:modal.url")&&(r.url=e.getAttribute("wire:modal.url")),e.hasAttribute("wire:modal.size")&&(r.size=e.getAttribute("wire:modal.size")),e.hasAttribute("wire:modal.icon")&&(r.icon=e.getAttribute("wire:modal.icon")),e.hasAttribute("wire:modal.template-id")&&(r.templateId=e.getAttribute("wire:modal.template-id")),e.hasAttribute("wire:modal.template")&&(r.template=e.getAttribute("wire:modal.template"));let n=!1,l=!1,a=function(){return window.showModal(o,r).cleanup(()=>{u=void 0,e.modalInstance=void 0,l=!1})},d=function(){l=!0,n=!1,e.modalInstance||(e.modalInstance=a()),e.modalInstance.show()},u,f,m=function(){f&&(clearTimeout(f),f=void 0),!n&&(n=!0,!u&&(u=setTimeout(()=>{e.modalInstance=a()},40)))},p=function(){f=setTimeout(()=>{f=void 0,n=!1,u&&(clearTimeout(u),u=void 0,e.modalInstance&&!l&&(e.modalInstance=void 0))},2e3)};e.addEventListener("click",d),e.addEventListener("mouseover",m),e.addEventListener("mouseleave",p),s(()=>{e.removeEventListener("click",d),e.removeEventListener("hover",m),e.removeEventListener("leave",p)})}},Ue={checkFirst:()=>window.QRCode!==void 0,local:{js:["/platform/module/sokeio/easyqrcodejs/dist/easy.qrcode.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{if(e.$sokeio_qrcode)return;let o={titleFont:"normal normal bold 18px Arial",titleColor:"#004284",titleBackgroundColor:"#ccc",titleHeight:40,titleTop:25,...r};(o.text==null||o.text=="")&&e.getAttribute("wire:qrcode")!=""&&(o.text=i.$wire[e.getAttribute("wire:qrcode")]),e.hasAttribute("wire:qrcode.text")&&(o.text=e.getAttribute("wire:qrcode.text")),e.hasAttribute("wire:qrcode.title")&&(o.title=e.getAttribute("wire:qrcode.title")),e.$sokeio_qrcode=new window.QRCode(e,o)}},Ke={checkFirst:()=>window.Sortable!==void 0,local:{js:["/platform/module/sokeio/sortable/sortable.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{e.$sokeio_sortable||(e.$sokeio_sortable=window.Sortable.create(e,{animation:150,...r,draggable:"[wire\\:sortable\\.item]",handle:e.querySelector("[wire\\:sortable\\.handle]")?"[wire\\:sortable\\.handle]":null,sort:!0,dataIdAttr:"data-sortable-id",group:{animation:150,...(r==null?void 0:r.group)??{},name:e.getAttribute("wire:sortable"),pull:!1,put:!1},store:{...(r==null?void 0:r.store)??{},set:function(o){var n;try{let l=o.toArray().map((a,d)=>({order:d+1,value:a}));t.expression?i.$wire.call(t.expression,l):(n=Alpine.$data(e).onSortable)==null||n.call(e,l)}catch(l){console.error(l)}}}}))}},Be={checkFirst:()=>window.Sortable!==void 0,local:{js:["/platform/module/sokeio/sortable/sortable.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{t.modifiers.includes("item-group")&&(e.$sokeio_sortable_group=window.Sortable.create(e,{animation:150,...r,draggable:"[wire\\:sortable-group\\.item]",handle:e.querySelector("[wire\\:sortable-group\\.handle]")?"[wire\\:sortable-group\\.handle]":null,sort:!0,dataIdAttr:"wire:sortable-group.item",group:{...(r==null?void 0:r.group)??{},name:e.closest("[wire\\:sortable-group]").getAttribute("wire:sortable-group"),pull:!0,put:!0},onSort:()=>{let o=e.closest("[wire\\:sortable-group]");if(!o)return;let n=Array.from(o.querySelectorAll("[wire\\:sortable-group\\.item-group]")).map((l,a)=>({order:a+1,value:l.getAttribute("wire:sortable-group.item-group"),items:l.$sokeio_sortable_group.toArray().map((d,u)=>({order:u+1,value:d}))}));i.$wire.call(o.getAttribute("wire:sortable-group"),n)}}))}},Ge={checkFirst:()=>window.tinymce!==void 0,local:{js:["/platform/module/sokeio/tinymce/tinymce.min.js"],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{if(e.$sokeio_tinymce)return;e.hasAttribute("wire:tinymce")&&(r=new Function(`return ${e.getAttribute("wire:tinymce")};`)()),s(()=>{e.$sokeio_tinymce&&e.$sokeio_tinymce.remove&&(e.$sokeio_tinymce.remove(),e.$sokeio_tinymce=null)});let o=e.getAttribute("wire:tinymce-model")??e.getAttribute("wire:model"),n={};e.hasAttribute("wire:tinymce-skip")&&(n={}),e.$sokeio_tinymce=window.tinymce.init({...n??{},...r,promotion:!1,target:e,setup:function(l){l.on("init",function(){l.setContent(e.value),l.undoManager.dispatchChange()}),l.on("input",function(a){c.dataSet(i.$wire,o,l.getContent())}),l.on("ExecCommand",a=>{["mceFocus"].includes(a.command)||c.dataSet(i.$wire,o,l.getContent())})},file_picker_callback:function(l,a,d){window.showFileManager(function(u){l(u[0].url)},{type:"image",value:a})}})}},Ve={checkFirst:()=>window.TomSelect!==void 0,local:{js:["/platform/module/sokeio/tom-select/tom-select.min.js"],css:["/platform/module/sokeio/tom-select/tom-select.css"]},cdn:{js:["https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"],css:["https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css"]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{if(e.$sokeio_tomselect)return;let o=e.getAttribute("wire:tom-select.remote-action"),n=e.getAttribute("wire:tom-select.base64"),l=e.getAttribute("wire:tom-select.options");l&&(l=JSON.parse(l)??{}),n?(n=JSON.parse(window.atob(n))??{},n={...n,...r}):n={...r},o&&(n={...n,load:function(a,d){i.$wire.callActionUI(o,a).then(function(u){d(u)})}}),l&&(n={...n,options:l}),e.$sokeio_tomselect=new TomSelect(e,n)}},ze={checkFirst:()=>window.Plyr!==void 0,local:{js:["/platform/module/sokeio/plyr/plyr.min.js"],css:["/platform/module/sokeio/plyr/plyr.css"]},cdn:{js:["https://cdn.jsdelivr.net/npm/plyr@latest/dist/plyr.min.js"],css:["https://cdn.jsdelivr.net/npm/plyr@latest/dist/plyr.css"]},init:({el:e,directive:t,component:i,cleanup:s,options:r})=>{e.$sokeio_plyr=new window.Plyr(e,r)}},We={checkFirst:()=>!0,local:{js:[],css:[]},cdn:{js:[],css:[]},init:({el:e,directive:t,component:i,cleanup:s})=>{window.sokeioUI.run(D,{selector:e})}},b={apexcharts:Oe,"get-value":Me,qrcode:Ue,carousel:Ce,clipboard:Ie,countup:xe,flatpickr:Pe,masonry:De,sortable:Ke,"sortable-group":Be,tinymce:Ge,modal:He,"tom-select":Ve,plyr:ze,media:We};let U=(e,t,i)=>{if(!e.checkFirst||e.checkFirst()){e.init(t);return}i>50||setTimeout(()=>{U(e,t,i+1)},50)};function Je(e){Object.keys(b).forEach(function(t){let i=b[t];e.directive(t,s=>{var o,n,l,a,d,u,f,m,p,E,j,F;if(s.length===0||s.directive.modifiers.length>0||s.el[`$$sokeio_${t.replace(/\./g,"_").replace(/\//g,"_").replace(/-/g,"_")}`])return;let r={};(r=s.el.getAttribute(`wire:${t}.options`))&&(r=new Function(`return ${r};`)()),i.checkFirst&&!i.checkFirst()&&((o=i==null?void 0:i.cdn)!=null&&o.js&&Array.isArray((n=i==null?void 0:i.cdn)==null?void 0:n.js)&&((l=i==null?void 0:i.cdn)==null?void 0:l.js.length)>0?c.addScriptToWindow((a=i==null?void 0:i.cdn)==null?void 0:a.js):(d=i==null?void 0:i.local)!=null&&d.js&&c.addScriptToWindow((u=i==null?void 0:i.local)==null?void 0:u.js),(f=i==null?void 0:i.cdn)!=null&&f.css&&Array.isArray((m=i==null?void 0:i.cdn)==null?void 0:m.css)&&((p=i==null?void 0:i.cdn)==null?void 0:p.css.length)>0?c.addStyleToWindow((E=i==null?void 0:i.cdn)==null?void 0:E.css):(j=i==null?void 0:i.local)!=null&&j.css&&c.addStyleToWindow((F=i==null?void 0:i.local)==null?void 0:F.css)),U(i,{...s,options:r},0)})})}const Xe={directive:b,install:Je};function Qe(e){setTimeout(()=>{var t,i,s,r;(r=(s=(i=(t=document.querySelector(`[wire\\:id="${e.wireId}"]`))==null?void 0:t.closest("[data-sokeio-id]"))==null?void 0:i._sokeio)==null?void 0:s.delete)==null||r.call(s)})}const Ye={state:{html:"",loading:!0},ready(){},render(){return H("<div style='min-height:100px' so-html='message'></div>1","",this.icon)}};function Ze(e){window.sokeioUI.run(Ye,{props:{...e}})}function et(e){setTimeout(()=>{var t;(t=window.Livewire.find(e.wireTargetId))==null||t.soLoadData()})}function tt(e){setTimeout(()=>{var t,i;(i=(t=window.Livewire.find(e.wireTargetId))==null?void 0:t.$parent)==null||i.soLoadData()})}const k={sokeio_message:Ze,sokeio_close:Qe,sokeio_refresh:et,sokeio_refresh_parent:tt};function it(e){e.type&&k[e.type]&&k[e.type](e.payload)}const st={dispatch:k,install:it};document.addEventListener("livewire:init",()=>{Xe.install(window.Livewire)});document.addEventListener("sokeio::dispatch",e=>{let t=e.detail[0];st.install({type:t.type,payload:{...t.payload,wireId:Ne(e.target)}})});document.addEventListener("alpine:init",()=>{Alpine.data("sokeioField",e=>({get FieldValue(){return c.dataGet(this.$wire,e)},set FieldValue(t){c.dataSet(this.$wire,e,t)}}))});document.addEventListener("alpine:init",()=>{Alpine.data("sokeioTable",()=>({searchExtra:!1,fieldSort:"",typeSort:"",statusCheckAll:!1,get dataSelecteds(){return this.$wire.dataSelecteds??[]},sortField(e){let t=e.getAttribute("data-field");t!=this.fieldSort?(this.fieldSort=t,this.typeSort="asc"):(this.typeSort=this.typeSort==="asc"?"desc":"asc",this.typeSort==="asc"&&(this.fieldSort="",this.typeSort=""));let i=this.$el.getAttribute("data-sokeio-table-order-by");this.$wire.callActionUI(i,{field:this.fieldSort,type:this.typeSort})},tableInit(){this.$watch("$wire.dataSelecteds",()=>{let e=[...this.$el.querySelectorAll(".sokeio-checkbox-one")].map(t=>t.value);this.statusCheckAll=e.length===e.filter(t=>this.$wire.dataSelecteds.includes(t)).length}),Livewire.hook("request",({component:e,commit:t,respond:i,succeed:s,fail:r})=>{s(({snapshot:o,effect:n})=>{setTimeout(()=>{let l=[...this.$el.querySelectorAll(".sokeio-checkbox-one")].map(a=>a.value);this.statusCheckAll=l.length===l.filter(a=>this.$wire.dataSelecteds.includes(a)).length},0)})})},checkboxAll(e){let t=e.target.checked,i=[...this.$el.closest("table").querySelectorAll(".sokeio-checkbox-one")].map(s=>s.value);this.$wire.dataSelecteds===void 0&&(this.$wire.dataSelecteds=[]),t?this.$wire.dataSelecteds=this.$wire.dataSelecteds.concat(i):this.$wire.dataSelecteds=this.$wire.dataSelecteds.filter(s=>!i.includes(s))}}))});document.addEventListener("alpine:init",()=>{Alpine.data("sokeioGlobal",()=>({isAuth:void 0,init(){window.sokeioGlobal=this,this.$watch("$wire.isAuth",e=>{this.sendAuth()}),setTimeout(()=>{this.sendAuth()},100)},sendAuth(){this.$wire.isAuth!=this.isAuth&&(this.isAuth=this.$wire.isAuth,document.dispatchEvent(new CustomEvent("sokeio::auth",{detail:{isAuth:this.$wire.isAuth,authUser:this.$wire.authUser}})))}}))});window.sokeioGlobal=null;document.addEventListener("alpine:init",()=>{Alpine.data("sokeioBody",()=>({isAuth:!1,authUser:null,themeDark:!1,init(){this.$watch("themeDark",e=>{this.$el.setAttribute("data-bs-theme",e?"dark":"light")}),document.removeEventListener("sokeio::auth",this.sendAuth.bind(this)),document.addEventListener("sokeio::auth",this.sendAuth.bind(this))},sendAuth({detail:{isAuth:e,authUser:t}}){this.isAuth=e,this.authUser=t,console.log(t)},toggleTheme(){this.themeDark=!this.themeDark}}))});window.sokeioGlobal=null;
