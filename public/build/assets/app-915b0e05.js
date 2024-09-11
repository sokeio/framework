var u=Object.defineProperty;var f=(o,t,e)=>t in o?u(o,t,{enumerable:!0,configurable:!0,writable:!0,value:e}):o[t]=e;var i=(o,t,e)=>(f(o,typeof t!="symbol"?t+"":t,e),e);class v{constructor(){i(this,"listeners",{});i(this,"state",{});i(this,"props",{});return this.state=this.state??{},this.props=this.props??{},new Proxy(this,{ownKeys(t){return Object.keys(t.state).concat(Object.keys(t.props))},set:(t,e,n)=>{if(e in t.state){const s=t.state[e];return t.state[e]=n,setTimeout(()=>{t.applyProperty(e,n,s)},1),!0}if(e in t.props){const s=t.props[e];return t.props[e]=n,setTimeout(()=>{t.applyProperty(e,n,s)},1),!0}return t[e]=n,!0},get:(t,e)=>e in t.state?t.state[e]:e in t.props?t.props[e]:t[e]})}watch(t,e){this.listeners[t]||(this.listeners[t]=[]),this.listeners[t].push(e)}applyProperty(t,e,n){this.listeners[t]&&this.listeners[t].forEach(s=>s(e,n))}onChange(t,e){this.watch(t,e)}}function b(o){const t=/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)(\](.*?)\[\/\1\]|\s*\/\])/gs;return[...o.matchAll(t)].map(e=>{const[n,s,r,,l]=e;return{component:n,tag:s,attrs:r,content:l}})}const w="############$$$$$$$$############";function g(...o){console.log(...o)}function T(o,t,e){return new Function("$event",`
 return  () => {
     ${o}
     console.log($event);
  }
     `).apply(e,[t])()}const a={getComponentsFromText:b,LOG:g,tagSplit:w,runFunction:T};class c{constructor(t){i(this,"selector","*");this.component=t}run(){let t=this.selector;t!="*"&&(t="["+this.selector+"]"),[...this.component.el.querySelectorAll(t)].filter(this.filter.bind(this)).forEach(this.applyItem.bind(this))}filter(t){return!0}applyItem(t){}}class x extends c{constructor(){super(...arguments);i(this,"selector","so-html")}applyItem(e){let n=e.getAttribute(this.selector);n&&(this.component.watch(n,()=>{e.innerHTML=this.component[n]}),e.innerHTML=this.component[n])}}class C extends c{constructor(){super(...arguments);i(this,"selector","so-model")}applyItem(e){let n=e.getAttribute(this.selector);n&&(this.component.watch(n,()=>{e.value=this.component[n]}),e.value=this.component[n],e.addEventListener("input",s=>{this.component[n]=s.target.value}))}}class k extends c{constructor(){super(...arguments);i(this,"selector","so-text")}applyItem(e){let n=e.getAttribute(this.selector);n&&(this.component.watch(n,()=>{e.innerText=this.component[n]}),e.innerText=this.component[n])}}class $ extends c{constructor(t){super(t)}filter(t){return Array.from(t.attributes).some(e=>e.name.startsWith("so-on:"))}applyItem(t){Array.from(t.attributes).forEach(e=>{if(e.name.startsWith("so-on:")){let n=e.name.replace("so-on:",""),s=e.value;t.addEventListener(n,r=>{a.runFunction(s,r,this.component)})}})}}class I{constructor(){i(this,"chain",[k,x,C,$])}run(t){this.chain.forEach(e=>{new e(t).run()})}}const A=new I;class h extends v{constructor(){super(...arguments);i(this,"number",new Date().getTime());i(this,"manager");i(this,"parent");i(this,"children",[]);i(this,"el");i(this,"$id")}nextId(){return++this.number}getId(){return this.$id||(this.$id=this.nextId()),this.$id}applyComponent(e){let n=document.createElement("template");e=e.trim();let r=a.getComponentsFromText(e).map(l=>(e=e.split(l.component).join(a.tagSplit),this.manager.getComponentByName(l.tag,l.attrs,this)));if(r.length){let l="";e.split(a.tagSplit).forEach((m,p)=>{l+=m,r[p]&&(l+='<span id="sokeio-component-'+r[p].getId()+'">'+r[p].getId()+"</span>")}),e=l,this.children=r}return n.innerHTML=e,n.content.firstChild}renderComponent(){let e=this.render();if(this.el||(this.el=document.createElement("div")),e&&(this.el.innerHTML=e),this.el.children.length){if(e&&this.el.parentNode){let s=this.el.children[0];this.el.parentNode.insertBefore(s,this.el),this.el.remove(),this.el=s}let n=this.applyComponent(this.el.innerHTML);n&&(this.el=n),this.el.setAttribute("data-sokeio-id",this.getId()),this.el.__sokeio=this,A.run(this),this.children.forEach(s=>{if(!s)return;s.parent=this,s.renderComponent();let r=this.el.querySelector("#sokeio-component-"+s.getId());r&&(r.parentNode.insertBefore(s.el,r),r.remove())})}else this.el.innerHTML||(this.el.innerHTML="[NOT ONE COMPONENT]")}render(){return""}boot(){}ready(){}}class M extends h{render(){return`
        <div>
            <h1>hello world</h1>
            [sokeio::demo2 /]
        </div>
        `}}class L extends h{constructor(){super(...arguments);i(this,"state",{tesst:"<h1>hello world</h1>test"})}render(){return`
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
        `}}class d extends h{constructor(){super(...arguments);i(this,"components",{"sokeio::demo":M,"sokeio::demo2":L})}registerComponent(e,n){this.components[e]=n}getComponentByName(e,n){if(!e||!this.components[e])return console.warn({name:e,$attrs:n}),null;let s=new this.components[e];return s.manager=this,s.boot&&s.boot(),s}render(){return`
    <div>
    Tesst [sokeio::demo /]
    </div>
    `}run(e=null){e||(e=document.body),typeof e=="string"&&(e=document.querySelector(e)),this.manager=this,this.el=null,this.renderComponent(),e.appendChild(this.el)}}window.sokeio={Application:d,Component:h};window.sokeioApp=new d;
