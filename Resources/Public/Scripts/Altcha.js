"use strict";(()=>{var ul=Object.defineProperty;var Ha=e=>{throw TypeError(e)};var fl=(e,t,n)=>t in e?ul(e,t,{enumerable:!0,configurable:!0,writable:!0,value:n}):e[t]=n;var A=(e,t,n)=>fl(e,typeof t!="symbol"?t+"":t,n),Mr=(e,t,n)=>t.has(e)||Ha("Cannot "+n);var d=(e,t,n)=>(Mr(e,t,"read from private field"),n?n.call(e):t.get(e)),O=(e,t,n)=>t.has(e)?Ha("Cannot add the same private member more than once"):t instanceof WeakSet?t.add(e):t.set(e,n),I=(e,t,n,r)=>(Mr(e,t,"write to private field"),r?r.call(e,n):t.set(e,n),n),re=(e,t,n)=>(Mr(e,t,"access private method"),n);var ho=Array.isArray,dl=Array.prototype.indexOf,kn=Array.prototype.includes,hl=Array.from,ur=Object.keys,Fn=Object.defineProperty,fn=Object.getOwnPropertyDescriptor,vl=Object.getOwnPropertyDescriptors,pl=Object.prototype,gl=Array.prototype,vo=Object.getPrototypeOf,Ka=Object.isExtensible,Pt=()=>{};function bl(e){for(var t=0;t<e.length;t++)e[t]()}function po(){var e,t,n=new Promise((r,a)=>{e=r,t=a});return{promise:n,resolve:e,reject:t}}var be=2,xn=4,gr=8,fa=1<<24,jt=16,gt=32,Mt=64,zr=128,Ye=512,ge=1024,_e=2048,nt=4096,pt=8192,tt=16384,zt=32768,Br=1<<25,Nt=65536,Ya=1<<17,ml=1<<18,on=1<<19,yl=1<<20,nn=65536,Hr=1<<21,da=1<<22,Lt=1<<23,dn=Symbol("$state"),wl=Symbol("legacy props"),_l=Symbol(""),wt=new class extends Error{constructor(){super(...arguments);A(this,"name","StaleReactionError");A(this,"message","The reaction that called `getAbortSignal()` was re-run or destroyed")}},Kn=!!globalThis.document?.contentType&&globalThis.document.contentType.includes("xml"),Yn=3,Gn=8;function go(e){return e===this.v}function bo(e,t){return e!=e?t==t:e!==t||e!==null&&typeof e=="object"||typeof e=="function"}function kl(e){return!bo(e,this.v)}function xl(e){throw new Error("https://svelte.dev/e/lifecycle_outside_component")}function El(){throw new Error("https://svelte.dev/e/async_derived_orphan")}function Cl(e){throw new Error("https://svelte.dev/e/effect_in_teardown")}function Sl(){throw new Error("https://svelte.dev/e/effect_in_unowned_derived")}function $l(e){throw new Error("https://svelte.dev/e/effect_orphan")}function Tl(){throw new Error("https://svelte.dev/e/effect_update_depth_exceeded")}function Al(){throw new Error("https://svelte.dev/e/hydration_failed")}function Rl(){throw new Error("https://svelte.dev/e/state_descriptors_fixed")}function Il(){throw new Error("https://svelte.dev/e/state_prototype_fixed")}function Ol(){throw new Error("https://svelte.dev/e/state_unsafe_mutation")}function Pl(){throw new Error("https://svelte.dev/e/svelte_boundary_reset_onerror")}var Ll=1,Dl=2,ha="[",mo="[!",Ga="[?",yo="]",rn={},pe=Symbol(),wo="http://www.w3.org/1999/xhtml",Ml="http://www.w3.org/2000/svg",Nl="http://www.w3.org/1998/Math/MathML",Ul="@attach",Re=null;function En(e){Re=e}function St(e,t=!1,n){Re={p:Re,i:!1,c:null,e:null,s:e,x:null,r:M,l:null}}function $t(e){var t=Re,n=t.e;if(n!==null){t.e=null;for(var r of n)ui(r)}return e!==void 0&&(t.x=e),t.i=!0,Re=t.p,e??{}}function _o(){return!0}var Gt=[];function ko(){var e=Gt;Gt=[],bl(e)}function Ct(e){if(Gt.length===0&&!Mn){var t=Gt;queueMicrotask(()=>{t===Gt&&ko()})}Gt.push(e)}function Fl(){for(;Gt.length>0;)ko()}function qn(e){console.warn("https://svelte.dev/e/hydration_mismatch")}function Vl(){console.warn("https://svelte.dev/e/select_multiple_invalid_value")}function jl(){console.warn("https://svelte.dev/e/svelte_boundary_reset_noop")}var L=!1;function vt(e){L=e}var V;function Ce(e){if(e===null)throw qn(),rn;return V=e}function an(){return Ce(mt(V))}function X(e){if(L){if(mt(V)!==null)throw qn(),rn;V=e}}function va(e=1){if(L){for(var t=e,n=V;t--;)n=mt(n);V=n}}function pa(e=!0){for(var t=0,n=V;;){if(n.nodeType===Gn){var r=n.data;if(r===yo){if(t===0)return n;t-=1}else(r===ha||r===mo||r[0]==="["&&!isNaN(Number(r.slice(1))))&&(t+=1)}var a=mt(n);e&&n.remove(),n=a}}function xo(e){if(!e||e.nodeType!==Gn)throw qn(),rn;return e.data}function Et(e){if(typeof e!="object"||e===null||dn in e)return e;let t=vo(e);if(t!==pl&&t!==gl)return e;var n=new Map,r=ho(e),a=N(0),o=tn,l=s=>{if(tn===o)return s();var c=P,u=tn;qe(null),lo(o);var h=s();return qe(c),lo(u),h};return r&&n.set("length",N(e.length)),new Proxy(e,{defineProperty(s,c,u){(!("value"in u)||u.configurable===!1||u.enumerable===!1||u.writable===!1)&&Rl();var h=n.get(c);return h===void 0?l(()=>{var g=N(u.value);return n.set(c,g),g}):k(h,u.value,!0),!0},deleteProperty(s,c){var u=n.get(c);if(u===void 0){if(c in s){let h=l(()=>N(pe));n.set(c,h),Nn(a)}}else k(u,pe),Nn(a);return!0},get(s,c,u){if(c===dn)return e;var h=n.get(c),g=c in s;if(h===void 0&&(!g||fn(s,c)?.writable)&&(h=l(()=>{var y=Et(g?s[c]:pe),b=N(y);return b}),n.set(c,h)),h!==void 0){var v=i(h);return v===pe?void 0:v}return Reflect.get(s,c,u)},getOwnPropertyDescriptor(s,c){var u=Reflect.getOwnPropertyDescriptor(s,c);if(u&&"value"in u){var h=n.get(c);h&&(u.value=i(h))}else if(u===void 0){var g=n.get(c),v=g?.v;if(g!==void 0&&v!==pe)return{enumerable:!0,configurable:!0,value:v,writable:!0}}return u},has(s,c){if(c===dn)return!0;var u=n.get(c),h=u!==void 0&&u.v!==pe||Reflect.has(s,c);if(u!==void 0||M!==null&&(!h||fn(s,c)?.writable)){u===void 0&&(u=l(()=>{var v=h?Et(s[c]):pe,y=N(v);return y}),n.set(c,u));var g=i(u);if(g===pe)return!1}return h},set(s,c,u,h){var g=n.get(c),v=c in s;if(r&&c==="length")for(var y=u;y<g.v;y+=1){var b=n.get(y+"");b!==void 0?k(b,pe):y in s&&(b=l(()=>N(pe)),n.set(y+"",b))}if(g===void 0)(!v||fn(s,c)?.writable)&&(g=l(()=>N(void 0)),k(g,Et(u)),n.set(c,g));else{v=g.v!==pe;var $=l(()=>Et(u));k(g,$)}var x=Reflect.getOwnPropertyDescriptor(s,c);if(x?.set&&x.set.call(h,u),!v){if(r&&typeof c=="string"){var D=n.get("length"),fe=Number(c);Number.isInteger(fe)&&fe>=D.v&&k(D,fe+1)}Nn(a)}return!0},ownKeys(s){i(a);var c=Reflect.ownKeys(s).filter(g=>{var v=n.get(g);return v===void 0||v.v!==pe});for(var[u,h]of n)h.v!==pe&&!(u in s)&&c.push(u);return c},setPrototypeOf(){Il()}})}function qa(e){try{if(e!==null&&typeof e=="object"&&dn in e)return e[dn]}catch{}return e}function zl(e,t){return Object.is(qa(e),qa(t))}var en,Kr,Eo,Co,So;function Yr(){if(en===void 0){en=window,Kr=document,Eo=/Firefox/.test(navigator.userAgent);var e=Element.prototype,t=Node.prototype,n=Text.prototype;Co=fn(t,"firstChild").get,So=fn(t,"nextSibling").get,Ka(e)&&(e.__click=void 0,e.__className=void 0,e.__attributes=null,e.__style=void 0,e.__e=void 0),Ka(n)&&(n.__t=void 0)}}function rt(e=""){return document.createTextNode(e)}function Ue(e){return Co.call(e)}function mt(e){return So.call(e)}function ae(e,t){if(!L)return Ue(e);var n=Ue(V);if(n===null)n=V.appendChild(rt());else if(t&&n.nodeType!==Yn){var r=rt();return n?.before(r),Ce(r),r}return t&&br(n),Ce(n),n}function cn(e,t=!1){if(!L){var n=Ue(e);return n instanceof Comment&&n.data===""?mt(n):n}if(t){if(V?.nodeType!==Yn){var r=rt();return V?.before(r),Ce(r),r}br(V)}return V}function Q(e,t=1,n=!1){let r=L?V:e;for(var a;t--;)a=r,r=mt(r);if(!L)return r;if(n){if(r?.nodeType!==Yn){var o=rt();return r===null?a?.after(o):r.before(o),Ce(o),o}br(r)}return Ce(r),r}function Bl(e){e.textContent=""}function Hl(){return!1}function ga(e,t,n){return document.createElementNS(t??wo,e,void 0)}function br(e){if(e.nodeValue.length<65536)return;let t=e.nextSibling;for(;t!==null&&t.nodeType===Yn;)t.remove(),e.nodeValue+=t.nodeValue,t=e.nextSibling}function $o(e){var t=M;if(t===null)return P.f|=Lt,e;if((t.f&zt)===0&&(t.f&xn)===0)throw e;Ot(e,t)}function Ot(e,t){for(;t!==null;){if((t.f&zr)!==0){if((t.f&zt)===0)throw e;try{t.b.error(e);return}catch(n){e=n}}t=t.parent}throw e}var Kl=-7169;function se(e,t){e.f=e.f&Kl|t}function ba(e){(e.f&Ye)!==0||e.deps===null?se(e,ge):se(e,nt)}function To(e){if(e!==null)for(let t of e)(t.f&be)===0||(t.f&nn)===0||(t.f^=nn,To(t.deps))}function Ao(e,t,n){(e.f&_e)!==0?t.add(e):(e.f&nt)!==0&&n.add(e),To(e.deps),se(e,ge)}function Yl(e){return e.endsWith("capture")&&e!=="gotpointercapture"&&e!=="lostpointercapture"}var Gl=["beforeinput","click","change","dblclick","contextmenu","focusin","focusout","input","keydown","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","pointerdown","pointermove","pointerout","pointerover","pointerup","touchend","touchmove","touchstart"];function ql(e){return Gl.includes(e)}var Wl={formnovalidate:"formNoValidate",ismap:"isMap",nomodule:"noModule",playsinline:"playsInline",readonly:"readOnly",defaultvalue:"defaultValue",defaultchecked:"defaultChecked",srcobject:"srcObject",novalidate:"noValidate",allowfullscreen:"allowFullscreen",disablepictureinpicture:"disablePictureInPicture",disableremoteplayback:"disableRemotePlayback"};function Zl(e){return e=e.toLowerCase(),Wl[e]??e}var Jl=["touchstart","touchmove"];function Xl(e){return Jl.includes(e)}function Ql(e,t){if(t){let n=document.body;e.autofocus=!0,Ct(()=>{document.activeElement===n&&e.focus()})}}var Wa=!1;function Ro(){Wa||(Wa=!0,document.addEventListener("reset",e=>{Promise.resolve().then(()=>{if(!e.defaultPrevented)for(let t of e.target.elements)t.__on_r?.()})},{capture:!0}))}function mr(e){var t=P,n=M;qe(null),bt(null);try{return e()}finally{qe(t),bt(n)}}function es(e,t,n,r=n){e.addEventListener(t,()=>mr(n));let a=e.__on_r;a?e.__on_r=()=>{a(),r(!0)}:e.__on_r=()=>r(!0),Ro()}var qt=Symbol("events"),Io=new Set,Gr=new Set;function Oo(e,t,n,r={}){function a(o){if(r.capture||qr.call(t,o),!o.cancelBubble)return mr(()=>n?.call(this,o))}return e.startsWith("pointer")||e.startsWith("touch")||e==="wheel"?Ct(()=>{t.addEventListener(e,a,r)}):t.addEventListener(e,a,r),a}function ve(e,t,n,r,a){var o={capture:r,passive:a},l=Oo(e,t,n,o);(t===document.body||t===window||t===document||t instanceof HTMLMediaElement)&&xr(()=>{t.removeEventListener(e,l,o)})}function yr(e,t,n){(t[qt]??(t[qt]={}))[e]=n}function wr(e){for(var t=0;t<e.length;t++)Io.add(e[t]);for(var n of Gr)n(e)}var Za=null;function qr(e){var t=this,n=t.ownerDocument,r=e.type,a=e.composedPath?.()||[],o=a[0]||e.target;Za=e;var l=0,s=Za===e&&e[qt];if(s){var c=a.indexOf(s);if(c!==-1&&(t===document||t===window)){e[qt]=t;return}var u=a.indexOf(t);if(u===-1)return;c<=u&&(l=c)}if(o=a[l]||e.target,o!==t){Fn(e,"currentTarget",{configurable:!0,get(){return o||n}});var h=P,g=M;qe(null),bt(null);try{for(var v,y=[];o!==null;){var b=o.assignedSlot||o.parentNode||o.host||null;try{var $=o[qt]?.[r];$!=null&&(!o.disabled||e.target===o)&&$.call(o,e)}catch(x){v?y.push(x):v=x}if(e.cancelBubble||b===t||b===null)break;o=b}if(v){for(let x of y)queueMicrotask(()=>{throw x});throw v}}finally{e[qt]=t,delete e.currentTarget,qe(h),bt(g)}}}var ts=globalThis?.window?.trustedTypes&&globalThis.window.trustedTypes.createPolicy("svelte-trusted-html",{createHTML:e=>e});function ns(e){return ts?.createHTML(e)??e}function Po(e){var t=ga("template");return t.innerHTML=ns(e.replaceAll("<!>","<!---->")),t.content}function Fe(e,t){var n=M;n.nodes===null&&(n.nodes={start:e,end:t,a:null,t:null})}function te(e,t){var n=(t&Ll)!==0,r=(t&Dl)!==0,a,o=!e.startsWith("<!>");return()=>{if(L)return Fe(V,null),V;a===void 0&&(a=Po(o?e:"<!>"+e),n||(a=Ue(a)));var l=r||Eo?document.importNode(a,!0):a.cloneNode(!0);if(n){var s=Ue(l),c=l.lastChild;Fe(s,c)}else Fe(l,l);return l}}function rs(e,t,n="svg"){var r=!e.startsWith("<!>"),a=`<${n}>${r?e:"<!>"+e}</${n}>`,o;return()=>{if(L)return Fe(V,null),V;if(!o){var l=Po(a),s=Ue(l);o=Ue(s)}var c=o.cloneNode(!0);return Fe(c,c),c}}function ma(e,t){return rs(e,t,"svg")}function rr(e=""){if(!L){var t=rt(e+"");return Fe(t,t),t}var n=V;return n.nodeType!==Yn?(n.before(n=rt()),Ce(n)):br(n),Fe(n,n),n}function Ja(){if(L)return Fe(V,null),V;var e=document.createDocumentFragment(),t=document.createComment(""),n=rt();return e.append(t,n),Fe(t,n),e}function F(e,t){if(L){var n=M;((n.f&zt)===0||n.nodes.end===null)&&(n.nodes.end=V),an();return}e!==null&&e.before(t)}function as(e){let t=0,n=Wn(0),r;return()=>{Ca()&&(i(n),Er(()=>(t===0&&(r=Jn(()=>e(()=>Nn(n)))),t+=1,()=>{Ct(()=>{t-=1,t===0&&(r?.(),r=void 0,Nn(n))})})))}}var os=Nt|on;function is(e,t,n,r){new Wr(e,t,n,r)}var De,jn,ct,Jt,Te,ut,Me,Ze,_t,Xt,Rt,hn,vn,pn,kt,hr,le,Lo,Do,Mo,Zr,or,ir,Jr,Wr=class{constructor(t,n,r,a){O(this,le);A(this,"parent");A(this,"is_pending",!1);A(this,"transform_error");O(this,De);O(this,jn,L?V:null);O(this,ct);O(this,Jt);O(this,Te);O(this,ut,null);O(this,Me,null);O(this,Ze,null);O(this,_t,null);O(this,Xt,0);O(this,Rt,0);O(this,hn,!1);O(this,vn,new Set);O(this,pn,new Set);O(this,kt,null);O(this,hr,as(()=>(I(this,kt,Wn(d(this,Xt))),()=>{I(this,kt,null)})));I(this,De,t),I(this,ct,n),I(this,Jt,o=>{var l=M;l.b=this,l.f|=zr,r(o)}),this.parent=M.b,this.transform_error=a??this.parent?.transform_error??(o=>o),I(this,Te,Xn(()=>{if(L){let o=d(this,jn);an();let l=o.data===mo;if(o.data.startsWith(Ga)){let c=JSON.parse(o.data.slice(Ga.length));re(this,le,Do).call(this,c)}else l?re(this,le,Mo).call(this):re(this,le,Lo).call(this)}else re(this,le,Zr).call(this)},os)),L&&I(this,De,V)}defer_effect(t){Ao(t,d(this,vn),d(this,pn))}is_rendered(){return!this.is_pending&&(!this.parent||this.parent.is_rendered())}has_pending_snippet(){return!!d(this,ct).pending}update_pending_count(t,n){re(this,le,Jr).call(this,t,n),I(this,Xt,d(this,Xt)+t),!(!d(this,kt)||d(this,hn))&&(I(this,hn,!0),Ct(()=>{I(this,hn,!1),d(this,kt)&&dr(d(this,kt),d(this,Xt))}))}get_effect_pending(){return d(this,hr).call(this),i(d(this,kt))}error(t){var n=d(this,ct).onerror;let r=d(this,ct).failed;if(!n&&!r)throw t;d(this,ut)&&(ye(d(this,ut)),I(this,ut,null)),d(this,Me)&&(ye(d(this,Me)),I(this,Me,null)),d(this,Ze)&&(ye(d(this,Ze)),I(this,Ze,null)),L&&(Ce(d(this,jn)),va(),Ce(pa()));var a=!1,o=!1;let l=()=>{if(a){jl();return}a=!0,o&&Pl(),d(this,Ze)!==null&&Un(d(this,Ze),()=>{I(this,Ze,null)}),re(this,le,ir).call(this,()=>{re(this,le,Zr).call(this)})},s=c=>{try{o=!0,n?.(c,l),o=!1}catch(u){Ot(u,d(this,Te)&&d(this,Te).parent)}r&&I(this,Ze,re(this,le,ir).call(this,()=>{try{return Xe(()=>{var u=M;u.b=this,u.f|=zr,r(d(this,De),()=>c,()=>l)})}catch(u){return Ot(u,d(this,Te).parent),null}}))};Ct(()=>{var c;try{c=this.transform_error(t)}catch(u){Ot(u,d(this,Te)&&d(this,Te).parent);return}c!==null&&typeof c=="object"&&typeof c.then=="function"?c.then(s,u=>Ot(u,d(this,Te)&&d(this,Te).parent)):s(c)})}};De=new WeakMap,jn=new WeakMap,ct=new WeakMap,Jt=new WeakMap,Te=new WeakMap,ut=new WeakMap,Me=new WeakMap,Ze=new WeakMap,_t=new WeakMap,Xt=new WeakMap,Rt=new WeakMap,hn=new WeakMap,vn=new WeakMap,pn=new WeakMap,kt=new WeakMap,hr=new WeakMap,le=new WeakSet,Lo=function(){try{I(this,ut,Xe(()=>d(this,Jt).call(this,d(this,De))))}catch(t){this.error(t)}},Do=function(t){let n=d(this,ct).failed;n&&I(this,Ze,Xe(()=>{n(d(this,De),()=>t,()=>()=>{})}))},Mo=function(){let t=d(this,ct).pending;if(t){this.is_pending=!0,I(this,Me,Xe(()=>t(d(this,De))));var n=H;Ct(()=>{var r=I(this,_t,document.createDocumentFragment()),a=rt();r.append(a),I(this,ut,re(this,le,ir).call(this,()=>Xe(()=>d(this,Jt).call(this,a)))),d(this,Rt)===0&&(d(this,De).before(r),I(this,_t,null),Un(d(this,Me),()=>{I(this,Me,null)}),re(this,le,or).call(this,n))})}},Zr=function(){var t=H;try{if(this.is_pending=this.has_pending_snippet(),I(this,Rt,0),I(this,Xt,0),I(this,ut,Xe(()=>{d(this,Jt).call(this,d(this,De))})),d(this,Rt)>0){var n=I(this,_t,document.createDocumentFragment());bi(d(this,ut),n);let r=d(this,ct).pending;I(this,Me,Xe(()=>r(d(this,De))))}else re(this,le,or).call(this,t)}catch(r){this.error(r)}},or=function(t){this.is_pending=!1;for(let n of d(this,vn))se(n,_e),t.schedule(n);for(let n of d(this,pn))se(n,nt),t.schedule(n);d(this,vn).clear(),d(this,pn).clear()},ir=function(t){var n=M,r=P,a=Re;bt(d(this,Te)),qe(d(this,Te)),En(d(this,Te).ctx);try{return Ft.ensure(),t()}catch(o){return $o(o),null}finally{bt(n),qe(r),En(a)}},Jr=function(t,n){var r;if(!this.has_pending_snippet()){this.parent&&re(r=this.parent,le,Jr).call(r,t,n);return}I(this,Rt,d(this,Rt)+t),d(this,Rt)===0&&(re(this,le,or).call(this,n),d(this,Me)&&Un(d(this,Me),()=>{I(this,Me,null)}),d(this,_t)&&(d(this,De).before(d(this,_t)),I(this,_t,null)))};function ht(e,t){var n=t==null?"":typeof t=="object"?`${t}`:t;n!==(e.__t??(e.__t=e.nodeValue))&&(e.__t=n,e.nodeValue=`${n}`)}function No(e,t){return Uo(e,t)}function ls(e,t){Yr(),t.intro=t.intro??!1;let n=t.target,r=L,a=V;try{for(var o=Ue(n);o&&(o.nodeType!==Gn||o.data!==ha);)o=mt(o);if(!o)throw rn;vt(!0),Ce(o);let l=Uo(e,{...t,anchor:o});return vt(!1),l}catch(l){if(l instanceof Error&&l.message.split(`
`).some(s=>s.startsWith("https://svelte.dev/e/")))throw l;return l!==rn&&console.warn("Failed to hydrate: ",l),t.recover===!1&&Al(),Yr(),Bl(n),vt(!1),No(e,t)}finally{vt(r),Ce(a)}}var ar=new Map;function Uo(e,{target:t,anchor:n,props:r={},events:a,context:o,intro:l=!0,transformError:s}){Yr();var c=void 0,u=qs(()=>{var h=n??t.appendChild(rt());is(h,{pending:()=>{}},y=>{St({});var b=Re;if(o&&(b.c=o),a&&(r.$$events=a),L&&Fe(y,null),c=e(y,r)||{},L&&(M.nodes.end=V,V===null||V.nodeType!==Gn||V.data!==yo))throw qn(),rn;$t()},s);var g=new Set,v=y=>{for(var b=0;b<y.length;b++){var $=y[b];if(!g.has($)){g.add($);var x=Xl($);for(let ce of[t,document]){var D=ar.get(ce);D===void 0&&(D=new Map,ar.set(ce,D));var fe=D.get($);fe===void 0?(ce.addEventListener($,qr,{passive:x}),D.set($,1)):D.set($,fe+1)}}}};return v(hl(Io)),Gr.add(v),()=>{for(var y of g)for(let x of[t,document]){var b=ar.get(x),$=b.get(y);--$==0?(x.removeEventListener(y,qr),b.delete(y),b.size===0&&ar.delete(x)):b.set(y,$)}Gr.delete(v),h!==n&&h.parentNode?.removeChild(h)}});return Xr.set(c,u),c}var Xr=new WeakMap;function ss(e,t){let n=Xr.get(e);return n?(Xr.delete(e),n(t)):Promise.resolve()}function ya(e){var t=be|_e,n=P!==null&&(P.f&be)!==0?P:null;return M!==null&&(M.f|=on),{ctx:Re,deps:null,effects:null,equals:go,f:t,fn:e,reactions:null,rv:0,v:pe,wv:0,parent:n??M,ac:null}}function cs(e,t,n){let r=M;r===null&&El();var a=void 0,o=Wn(pe),l=!P,s=new Map;return Ws(()=>{var c=M,u=po();a=u.promise;try{Promise.resolve(e()).then(u.resolve,u.reject).finally(fr)}catch(y){u.reject(y),fr()}var h=H;if(l){if((c.f&zt)!==0)var g=zo();if(r.b.is_rendered())s.get(h)?.reject(wt),s.delete(h);else{for(let y of s.values())y.reject(wt);s.clear()}s.set(h,u)}let v=(y,b=void 0)=>{if(g){var $=b===wt;g($)}if(!(b===wt||(c.f&tt)!==0)){if(h.activate(),b)o.f|=Lt,dr(o,b);else{(o.f&Lt)!==0&&(o.f^=Lt),dr(o,y);for(let[x,D]of s){if(s.delete(x),x===h)break;D.reject(wt)}}h.deactivate()}};u.promise.then(v,y=>v(null,y||"unknown"))}),xr(()=>{for(let c of s.values())c.reject(wt)}),new Promise(c=>{function u(h){function g(){h===a?c(o):u(a)}h.then(g,g)}u(a)})}function Ee(e){let t=ya(e);return ri(t),t}function us(e){var t=e.effects;if(t!==null){e.effects=null;for(var n=0;n<t.length;n+=1)ye(t[n])}}function fs(e){for(var t=e.parent;t!==null;){if((t.f&be)===0)return(t.f&tt)===0?t:null;t=t.parent}return null}function wa(e){var t,n=M;bt(fs(e));try{e.f&=~nn,us(e),t=li(e)}finally{bt(n)}return t}function Fo(e){var t=wa(e);if(!e.equals(t)&&(e.wv=oi(),(!H?.is_fork||e.deps===null)&&(e.v=t,e.deps===null))){se(e,ge);return}Vt||(Qe!==null?(Ca()||H?.is_fork)&&Qe.set(e,t):ba(e))}function ds(e){if(e.effects!==null)for(let t of e.effects)(t.teardown||t.ac)&&(t.teardown?.(),t.ac?.abort(wt),t.teardown=Pt,t.ac=null,Vn(t,0),$a(t))}function Vo(e){if(e.effects!==null)for(let t of e.effects)t.teardown&&Sn(t)}function jo(e,t,n,r){let a=ya;var o=e.filter(v=>!v.settled);if(n.length===0&&o.length===0){r(t.map(a));return}var l=M,s=hs(),c=o.length===1?o[0].promise:o.length>1?Promise.all(o.map(v=>v.promise)):null;function u(v){s();try{r(v)}catch(y){(l.f&tt)===0&&Ot(y,l)}fr()}if(n.length===0){c.then(()=>u(t.map(a)));return}var h=zo();function g(){Promise.all(n.map(v=>cs(v))).then(v=>u([...t.map(a),...v])).catch(v=>Ot(v,l)).finally(()=>h())}c?c.then(()=>{s(),g(),fr()}):g()}function hs(){var e=M,t=P,n=Re,r=H;return function(o=!0){bt(e),qe(t),En(n),o&&(e.f&tt)===0&&(r?.activate(),r?.apply())}}function fr(e=!0){bt(null),qe(null),En(null),e&&H?.deactivate()}function zo(){var e=M.b,t=H,n=e.is_rendered();return e.update_pending_count(1,t),t.increment(n),(r=!1)=>{e.update_pending_count(-1,t),t.decrement(n,r)}}var Je,ft,Ne,Qt,zn,Bn,vr,Cn=class{constructor(t,n=!0){A(this,"anchor");O(this,Je,new Map);O(this,ft,new Map);O(this,Ne,new Map);O(this,Qt,new Set);O(this,zn,!0);O(this,Bn,t=>{if(d(this,Je).has(t)){var n=d(this,Je).get(t),r=d(this,ft).get(n);if(r)Js(r),d(this,Qt).delete(n);else{var a=d(this,Ne).get(n);a&&(d(this,ft).set(n,a.effect),d(this,Ne).delete(n),a.fragment.lastChild.remove(),this.anchor.before(a.fragment),r=a.effect)}for(let[o,l]of d(this,Je)){if(d(this,Je).delete(o),o===t)break;let s=d(this,Ne).get(l);s&&(ye(s.effect),d(this,Ne).delete(l))}for(let[o,l]of d(this,ft)){if(o===n||d(this,Qt).has(o))continue;let s=()=>{if(Array.from(d(this,Je).values()).includes(o)){var u=document.createDocumentFragment();bi(l,u),u.append(rt()),d(this,Ne).set(o,{effect:l,fragment:u})}else ye(l);d(this,Qt).delete(o),d(this,ft).delete(o)};d(this,zn)||!r?(d(this,Qt).add(o),Un(l,s,!1)):s()}}});O(this,vr,t=>{d(this,Je).delete(t);let n=Array.from(d(this,Je).values());for(let[r,a]of d(this,Ne))n.includes(r)||(ye(a.effect),d(this,Ne).delete(r))});this.anchor=t,I(this,zn,n)}ensure(t,n){var r=H,a=Hl();if(n&&!d(this,ft).has(t)&&!d(this,Ne).has(t))if(a){var o=document.createDocumentFragment(),l=rt();o.append(l),d(this,Ne).set(t,{effect:Xe(()=>n(l)),fragment:o})}else d(this,ft).set(t,Xe(()=>n(this.anchor)));if(d(this,Je).set(r,t),a){for(let[s,c]of d(this,ft))s===t?r.unskip_effect(c):r.skip_effect(c);for(let[s,c]of d(this,Ne))s===t?r.unskip_effect(c.effect):r.skip_effect(c.effect);r.oncommit(d(this,Bn)),r.ondiscard(d(this,vr))}else L&&(this.anchor=V),d(this,Bn).call(this,r)}};Je=new WeakMap,ft=new WeakMap,Ne=new WeakMap,Qt=new WeakMap,zn=new WeakMap,Bn=new WeakMap,vr=new WeakMap;function he(e,t,n=!1){var r;L&&(r=V,an());var a=new Cn(e),o=n?Nt:0;function l(s,c){if(L){var u=xo(r);if(s!==parseInt(u.substring(1))){var h=pa();Ce(h),a.anchor=h,vt(!1),a.ensure(s,c),vt(!0);return}}a.ensure(s,c)}Xn(()=>{var s=!1;t((c,u=0)=>{s=!0,l(u,c)}),s||l(-1,null)},o)}var vs=Symbol("NaN");function ps(e,t,n){L&&an();var r=new Cn(e);Xn(()=>{var a=t();a!==a&&(a=vs),r.ensure(a,n)})}function Bo(e,t,n=!1,r=!1,a=!1,o=!1){var l=e,s="";if(n){var c=e;L&&(l=Ce(Ue(c)))}we(()=>{var u=M;if(s===(s=t()??"")){L&&an();return}if(n&&!L){u.nodes=null,c.innerHTML=s,s!==""&&Fe(Ue(c),c.lastChild);return}if(u.nodes!==null&&(hi(u.nodes.start,u.nodes.end),u.nodes=null),s!==""){if(L){V.data;for(var h=an(),g=h;h!==null&&(h.nodeType!==Gn||h.data!=="");)g=h,h=mt(h);if(h===null)throw qn(),rn;Fe(V,g),l=Ce(h);return}var v=r?Ml:a?Nl:void 0,y=ga(r?"svg":a?"math":"template",v);y.innerHTML=s;var b=r||a?y:y.content;if(Fe(Ue(b),b.lastChild),r||a)for(;Ue(b);)l.before(Ue(b));else l.before(b)}})}function gs(e,t,...n){var r=new Cn(e);Xn(()=>{let a=t()??null;r.ensure(a,a&&(o=>a(o,...n)))},Nt)}function bs(e,t,n){var r;L&&(r=V,an());var a=new Cn(e);Xn(()=>{var o=t()??null;if(L){var l=xo(r),s=l===ha,c=o!==null;if(s!==c){var u=pa();Ce(u),a.anchor=u,vt(!1),a.ensure(o,o&&(h=>n(h,o))),vt(!0);return}}a.ensure(o,o&&(h=>n(h,o)))},Nt)}function ms(e,t){var n=void 0,r;fi(()=>{n!==(n=t())&&(r&&(ye(r),r=null),n&&(r=Xe(()=>{Sa(()=>n(e))})))})}function Ho(e){var t,n,r="";if(typeof e=="string"||typeof e=="number")r+=e;else if(typeof e=="object")if(Array.isArray(e)){var a=e.length;for(t=0;t<a;t++)e[t]&&(n=Ho(e[t]))&&(r&&(r+=" "),r+=n)}else for(n in e)e[n]&&(r&&(r+=" "),r+=n);return r}function ys(){for(var e,t,n=0,r="",a=arguments.length;n<a;n++)(e=arguments[n])&&(t=Ho(e))&&(r&&(r+=" "),r+=t);return r}function ws(e){return typeof e=="object"?ys(e):e??""}var Xa=[...` 	
\r\f\xA0\v\uFEFF`];function _s(e,t,n){var r=e==null?"":""+e;if(n){for(var a of Object.keys(n))if(n[a])r=r?r+" "+a:a;else if(r.length)for(var o=a.length,l=0;(l=r.indexOf(a,l))>=0;){var s=l+o;(l===0||Xa.includes(r[l-1]))&&(s===r.length||Xa.includes(r[s]))?r=(l===0?"":r.substring(0,l))+r.substring(s+1):l=s}}return r===""?null:r}function Qa(e,t=!1){var n=t?" !important;":";",r="";for(var a of Object.keys(e)){var o=e[a];o!=null&&o!==""&&(r+=" "+a+": "+o+n)}return r}function Nr(e){return e[0]!=="-"||e[1]!=="-"?e.toLowerCase():e}function ks(e,t){if(t){var n="",r,a;if(Array.isArray(t)?(r=t[0],a=t[1]):r=t,e){e=String(e).replaceAll(/\s*\/\*.*?\*\/\s*/g,"").trim();var o=!1,l=0,s=!1,c=[];r&&c.push(...Object.keys(r).map(Nr)),a&&c.push(...Object.keys(a).map(Nr));var u=0,h=-1;let $=e.length;for(var g=0;g<$;g++){var v=e[g];if(s?v==="/"&&e[g-1]==="*"&&(s=!1):o?o===v&&(o=!1):v==="/"&&e[g+1]==="*"?s=!0:v==='"'||v==="'"?o=v:v==="("?l++:v===")"&&l--,!s&&o===!1&&l===0){if(v===":"&&h===-1)h=g;else if(v===";"||g===$-1){if(h!==-1){var y=Nr(e.substring(u,h).trim());if(!c.includes(y)){v!==";"&&g++;var b=e.substring(u,g).trim();n+=" "+b+";"}}u=g+1,h=-1}}}}return r&&(n+=Qa(r)),a&&(n+=Qa(a,!0)),n=n.trim(),n===""?null:n}return e==null?null:String(e)}function xs(e,t,n,r,a,o){var l=e.__className;if(L||l!==n||l===void 0){var s=_s(n,r,o);(!L||s!==e.getAttribute("class"))&&(s==null?e.removeAttribute("class"):t?e.className=s:e.setAttribute("class",s)),e.__className=n}else if(o&&a!==o)for(var c in o){var u=!!o[c];(a==null||u!==!!a[c])&&e.classList.toggle(c,u)}return o}function Ur(e,t={},n,r){for(var a in n){var o=n[a];t[a]!==o&&(n[a]==null?e.style.removeProperty(a):e.style.setProperty(a,o,r))}}function Es(e,t,n,r){var a=e.__style;if(L||a!==t){var o=ks(t,r);(!L||o!==e.getAttribute("style"))&&(o==null?e.removeAttribute("style"):e.style.cssText=o),e.__style=t}else r&&(Array.isArray(r)?(Ur(e,n?.[0],r[0]),Ur(e,n?.[1],r[1],"important")):Ur(e,n,r));return r}function Qr(e,t,n=!1){if(e.multiple){if(t==null)return;if(!ho(t))return Vl();for(var r of e.options)r.selected=t.includes(eo(r));return}for(r of e.options){var a=eo(r);if(zl(a,t)){r.selected=!0;return}}(!n||t!==void 0)&&(e.selectedIndex=-1)}function Cs(e){var t=new MutationObserver(()=>{Qr(e,e.__value)});t.observe(e,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["value"]}),xr(()=>{t.disconnect()})}function eo(e){return"__value"in e?e.__value:e.value}var On=Symbol("class"),Pn=Symbol("style"),Ko=Symbol("is custom element"),Yo=Symbol("is html"),Ss=Kn?"link":"LINK",$s=Kn?"input":"INPUT",Ts=Kn?"option":"OPTION",As=Kn?"select":"SELECT",Rs=Kn?"progress":"PROGRESS";function _a(e){if(L){var t=!1,n=()=>{if(!t){if(t=!0,e.hasAttribute("value")){var r=e.value;Y(e,"value",null),e.value=r}if(e.hasAttribute("checked")){var a=e.checked;Y(e,"checked",null),e.checked=a}}};e.__on_r=n,Ct(n),Ro()}}function Is(e,t){var n=ka(e);n.value===(n.value=t??void 0)||e.value===t&&(t!==0||e.nodeName!==Rs)||(e.value=t??"")}function Os(e,t){t?e.hasAttribute("selected")||e.setAttribute("selected",""):e.removeAttribute("selected")}function Y(e,t,n,r){var a=ka(e);L&&(a[t]=e.getAttribute(t),t==="src"||t==="srcset"||t==="href"&&e.nodeName===Ss)||a[t]!==(a[t]=n)&&(t==="loading"&&(e[_l]=n),n==null?e.removeAttribute(t):typeof n!="string"&&Go(e).includes(t)?e[t]=n:e.setAttribute(t,n))}function Ps(e,t,n,r,a=!1,o=!1){if(L&&a&&e.nodeName===$s){var l=e,s=l.type==="checkbox"?"defaultChecked":"defaultValue";s in n||_a(l)}var c=ka(e),u=c[Ko],h=!c[Yo];let g=L&&u;g&&vt(!1);var v=t||{},y=e.nodeName===Ts;for(var b in t)b in n||(n[b]=null);n.class?n.class=ws(n.class):n[On]&&(n.class=null),n[Pn]&&(n.style??(n.style=null));var $=Go(e);for(let C in n){let U=n[C];if(y&&C==="value"&&U==null){e.value=e.__value="",v[C]=U;continue}if(C==="class"){var x=e.namespaceURI==="http://www.w3.org/1999/xhtml";xs(e,x,U,r,t?.[On],n[On]),v[C]=U,v[On]=n[On];continue}if(C==="style"){Es(e,U,t?.[Pn],n[Pn]),v[C]=U,v[Pn]=n[Pn];continue}var D=v[C];if(!(U===D&&!(U===void 0&&e.hasAttribute(C)))){v[C]=U;var fe=C[0]+C[1];if(fe!=="$$")if(fe==="on"){let oe={},z="$$"+C,j=C.slice(2);var ce=ql(j);if(Yl(j)&&(j=j.slice(0,-7),oe.capture=!0),!ce&&D){if(U!=null)continue;e.removeEventListener(j,v[z],oe),v[z]=null}if(ce)yr(j,e,U),wr([j]);else if(U!=null){let We=function(xe){v[C].call(this,xe)};var de=We;v[z]=Oo(j,e,We,oe)}}else if(C==="style")Y(e,C,U);else if(C==="autofocus")Ql(e,!!U);else if(!u&&(C==="__value"||C==="value"&&U!=null))e.value=e.__value=U;else if(C==="selected"&&y)Os(e,U);else{var G=C;h||(G=Zl(G));var ot=G==="defaultValue"||G==="defaultChecked";if(U==null&&!u&&!ot)if(c[C]=null,G==="value"||G==="checked"){let oe=e,z=t===void 0;if(G==="value"){let j=oe.defaultValue;oe.removeAttribute(G),oe.defaultValue=j,oe.value=oe.__value=z?j:null}else{let j=oe.defaultChecked;oe.removeAttribute(G),oe.defaultChecked=j,oe.checked=z?j:!1}}else e.removeAttribute(C);else ot||$.includes(G)&&(u||typeof U!="string")?(e[G]=U,G in c&&(c[G]=pe)):typeof U!="function"&&Y(e,G,U)}}}return g&&vt(!0),v}function _r(e,t,n=[],r=[],a=[],o,l=!1,s=!1){jo(a,n,r,c=>{var u=void 0,h={},g=e.nodeName===As,v=!1;if(fi(()=>{var b=t(...c.map(i)),$=Ps(e,u,b,o,l,s);v&&g&&"value"in b&&Qr(e,b.value);for(let D of Object.getOwnPropertySymbols(h))b[D]||ye(h[D]);for(let D of Object.getOwnPropertySymbols(b)){var x=b[D];D.description===Ul&&(!u||x!==u[D])&&(h[D]&&ye(h[D]),h[D]=Xe(()=>ms(e,()=>x))),$[D]=x}u=$}),g){var y=e;Sa(()=>{Qr(y,u.value,!0),Cs(y)})}v=!0})}function ka(e){return e.__attributes??(e.__attributes={[Ko]:e.nodeName.includes("-"),[Yo]:e.namespaceURI===wo})}var to=new Map;function Go(e){var t=e.getAttribute("is")||e.nodeName,n=to.get(t);if(n)return n;to.set(t,n=[]);for(var r,a=e,o=Element.prototype;o!==a;){r=vl(a);for(var l in r)r[l].set&&n.push(l);a=vo(a)}return n}function Ls(e,t,n=t){var r=new WeakSet;es(e,"input",async a=>{var o=a?e.defaultValue:e.value;if(o=Fr(e)?Vr(o):o,n(o),H!==null&&r.add(H),await Zt(),o!==(o=t())){var l=e.selectionStart,s=e.selectionEnd,c=e.value.length;if(e.value=o??"",s!==null){var u=e.value.length;l===s&&s===c&&u>c?(e.selectionStart=u,e.selectionEnd=u):(e.selectionStart=l,e.selectionEnd=Math.min(s,u))}}}),(L&&e.defaultValue!==e.value||Jn(t)==null&&e.value)&&(n(Fr(e)?Vr(e.value):e.value),H!==null&&r.add(H)),Er(()=>{var a=t();if(e===document.activeElement){var o=H;if(r.has(o))return}Fr(e)&&a===Vr(e.value)||e.type==="date"&&!a&&!e.value||a!==e.value&&(e.value=a??"")})}function Fr(e){var t=e.type;return t==="number"||t==="range"}function Vr(e){return e===""?null:+e}function no(e,t){return e===t||e?.[dn]===t}function Ut(e={},t,n,r){var a=Re.r,o=M;return Sa(()=>{var l,s;return Er(()=>{l=s,s=[],Jn(()=>{e!==n(...s)&&(t(e,...s),l&&no(n(...l),e)&&t(null,...l))})}),()=>{let c=o;for(;c!==a&&c.parent!==null&&c.parent.f&Br;)c=c.parent;let u=()=>{s&&no(n(...s),e)&&t(null,...s)},h=c.teardown;c.teardown=()=>{u(),h?.()}}}),e}var Ds={get(e,t){if(!e.exclude.includes(t))return e.props[t]},set(e,t){return!1},getOwnPropertyDescriptor(e,t){if(!e.exclude.includes(t)&&t in e.props)return{enumerable:!0,configurable:!0,value:e.props[t]}},has(e,t){return e.exclude.includes(t)?!1:t in e.props},ownKeys(e){return Reflect.ownKeys(e.props).filter(t=>!e.exclude.includes(t))}};function kr(e,t,n){return new Proxy({props:e,exclude:t},Ds)}function ee(e,t,n,r){var a=r,o=!0,l=()=>(o&&(o=!1,a=r),a),s;s=e[t],s===void 0&&r!==void 0&&(s=l());var c;c=()=>{var v=e[t];return v===void 0?l():(o=!0,v)};var u=!1,h=ya(()=>(u=!1,c())),g=M;return(function(v,y){if(arguments.length>0){let b=y?i(h):v;return k(h,b),u=!0,a!==void 0&&(a=b),v}return Vt&&u||(g.f&tt)!==0?h.v:i(h)})}function Ms(e){return new ea(e)}var xt,He,ea=class{constructor(t){O(this,xt);O(this,He);var n=new Map,r=(o,l)=>{var s=ti(l,!1,!1);return n.set(o,s),s};let a=new Proxy({...t.props||{},$$events:{}},{get(o,l){return i(n.get(l)??r(l,Reflect.get(o,l)))},has(o,l){return l===wl?!0:(i(n.get(l)??r(l,Reflect.get(o,l))),Reflect.has(o,l))},set(o,l,s){return k(n.get(l)??r(l,s),s),Reflect.set(o,l,s)}});I(this,He,(t.hydrate?ls:No)(t.component,{target:t.target,anchor:t.anchor,props:a,context:t.context,intro:t.intro??!1,recover:t.recover,transformError:t.transformError})),(!t?.props?.$$host||t.sync===!1)&&Z(),I(this,xt,a.$$events);for(let o of Object.keys(d(this,He)))o==="$set"||o==="$destroy"||o==="$on"||Fn(this,o,{get(){return d(this,He)[o]},set(l){d(this,He)[o]=l},enumerable:!0});d(this,He).$set=o=>{Object.assign(a,o)},d(this,He).$destroy=()=>{ss(d(this,He))}}$set(t){d(this,He).$set(t)}$on(t,n){d(this,xt)[t]=d(this,xt)[t]||[];let r=(...a)=>n.call(this,...a);return d(this,xt)[t].push(r),()=>{d(this,xt)[t]=d(this,xt)[t].filter(a=>a!==r)}}$destroy(){d(this,He).$destroy()}};xt=new WeakMap,He=new WeakMap;var qo=class{};typeof HTMLElement=="function"&&(qo=class extends HTMLElement{constructor(t,n,r){super();A(this,"$$ctor");A(this,"$$s");A(this,"$$c");A(this,"$$cn",!1);A(this,"$$d",{});A(this,"$$r",!1);A(this,"$$p_d",{});A(this,"$$l",{});A(this,"$$l_u",new Map);A(this,"$$me");A(this,"$$shadowRoot",null);this.$$ctor=t,this.$$s=n,r&&(this.$$shadowRoot=this.attachShadow(r))}addEventListener(t,n,r){if(this.$$l[t]=this.$$l[t]||[],this.$$l[t].push(n),this.$$c){let a=this.$$c.$on(t,n);this.$$l_u.set(n,a)}super.addEventListener(t,n,r)}removeEventListener(t,n,r){if(super.removeEventListener(t,n,r),this.$$c){let a=this.$$l_u.get(n);a&&(a(),this.$$l_u.delete(n))}}async connectedCallback(){if(this.$$cn=!0,!this.$$c){let n=function(o){return l=>{let s=ga("slot");o!=="default"&&(s.name=o),F(l,s)}};var t=n;if(await Promise.resolve(),!this.$$cn||this.$$c)return;let r={},a=Ns(this);for(let o of this.$$s)o in a&&(o==="default"&&!this.$$d.children?(this.$$d.children=n(o),r.default=!0):r[o]=n(o));for(let o of this.attributes){let l=this.$$g_p(o.name);l in this.$$d||(this.$$d[l]=lr(l,o.value,this.$$p_d,"toProp"))}for(let o in this.$$p_d)!(o in this.$$d)&&this[o]!==void 0&&(this.$$d[o]=this[o],delete this[o]);this.$$c=Ms({component:this.$$ctor,target:this.$$shadowRoot||this,props:{...this.$$d,$$slots:r,$$host:this}}),this.$$me=Gs(()=>{Er(()=>{this.$$r=!0;for(let o of ur(this.$$c)){if(!this.$$p_d[o]?.reflect)continue;this.$$d[o]=this.$$c[o];let l=lr(o,this.$$d[o],this.$$p_d,"toAttribute");l==null?this.removeAttribute(this.$$p_d[o].attribute||o):this.setAttribute(this.$$p_d[o].attribute||o,l)}this.$$r=!1})});for(let o in this.$$l)for(let l of this.$$l[o]){let s=this.$$c.$on(o,l);this.$$l_u.set(l,s)}this.$$l={}}}attributeChangedCallback(t,n,r){this.$$r||(t=this.$$g_p(t),this.$$d[t]=lr(t,r,this.$$p_d,"toProp"),this.$$c?.$set({[t]:this.$$d[t]}))}disconnectedCallback(){this.$$cn=!1,Promise.resolve().then(()=>{!this.$$cn&&this.$$c&&(this.$$c.$destroy(),this.$$me(),this.$$c=void 0)})}$$g_p(t){return ur(this.$$p_d).find(n=>this.$$p_d[n].attribute===t||!this.$$p_d[n].attribute&&n.toLowerCase()===t)||t}});function lr(e,t,n,r){let a=n[e]?.type;if(t=a==="Boolean"&&typeof t!="boolean"?t!=null:t,!r||!n[e])return t;if(r==="toAttribute")switch(a){case"Object":case"Array":return t==null?null:JSON.stringify(t);case"Boolean":return t?"":null;case"Number":return t??null;default:return t}else switch(a){case"Object":case"Array":return t&&JSON.parse(t);case"Boolean":return t;case"Number":return t!=null?+t:t;default:return t}}function Ns(e){let t={};return e.childNodes.forEach(n=>{t[n.slot||"default"]=!0}),t}function Bt(e,t,n,r,a,o){let l=class extends qo{constructor(){super(e,n,a),this.$$p_d=t}static get observedAttributes(){return ur(t).map(s=>(t[s].attribute||s).toLowerCase())}};return ur(t).forEach(s=>{Fn(l.prototype,s,{get(){return this.$$c&&s in this.$$c?this.$$c[s]:this.$$d[s]},set(c){c=lr(s,c,t),this.$$d[s]=c;var u=this.$$c;if(u){var h=fn(u,s)?.get;h?u[s]=c:u.$set({[s]:c})}}})}),r.forEach(s=>{Fn(l.prototype,s,{get(){return this.$$c?.[s]}})}),e.element=l,l}function xa(e){Re===null&&xl(),Le(()=>{let t=Jn(e);if(typeof t=="function")return t})}function Wo(e,t,n){if(e==null)return t(void 0),Pt;let r=Jn(()=>e.subscribe(t,n));return r.unsubscribe?()=>r.unsubscribe():r}var sn=[];function Us(e,t=Pt){let n=null,r=new Set;function a(s){if(bo(e,s)&&(e=s,n)){let c=!sn.length;for(let u of r)u[1](),sn.push(u,e);if(c){for(let u=0;u<sn.length;u+=2)sn[u][0](sn[u+1]);sn.length=0}}}function o(s){a(s(e))}function l(s,c=Pt){let u=[s,c];return r.add(u),r.size===1&&(n=t(a,o)||Pt),s(e),()=>{r.delete(u),r.size===0&&n&&(n(),n=null)}}return{set:a,update:o,subscribe:l}}function Dn(e){let t;return Wo(e,n=>t=n)(),t}var ta=Symbol();function ro(e,t,n){let r=n[t]??(n[t]={store:null,source:ti(void 0),unsubscribe:Pt});if(r.store!==e&&!(ta in n))if(r.unsubscribe(),r.store=e??null,e==null)r.source.v=void 0,r.unsubscribe=Pt;else{var a=!0;r.unsubscribe=Wo(e,o=>{a?r.source.v=o:k(r.source,o)}),a=!1}return e&&ta in n?Dn(e):i(r.source)}function Fs(){let e={};function t(){xr(()=>{for(var n in e)e[n].unsubscribe();Fn(e,ta,{enumerable:!1,value:!0})})}return[e,t]}var Ln=new Set,H=null,Qe=null,na=null,Mn=!1,jr=!1,un=null,sr=null,ao=0,Vs=1,gn,bn,mn,yn,Hn,Ke,wn,It,dt,_n,ke,ra,aa,oa,ia,Zo,pr=class pr{constructor(){O(this,ke);A(this,"id",Vs++);A(this,"current",new Map);A(this,"previous",new Map);O(this,gn,new Set);O(this,bn,new Set);O(this,mn,0);O(this,yn,0);O(this,Hn,null);O(this,Ke,[]);O(this,wn,new Set);O(this,It,new Set);O(this,dt,new Map);A(this,"is_fork",!1);O(this,_n,!1)}skip_effect(t){d(this,dt).has(t)||d(this,dt).set(t,{d:[],m:[]})}unskip_effect(t){var n=d(this,dt).get(t);if(n){d(this,dt).delete(t);for(var r of n.d)se(r,_e),this.schedule(r);for(r of n.m)se(r,nt),this.schedule(r)}}capture(t,n){n!==pe&&!this.previous.has(t)&&this.previous.set(t,n),(t.f&Lt)===0&&(this.current.set(t,t.v),Qe?.set(t,t.v))}activate(){H=this}deactivate(){H=null,Qe=null}flush(){try{if(jr=!0,H=this,!re(this,ke,ra).call(this)){for(let t of d(this,wn))d(this,It).delete(t),se(t,_e),this.schedule(t);for(let t of d(this,It))se(t,nt),this.schedule(t)}re(this,ke,aa).call(this)}finally{ao=0,na=null,un=null,sr=null,jr=!1,H=null,Qe=null,Dt.clear()}}discard(){for(let t of d(this,bn))t(this);d(this,bn).clear()}increment(t){I(this,mn,d(this,mn)+1),t&&I(this,yn,d(this,yn)+1)}decrement(t,n){I(this,mn,d(this,mn)-1),t&&I(this,yn,d(this,yn)-1),!(d(this,_n)||n)&&(I(this,_n,!0),Ct(()=>{I(this,_n,!1),this.flush()}))}oncommit(t){d(this,gn).add(t)}ondiscard(t){d(this,bn).add(t)}settled(){return(d(this,Hn)??I(this,Hn,po())).promise}static ensure(){if(H===null){let t=H=new pr;jr||(Ln.add(H),Mn||Ct(()=>{H===t&&t.flush()}))}return H}apply(){}schedule(t){if(na=t,t.b?.is_pending&&(t.f&(xn|gr|fa))!==0&&(t.f&zt)===0){t.b.defer_effect(t);return}for(var n=t;n.parent!==null;){n=n.parent;var r=n.f;if(un!==null&&n===M&&(P===null||(P.f&be)===0))return;if((r&(Mt|gt))!==0){if((r&ge)===0)return;n.f^=ge}}d(this,Ke).push(n)}};gn=new WeakMap,bn=new WeakMap,mn=new WeakMap,yn=new WeakMap,Hn=new WeakMap,Ke=new WeakMap,wn=new WeakMap,It=new WeakMap,dt=new WeakMap,_n=new WeakMap,ke=new WeakSet,ra=function(){return this.is_fork||d(this,yn)>0},aa=function(){var s;ao++>1e3&&js();let t=d(this,Ke);I(this,Ke,[]),this.apply();var n=un=[],r=[],a=sr=[];for(let c of t)re(this,ke,oa).call(this,c,n,r);if(H=null,a.length>0){var o=pr.ensure();for(let c of a)o.schedule(c)}if(un=null,sr=null,re(this,ke,ra).call(this)){re(this,ke,ia).call(this,r),re(this,ke,ia).call(this,n);for(let[c,u]of d(this,dt))Qo(c,u)}else{d(this,wn).clear(),d(this,It).clear();for(let c of d(this,gn))c(this);d(this,gn).clear(),oo(r),oo(n),d(this,mn)===0&&re(this,ke,Zo).call(this),d(this,Hn)?.resolve()}var l=H;if(d(this,Ke).length>0){let c=l??(l=this);d(c,Ke).push(...d(this,Ke).filter(u=>!d(c,Ke).includes(u)))}l!==null&&(Ln.add(l),re(s=l,ke,aa).call(s))},oa=function(t,n,r){t.f^=ge;for(var a=t.first;a!==null;){var o=a.f,l=(o&(gt|Mt))!==0,s=l&&(o&ge)!==0,c=s||(o&pt)!==0||d(this,dt).has(a);if(!c&&a.fn!==null){l?a.f^=ge:(o&xn)!==0?n.push(a):Zn(a)&&((o&jt)!==0&&d(this,It).add(a),Sn(a));var u=a.first;if(u!==null){a=u;continue}}for(;a!==null;){var h=a.next;if(h!==null){a=h;break}a=a.parent}}},ia=function(t){for(var n=0;n<t.length;n+=1)Ao(t[n],d(this,wn),d(this,It))},Zo=function(){var a;if(Ln.size>1){this.previous.clear();var t=H,n=Qe,r=!0;for(let o of Ln){if(o===this){r=!1;continue}let l=[];for(let[c,u]of this.current){if(o.current.has(c))if(r&&u!==o.current.get(c))o.current.set(c,u);else continue;l.push(c)}if(l.length===0)continue;let s=[...o.current.keys()].filter(c=>!this.current.has(c));if(s.length>0){o.activate();let c=new Set,u=new Map;for(let h of l)Jo(h,s,c,u);if(d(o,Ke).length>0){o.apply();for(let h of d(o,Ke))re(a=o,ke,oa).call(a,h,[],[])}o.deactivate()}}H=t,Qe=n}d(this,dt).clear(),Ln.delete(this)};var Ft=pr;function Z(e){var t=Mn;Mn=!0;try{for(var n;;){if(Fl(),H===null)return n;H.flush()}}finally{Mn=t}}function js(){try{Tl()}catch(e){Ot(e,na)}}var yt=null;function oo(e){var t=e.length;if(t!==0){for(var n=0;n<t;){var r=e[n++];if((r.f&(tt|pt))===0&&Zn(r)&&(yt=new Set,Sn(r),r.deps===null&&r.first===null&&r.nodes===null&&r.teardown===null&&r.ac===null&&vi(r),yt?.size>0)){Dt.clear();for(let a of yt){if((a.f&(tt|pt))!==0)continue;let o=[a],l=a.parent;for(;l!==null;)yt.has(l)&&(yt.delete(l),o.push(l)),l=l.parent;for(let s=o.length-1;s>=0;s--){let c=o[s];(c.f&(tt|pt))===0&&Sn(c)}}yt.clear()}}yt=null}}function Jo(e,t,n,r){if(!n.has(e)&&(n.add(e),e.reactions!==null))for(let a of e.reactions){let o=a.f;(o&be)!==0?Jo(a,t,n,r):(o&(da|jt))!==0&&(o&_e)===0&&Xo(a,t,r)&&(se(a,_e),Ea(a))}}function Xo(e,t,n){let r=n.get(e);if(r!==void 0)return r;if(e.deps!==null)for(let a of e.deps){if(kn.call(t,a))return!0;if((a.f&be)!==0&&Xo(a,t,n))return n.set(a,!0),!0}return n.set(e,!1),!1}function Ea(e){H.schedule(e)}function Qo(e,t){if(!((e.f&gt)!==0&&(e.f&ge)!==0)){(e.f&_e)!==0?t.d.push(e):(e.f&nt)!==0&&t.m.push(e),se(e,ge);for(var n=e.first;n!==null;)Qo(n,t),n=n.next}}var la=new Set,Dt=new Map,ei=!1;function Wn(e,t){var n={f:0,v:e,reactions:null,equals:go,rv:0,wv:0};return n}function N(e,t){let n=Wn(e);return ri(n),n}function ti(e,t=!1,n=!0){let r=Wn(e);return t||(r.equals=kl),r}function k(e,t,n=!1){P!==null&&(!et||(P.f&Ya)!==0)&&_o()&&(P.f&(be|jt|da|Ya))!==0&&(Ge===null||!kn.call(Ge,e))&&Ol();let r=n?Et(t):t;return dr(e,r,sr)}function dr(e,t,n=null){if(!e.equals(t)){var r=e.v;Vt?Dt.set(e,t):Dt.set(e,r),e.v=t;var a=Ft.ensure();if(a.capture(e,r),(e.f&be)!==0){let o=e;(e.f&_e)!==0&&wa(o),ba(o)}e.wv=oi(),ni(e,_e,n),M!==null&&(M.f&ge)!==0&&(M.f&(gt|Mt))===0&&(Be===null?Bs([e]):Be.push(e)),!a.is_fork&&la.size>0&&!ei&&zs()}return t}function zs(){ei=!1;for(let e of la)(e.f&ge)!==0&&se(e,nt),Zn(e)&&Sn(e);la.clear()}function Nn(e){k(e,e.v+1)}function ni(e,t,n){var r=e.reactions;if(r!==null)for(var a=r.length,o=0;o<a;o++){var l=r[o],s=l.f,c=(s&_e)===0;if(c&&se(l,t),(s&be)!==0){var u=l;Qe?.delete(u),(s&nn)===0&&(s&Ye&&(l.f|=nn),ni(u,nt,n))}else if(c){var h=l;(s&jt)!==0&&yt!==null&&yt.add(h),n!==null?n.push(h):Ea(h)}}}var cr=!1,Vt=!1;function io(e){Vt=e}var P=null,et=!1;function qe(e){P=e}var M=null;function bt(e){M=e}var Ge=null;function ri(e){P!==null&&(Ge===null?Ge=[e]:Ge.push(e))}var Ae=null,Pe=0,Be=null;function Bs(e){Be=e}var ai=1,Wt=0,tn=Wt;function lo(e){tn=e}function oi(){return++ai}function Zn(e){var t=e.f;if((t&_e)!==0)return!0;if(t&be&&(e.f&=~nn),(t&nt)!==0){for(var n=e.deps,r=n.length,a=0;a<r;a++){var o=n[a];if(Zn(o)&&Fo(o),o.wv>e.wv)return!0}(t&Ye)!==0&&Qe===null&&se(e,ge)}return!1}function ii(e,t,n=!0){var r=e.reactions;if(r!==null&&!(Ge!==null&&kn.call(Ge,e)))for(var a=0;a<r.length;a++){var o=r[a];(o.f&be)!==0?ii(o,t,!1):t===o&&(n?se(o,_e):(o.f&ge)!==0&&se(o,nt),Ea(o))}}function li(e){var $;var t=Ae,n=Pe,r=Be,a=P,o=Ge,l=Re,s=et,c=tn,u=e.f;Ae=null,Pe=0,Be=null,P=(u&(gt|Mt))===0?e:null,Ge=null,En(e.ctx),et=!1,tn=++Wt,e.ac!==null&&(mr(()=>{e.ac.abort(wt)}),e.ac=null);try{e.f|=Hr;var h=e.fn,g=h();e.f|=zt;var v=e.deps,y=H?.is_fork;if(Ae!==null){var b;if(y||Vn(e,Pe),v!==null&&Pe>0)for(v.length=Pe+Ae.length,b=0;b<Ae.length;b++)v[Pe+b]=Ae[b];else e.deps=v=Ae;if(Ca()&&(e.f&Ye)!==0)for(b=Pe;b<v.length;b++)(($=v[b]).reactions??($.reactions=[])).push(e)}else!y&&v!==null&&Pe<v.length&&(Vn(e,Pe),v.length=Pe);if(_o()&&Be!==null&&!et&&v!==null&&(e.f&(be|nt|_e))===0)for(b=0;b<Be.length;b++)ii(Be[b],e);if(a!==null&&a!==e){if(Wt++,a.deps!==null)for(let x=0;x<n;x+=1)a.deps[x].rv=Wt;if(t!==null)for(let x of t)x.rv=Wt;Be!==null&&(r===null?r=Be:r.push(...Be))}return(e.f&Lt)!==0&&(e.f^=Lt),g}catch(x){return $o(x)}finally{e.f^=Hr,Ae=t,Pe=n,Be=r,P=a,Ge=o,En(l),et=s,tn=c}}function Hs(e,t){let n=t.reactions;if(n!==null){var r=dl.call(n,e);if(r!==-1){var a=n.length-1;a===0?n=t.reactions=null:(n[r]=n[a],n.pop())}}if(n===null&&(t.f&be)!==0&&(Ae===null||!kn.call(Ae,t))){var o=t;(o.f&Ye)!==0&&(o.f^=Ye,o.f&=~nn),ba(o),ds(o),Vn(o,0)}}function Vn(e,t){var n=e.deps;if(n!==null)for(var r=t;r<n.length;r++)Hs(e,n[r])}function Sn(e){var t=e.f;if((t&tt)===0){se(e,ge);var n=M,r=cr;M=e,cr=!0;try{(t&(jt|fa))!==0?Zs(e):$a(e),di(e);var a=li(e);e.teardown=typeof a=="function"?a:null,e.wv=ai;var o}finally{cr=r,M=n}}}async function Zt(){await Promise.resolve(),Z()}function i(e){var t=e.f,n=(t&be)!==0;if(P!==null&&!et){var r=M!==null&&(M.f&tt)!==0;if(!r&&(Ge===null||!kn.call(Ge,e))){var a=P.deps;if((P.f&Hr)!==0)e.rv<Wt&&(e.rv=Wt,Ae===null&&a!==null&&a[Pe]===e?Pe++:Ae===null?Ae=[e]:Ae.push(e));else{(P.deps??(P.deps=[])).push(e);var o=e.reactions;o===null?e.reactions=[P]:kn.call(o,P)||o.push(P)}}}if(Vt&&Dt.has(e))return Dt.get(e);if(n){var l=e;if(Vt){var s=l.v;return((l.f&ge)===0&&l.reactions!==null||ci(l))&&(s=wa(l)),Dt.set(l,s),s}var c=(l.f&Ye)===0&&!et&&P!==null&&(cr||(P.f&Ye)!==0),u=(l.f&zt)===0;Zn(l)&&(c&&(l.f|=Ye),Fo(l)),c&&!u&&(Vo(l),si(l))}if(Qe?.has(e))return Qe.get(e);if((e.f&Lt)!==0)throw e.v;return e.v}function si(e){if(e.f|=Ye,e.deps!==null)for(let t of e.deps)(t.reactions??(t.reactions=[])).push(e),(t.f&be)!==0&&(t.f&Ye)===0&&(Vo(t),si(t))}function ci(e){if(e.v===pe)return!0;if(e.deps===null)return!1;for(let t of e.deps)if(Dt.has(t)||(t.f&be)!==0&&ci(t))return!0;return!1}function Jn(e){var t=et;try{return et=!0,e()}finally{et=t}}function Ks(e){M===null&&(P===null&&$l(),Sl()),Vt&&Cl()}function Ys(e,t){var n=t.last;n===null?t.last=t.first=e:(n.next=e,e.prev=n,t.last=e)}function at(e,t){var n=M;n!==null&&(n.f&pt)!==0&&(e|=pt);var r={ctx:Re,deps:null,nodes:null,f:e|_e|Ye,first:null,fn:t,last:null,next:null,parent:n,b:n&&n.b,prev:null,teardown:null,wv:0,ac:null},a=r;if((e&xn)!==0)un!==null?un.push(r):Ft.ensure().schedule(r);else if(t!==null){try{Sn(r)}catch(l){throw ye(r),l}a.deps===null&&a.teardown===null&&a.nodes===null&&a.first===a.last&&(a.f&on)===0&&(a=a.first,(e&jt)!==0&&(e&Nt)!==0&&a!==null&&(a.f|=Nt))}if(a!==null&&(a.parent=n,n!==null&&Ys(a,n),P!==null&&(P.f&be)!==0&&(e&Mt)===0)){var o=P;(o.effects??(o.effects=[])).push(a)}return r}function Ca(){return P!==null&&!et}function xr(e){let t=at(gr,null);return se(t,ge),t.teardown=e,t}function Le(e){Ks();var t=M.f,n=!P&&(t&gt)!==0&&(t&zt)===0;if(n){var r=Re;(r.e??(r.e=[])).push(e)}else return ui(e)}function ui(e){return at(xn|yl,e)}function Gs(e){Ft.ensure();let t=at(Mt|on,e);return()=>{ye(t)}}function qs(e){Ft.ensure();let t=at(Mt|on,e);return(n={})=>new Promise(r=>{n.outro?Un(t,()=>{ye(t),r(void 0)}):(ye(t),r(void 0))})}function Sa(e){return at(xn,e)}function Ws(e){return at(da|on,e)}function Er(e,t=0){return at(gr|t,e)}function we(e,t=[],n=[],r=[]){jo(r,t,n,a=>{at(gr,()=>e(...a.map(i)))})}function Xn(e,t=0){var n=at(jt|t,e);return n}function fi(e,t=0){var n=at(fa|t,e);return n}function Xe(e){return at(gt|on,e)}function di(e){var t=e.teardown;if(t!==null){let n=Vt,r=P;io(!0),qe(null);try{t.call(null)}finally{io(n),qe(r)}}}function $a(e,t=!1){var n=e.first;for(e.first=e.last=null;n!==null;){let a=n.ac;a!==null&&mr(()=>{a.abort(wt)});var r=n.next;(n.f&Mt)!==0?n.parent=null:ye(n,t),n=r}}function Zs(e){for(var t=e.first;t!==null;){var n=t.next;(t.f&gt)===0&&ye(t),t=n}}function ye(e,t=!0){var n=!1;(t||(e.f&ml)!==0)&&e.nodes!==null&&e.nodes.end!==null&&(hi(e.nodes.start,e.nodes.end),n=!0),se(e,Br),$a(e,t&&!n),Vn(e,0);var r=e.nodes&&e.nodes.t;if(r!==null)for(let o of r)o.stop();di(e),e.f^=Br,e.f|=tt;var a=e.parent;a!==null&&a.first!==null&&vi(e),e.next=e.prev=e.teardown=e.ctx=e.deps=e.fn=e.nodes=e.ac=null}function hi(e,t){for(;e!==null;){var n=e===t?null:mt(e);e.remove(),e=n}}function vi(e){var t=e.parent,n=e.prev,r=e.next;n!==null&&(n.next=r),r!==null&&(r.prev=n),t!==null&&(t.first===e&&(t.first=r),t.last===e&&(t.last=n))}function Un(e,t,n=!0){var r=[];pi(e,r,!0);var a=()=>{n&&ye(e),t&&t()},o=r.length;if(o>0){var l=()=>--o||a();for(var s of r)s.out(l)}else a()}function pi(e,t,n){if((e.f&pt)===0){e.f^=pt;var r=e.nodes&&e.nodes.t;if(r!==null)for(let s of r)(s.is_global||n)&&t.push(s);for(var a=e.first;a!==null;){var o=a.next,l=(a.f&Nt)!==0||(a.f&gt)!==0&&(e.f&jt)!==0;pi(a,t,l?n:!1),a=o}}}function Js(e){gi(e,!0)}function gi(e,t){if((e.f&pt)!==0){e.f^=pt,(e.f&ge)===0&&(se(e,_e),Ft.ensure().schedule(e));for(var n=e.first;n!==null;){var r=n.next,a=(n.f&Nt)!==0||(n.f&gt)!==0;gi(n,a?t:!1),n=r}var o=e.nodes&&e.nodes.t;if(o!==null)for(let l of o)(l.is_global||t)&&l.in()}}function bi(e,t){if(e.nodes)for(var n=e.nodes.start,r=e.nodes.end;n!==null;){var a=n===r?null:mt(n);t.append(n),n=a}}function so(e){let t={get:n=>Dn(t.store)[n],set:(n,r)=>{typeof n=="string"?Object.assign(Dn(t.store),{[n]:r}):Object.assign(Dn(t.store),n),t.store.set(Dn(t.store))},store:Us(e)};return t}globalThis.$altcha=globalThis.$altcha||{algorithms:new Map,defaults:so({}),i18n:so({}),instances:new Set,plugins:new Set};var Xs={ariaLinkLabel:"Altcha (official website)",cancel:"Cancel",enterCode:"Enter code",enterCodeAria:"Enter code you hear. Press Space to play audio.",enterCodeFromImage:"To proceed, please enter the code from the image below.",error:"Verification failed. Try again later.",expired:"Verification expired. Try again.",footer:'Protected by <a href="https://altcha.org/" tabindex="-1" target="_blank" aria-label="Altcha (official website)">ALTCHA</a>',getAudioChallenge:"Get an audio challenge",label:"I'm not a robot",loading:"Loading...",reload:"Reload",verify:"Verify",verificationRequired:"Verification required!",verified:"Verified",verifying:"Verifying...",waitAlert:"Verifying... please wait."};"$altcha"in globalThis&&globalThis.$altcha.i18n.set("en",Xs);var Qs="5",fo;typeof window<"u"&&((fo=window.__svelte??(window.__svelte={})).v??(fo.v=new Set)).add(Qs);var ec=te('<div class="altcha-checkbox"><input/> <svg aria-hidden="true" width="12" height="9" viewBox="0 0 12 9"><polyline points="1 5 4 8 11 1"></polyline></svg> <div class="altcha-spinner altcha-checkbox-spinner" aria-hidden="true"></div></div>');function mi(e,t){St(t,!0);let n=ee(t,"loading"),r=kr(t,["$$slots","$$events","$$legacy","$$host","loading"]),a;function o(){a?.click()}var l={get loading(){return n()},set loading(h){n(h),Z()}},s=ec(),c=ae(s);_r(c,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),Ut(c,h=>a=h,()=>a);var u=Q(c,2);return va(2),X(s),we(()=>Y(s,"data-loading",n())),yr("click",u,o),F(e,s),$t(l)}wr(["click"]);Bt(mi,{loading:{}},[],[],{mode:"open"});var tc=te('<div class="altcha-checkbox-native"><input/> <div class="altcha-spinner altcha-checkbox-native-spinner"></div></div>');function yi(e,t){St(t,!0);let n=ee(t,"loading"),r=kr(t,["$$slots","$$events","$$legacy","$$host","loading"]);var a={get loading(){return n()},set loading(s){n(s),Z()}},o=tc(),l=ae(o);return _r(l,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),va(2),X(o),we(()=>Y(o,"data-loading",n())),F(e,o),$t(a)}Bt(yi,{loading:{}},[],[],{mode:"open"});var nc=te('<div><a target="_blank" class="altcha-logo" aria-hidden="true" tabindex="-1"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.33955 16.4279C5.88954 20.6586 12.1971 21.2105 16.4279 17.6604C18.4699 15.947 19.6548 13.5911 19.9352 11.1365L17.9886 10.4279C17.8738 12.5624 16.909 14.6459 15.1423 16.1284C11.7577 18.9684 6.71167 18.5269 3.87164 15.1423C1.03163 11.7577 1.4731 6.71166 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577C16.9767 5.86872 17.5322 7.02798 17.804 8.2324L19.9522 9.01429C19.7622 7.07737 19.0059 5.17558 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956C-0.658625 5.88958 -1.21046 12.1971 2.33955 16.4279Z" fill="currentColor"></path><path d="M3.57212 2.33956C1.65755 3.94607 0.496389 6.11731 0.12782 8.40523L2.04639 9.13961C2.26047 7.15832 3.21057 5.25375 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577L13.8302 6.78606L19.9633 9.13364C19.7929 7.15555 19.0335 5.20847 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956Z" fill="currentColor"></path><path d="M7 10H5C5 12.7614 7.23858 15 10 15C12.7614 15 15 12.7614 15 10H13C13 11.6569 11.6569 13 10 13C8.3431 13 7 11.6569 7 10Z" fill="currentColor"></path></svg></a></div>');function Ta(e,t){St(t,!0);let n=ee(t,"strings"),r="https://altcha.org";var a={get strings(){return n()},set strings(s){n(s),Z()}},o=nc(),l=ae(o);return Y(l,"href",r),X(o),we(()=>Y(l,"aria-label",n().ariaLinkLabel)),F(e,o),$t(a)}Bt(Ta,{strings:{}},[],[],{mode:"open"});var rc=te('<div class="altcha-footer"><p></p> <!></div>');function sa(e,t){St(t,!0);let n=ee(t,"logo"),r=ee(t,"strings");var a={get logo(){return n()},set logo(u){n(u),Z()},get strings(){return r()},set strings(u){r(u),Z()}},o=rc(),l=ae(o);Bo(l,()=>r().footer,!0),X(l);var s=Q(l,2);{var c=u=>{Ta(u,{get strings(){return r()}})};he(s,u=>{n()&&u(c)})}return X(o),F(e,o),$t(a)}Bt(sa,{logo:{},strings:{}},[],[],{mode:"open"});var ac=te('<div class="altcha-switch"><input/>  <div class="altcha-switch-toggle"><div class="altcha-spinner altcha-switch-spinner"></div></div></div>');function wi(e,t){St(t,!0);let n=ee(t,"loading"),r=kr(t,["$$slots","$$events","$$legacy","$$host","loading"]),a;function o(){a?.click()}var l={get loading(){return n()},set loading(h){n(h),Z()}},s=ac(),c=ae(s);_r(c,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),Ut(c,h=>a=h,()=>a);var u=Q(c,2);return X(s),we(()=>Y(s,"data-loading",n())),yr("click",u,o),F(e,s),$t(l)}wr(["click"]);Bt(wi,{loading:{}},[],[],{mode:"open"});var me=(e=>(e.ERROR="error",e.LOADING="loading",e.PLAYING="playing",e.PAUSED="paused",e.READY="ready",e))(me||{}),B=(e=>(e.CODE="code",e.ERROR="error",e.VERIFIED="verified",e.VERIFYING="verifying",e.UNVERIFIED="unverified",e.EXPIRED="expired",e))(B||{}),oc=te('<div class="altcha-code-challenge-title"> </div>'),ic=te('<div class="altcha-spinner"></div>'),lc=ma('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>'),sc=ma('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 7C15 6.44772 15.4477 6 16 6C16.5523 6 17 6.44772 17 7V17C17 17.5523 16.5523 18 16 18C15.4477 18 15 17.5523 15 17V7ZM7 7C7 6.44772 7.44772 6 8 6C8.55228 6 9 6.44772 9 7V17C9 17.5523 8.55228 18 8 18C7.44772 18 7 17.5523 7 17V7Z"></path></svg>'),cc=ma('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 12H7C8.10457 12 9 12.8954 9 14V19C9 20.1046 8.10457 21 7 21H4C2.89543 21 2 20.1046 2 19V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12V19C22 20.1046 21.1046 21 20 21H17C15.8954 21 15 20.1046 15 19V14C15 12.8954 15.8954 12 17 12H20C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12Z"></path></svg>'),uc=te('<button type="button" class="altcha-button altcha-button-secondary"><!></button>'),fc=te('<audio hidden="" autoplay=""></audio>'),dc=te('<div class="altcha-code-challenge"><form data-code-challenge="true"><!> <div class="altcha-code-challenge-text"> </div> <img class="altcha-code-challenge-image" alt=""/> <div class="altcha-code-challenge-row"><input type="text" class="altcha-input" autocomplete="off" name="" required=""/> <!> <button type="button" class="altcha-button altcha-button-secondary"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 9.25022 5.38734 6.82447 7.50024 5.38451L7.5 8H9.5V2L3.5 2V4L5.99918 3.99989C3.57075 5.82434 2 8.72873 2 12Z"></path></svg></button></div> <div class="altcha-code-challenge-buttons"><button type="submit" class="altcha-button"> </button> <button type="button" class="altcha-button altcha-button-secondary"> </button></div></form> <!></div>');function _i(e,t){St(t,!0);let n=ee(t,"audioUrl"),r=ee(t,"codeChallenge"),a=ee(t,"config"),o=ee(t,"imageUrl"),l=ee(t,"onCancel"),s=ee(t,"onReload"),c=ee(t,"onSubmit"),u=ee(t,"strings"),h=N(void 0),g=N(void 0),v=N(void 0),y=N(!1),b=N(""),$=N(!1);xa(()=>(a().disableAutoFocus||Zt().then(()=>{i(v)?.focus()}),()=>{i(g)&&(i(g).pause(),k(g,void 0))}));function x(){k(h,me.PAUSED,!0)}function D(w){k(h,me.ERROR,!0)}function fe(){k(h,me.READY,!0)}function ce(){k(h,me.LOADING,!0)}function de(){k(h,me.PLAYING,!0)}function G(){k(h,me.PAUSED,!0)}function ot(w){w.code==="Space"?(w.preventDefault(),w.stopPropagation(),U()):w.code==="Escape"&&(w.preventDefault(),w.stopPropagation(),l()?.())}function C(w){w.preventDefault(),w.stopPropagation(),c()?.(i(b))}function U(){i(g)?i(h)===me.LOADING||(i(g).paused?(n()&&i(g).src!==n()&&(i(g).src=n()),i(g).currentTime=0,i(g).play()):i(g).pause()):(k($,!0),requestAnimationFrame(()=>{i(g)&&n()&&(i(g).src=n(),i(g).play())}))}var oe={get audioUrl(){return n()},set audioUrl(w){n(w),Z()},get codeChallenge(){return r()},set codeChallenge(w){r(w),Z()},get config(){return a()},set config(w){a(w),Z()},get imageUrl(){return o()},set imageUrl(w){o(w),Z()},get onCancel(){return l()},set onCancel(w){l(w),Z()},get onReload(){return s()},set onReload(w){s(w),Z()},get onSubmit(){return c()},set onSubmit(w){c(w),Z()},get strings(){return u()},set strings(w){u(w),Z()}},z=dc(),j=ae(z),We=ae(j);{var xe=w=>{var ie=oc(),Yt=ae(ie,!0);X(ie),we(()=>ht(Yt,u().verificationRequired)),F(w,ie)};he(We,w=>{a().codeChallengeDisplay!=="standard"&&w(xe)})}var Se=Q(We,2),ne=ae(Se,!0);X(Se);var it=Q(Se,2),S=Q(it,2),q=ae(S);_a(q),q.disabled=i(y),Ut(q,w=>k(v,w),()=>i(v));var Ie=Q(q,2);{var m=w=>{var ie=uc(),Yt=ae(ie);{var Sr=$e=>{var lt=ic();F($e,lt)},Rn=$e=>{var lt=lc();F($e,lt)},$r=$e=>{var lt=sc();F($e,lt)},Tr=$e=>{var lt=cc();F($e,lt)};he(Yt,$e=>{i(h)===me.LOADING?$e(Sr):i(h)===me.ERROR?$e(Rn,1):i(h)===me.PLAYING?$e($r,2):$e(Tr,-1)})}X(ie),we(()=>{Y(ie,"title",u().getAudioChallenge),ie.disabled=i(h)===me.LOADING||i(h)===me.ERROR,Y(ie,"aria-label",i(h)===me.LOADING?u().loading:u().getAudioChallenge)}),ve("click",ie,()=>U(),!0),F(w,ie)};he(Ie,w=>{r().audio&&w(m)})}var Ht=Q(Ie,2);X(S);var Qn=Q(S,2),Ve=ae(Qn),Cr=ae(Ve,!0);X(Ve);var Kt=Q(Ve,2),$n=ae(Kt,!0);X(Kt),X(Qn),X(j);var Tn=Q(j,2);{var An=w=>{var ie=fc();Ut(ie,Yt=>k(g,Yt),()=>i(g)),ve("error",ie,D),ve("loadstart",ie,ce),ve("canplay",ie,fe),ve("pause",ie,G),ve("playing",ie,de),ve("ended",ie,x),F(w,ie)};he(Tn,w=>{i($)&&w(An)})}return X(z),we(()=>{ht(ne,u().enterCodeFromImage),Y(it,"src",o()),Y(q,"minlength",r().length||1),Y(q,"maxlength",r().length),Y(q,"placeholder",u().enterCode),Y(q,"aria-label",i(h)===me.LOADING?u().loading:i(h)===me.PLAYING?"":u().enterCodeAria),Y(q,"aria-live",i(h)?"assertive":"polite"),Y(q,"aria-busy",i(h)===me.LOADING),Y(Ht,"title",u().reload),Y(Ht,"aria-label",u().reload),Y(Ve,"aria-label",u().verify),ht(Cr,u().verify),Y(Kt,"aria-label",u().cancel),ht($n,u().cancel)}),ve("submit",j,C,!0),yr("keydown",q,ot),Ls(q,()=>i(b),w=>k(b,w)),ve("click",Ht,()=>s()?.(),!0),ve("click",Kt,()=>l()?.(),!0),F(e,z),$t(oe)}wr(["keydown"]);Bt(_i,{audioUrl:{},codeChallenge:{},config:{},imageUrl:{},onCancel:{},onReload:{},onSubmit:{},strings:{}},[],[],{mode:"open"});var hc=te('<div class="altcha-popover-backdrop" data-backdrop=""></div>'),vc=te('<div class="altcha-popover-arrow"></div>'),pc=te('<div role="button" class="altcha-popover-close">&times;</div>'),gc=te('<!> <div><!> <!> <div class="altcha-popover-content"><!></div></div>',1);function ca(e,t){St(t,!0);let n=ee(t,"anchor"),r=ee(t,"children"),a=ee(t,"display",7,"standard"),o=ee(t,"backdrop",7,!1),l=ee(t,"onClickOutside"),s=ee(t,"onClickOutsideDelay",7,600),c=ee(t,"onClose"),u=ee(t,"placement",7,"auto"),h=ee(t,"updateUISignal"),g=ee(t,"variant",7,"neutral"),v=kr(t,["$$slots","$$events","$$legacy","$$host","anchor","children","display","backdrop","onClickOutside","onClickOutsideDelay","onClose","placement","updateUISignal","variant"]),y=N(void 0),b=N(void 0),$=N(!1),x=N(0);Le(()=>{u()!=="auto"&&k($,u()==="top")}),Le(()=>{h()&&G()}),xa(()=>{let S=a()==="bottomsheet"||a()==="overlay";return S&&(i(b)&&document.body.append(i(b)),i(y)&&document.body.append(i(y))),G(),Zt().then(()=>{k(x,Date.now(),!0)}),()=>{S&&(i(b)&&document.body.removeChild(i(b)),i(y)&&document.body.removeChild(i(y)))}});function D(){c()?.()}function fe(S){let q=S.target;!i(y)?.contains(q)&&(!s()||i(x)+s()<Date.now())&&l()?.()}function ce(){G()}function de(){G()}function G(){if(n()&&u()==="auto"&&i(y)){let S=n().getBoundingClientRect(),Ie=document.documentElement.clientHeight-(S.top+S.height)<i(y).clientHeight;i($)!==Ie&&k($,Ie)}}var ot={get anchor(){return n()},set anchor(S){n(S),Z()},get children(){return r()},set children(S){r(S),Z()},get display(){return a()},set display(S="standard"){a(S),Z()},get backdrop(){return o()},set backdrop(S=!1){o(S),Z()},get onClickOutside(){return l()},set onClickOutside(S){l(S),Z()},get onClickOutsideDelay(){return s()},set onClickOutsideDelay(S=600){s(S),Z()},get onClose(){return c()},set onClose(S){c(S),Z()},get placement(){return u()},set placement(S="auto"){u(S),Z()},get updateUISignal(){return h()},set updateUISignal(S){h(S),Z()},get variant(){return g()},set variant(S="neutral"){g(S),Z()}},C=gc();ve("click",en,fe,!0),ve("resize",en,ce),ve("scroll",en,de);var U=cn(C);{var oe=S=>{var q=hc();Ut(q,Ie=>k(b,Ie),()=>i(b)),F(S,q)};he(U,S=>{o()&&S(oe)})}var z=Q(U,2);_r(z,()=>({...v,class:`altcha-popover ${(t.class||"")??""}`,"data-popover":!0,"data-variant":g(),"data-top":i($),"data-display":a()}));var j=ae(z);{var We=S=>{var q=vc();F(S,q)};he(j,S=>{a()==="standard"&&S(We)})}var xe=Q(j,2);{var Se=S=>{var q=pc();ve("click",q,D,!0),F(S,q)};he(xe,S=>{a()!=="standard"&&S(Se)})}var ne=Q(xe,2),it=ae(ne);return gs(it,()=>r()??Pt),X(ne),X(z),Ut(z,S=>k(y,S),()=>i(y)),F(e,C),$t(ot)}Bt(ca,{anchor:{},children:{},display:{},backdrop:{},onClickOutside:{},onClickOutsideDelay:{},onClose:{},placement:{},updateUISignal:{},variant:{}},[],[],{mode:"open"});function bc(e){return Array.from(new Uint8Array(e)).map(t=>t.toString(16).padStart(2,"0")).join("")}function mc(e,t="altcha-css"){if(typeof document<"u"&&document&&!document.getElementById(t)){let n=document.createElement("style");n.id=t,n.textContent=e,document.head.appendChild(n)}}async function ki(e){let{challenge:t,concurrency:n=navigator.hardwareConcurrency,controller:r=new AbortController,createWorker:a,onOutOfMemory:o=v=>v>1?Math.floor(v/2):0,counterMode:l,timeout:s=9e4}=e,c=Math.min(16,Math.max(1,n)),u=[],h=()=>{for(let v of u)v.terminate()};for(let v=0;v<c;v++)u.push(await a(t.parameters.algorithm));let g=null;try{g=await Promise.race(u.map((v,y)=>(r.signal.addEventListener("abort",()=>{v.postMessage({type:"abort"})}),new Promise((b,$)=>{v.addEventListener("error",x=>{$(x)}),v.addEventListener("message",x=>{if(x.data){for(let D of u)D!==v&&D.postMessage({type:"abort"});if(x.data.error)return $(new Error(x.data.error))}b(x.data)}),v.postMessage({challenge:t,counterMode:l,counterStart:y,counterStep:c,timeout:s,type:"work"})}))))}catch(v){if(v instanceof Error&&!!v?.message?.includes("Out of memory")&&o){h();let b=o(c);if(b)return ki({...e,challenge:t,controller:r,concurrency:b,createWorker:a})}throw v}finally{h()}return r.signal.aborted?null:g||null}var ua=class{constructor(t={}){A(this,"TAG_CODES",{INPUT:1,TEXTAREA:2,SELECT:3,BUTTON:4,A:5,DETAILS:6,SUMMARY:7,IFRAME:8,VIDEO:9,AUDIO:10});A(this,"maxSamples");A(this,"sampleInterval");A(this,"target");A(this,"focusStartTime",0);A(this,"focusInteraction",0);A(this,"focusInteractionTimer",null);A(this,"lastPointerSample",0);A(this,"lastTouchSample",0);A(this,"lastScrollSample",0);A(this,"pendingPointer",null);A(this,"pendingTouch",null);A(this,"focus",[]);A(this,"pointer",[]);A(this,"scroll",[]);A(this,"touch",[]);A(this,"onFocus",t=>{if(this.focusInteraction===2)return;let n=t.target;if(!(n instanceof Element))return;let r=performance.now();this.focusStartTime===0&&(this.focusStartTime=r),this.focus.push([Math.round(r-this.focusStartTime),n.tabIndex,this.TAG_CODES[n.tagName]??0,this.focusInteraction?1:0]),this.evict(this.focus)});A(this,"onInteraction",t=>{this.focusInteraction="keyCode"in t?1:2,this.focusInteractionTimer&&clearTimeout(this.focusInteractionTimer),this.focusInteractionTimer=setTimeout(()=>{this.focusInteraction=0},100)});A(this,"onPointer",t=>{if(t.pointerType==="touch")return;let n=t.timeStamp||performance.now();this.pendingPointer=[Math.round(t.clientX),Math.round(t.clientY),Math.round(n)],n-this.lastPointerSample>=this.sampleInterval&&(this.pointer.push(this.pendingPointer),this.lastPointerSample=n,this.pendingPointer=null,this.evict(this.pointer))});A(this,"onScroll",()=>{let t=performance.now();t-this.lastScrollSample<this.sampleInterval||(this.scroll.push([Math.round(window.scrollY),Math.round(t)]),this.lastScrollSample=t,this.evict(this.scroll))});A(this,"onTouchMove",t=>{let n=t.timeStamp||performance.now(),r=t.touches[0];r&&(this.pendingTouch=[Math.round(r.clientX),Math.round(r.clientY),Math.round(n),Math.round(r.force*1e3)/1e3,Math.round(r.radiusX||0),Math.round(r.radiusY||0)],n-this.lastTouchSample>=this.sampleInterval&&(this.touch.push(this.pendingTouch),this.lastTouchSample=n,this.pendingTouch=null,this.evict(this.touch)))});let{maxSamples:n=60,sampleInterval:r=50,target:a=window}=t;this.maxSamples=n,this.sampleInterval=r,this.target=a,this.attach()}destroy(){let t={capture:!0};this.target.removeEventListener("focusin",this.onFocus,t),this.target.removeEventListener("keydown",this.onInteraction,t),this.target.removeEventListener("pointerdown",this.onInteraction,t),this.target.removeEventListener("pointermove",this.onPointer,t),this.target.removeEventListener("scroll",this.onScroll,t),this.target.removeEventListener("touchmove",this.onTouchMove,t)}export(){return{focus:this.focus,maxTouchPoints:navigator.maxTouchPoints||0,pointer:this.pointer,scroll:this.scroll,time:Date.now(),touch:this.touch}}attach(){let t={passive:!0,capture:!0};this.target.addEventListener("focusin",this.onFocus,t),this.target.addEventListener("keydown",this.onInteraction,t),this.target.addEventListener("pointerdown",this.onInteraction,t),this.target.addEventListener("pointermove",this.onPointer,t),this.target.addEventListener("scroll",this.onScroll,t),this.target.addEventListener("touchmove",this.onTouchMove,t)}evict(t){t.length>this.maxSamples&&t.splice(0,t.length-this.maxSamples)}},yc=te('<div class="altcha-overlay-backdrop" data-backdrop=""></div>'),wc=te('<div class="altcha-overlay-content"></div>'),_c=te('<div role="button" class="altcha-overlay-close">&times;</div> <!>',1),kc=te('<div class="altcha-floating-arrow"></div>'),xc=te('<input type="hidden"/>'),Ec=te('<div class="altcha-error">Secure context (HTTPS) required.</div>'),Cc=te('<div class="altcha-error"> </div>'),Sc=te('<div class="altcha-error"> </div>'),$c=te("<!> <!>",1),Tc=te('<!> <div class="altcha"><!> <div class="altcha-main"><div><div class="altcha-checkbox-wrap"><!> <label><!></label></div> <!></div> <!> <!> <!></div> <!></div>',1);function Ac(e,t){St(t,!0);let n=()=>ro(h,"$altchaDefaults",a),r=()=>ro(b,"$altchaI18nStore",a),[a,o]=Fs(),l='input[type="text"]:not([data-no-spamfilter]), textarea:not([data-no-spamfilter])',s='input[type="submit"], button[type="submit"], button:not([type="button"]):not([type="reset"])',c=["ar","fa","he","ur"],{isSecureContext:u}=globalThis,{store:h}=globalThis.$altcha.defaults,g=navigator.hardwareConcurrency||2,v=navigator.deviceMemory||0,y=v&&v<=4?Math.min(4,g):g,b=globalThis.$altcha.i18n.store,$=t.$$host,x=(f,p)=>{Zt().then(()=>{$?.dispatchEvent(new CustomEvent(f,{detail:p}))})},D=null,fe=N(Et(new URL(location.origin))),ce=N(!1),de=N(null),G=N(null),ot=N(null),C=N(Et(B.UNVERIFIED)),U=N(void 0),oe=N(void 0),z=N(null),j=N(void 0),We=N(null),xe=N(null),Se=N(null),ne=N(null),it=N(Et([])),S=N(0),q=N(Et({})),Ie=N(!0),m=Ee(()=>({fetch:(f,p)=>fetch(f,p),audioChallengeLanguage:"",auto:"off",barPlacement:"bottom",challenge:"",codeChallenge:null,codeChallengeDisplay:"standard",credentials:null,debug:!1,disableAutoFocus:!1,display:"standard",floatingAnchor:"",floatingOffset:8,floatingPersist:!1,floatingPlacement:"auto",hideFooter:!1,hideLogo:!1,humanInteractionSignature:!0,language:"",mockError:!1,minDuration:500,overlayContent:"",name:"altcha",popoverPlacement:"auto",retryOnOutOfMemoryError:!0,setCookie:null,serverVerificationFields:!1,serverVerificationTimeZone:!1,test:!1,timeout:9e4,type:"checkbox",validationMessage:"",verifyFunction:null,verifyUrl:"",workers:y,...n(),...i(q)})),Ht=Ee(()=>`altcha-checkbox-${t.id||Math.floor(Math.random()*1e12).toString(16)}`),Qn=Ee(()=>$i(i(m).type)),Ve=Ee(()=>i(m).auto),Cr=Ee(()=>i(C)===B.VERIFYING),Kt=Ee(()=>!i(m).hideFooter),$n=Ee(()=>!i(m).hideLogo&&i(m).display!=="bar"),Tn=Ee(()=>Ti(r(),[i(m).language,document.documentElement.lang,...navigator.languages])),An=Ee(()=>c.includes(i(Tn).language)?"rtl":void 0),w=Ee(()=>({...i(Tn).strings})),ie=Ee(()=>i(de)?.audio?.match(/^(https?:)?\//)?er(i(de).audio,i(fe),{language:i(m).audioChallengeLanguage||i(Tn).language}).toString():i(de)?.audio),Yt=Ee(()=>i(de)?.image?.match(/^(https?:)?\//)?er(i(de).image,i(fe)):i(de)?.image);Le(()=>{In({auto:t.auto,challenge:t.challenge,display:t.display,language:t.language,name:t.name,type:t.type,workers:t.workers})}),Le(()=>{if(t.configuration)try{In(JSON.parse(t.configuration))}catch{W("unable to parse the `configuration` attribute (JSON expected)")}}),Le(()=>{i(ot)!==i(m).display&&tr(i(m).display)}),Le(()=>{i(ce)&&i(C)===B.VERIFYING&&k(ce,!1)}),Le(()=>{!i(ce)&&i(C)===B.VERIFIED&&k(ce,!0)}),Le(()=>{if(!i(ce)){let f=Ar();f&&f.checked&&(f.checked=!1)}}),Le(()=>{i(C)===B.VERIFIED&&Ar()?.setCustomValidity("")}),Le(()=>{if(i(Ve)==="onload"){let f=setTimeout(()=>{ln()},1);return()=>{f&&clearTimeout(f)}}}),Le(()=>{i(xe)&&W("error:",i(xe))}),Le(()=>{i(ne)&&i(m).setCookie&&zi(i(ne),i(m).setCookie)}),xa(()=>(W("mounted","3.0.9"),$&&globalThis.$altcha.instances.add($),k(z,i(j)?.closest("form"),!0),i(z)?.addEventListener("reset",Pa),i(z)?.addEventListener("submit",La,{capture:!0}),i(z)?.addEventListener("focusin",Oa),Sr(),i(m).humanInteractionSignature&&(W("human interaction signature enabled"),D=new ua),x("load"),u||W("secure context (HTTPS) required"),()=>{$r(),$&&globalThis.$altcha.instances.delete($),i(Se)&&clearTimeout(i(Se)),i(z)?.removeEventListener("reset",Pa),i(z)?.removeEventListener("submit",La,{capture:!0}),i(z)?.removeEventListener("focusin",Oa),D?.destroy()}));function Sr(){k(it,[...globalThis.$altcha.plugins].map(f=>new f($)),!0),W("activating plugins",i(it).map(f=>f.constructor.name));for(let f of i(it))f.activate()}async function Rn(f,...p){let _;for(let E of i(it))_=await E[f].call(E,...p);return _}function $r(){for(let f of i(it))f.destroy()}function Tr(f){let[p,_]=f.salt.split("?"),E={};if(_)try{Object.assign(E,Object.fromEntries(new URLSearchParams(_).entries()))}catch{}let R={codeChallenge:f.codeChallenge,parameters:{algorithm:f.algorithm,cost:1,data:E,expiresAt:E?.expires?parseInt(E.expires,10):void 0,keyLength:f.algorithm==="SHA-512"?64:f.algorithm==="SHA-384"?48:32,nonce:bc(new TextEncoder().encode(f.salt)),keyPrefix:f.challenge,salt:""},signature:f.signature};return Object.defineProperties(R,{_originalSalt:{enumerable:!1,value:f.salt,writable:!1},_version:{enumerable:!1,value:1,writable:!1}}),R}function $e(f,p){return{algorithm:f.parameters.algorithm,challenge:f.parameters.keyPrefix,number:p.counter,salt:"_originalSalt"in f?f._originalSalt:f.parameters.nonce,signature:f.signature,took:p.time||0}}async function lt(f){await new Promise(p=>setTimeout(p,f))}async function Ia(f=i(m).challenge,p){let _=await Rn("onFetchChallenge",f),E=null;if(_!==void 0)return _;if(typeof f=="string")if(f.startsWith("{")){W("parsing JSON challenge");try{E=JSON.parse(f)}catch{throw new Error("Unable to parse JSON challenge.")}}else{W("fetching challenge from",p?.method||"GET",f),k(fe,new URL(f,location.origin),!0);let R=await i(m).fetch(f,{credentials:i(m).credentials||void 0,...p});await Ma(R);let T=R.headers.get("x-altcha-config");T&&Fi(T);let K=await R.json();if(K&&"his"in K&&K.his){if(W("requested HIS"),!D)throw new Error("Server requested HIS data but collector is disabled.");return Ia(er(K.his.url,i(fe)),{body:JSON.stringify({his:D.export()}),headers:{"content-type":"application/json"},method:"POST"})}K&&"hisResult"in K&&K.hisResult&&W("HIS result",K.hisResult),E=K}else if(f&&typeof f=="object")try{E=JSON.parse(JSON.stringify(f))}catch{throw new Error("Unable to parse JSON challenge.")}if(Ci(E)&&(E=Tr(E)),!Si(E))throw new Error("Challenge validation failed.");return E}function Ci(f){return typeof f=="object"&&"challenge"in f}function Si(f){return!!f&&typeof f=="object"&&"parameters"in f&&!!f.parameters&&typeof f.parameters=="object"&&"algorithm"in f.parameters&&"nonce"in f.parameters&&"salt"in f.parameters&&"keyPrefix"in f.parameters}function Ar(){return document.getElementById(i(Ht))}function $i(f){switch(f){case"checkbox":return mi;case"switch":return wi;default:return yi}}function Ti(f,p){let _=Object.keys(f).map(R=>R.toLowerCase()),E=p.reduce((R,T)=>(T=T.toLowerCase(),R||(f[T]?T:null)||_.find(K=>T.split("-")[0]===K.split("-")[0])||null),null);return f[E||""]||(E="en"),{language:E,strings:f[E]}}function Ai(f){switch(f){case"bar":return i(m).barPlacement||"bottom";case"floating":return i(m).floatingPlacement||"auto";default:return}}function Ri(f){return[...i(z)?.querySelectorAll(l)||[]].reduce((_,E)=>{let R=E.name,T=E.value;return R&&T&&(_[R]=/\n/.test(T)?T.replace(new RegExp("(?<!\\r)\\n","g"),`\r
`):T),_},{})}function Ii(){try{return Intl.DateTimeFormat().resolvedOptions().timeZone}catch{}}function er(f,p,_){let E=new URL(f,p);if(E.search||(E.search=p.search),_)for(let R in _)_[R]!==void 0&&_[R]!==null&&E.searchParams.set(R,_[R]);return E.toString()}function Oi(f){!i(ce)&&f.currentTarget.checked?(f.preventDefault(),f.currentTarget.checked=!1,i(C)!==B.VERIFYING&&ln()):f.currentTarget.checked||(f.preventDefault(),je())}function Pi(f){i(C)===B.VERIFYING?f.currentTarget.setCustomValidity(i(w).waitAlert):i(m).validationMessage&&f.currentTarget.setCustomValidity(i(m).validationMessage)}function Li(){tr(i(m).display),je()}function Di(){nr()}function Mi(f){let p=f.target;i(m).display==="floating"&&p&&!$?.contains(p)&&!p.hasAttribute("data-backdrop")&&!p.closest("[data-popover]")&&i(C)!==B.VERIFIED&&!i(m).floatingPersist&&Rr()}function Oa(f){i(Ve)==="onfocus"&&i(C)===B.UNVERIFIED&&ln()}function Pa(){tr(i(m).display),je()}function La(f){f.target?.getAttribute("data-code-challenge")!=="true"&&i(Ve)==="onsubmit"&&i(C)===B.UNVERIFIED&&(f.preventDefault(),f.stopPropagation(),k(We,f.submitter,!0),Ir(),ln().then(_=>{_&&!i(de)&&Zt().then(()=>{Da(i(We))})}))}function Ni(f){f.persisted&&(tr(i(m).display),je())}function Ui(){nr()}function Fi(f){try{let p=JSON.parse(f);p&&typeof p=="object"&&In({serverVerificationFields:p?.sentinel?.fields,serverVerificationTimeZone:p?.sentinel?.timeZone,verifyUrl:p.verifyurl,...p})}catch(p){W("unable to configure from x-altcha-config header",p)}}function Vi(f=20){if(!i(j))return;let p=i(m).floatingPlacement;if(!i(oe)&&(k(oe,(i(m).floatingAnchor instanceof HTMLElement?i(m).floatingAnchor:i(m).floatingAnchor?document.querySelector(i(m).floatingAnchor):i(z)?.querySelector(s))||i(z),!0),!i(oe))){W("unable to find floating anchor element");return}let _=parseInt(i(m).floatingOffset,10)||12,E=i(oe).getBoundingClientRect(),R=i(j).getBoundingClientRect(),T=document.documentElement.clientHeight,K=document.documentElement.clientWidth,Oe=!p||p==="auto"?E.bottom+R.height+_+f>T:p==="top",J=Math.max(f,Math.min(K-f-R.width,E.left+E.width/2-R.width/2));if(i(j).style.setProperty("--altcha-floating-left",`${J}px`),i(j).style.setProperty("--altcha-floating-top",Oe?`${E.top-(R.height+_)}px`:`${E.bottom+_}px`),i(j).setAttribute("data-floating-position",Oe?"top":"bottom"),i(U)){let ue=i(U).getBoundingClientRect();i(U).style.left=E.left-J+E.width/2-ue.width/2+"px"}}async function ji(f,p){let _=await Rn("onRequestServerVerification",f,p);if(_!==void 0)return _;if(W("requesting server verification from",i(m).verifyUrl),!i(m).verifyUrl)throw new Error("Parameter verifyUrl must be set for server verification.");let E=await i(m).fetch(er(i(m).verifyUrl,i(fe)),{body:JSON.stringify({code:p,fields:i(m).serverVerificationFields?Ri():void 0,payload:f,timeZone:i(m).serverVerificationTimeZone?Ii():void 0}),credentials:i(m).credentials||void 0,headers:{"Content-Type":"application/json"},method:"POST"});await Ma(E);let R=await E.json();return R&&typeof R=="object"&&"payload"in R&&R.payload&&x("serververification",R),R}function Da(f){i(z)&&"requestSubmit"in i(z)?i(z).requestSubmit(f):i(z)?.reportValidity()&&(f?f.click():i(z).submit())}function zi(f,p={}){let{domain:_,name:E=i(m).name,maxAge:R,path:T,sameSite:K,secure:Oe}=p,J=`${encodeURIComponent(E)}=${encodeURIComponent(f)}`;_&&(J+=`; Domain=${_}`),R!=null&&(J+=`; Max-Age=${R}`),T&&(J+=`; Path=${T}`),K&&(J+=`; SameSite=${K}`),Oe&&(J+="; Secure"),document.cookie=J}function tr(f){switch(f){case"bar":case"floating":case"overlay":Rr(),(!i(Ve)||i(Ve)==="off")&&(i(q).auto="onsubmit");break;case"standard":Ir()}i(ot)!==f&&k(ot,f,!0)}function Bi(f){i(Se)&&clearTimeout(i(Se));let p=()=>{i(C)!==B.UNVERIFIED?(k(ce,!1),ze(B.EXPIRED)):je(),x("expired")},_=f*1e3-Date.now();_>=1?k(Se,setTimeout(p,_),!0):p()}async function Ma(f){if(f.status>=400){if(f.headers.get("content-type")?.includes("/json")){let _;try{_=await f.json()}catch{}if(_&&"error"in _)throw new Error(`Server responded with ${f.status} - ${_.error}`)}throw new Error(`Server responded with ${f.status}.`)}let p=f.headers.get("content-type");if(!p||!p.includes("/json"))throw new Error(`Server responded with invalid content-type. Expected application/json, received ${p}.`)}async function Na(f){if(!i(ne)){ze(B.ERROR,"Cannot verify code challenge without PoW payload.");return}ze(B.VERIFYING);let p=null;if(i(m).verifyUrl)p=await ji(i(ne),f);else if(i(m).verifyFunction)p=await i(m).verifyFunction(i(ne),f);else{ze(B.ERROR,"Parameter verifyUrl is required for code challenge verification.");return}p?.payload&&(k(ne,p.payload,!0),W("server payload",i(ne))),p?.verified===!0?(W("verified"),ze(B.VERIFIED),x("verified",{payload:i(ne)}),i(Ve)==="onsubmit"&&Zt().then(()=>{Da(i(We))})):ze(B.ERROR,p?.reason||"Verification failed."),i(m).disableAutoFocus||Ar()?.focus()}function In(f){Object.assign(i(q),{...Object.fromEntries(Object.entries(f).filter(([p,_])=>_!==void 0))})}function Hi(){return{...i(m)}}function Ki(){return i(C)}function Rr(){k(Ie,!1)}function W(...f){(i(m).debug||f.some(p=>p instanceof Error))&&console[f[0]instanceof Error?"error":"log"]("ALTCHA",`[name=${i(m).name}]`,...f)}function je(f=B.UNVERIFIED,p=null){k(ce,!1),k(xe,p,!0),k(ne,null),i(G)&&i(G).abort(),i(Se)&&(clearTimeout(i(Se)),k(Se,null)),ze(f)}function ze(f,p=null){k(C,f,!0),k(xe,p,!0),x("statechange",{payload:i(ne),state:i(C)})}function Ir(){k(Ie,!0),Zt().then(()=>{nr()})}function nr(){if(i(m).display==="floating")return Vi();k(S,i(S)+1)}async function ln(f={}){let{concurrency:p=Math.max(1,i(m).workers),controller:_=new AbortController,minDuration:E=i(m).minDuration}=f,R=performance.now(),T=null,K=null,Oe=!1,J=await Rn("onVerify",f);if(J!==void 0)return J;je(B.VERIFYING),k(G,_,!0);try{if(!u)throw new Error("Secure context (HTTPS) required.");if(i(m).mockError)throw new Error("Mock error.");if(i(m).test)return W("running test mode with null challenge"),await lt(Math.max(0,E-(performance.now()-R))),i(G)?.signal.aborted?(je(),null):(k(ne,btoa(JSON.stringify({challenge:null,solution:null,test:!0})),!0),W("verified"),ze(B.VERIFIED),x("verified",{payload:i(ne)}),{payload:i(ne)});if(T=await Ia(),!T)throw new Error("Failed to fetch challenge.");W("challenge",T),"configuration"in T&&(W("re-configuring from challenge",T.configuration),In(T.configuration)),T.parameters.expiresAt&&Bi(T.parameters.expiresAt),Oe="_version"in T&&T._version===1;let ue=globalThis.$altcha.algorithms.get(T.parameters.algorithm);if(!ue)throw new Error(`Unsupported algorithm ${T.parameters.algorithm}.`);if(K=await ki({challenge:T,concurrency:p,controller:_,createWorker:ue,counterMode:Oe?"string":"uint32",onOutOfMemory:Tt=>{if(W("out of memory error received"),x("outofmemory"),i(m).retryOnOutOfMemoryError&&Tt>1){let At=Math.floor(Tt/2);return W(`retrying with ${At} workers...`),At}},timeout:i(m).timeout}),i(G)?.signal.aborted)return je(),null;if(!K)throw new Error("Failed to find solution.");W("solution",K),await lt(Math.max(0,E-(performance.now()-R))),k(de,T.codeChallenge||i(m).codeChallenge||null,!0),Oe?k(ne,btoa(JSON.stringify($e(T,K))),!0):k(ne,btoa(JSON.stringify({challenge:{parameters:T.parameters,signature:T.signature},solution:K})),!0),i(de)?(W("requesting code verification"),ze(B.CODE),x("codechallenge",{codeChallenge:i(de)})):i(m).verifyUrl?await Na():(W("verified"),ze(B.VERIFIED),x("verified",{payload:i(ne)}))}catch(ue){return W("verification failed",ue),ze(B.ERROR,String(ue)),null}finally{k(G,null)}return{challenge:T,payload:i(ne),solution:K}}var Yi={configure:In,getConfiguration:Hi,getState:Ki,hide:Rr,log:W,reset:je,setState:ze,show:Ir,updateUI:nr,verify:ln},Ua=Tc();ve("scroll",Kr,Di),ve("click",Kr,Mi),ve("pageshow",en,Ni),ve("resize",en,Ui);var Fa=cn(Ua);{var Gi=f=>{var p=yc();F(f,p)};he(Fa,f=>{i(m).display==="overlay"&&i(Ie)&&f(Gi)})}var st=Q(Fa,2),Va=ae(st);{var qi=f=>{var p=_c(),_=cn(p),E=Q(_,2);{var R=T=>{var K=wc();Bo(K,()=>document.querySelector(i(m).overlayContent)?.innerHTML,!0),X(K),F(T,K)};he(E,T=>{i(m).overlayContent&&T(R)})}ve("click",_,Li,!0),F(f,p)};he(Va,f=>{i(m).display==="overlay"&&i(Ie)&&f(qi)})}var Or=Q(Va,2),Pr=ae(Or),Lr=ae(Pr),ja=ae(Lr);{let f=Ee(()=>i(m).display==="standard"&&i(Ve)!=="onsubmit"||i(C)===B.VERIFYING);bs(ja,()=>i(Qn),(p,_)=>{_(p,{get id(){return i(Ht)},name:"",get required(){return i(f)},get loading(){return i(Cr)},get checked(){return i(ce)},onchange:Oi,oninvalid:Pi})})}var Dr=Q(ja,2),Wi=ae(Dr);{var Zi=f=>{var p=rr();we(()=>ht(p,i(w).verificationRequired)),F(f,p)},Ji=f=>{var p=rr();we(()=>ht(p,i(w).verifying)),F(f,p)},Xi=f=>{var p=rr();we(()=>ht(p,i(w).verified)),F(f,p)},Qi=f=>{var p=rr();we(()=>ht(p,i(w).label)),F(f,p)};he(Wi,f=>{i(C)===B.CODE&&i(de)?f(Zi):i(C)===B.VERIFYING?f(Ji,1):i(C)===B.VERIFIED?f(Xi,2):f(Qi,-1)})}X(Dr),X(Lr);var el=Q(Lr,2);{var tl=f=>{Ta(f,{get strings(){return i(w)}})};he(el,f=>{i($n)&&f(tl)})}X(Pr);var za=Q(Pr,2);{var nl=f=>{{let p=Ee(()=>i(m).display==="bar"&&i($n));sa(f,{get logo(){return i(p)},get strings(){return i(w)}})}};he(za,f=>{i(Kt)&&f(nl)})}var Ba=Q(za,2);{var rl=f=>{var p=kc();Ut(p,_=>k(U,_),()=>i(U)),F(f,p)};he(Ba,f=>{i(m).display==="floating"&&f(rl)})}var al=Q(Ba,2);{var ol=f=>{var p=xc();_a(p),we(()=>{Y(p,"name",i(m).name),Is(p,i(ne))}),F(f,p)};he(al,f=>{i(m).setCookie||f(ol)})}X(Or);var il=Q(Or,2);{var ll=f=>{ca(f,{get anchor(){return i(j)},onClickOutside:()=>{u&&je()},get placement(){return i(m).popoverPlacement},role:"alert",variant:"error",get dir(){return i(An)},get updateUISignal(){return i(S)},children:(p,_)=>{var E=Ja(),R=cn(E);{var T=J=>{var ue=Ec();F(J,ue)},K=J=>{var ue=Cc(),Tt=ae(ue,!0);X(ue),we(()=>ht(Tt,i(w).expired)),F(J,ue)},Oe=J=>{var ue=Sc(),Tt=ae(ue,!0);X(ue),we(()=>{Y(ue,"title",i(xe)),ht(Tt,i(w).error)}),F(J,ue)};he(R,J=>{!i(xe)&&!u?J(T):!i(xe)&&i(C)===B.EXPIRED?J(K,1):J(Oe,-1)})}F(p,E)},$$slots:{default:!0}})},sl=f=>{var p=Ja(),_=cn(p);ps(_,()=>i(de),E=>{{let R=Ee(()=>i(m).codeChallengeDisplay!=="standard");ca(E,{get anchor(){return i(j)},get backdrop(){return i(R)},get display(){return i(m).codeChallengeDisplay},onClose:()=>{je()},get placement(){return i(m).popoverPlacement},role:"dialog",get"aria-label"(){return i(w).verificationRequired},get dir(){return i(An)},get updateUISignal(){return i(S)},children:(T,K)=>{var Oe=$c(),J=cn(Oe);_i(J,{get audioUrl(){return i(ie)},get imageUrl(){return i(Yt)},onCancel:()=>je(),onReload:()=>ln(),onSubmit:At=>Na(At),get codeChallenge(){return i(de)},get config(){return i(m)},get strings(){return i(w)}});var ue=Q(J,2);{var Tt=At=>{sa(At,{get logo(){return i($n)},get strings(){return i(w)}})};he(ue,At=>{i(Kt)&&i(m).codeChallengeDisplay!=="standard"&&At(Tt)})}F(T,Oe)},$$slots:{default:!0}})}}),F(f,p)};he(il,f=>{i(xe)||i(C)===B.EXPIRED||!u?f(ll):i(de)&&i(C)===B.CODE&&f(sl,1)})}X(st),Ut(st,f=>k(j,f),()=>i(j)),we(f=>{Y(st,"data-state",i(C)),Y(st,"data-display",i(m).display||void 0),Y(st,"data-placement",f),Y(st,"data-visible",i(Ie)||void 0),Y(st,"dir",i(An)),Y(Dr,"for",i(Ht)),st.dir=st.dir},[()=>Ai(i(m).display)]),F(e,Ua);var cl=$t(Yi);return o(),cl}typeof window<"u"&&window.customElements&&customElements.define("altcha-widget",Bt(Ac,{auto:{type:"String"},challenge:{type:"String"},configuration:{type:"String"},display:{type:"String"},language:{type:"String"},name:{type:"String"},theme:{type:"String"},type:{type:"String"},workers:{type:"Number"}},[],["configure","getConfiguration","getState","hide","log","reset","setState","show","updateUI","verify"]));var xi=`(function() {
  "use strict";
  function bufferStartsWith(buffer, prefix) {
    if (prefix.length > buffer.length) {
      return false;
    }
    for (let i = 0; i < prefix.length; i++) {
      if (buffer[i] !== prefix[i]) {
        return false;
      }
    }
    return true;
  }
  function bufferToHex(buffer) {
    return Array.from(new Uint8Array(buffer)).map((b) => b.toString(16).padStart(2, "0")).join("");
  }
  function concatBuffers(a, b) {
    const out = new Uint8Array(a.length + b.length);
    out.set(a, 0);
    out.set(b, a.length);
    return out;
  }
  function hexToBuffer(hex) {
    if (hex.length % 2 !== 0) {
      throw new Error(\`Hex string must have an even length. Got: \${hex}\`);
    }
    const buffer = new ArrayBuffer(hex.length / 2);
    const view = new DataView(buffer);
    for (let i = 0; i < hex.length; i += 2) {
      const byteString = hex.substring(i, i + 2);
      const byteValue = parseInt(byteString, 16);
      view.setUint8(i / 2, byteValue);
    }
    return new Uint8Array(buffer);
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     */
    setCounter(n) {
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  async function solveChallenge(options) {
    const {
      challenge,
      controller,
      counterMode = "uint32",
      counterStart = 0,
      counterStep = 1,
      deriveKey: deriveKey2,
      timeout = 9e4
    } = options;
    const { nonce, keyPrefix, salt } = challenge.parameters;
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixBuf = keyPrefix.length % 2 === 0 ? hexToBuffer(keyPrefix) : null;
    const password = new PasswordBuffer(nonceBuf, counterMode);
    const start = performance.now();
    let counter = counterStart;
    let iterations = 0;
    let derivedKeyHex = "";
    let lastYield = start;
    while (true) {
      if (controller?.signal.aborted || timeout && iterations % 10 === 0 && performance.now() - start > timeout) {
        return null;
      }
      const { derivedKey } = await deriveKey2(
        challenge.parameters,
        saltBuf,
        password.setCounter(counter)
      );
      if (iterations % 10 === 0 && performance.now() - lastYield > 200) {
        await delay(0);
        lastYield = performance.now();
      }
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefix)) {
        derivedKeyHex = bufferToHex(derivedKey);
        break;
      }
      counter = counter + counterStep;
      iterations = iterations + 1;
    }
    return {
      counter,
      derivedKey: derivedKeyHex,
      time: timeDuration(start)
    };
  }
  function handler(options) {
    const { deriveKey: deriveKey2 } = options;
    let controller = void 0;
    self.onmessage = async (message) => {
      const { challenge, counterMode, counterStart, counterStep, timeout, type } = message.data;
      if (type === "abort") {
        controller?.abort();
      } else if (type === "work") {
        controller = new AbortController();
        let solution;
        try {
          solution = await solveChallenge({
            challenge,
            controller,
            counterStart,
            counterStep,
            deriveKey: deriveKey2,
            counterMode,
            timeout
          });
        } catch (err) {
          return self.postMessage({ error: err });
        }
        self.postMessage(solution);
      }
    };
  }
  function getDigest(algorithm) {
    switch (algorithm) {
      case "PBKDF2/SHA-512":
        return "SHA-512";
      case "PBKDF2/SHA-384":
        return "SHA-384";
      case "PBKDF2/SHA-256":
      default:
        return "SHA-256";
    }
  }
  async function deriveKey(parameters, salt, password) {
    const { algorithm, cost, keyLength = 32 } = parameters;
    const passwordKey = await crypto.subtle.importKey(
      "raw",
      password,
      { name: "PBKDF2" },
      false,
      ["deriveKey"]
    );
    const derivedKey = await crypto.subtle.deriveKey(
      {
        name: "PBKDF2",
        salt,
        iterations: cost,
        hash: getDigest(algorithm)
      },
      passwordKey,
      { name: "AES-GCM", length: keyLength * 8 },
      true,
      ["encrypt"]
    );
    return {
      derivedKey: new Uint8Array(await crypto.subtle.exportKey("raw", derivedKey))
    };
  }
  handler({
    deriveKey
  });
})();
`,co=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",xi],{type:"text/javascript;charset=utf-8"});function Aa(e){let t;try{if(t=co&&(self.URL||self.webkitURL).createObjectURL(co),!t)throw"";let n=new Worker(t,{name:e?.name});return n.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(t)}),n}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(xi),{name:e?.name})}}var Ei=`(function() {
  "use strict";
  function bufferStartsWith(buffer, prefix) {
    if (prefix.length > buffer.length) {
      return false;
    }
    for (let i = 0; i < prefix.length; i++) {
      if (buffer[i] !== prefix[i]) {
        return false;
      }
    }
    return true;
  }
  function bufferToHex(buffer) {
    return Array.from(new Uint8Array(buffer)).map((b) => b.toString(16).padStart(2, "0")).join("");
  }
  function concatBuffers(a, b) {
    const out = new Uint8Array(a.length + b.length);
    out.set(a, 0);
    out.set(b, a.length);
    return out;
  }
  function hexToBuffer(hex) {
    if (hex.length % 2 !== 0) {
      throw new Error(\`Hex string must have an even length. Got: \${hex}\`);
    }
    const buffer = new ArrayBuffer(hex.length / 2);
    const view = new DataView(buffer);
    for (let i = 0; i < hex.length; i += 2) {
      const byteString = hex.substring(i, i + 2);
      const byteValue = parseInt(byteString, 16);
      view.setUint8(i / 2, byteValue);
    }
    return new Uint8Array(buffer);
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     */
    setCounter(n) {
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  async function solveChallenge(options) {
    const {
      challenge,
      controller,
      counterMode = "uint32",
      counterStart = 0,
      counterStep = 1,
      deriveKey: deriveKey2,
      timeout = 9e4
    } = options;
    const { nonce, keyPrefix, salt } = challenge.parameters;
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixBuf = keyPrefix.length % 2 === 0 ? hexToBuffer(keyPrefix) : null;
    const password = new PasswordBuffer(nonceBuf, counterMode);
    const start = performance.now();
    let counter = counterStart;
    let iterations = 0;
    let derivedKeyHex = "";
    let lastYield = start;
    while (true) {
      if (controller?.signal.aborted || timeout && iterations % 10 === 0 && performance.now() - start > timeout) {
        return null;
      }
      const { derivedKey } = await deriveKey2(
        challenge.parameters,
        saltBuf,
        password.setCounter(counter)
      );
      if (iterations % 10 === 0 && performance.now() - lastYield > 200) {
        await delay(0);
        lastYield = performance.now();
      }
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefix)) {
        derivedKeyHex = bufferToHex(derivedKey);
        break;
      }
      counter = counter + counterStep;
      iterations = iterations + 1;
    }
    return {
      counter,
      derivedKey: derivedKeyHex,
      time: timeDuration(start)
    };
  }
  function handler(options) {
    const { deriveKey: deriveKey2 } = options;
    let controller = void 0;
    self.onmessage = async (message) => {
      const { challenge, counterMode, counterStart, counterStep, timeout, type } = message.data;
      if (type === "abort") {
        controller?.abort();
      } else if (type === "work") {
        controller = new AbortController();
        let solution;
        try {
          solution = await solveChallenge({
            challenge,
            controller,
            counterStart,
            counterStep,
            deriveKey: deriveKey2,
            counterMode,
            timeout
          });
        } catch (err) {
          return self.postMessage({ error: err });
        }
        self.postMessage(solution);
      }
    };
  }
  async function deriveKey(parameters, salt, password) {
    const { algorithm, keyLength = 32 } = parameters;
    const iterations = Math.max(1, parameters.cost);
    let data = void 0;
    let derivedKey = void 0;
    for (let i = 0; i < iterations; i++) {
      if (i === 0) {
        data = concatBuffers(salt, password);
      } else {
        data = derivedKey;
      }
      derivedKey = new Uint8Array(
        (await crypto.subtle.digest(algorithm, data)).slice(0, keyLength)
      );
    }
    return {
      parameters: {},
      derivedKey
    };
  }
  handler({
    deriveKey
  });
})();
`,uo=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",Ei],{type:"text/javascript;charset=utf-8"});function Ra(e){let t;try{if(t=uo&&(self.URL||self.webkitURL).createObjectURL(uo),!t)throw"";let n=new Worker(t,{name:e?.name});return n.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(t)}),n}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(Ei),{name:e?.name})}}var Rc=`:root {
  --altcha-border-color: var(--altcha-color-neutral);
  --altcha-border-width: 1px;
  --altcha-border-radius: 6px;
  --altcha-color-base: light-dark(oklch(100% 0.00011 271.152), oklch(20.904% 0.00002 271.152));
  --altcha-color-base-content: light-dark(
  	oklch(20.904% 0.00002 271.152),
  	oklch(100% 0.00011 271.152)
  );
  --altcha-color-error: oklch(51.284% 0.20527 28.678);
  --altcha-color-error-content: oklch(100% 0.00011 271.152);
  --altcha-color-neutral: light-dark(oklch(83.591% 0.0001 271.152), oklch(46.04% 0.00005 271.152));
  --altcha-color-neutral-content: light-dark(
  	oklch(46.76% 0.00005 271.152),
  	oklch(100% 0.00011 271.152)
  );
  --altcha-color-primary: oklch(40.279% 0.2449 268.131);
  --altcha-color-primary-content: oklch(100% 0.00011 271.152);
  --altcha-color-success: oklch(55.748% 0.18968 142.511);
  --altcha-color-success-content: oklch(100% 0.00011 271.152);
  --altcha-checkbox-border-color: light-dark(
  	oklch(66.494% 0.00233 15.434),
  	oklch(51.028% 0.00006 271.152)
  );
  --altcha-checkbox-border-radius: 5px;
  --altcha-checkbox-border-width: var(--altcha-border-width);
  --altcha-checkbox-outline: 2px solid var(--altcha-checkbox-outline-color);
  --altcha-checkbox-outline-color: -webkit-focus-ring-color;
  --altcha-checkbox-outline-offset: 2px;
  --altcha-checkbox-size: 22px;
  --altcha-checkbox-transition-duration: var(--altcha-transition-duration);
  --altcha-input-background-color: var(--altcha-color-base);
  --altcha-input-border-radius: 3px;
  --altcha-input-border-width: 1px;
  --altcha-input-color: var(--altcha-color-base-content);
  --altcha-max-width: 320px;
  --altcha-padding: 0.75rem;
  --altcha-popover-arrow-size: 6px;
  --altcha-popover-color: var(--altcha-border-color);
  --altcha-shadow: drop-shadow(3px 3px 6px oklch(0% 0 0 / 0.2));
  --altcha-spinner-color: var(--altcha-color-base-content);
  --altcha-switch-background-color: var(--altcha-color-neutral);
  --altcha-switch-border-radius: calc(infinity * 1px);
  --altcha-switch-height: var(--altcha-checkbox-size);
  --altcha-switch-padding: 0.25rem;
  --altcha-switch-width: calc(var(--altcha-checkbox-size) * 1.75);
  --altcha-switch-toggle-border-radius: 100%;
  --altcha-switch-toggle-color: var(--altcha-color-neutral-content);
  --altcha-switch-toggle-size: calc(
  	var(--altcha-switch-height) - calc(var(--altcha-switch-padding) * 2)
  );
  --altcha-transition-duration: 0.6s;
  --altcha-z-index: 99999999;
  --altcha-z-index-popover: 999999999;
}

@supports (-moz-appearance: none) {
  :root {
    --altcha-checkbox-outline-color: var(--altcha-color-primary);
  }
}
.altcha {
  all: revert-layer;
  display: none;
  font-family: inherit;
  font-size: inherit;
  position: relative;
}
.altcha[data-visible] {
  display: block;
}
.altcha-popover, .altcha-popover * {
  all: revert-layer;
  box-sizing: border-box;
  font-family: inherit;
  font-size: inherit;
  line-height: 1.25;
}
.altcha * {
  all: revert-layer;
  box-sizing: border-box;
  font-family: inherit;
  font-size: inherit;
  line-height: 1.25;
}
.altcha a, .altcha-popover a {
  color: currentColor;
  text-decoration: none;
}
.altcha a:hover, .altcha-popover a:hover {
  color: currentColor;
}
.altcha-main {
  align-items: start;
  background-color: var(--altcha-color-base);
  border: var(--altcha-border-width, 1px) solid var(--altcha-border-color);
  border-radius: var(--altcha-border-radius, 0);
  color: var(--altcha-color-base-content);
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  justify-content: space-between;
  padding: var(--altcha-padding);
  max-width: var(--altcha-max-width, 100%);
}
.altcha-main > * {
  display: flex;
  width: 100%;
}
.altcha-main > *:first-child {
  flex-grow: 1;
}
.altcha-checkbox-wrap {
  align-items: center;
  display: flex;
  flex-direction: row;
  flex-grow: 1;
  gap: 0.5rem;
}
.altcha-checkbox-wrap > * {
  display: flex;
}
.altcha-logo {
  opacity: 0.7;
}
.altcha-footer {
  align-items: center;
  display: flex;
  flex-grow: 1;
  gap: 0.5rem;
  justify-content: flex-end;
  font-size: 0.7rem;
  opacity: 0.7;
}
.altcha-footer p {
  margin: 0;
  padding: 0;
}
.altcha-error {
  font-size: 0.85rem;
}
.altcha-button {
  align-items: center;
  background: var(--altcha-color-primary);
  border: var(--altcha-input-border-width) solid var(--altcha-color-primary);
  border-radius: var(--altcha-input-border-radius);
  color: var(--altcha-color-primary-content);
  cursor: pointer;
  display: flex;
  font-size: 0.9rem;
  gap: 0.5rem;
  padding: 0.35rem;
}
.altcha-button:focus {
  border-color: var(--altcha-color-primary);
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-button > .altcha-spinner, .altcha-button > svg {
  height: 20px;
  width: 20px;
}
.altcha-button-secondary {
  background: transparent;
  border-color: var(--altcha-color-neutral);
  color: var(--altcha-color-neutral-content);
}
.altcha-input {
  background: var(--altcha-input-background-color);
  border: var(--altcha-input-border-width) solid var(--altcha-color-neutral);
  border-radius: var(--altcha-input-border-radius);
  color: var(--altcha-input-color);
  flex-grow: 1;
  font-size: 1rem;
  min-width: 0;
  padding: 0.25rem;
  width: auto;
}
.altcha-input:focus {
  border-color: var(--altcha-color-primary);
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-spinner {
  animation: altcha-rotate 0.6s linear infinite;
  border-radius: 100%;
  border: var(--altcha-checkbox-border-width) solid var(--altcha-spinner-color);
  border-bottom-color: transparent;
  border-right-color: transparent;
  opacity: 0.7;
}
.altcha-popover {
  background-color: var(--altcha-color-base);
  border: var(--altcha-border-width) solid var(--altcha-border-color);
  border-radius: var(--altcha-border-radius);
  color: var(--altcha-color-base-content);
  filter: var(--altcha-shadow);
  position: absolute;
  left: calc(var(--altcha-padding) / 2);
  max-width: calc(var(--altcha-max-width) - var(--altcha-padding));
  top: calc(var(--altcha-padding) + var(--altcha-checkbox-size) + var(--altcha-popover-arrow-size));
  z-index: var(--altcha-z-index-popover);
}
.altcha-popover-arrow {
  border: var(--altcha-popover-arrow-size) solid transparent;
  border-bottom-color: var(--altcha-popover-color);
  content: "";
  height: 0;
  left: calc(var(--altcha-checkbox-size) / 2);
  position: absolute;
  top: calc(var(--altcha-popover-arrow-size) * -2);
  width: 0;
}
.altcha-popover-content {
  max-height: 100dvh;
  overflow: auto;
  padding: var(--altcha-padding);
}
.altcha-popover[data-top=true][data-display=standard] {
  bottom: calc(100% - (var(--altcha-padding) - var(--altcha-popover-arrow-size)));
  top: auto;
}
.altcha-popover[data-top=true][data-display=standard] .altcha-popover-arrow {
  border-bottom-color: transparent;
  border-top-color: var(--altcha-popover-color);
  bottom: calc(var(--altcha-popover-arrow-size) * -2);
  top: auto;
}
.altcha-popover[data-variant=error] {
  --altcha-popover-color: var(--altcha-color-error);
  background-color: var(--altcha-color-error);
  border-color: var(--altcha-color-error);
  color: var(--altcha-color-error-content);
}
.altcha-popover[data-variant=error] .altcha-popover-content {
  padding: calc(var(--altcha-padding) / 1.5) var(--altcha-padding);
}
.altcha-popover[data-display=overlay] {
  animation: altcha-overlay-slidein 0.5s forwards;
  left: 50%;
  position: fixed;
  top: 45%;
  transform: translate(-50%, -50%);
  width: var(--altcha-max-width);
  z-index: var(--altcha-z-index);
}
.altcha-popover[data-display=bottomsheet] {
  animation: altcha-bottomsheet-slideup 0.5s forwards;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom: 0;
  bottom: -100%;
  left: 50%;
  position: fixed;
  top: auto;
  transform: translate(-50%, 0);
  width: var(--altcha-max-width);
  z-index: var(--altcha-z-index);
}
.altcha-popover[data-display=bottomsheet] .altcha-popover-content {
  padding-bottom: calc(var(--altcha-padding) * 2);
}
.altcha-popover-backdrop {
  background: var(--altcha-color-base-content);
  bottom: 0;
  left: 0;
  opacity: 0.1;
  position: fixed;
  right: 0;
  top: 0;
  transition: opacity 0.5s;
  z-index: var(--altcha-z-index);
}
.altcha-popover-close {
  color: var(--altcha-color-base-content);
  cursor: pointer;
  display: inline-block;
  font-size: 1rem;
  height: 1.25rem;
  line-height: 0.95;
  position: absolute;
  right: 0;
  text-align: center;
  text-shadow: 0 0 1px var(--altcha-color-base);
  top: -1.5rem;
  width: 1.25rem;
  z-index: var(--altcha-z-index);
}
[dir=rtl] .altcha-popover {
  left: auto;
  right: calc(var(--altcha-padding) / 2);
}
[dir=rtl] .altcha-popover-arrow {
  left: auto;
  right: calc(var(--altcha-checkbox-size) / 2);
}
[dir=rtl] .altcha-popover-close {
  left: 0;
  right: auto;
}
.altcha-popover[data-display=bottomsheet] .altcha-footer, .altcha-popover[data-display=overlay] .altcha-footer {
  align-items: center;
  justify-content: center;
  padding-top: 1rem;
  gap: 0.5rem;
}
.altcha-popover[data-display=bottomsheet] .altcha-footer svg, .altcha-popover[data-display=overlay] .altcha-footer svg {
  height: 18px;
  width: 18px;
  vertical-align: middle;
}
.altcha-code-challenge > form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.altcha-code-challenge-title {
  font-weight: 600;
}
.altcha-code-challenge-text {
  font-size: 0.85rem;
}
.altcha-code-challenge-image {
  background: white;
  border: var(--altcha-input-border-width) solid var(--altcha-color-neutral);
  border-radius: var(--altcha-input-border-radius);
  object-fit: contain;
  height: 50px;
}
.altcha-code-challenge-row {
  display: flex;
  gap: 0.5rem;
}
.altcha-code-challenge-buttons {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: var(--altcha-padding);
  justify-content: space-between;
}
.altcha-code-challenge-buttons button {
  justify-content: center;
  width: 100%;
}
.altcha-checkbox {
  cursor: pointer;
  height: var(--altcha-checkbox-size);
  position: relative;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox input {
  appearance: none;
  background: var(--altcha-input-background-color);
  border: var(--altcha-checkbox-border-width, 2px) solid var(--altcha-checkbox-border-color);
  border-radius: var(--altcha-checkbox-border-radius);
  cursor: pointer;
  height: var(--altcha-checkbox-size);
  left: 0;
  margin: 0;
  padding: 0;
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox input:before {
  border-radius: var(--altcha-checkbox-border-radius);
  content: "";
  width: 100%;
  height: 100%;
  background: var(--altcha-color-neutral);
  display: block;
  transform: scale(0);
}
.altcha-checkbox input:checked {
  background-color: var(--altcha-color-success);
  border-color: var(--altcha-color-success);
}
.altcha-checkbox input:checked::before {
  background-color: var(--altcha-color-success);
  opacity: 0;
  transform: scale(2.2);
  transition: all var(--altcha-checkbox-transition-duration) ease;
  transition-delay: 0.1s;
}
.altcha-checkbox svg {
  --altcha-radio-svg-size: calc(var(--altcha-checkbox-size) * 0.5);
  --altcha-radio-svg-offset: calc(var(--altcha-checkbox-size) * 0.25);
  fill: none;
  left: var(--altcha-radio-svg-offset);
  height: var(--altcha-radio-svg-size);
  opacity: 0;
  position: absolute;
  stroke: currentColor;
  stroke-width: 2;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 16px;
  stroke-dashoffset: 16px;
  top: var(--altcha-radio-svg-offset);
  transform: translate3d(0, 0, 0);
  width: var(--altcha-radio-svg-size);
}
.altcha-checkbox input:checked + svg {
  color: var(--altcha-color-success-content);
  opacity: 1;
  stroke-dashoffset: 0;
  transition: all var(--altcha-checkbox-transition-duration) ease;
  transition-delay: 0.1s;
}
.altcha-checkbox-spinner {
  display: none;
  left: 0;
  height: var(--altcha-checkbox-size);
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox[data-loading=true] input {
  appearance: none;
  opacity: 0;
  pointer-events: none;
}
.altcha-checkbox[data-loading=true] .altcha-checkbox-spinner {
  display: block;
}
.altcha-checkbox-native {
  height: var(--altcha-checkbox-size);
  position: relative;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native input {
  height: var(--altcha-checkbox-size);
  margin: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native-spinner {
  display: none;
  left: 0;
  height: var(--altcha-checkbox-size);
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native[data-loading=true] input {
  appearance: none;
  opacity: 0;
  pointer-events: none;
}
.altcha-checkbox-native[data-loading=true] .altcha-checkbox-native-spinner {
  display: block;
}
.altcha-switch {
  align-items: center;
  border-radius: var(--altcha-switch-border-radius);
  background-color: var(--altcha-switch-background-color);
  display: flex;
  height: var(--altcha-switch-height);
  padding: var(--altcha-switch-padding);
  position: relative;
  width: var(--altcha-switch-width);
}
.altcha-switch:focus-within {
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-switch input {
  appearance: none;
  cursor: pointer;
  height: 100%;
  left: 0;
  opacity: 0;
  position: absolute;
  top: 0;
  width: 100%;
}
.altcha-switch-toggle {
  align-items: center;
  background-color: var(--altcha-switch-toggle-color);
  border-radius: var(--altcha-switch-toggle-border-radius);
  cursor: pointer;
  display: flex;
  height: var(--altcha-switch-toggle-size);
  justify-content: center;
  left: var(--altcha-switch-padding);
  position: absolute;
  transition: width 150ms ease-out, left 150ms ease-out;
  width: var(--altcha-switch-toggle-size);
}
.altcha-switch-spinner {
  display: none;
  height: var(--altcha-switch-toggle-size);
  width: var(--altcha-switch-toggle-size);
}
.altcha-switch[data-loading=true] {
  pointer-events: none;
}
.altcha-switch[data-loading=true] .altcha-switch-spinner {
  display: block;
}
.altcha-switch[data-loading=true] .altcha-switch-toggle {
  background-color: transparent;
  left: calc(50% - var(--altcha-switch-toggle-size) / 2);
}
[data-state=verified] .altcha-switch {
  --altcha-switch-background-color: var(--altcha-color-success);
}
[data-state=verified] .altcha-switch-toggle {
  background-color: var(--altcha-color-success-content);
  left: calc(100% - var(--altcha-switch-height) + var(--altcha-switch-padding));
}
[dir=rtl] .altcha-switch-toggle {
  left: calc(100% - var(--altcha-switch-height) + var(--altcha-switch-padding));
}
[dir=rtl][data-state=verified] .altcha-switch-toggle {
  left: var(--altcha-switch-padding);
}
.altcha-floating-arrow {
  border: 6px solid transparent;
  border-bottom-color: var(--altcha-border-color);
  content: "";
  height: 0;
  left: 12px;
  position: absolute;
  top: -12px;
  width: 0;
}
.altcha-overlay-backdrop {
  bottom: 0;
  left: 0;
  position: fixed;
  right: 0;
  top: 0;
  transition: opacity var(--altcha-transition-duration);
  z-index: var(--altcha-z-index);
}
.altcha-overlay-close {
  display: inline-block;
  color: currentColor;
  cursor: pointer;
  font-size: 1rem;
  height: 1rem;
  line-height: 0.85;
  position: absolute;
  right: 0;
  text-align: center;
  text-shadow: 0 0 1px var(--altcha-color-base);
  top: -1.5rem;
  width: 1rem;
  z-index: var(--altcha-z-index);
}
.altcha[data-display=overlay] {
  animation: altcha-overlay-slidein var(--altcha-transition-duration) forwards;
  filter: var(--altcha-shadow);
  left: 50%;
  opacity: 0;
  position: fixed;
  top: 45%;
  transform: translate(-50%, -50%);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=overlay] .altcha-main {
  width: var(--altcha-max-width);
}
.altcha[data-display=floating] {
  display: none;
  filter: var(--altcha-shadow);
  left: var(--altcha-floating-left, -100%);
  position: fixed;
  top: var(--altcha-floating-top, -100%);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=floating] .altcha-main {
  width: var(--altcha-max-width);
}
.altcha[data-display=floating][data-floating-position=top] .altcha-floating-arrow {
  border-bottom-color: transparent;
  border-top-color: var(--altcha-border-color);
  bottom: -12px;
  top: auto;
}
.altcha[data-display=floating][data-visible] {
  display: flex;
}
.altcha[data-display=bar] {
  bottom: -100%;
  filter: var(--altcha-shadow);
  left: 0;
  position: fixed;
  right: 0;
  transition: bottom var(--altcha-transition-duration), top var(--altcha-transition-duration);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=bar] .altcha-main {
  align-items: center;
  border-radius: 0;
  border-width: var(--altcha-border-width) 0 0 0;
  flex-direction: row;
  max-width: 100% !important;
}
.altcha[data-display=bar] .altcha-main > * {
  width: auto;
}
.altcha[data-display=bar][data-placement=top] {
  bottom: auto;
  top: -100%;
}
.altcha[data-display=bar][data-placement=top] .altcha-main {
  border-width: 0 0 var(--altcha-border-width) 0;
}
.altcha[data-display=bar][data-placement=bottom]:not([data-state=unverified]) {
  bottom: 0;
}
.altcha[data-display=bar][data-placement=top]:not([data-state=unverified]) {
  top: 0;
}
.altcha[data-display=invisible] {
  display: none;
}

@keyframes altcha-rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
@keyframes altcha-bottomsheet-slideup {
  100% {
    bottom: 0;
  }
}
@keyframes altcha-overlay-slidein {
  100% {
    opacity: 1;
    top: 50%;
  }
}`;mc(Rc);$altcha.algorithms.set("SHA-256",()=>new Ra);$altcha.algorithms.set("SHA-384",()=>new Ra);$altcha.algorithms.set("SHA-512",()=>new Ra);$altcha.algorithms.set("PBKDF2/SHA-256",()=>new Aa);$altcha.algorithms.set("PBKDF2/SHA-384",()=>new Aa);$altcha.algorithms.set("PBKDF2/SHA-512",()=>new Aa);})();
//# sourceMappingURL=Altcha.js.map
