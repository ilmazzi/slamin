window.Echo=void 0;(function(){function g(){var r;const d=document.querySelector("[data-chat-room]");if(!d)return;const l=d.getAttribute("data-chat-room");if(!l)return;const i=document.querySelector("[data-chat-messages]");if(!i)return;const c=document.querySelector("[data-chat-form]"),u=c?c.querySelector("[data-chat-input]"):null;function p(t){const e=parseInt(String(t??"0"),10);return Number.isFinite(e)&&e>0?e:0}const m=p((r=document.querySelector('meta[name="current-user-id"]'))==null?void 0:r.getAttribute("content"))||p(d.getAttribute("data-current-user-id"))||p(window.currentUser&&(window.currentUser.id||window.currentUser.user_id));function o(t){return String(t).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}const h=o;function f({content:t,time:e,sender_id:n,avatar_url:s}){return Number(n)===m?`
  <div class="d-flex position-relative mb-3">
    <div class="chat-box-right ms-auto text-end">
      <div>
        <p class="chat-text mb-1">${o(t)}</p>
        <p class="text-muted mb-0"><i class="ti ti-checks text-primary"></i> ${o(e)}</p>
      </div>
    </div>
    <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
      <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto" data-user-id="${n}">
        <img alt="avatar" class="img-fluid b-r-10" src="${h(s)}">
        <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
      </span>
    </div>
  </div>`:`
  <div class="d-flex position-relative mb-3">
    <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
      <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto" data-user-id="${n}">
        <img alt="avatar" class="img-fluid b-r-10" src="${h(s)}">
        <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
      </span>
    </div>
    <div class="chat-box">
      <div>
        <p class="chat-text mb-1">${o(t)}</p>
        <p class="text-muted mb-0"><i class="ti ti-checks text-primary"></i> ${o(e)}</p>
      </div>
    </div>
  </div>`}function a(t){let e=!1;try{e=i.scrollHeight-i.scrollTop-i.clientHeight<80}catch{}const n=document.createElement("div");if(n.innerHTML=t.trim(),Array.from(n.children).forEach(s=>i.appendChild(s)),e)try{i.scrollTop=i.scrollHeight}catch{}}window.Echo&&window.Echo.private(`chat.room.${l}`).listen(".chat.message",t=>{const e=f(t);a(e)}),c&&u&&c.addEventListener("submit",async t=>{t.preventDefault();const e=(u.value||"").trim();if(!e)return;const n=c.querySelector('[type="submit"]');n&&(n.disabled=!0);try{const s=await fetch(c.action,{method:"POST",headers:{"Content-Type":"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify({content:e})});if(!s.ok)throw new Error(`Send failed: ${s.status}`);u.value=""}catch(s){console.error("[chat-realtime] send error",s)}finally{n&&(n.disabled=!1)}},{once:!1});try{i.scrollTop=i.scrollHeight}catch{}}function b(){const d=document.getElementById("userSearch"),l=document.getElementById("searchResults"),i=document.getElementById("loadingSpinner"),c=document.getElementById("noResults");if(!d||!l)return;let u=null,p="";function m(a){i&&i.classList.toggle("d-none",!a)}function o(a=!1){l.innerHTML="",c&&c.classList.toggle("d-none",!a)}async function h(a){try{m(!0);const r=await fetch(`/chat/search-users?q=${encodeURIComponent(a)}`,{headers:{Accept:"application/json"}});if(!r.ok)throw new Error(`search ${r.status}`);const t=await r.json(),e=Array.isArray(t.users)?t.users:[];if(e.length===0){o(!0);return}c&&c.classList.add("d-none");const n=e.map(s=>`
            <div class="d-flex align-items-center py-2 border-bottom">
              <div class="me-2">${s.avatar_html||""}</div>
              <div class="flex-grow-1">
                <div class="f-w-500">${(s.name||"").replace(/</g,"&lt;")}</div>
              </div>
              <div>
                <button class="btn btn-sm btn-primary" data-start-chat data-user-id="${s.id}">
                  <i class="ti ti-brand-hipchat"></i> Avvia
                </button>
              </div>
            </div>`).join("");l.innerHTML=n}catch(r){console.error("[chat-new] search error",r),o(!0)}finally{m(!1)}}async function f(a,r){try{r&&(r.disabled=!0);const t=await fetch(`/chat/create-private/${encodeURIComponent(a)}`,{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"}});if(!t.ok)throw new Error(`create ${t.status}`);const e=await t.json();if(e&&e.success&&e.chat_id){const n=e.redirect&&String(e.redirect)||"/chat";window.location.href=`${n}?room=${encodeURIComponent(e.chat_id)}`}}catch(t){console.error("[chat-new] create error",t)}finally{r&&(r.disabled=!1)}}d.addEventListener("input",()=>{const a=(d.value||"").trim();if(a.length<2){o(!1);return}a!==p&&(p=a,u&&clearTimeout(u),u=setTimeout(()=>h(a),300))},{passive:!0}),l.addEventListener("click",a=>{const r=a.target.closest("[data-start-chat]");if(!r)return;const t=r.getAttribute("data-user-id");t&&f(t,r)})}document.readyState==="loading"?document.addEventListener("DOMContentLoaded",()=>{g(),b()},{once:!0}):(g(),b())})();console.log("[DEBUG] app.js caricato");
