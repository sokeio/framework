var o=Object.defineProperty;var c=(i,e,s)=>e in i?o(i,e,{enumerable:!0,configurable:!0,writable:!0,value:s}):i[e]=s;var t=(i,e,s)=>(c(i,typeof e!="symbol"?e+"":e,s),s);class u{constructor(){return this.state=this.state??{},this.props=this.props??{},this.listeners={},new Proxy(this,{set:(e,s,n)=>{if(s in e.state){const r=e.state[s];return e.state[s]=n,e.applyProperty(s,n,r),!0}if(s in e.props){const r=e.props[s];return e.props[s]=n,e.applyProperty(s,n,r),!0}return e[s]=n,!1},get:(e,s)=>s in e.state?e.state[s]:s in e.props?e.props[s]:e[s]})}watch(e,s){this.listeners[e]||(this.listeners[e]=[]),this.listeners[e].push(s)}applyProperty(e,s,n){this.listeners[e]&&this.listeners[e].forEach(r=>r(s,n))}onChange(e,s){this.watch(e,s)}}class l extends u{constructor(){super(...arguments);t(this,"manager");t(this,"parent");t(this,"children",[]);t(this,"el")}renderComponent(){let s=this.render();if(this.el.innerHTML=s,this.el.children.length!==1){let n=this.el.children[0];this.el.parentNode.insertBefore(n,this.el),this.el.remove(),this.el=n}else this.el.innerHTML="[NOT ONE COMPONENT]"}render(){return""}run(){}ready(){}}class h extends l{constructor(){var e=(...args)=>{super(...args);t(this,"number",1e3);t(this,"number",new Date().getTime());t(this,"components",{})};return super()}nextId(){return++this.number}registerComponent(s,n){this.components[s]=n}render(){return""}}window.sokeio={Application:h,Component:l};window.sokeioApp=new h;
