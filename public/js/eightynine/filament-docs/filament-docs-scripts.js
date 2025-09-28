document.addEventListener("DOMContentLoaded",function(){l(),m(),f(),h(),g()});function l(){const e=document.querySelector('[wire\\:model\\.live\\.debounce\\.300ms="searchQuery"]');e&&e.addEventListener("input",function(t){const n=t.target.value.trim();n.length>2?d(n):c()})}function d(e){const t=document.querySelector(".filament-docs-prose");if(!t)return;c();const n=new RegExp(`(${p(e)})`,"gi");u(t,function(o){if(n.test(o.textContent)){const r=o.parentNode,i=document.createElement("span");for(i.innerHTML=o.textContent.replace(n,'<mark class="filament-docs-search-highlight">$1</mark>');i.firstChild;)r.insertBefore(i.firstChild,o);r.removeChild(o)}})}function c(){document.querySelectorAll(".filament-docs-search-highlight").forEach(t=>{const n=t.parentNode;n.replaceChild(document.createTextNode(t.textContent),t),n.normalize()})}function u(e,t){const n=document.createTreeWalker(e,NodeFilter.SHOW_TEXT,null,!1),o=[];let r;for(;r=n.nextNode();)o.push(r);o.forEach(t)}function p(e){return e.replace(/[.*+?^${}()|[\\]\\\\]/g,"\\\\$&")}function m(){document.addEventListener("keydown",function(e){if((e.ctrlKey||e.metaKey)&&e.key==="k"){e.preventDefault();const t=document.querySelector('[wire\\:model\\.live\\.debounce\\.300ms="searchQuery"]');t&&(t.focus(),t.select())}if(!e.target.matches("input, textarea")){if(e.key==="ArrowLeft"){const t=document.querySelector('[wire\\:click*="selectSection"]:has([class*="arrow-left"])');t&&t.click()}else if(e.key==="ArrowRight"){const t=document.querySelector('[wire\\:click*="selectSection"]:has([class*="arrow-right"])');t&&t.click()}}if(e.key==="Escape"){const t=document.querySelector('[wire\\:click="clearSearch"]');t&&t.click()}})}function f(){const e=document.createElement("style");e.textContent=`
        @media print {
            .no-print { display: none !important; }
            .print-break-before { page-break-before: always; }
            .print-break-after { page-break-after: always; }
            .print-break-inside-avoid { page-break-inside: avoid; }
            
            /* Ensure content is visible when printing */
            .filament-docs-prose * {
                color: #000 !important;
                background: transparent !important;
            }
            
            .filament-docs-prose h1,
            .filament-docs-prose h2,
            .filament-docs-prose h3 {
                page-break-after: avoid;
            }
            
            .filament-docs-prose pre,
            .filament-docs-prose blockquote,
            .filament-docs-table {
                page-break-inside: avoid;
            }
        }
    `,document.head.appendChild(e),window.addEventListener("beforeprint",function(){document.body.classList.add("printing")}),window.addEventListener("afterprint",function(){document.body.classList.remove("printing")})}function h(){const e=document.createElement("button");e.innerHTML=`
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    `,e.className="fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all duration-200 opacity-0 pointer-events-none z-50",e.setAttribute("aria-label","Scroll to top"),document.body.appendChild(e);function t(){window.scrollY>500?(e.classList.remove("opacity-0","pointer-events-none"),e.classList.add("opacity-100")):(e.classList.add("opacity-0","pointer-events-none"),e.classList.remove("opacity-100"))}window.addEventListener("scroll",t),e.addEventListener("click",function(){window.scrollTo({top:0,behavior:"smooth"})})}function g(){document.querySelectorAll(".filament-docs-prose pre code").forEach(function(t){const n=t.parentElement,o=document.createElement("button");o.innerHTML=`
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        `,o.className="absolute top-2 right-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 p-2 rounded text-xs transition-colors duration-200 opacity-0 group-hover:opacity-100",o.setAttribute("aria-label","Copy code"),n.style.position="relative",n.classList.add("group"),o.addEventListener("click",function(){const r=t.textContent;if(navigator.clipboard)navigator.clipboard.writeText(r).then(function(){a(o)});else{const i=document.createElement("textarea");i.value=r,i.style.position="fixed",i.style.opacity="0",document.body.appendChild(i),i.select();try{document.execCommand("copy"),a(o)}catch(s){console.error("Failed to copy code:",s)}document.body.removeChild(i)}}),n.appendChild(o)})}function a(e){const t=e.innerHTML;e.innerHTML=`
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    `,e.classList.add("text-green-600"),setTimeout(function(){e.innerHTML=t,e.classList.remove("text-green-600")},2e3)}document.addEventListener("click",function(e){if(e.target.matches('a[href^="#"]')){e.preventDefault();const t=e.target.getAttribute("href").substring(1),n=document.getElementById(t);n&&n.scrollIntoView({behavior:"smooth",block:"start"})}});function v(){document.addEventListener("keydown",function(e){e.key==="Tab"&&document.body.classList.add("user-is-tabbing")}),document.addEventListener("mousedown",function(){document.body.classList.remove("user-is-tabbing")})}v();function y(){const e=document.querySelector('[wire\\:model\\.live\\.debounce\\.300ms="searchQuery"]');if(e){const t=localStorage.getItem("filament-docs-last-search");t&&!e.value&&(e.value=t,e.dispatchEvent(new Event("input"))),e.addEventListener("input",function(n){n.target.value.trim()?localStorage.setItem("filament-docs-last-search",n.target.value):localStorage.removeItem("filament-docs-last-search")})}}y();
