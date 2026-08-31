<?php
    
    if (!function_exists('av')) {
        function av($file = '') {
            static $base = null;

            if ($base === null) {
                $CI =& get_instance();
                $base = $CI->config->item('app_version');

                $marker = APPPATH . 'cache/assets.version';
                if (file_exists($marker)) {
                    $ts = @file_get_contents($marker);
                    if ($ts !== false && ($ts = trim($ts)) !== '') {
                        $base .= '.' . $ts;
                    }
                }
            }

            if ($file !== '') {
                $full = FCPATH . $file;
                if (file_exists($full)) {
                    return $base . '.' . filemtime($full);
                }
            }

            return $base;
        }
    }

    if (!function_exists('asset_url')) {
        function asset_url($path) {
            return base_url($path) . '?v=' . av($path);
        }
    }

    if (!function_exists('bust_assets_cache')) {
        function bust_assets_cache() {
            $marker = APPPATH . 'cache/assets.version';
            return @file_put_contents($marker, time()) !== false;
        }
    }

    /**
     * Whether the JS error debugger is enabled (config: js_error_debug_enabled).
     * When FALSE, no reporter/listener scripts and public log endpoints reject writes.
     */
    if (!function_exists('js_error_debug_enabled')) {
        function js_error_debug_enabled() {
            $ci = function_exists('get_instance') ? get_instance() : null;
            if (!$ci || empty($ci->config)) {
                return true;
            }

            return $ci->config->item('js_error_debug_enabled') !== false;
        }
    }

    /**
     * Inline JS helpers for resource error filtering (store reporter + admin listener).
     * Defers img reporting so onerror fallbacks / DOM replacement do not create false positives;
     * ignores analytics/segment/extensions; uses URL path in messages for actionable triage.
     */
    if (!function_exists('_js_error_resource_handling_js')) {
        function _js_error_resource_handling_js() {
            static $js = null;
            if ($js !== null) {
                return $js;
            }
            $js = 'function jsErrNormResSrc(src){'
                . 'if(!src||typeof src!=="string")return"";'
                . 'var t=src.replace(/^\\s+|\\s+$/g,"");'
                . 'if(!t)return"";'
                . 'var tl=t.toLowerCase();'
                . 'if(tl.indexOf("data:")===0||tl.indexOf("blob:")===0)return"";'
                . 'return t;}'
                . 'function jsErrResPath(src){'
                . 'try{var u=new URL(src,location.href);return u.pathname+(u.search||"");}'
                . 'catch(x){var i=src.indexOf("?");return i===-1?src:src.substring(0,i);}}'
                . 'function jsErrResIgnore(src){'
                . 'if(!src)return true;'
                . 'var s=src.toLowerCase();'
                . 'var p=["product/upload/","user_upload/","assets/images/users/","form/favi/","images/flags/",'
                . '"adsbygoogle","chrome-extension://","moz-extension://","safari-extension://","edge-extension://",'
                . '"segment.com","segment.io","cdn.segment","ajs-destination","googletagmanager","google-analytics",'
                . '"gtag/js","doubleclick.net","facebook.net","connect.facebook","hotjar","intercom.io","intercomcdn",'
                . '"clarity.ms","bat.bing","cookielaw","onetrust","newrelic","nr-data.net","sentry.io","fullstory",'
                . '"licdn.com","snap.licdn","twitter.com/widgets","pinterest.com","pinimg.com","tiktok.com","sc-static.net",'
                . '"optimizely","mouseflow","luckyorange","hs-scripts.com","hs-analytics","hubspot","hs-banner",'
                . '"driftt.com","drift.com","pendo.io","pendo-data","launchdarkly","cdn.amplitude","mixpanel.com",'
                . '"plausible.io","matomo","heap-analytics","cdn.heapanalytics","yandex.ru/metrika","mc.yandex.ru"];'
                . 'for(var i=0;i<p.length;i++){if(s.indexOf(p[i])!==-1)return true;}'
                . 'return false;}'
                . 'function jsErrEmitRes(emit,tag,src){'
                . 'var path=jsErrResPath(src);'
                . 'if(path)emit("Failed to load "+tag+": "+path,"resource");}'
                . 'function jsErrHandleResErr(emit,tag,raw,el){'
                . 'var n=jsErrNormResSrc(raw);'
                . 'if(!n){if(tag==="img"&&el)try{el.onerror=null;}catch(x){}return;}'
                . 'if(jsErrResIgnore(n)){if(tag==="img"&&el)try{el.onerror=null;}catch(x){}return;}'
                . 'if(tag==="img"){'
                . 'setTimeout(function(){'
                . 'if(!el||!el.isConnected)return;'
                . 'if(String(el.tagName).toLowerCase()!=="img")return;'
                . 'if(el.naturalWidth>0)return;'
                . 'jsErrEmitRes(emit,"img",n);'
                . '},180);'
                . 'return;}'
                . 'if(tag==="script"||tag==="link")jsErrEmitRes(emit,tag,n);'
                . '}';

            return $js;
        }
    }

    /**
     * Lightweight JS error reporter for non-admin pages (user dashboard, store, login themes).
     * Captures errors and silently sends them to the shared admin JS error log.
     * No badge or modal UI — admin sees the results in the admin panel debugger.
     */
    if (!function_exists('render_js_error_reporter')) {
        function render_js_error_reporter() {
            if (!js_error_debug_enabled()) {
                return '';
            }
            $logUrl   = base_url('common/js_error_log');
            $cleanUrl = base_url('common/js_page_clean');
            $html   = '<script>(function(){';
            $html  .= 'var LOG_URL='   . json_encode($logUrl)   . ';';
            $html  .= 'var CLEAN_URL=' . json_encode($cleanUrl) . ';';
            $html  .= _js_error_resource_handling_js();
            $html  .= 'var s=new Set();';
            $html  .= 'var _hadError=false;';
            /* true when running inside a re-check iframe — console.error capture is skipped so that
               UI components that misbehave at unusual viewport sizes cannot poison _hadError */
            $html  .= 'var _inIframe=false;try{_inIframe=(window.top!==window.self);}catch(e){}';

            /* send error to server */
            $html .= 'function rep(m,type){';
            $html .= 'if(!m||s.has(m))return;s.add(m);_hadError=true;';
            $html .= 'var d={msg:String(m),type:type||"runtime",path:window.location.pathname};';
            $html .= 'if(typeof $!=="undefined"){$.ajax({global:false,url:LOG_URL,type:"POST",data:d});}';
            $html .= 'else{var x=new XMLHttpRequest();x.open("POST",LOG_URL,true);';
            $html .= 'x.setRequestHeader("Content-Type","application/x-www-form-urlencoded");';
            $html .= 'x.send("msg="+encodeURIComponent(d.msg)+"&type="+encodeURIComponent(d.type)+"&path="+encodeURIComponent(d.path));}}';

            /* runtime errors + resource load failures (filtered + img deferred — fewer false positives) */
            $html .= 'window.addEventListener("error",function(e){';
            $html .= 'if(e.target&&e.target!==window&&!e.message){';
            $html .= 'var tag=(e.target.tagName||"").toLowerCase();';
            $html .= 'if(tag!=="script"&&tag!=="link"&&tag!=="img")return;';
            $html .= 'var raw=e.target.src||e.target.href||"";';
            $html .= 'jsErrHandleResErr(rep,tag,raw,e.target);';
            $html .= 'return;}';
            $html .= 'if(!e||!e.message)return;';
            $html .= 'var loc=e.filename||"";';
            /* skip errors from browser extensions and cross-origin scripts entirely */
            $html .= 'if(loc.indexOf("extension://")!==-1)return;';
            $html .= 'if(e.message==="Script error."||e.message==="Script error"){rep("Cross-origin script error","cross-origin");return;}';
            $html .= 'if(e.message.indexOf("Non-Error")!==-1)return;';
            $html .= 'var ln=e.lineno||0,cn=e.colno||0;';
            $html .= 'rep(e.message+(loc?" ("+loc.split("/").pop()+":"+ln+":"+cn+")":""),"runtime");';
            $html .= '},true);';

            /* unhandled promise rejections */
            $html .= 'window.addEventListener("unhandledrejection",function(e){';
            $html .= 'if(!e||!e.reason)return;var r=e.reason;';
            $html .= 'if(typeof r==="string"&&r.indexOf("Non-Error")!==-1)return;';
            /* route CORS "Origin not allowed" as config notice — not a code bug; needs third-party API domain whitelisting */
            $html .= 'if(typeof r==="string"&&r.indexOf("Origin not allowed")!==-1){rep(r,"config");return;}';
            $html .= 'if(r&&r.message&&r.message.indexOf("Origin not allowed")!==-1){rep(r.message,"config");return;}';
            /* skip isTrusted-only objects — these are DOM Event objects, not real errors */
            $html .= 'if(r&&typeof r==="object"&&Object.keys(r).length===0)return;';
            $html .= 'if(r&&typeof r==="object"&&r.isTrusted===true&&!r.message&&!r.stack)return;';
            $html .= 'var msg=(r&&r.message)?r.message:(typeof r==="string"?r:null);';
            $html .= 'if(!msg&&r&&typeof r==="object"){try{var j=JSON.stringify(r);if(j==="{}"||j===\'{"isTrusted":true}\')return;msg=j;}catch(x){msg="Promise Rejection";}}';
            $html .= 'rep(msg||"Promise Rejection","promise");},true);';

            /* console.error interception */
            $html .= '(function(){var _o=console.error;console.error=function(){';
            $html .= 'try{var a=Array.prototype.slice.call(arguments);';
            $html .= 'var m=a.map(function(x){if(!x)return"";if(x instanceof Error)return x.message;if(typeof x==="string")return x;try{return JSON.stringify(x);}catch(e){return String(x);}}).filter(Boolean).join(" ");';
            $html .= 'var skip=["favicon","[Deprecation]","[Violation]","Non-Error","net::ERR_ABORTED","ResizeObserver","404 (Not Found)","isTrusted","segment","ajs-destination","analytics.min","gtag","googletagmanager"];';
            $html .= 'if(!_inIframe&&m&&!skip.some(function(k){return m.indexOf(k)!==-1;}))rep(m,"console");}catch(e){}';
            $html .= 'if(typeof _o==="function")_o.apply(console,arguments);};}());';

            /* Auto-clean after quiet load: 5s so deferred img checks (≈180ms) can set _hadError first.
               Admin "Re-check Pages" iframes rely on this to drop fixed store paths from the log. */
            $html .= 'window.addEventListener("load",function(){';
            $html .= 'setTimeout(function(){';
            $html .= 'if(_hadError)return;';
            $html .= 'var p={path:window.location.pathname};';
            $html .= 'if(typeof $!=="undefined"){$.ajax({global:false,url:CLEAN_URL,type:"POST",data:p});}';
            $html .= 'else{var x=new XMLHttpRequest();x.open("POST",CLEAN_URL,true);';
            $html .= 'x.setRequestHeader("Content-Type","application/x-www-form-urlencoded");';
            $html .= 'x.send("path="+encodeURIComponent(p.path));}';
            $html .= '},5000);});';

            $html .= '})();</script>';
            return $html;
        }
    }

    if (!function_exists('render_js_error_listener')) {
        function render_js_error_listener() {
            if (!js_error_debug_enabled()) {
                return '';
            }
            $toastTitle = __('admin.error');

            /* ── Badge button (fixed bottom-left)
                   0 errors  → compact footer strip (non-intrusive, sits at the very bottom)
                   N errors  → prominent floating red circle                                  ── */
            $html  = '<div id="js-err-badge" style="display:none">';
            $html .= '<button id="js-err-badge-btn" type="button"';
            $html .= ' class="js-err-footer-btn js-err-footer-btn--ok"';
            $html .= ' data-bs-toggle="modal" data-bs-target="#js-err-modal"';
            $html .= ' onclick="jsErrRenderTable()" title="All clear — no JS errors">';
            $html .= '<i id="js-err-badge-ico" class="fas fa-check-circle"></i>';
            $html .= '<span id="js-err-badge-label">All clear</span>';
            $html .= '<span id="js-err-badge-count" class="badge rounded-pill bg-warning text-dark ms-1" style="font-size:.6rem;min-width:16px;display:none"></span>';
            $html .= '</button></div>';

            /* ── Modal ── */
            $html .= '<div class="modal fade" id="js-err-modal" tabindex="-1" aria-labelledby="jsErrModalLabel" aria-hidden="true">';
            $html .= '<div class="modal-dialog modal-xl modal-dialog-scrollable">';
            /* Setting both the Bootstrap CSS vars AND the inline property ensures the header's
               auto-calculated inner-radius matches our 12 px outer corner in every BS5 version */
            $html .= '<div class="modal-content border-0 shadow-lg" style="border-radius:12px;--bs-modal-border-radius:12px;--bs-modal-inner-border-radius:12px;overflow:hidden">';

            /* header — zero out Bootstrap's own border-radius on the header element; the parent
               overflow:hidden already clips the top corners cleanly */
            $html .= '<div class="modal-header py-3" style="background:linear-gradient(135deg,#c0392b,#e74c3c);border-bottom:none;border-radius:0">';
            $html .= '<div class="d-flex align-items-center gap-2">';
            $html .= '<div class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle" style="width:34px;height:34px"><i class="fas fa-bug text-white" style="font-size:1rem"></i></div>';
            $html .= '<div><h6 class="modal-title mb-0 text-white fw-bold" id="jsErrModalLabel">JS Error Log</h6>';
            $html .= '<span id="js-err-last-check" class="text-white-50" style="font-size:.68rem"><i class="fas fa-sync-alt me-1"></i>Checking…</span></div>';
            $html .= '</div>';
            $html .= '<div class="ms-auto d-flex gap-2 align-items-center me-2">';
            $html .= '<button id="js-err-recheck-btn" class="btn btn-sm text-white border-white border-opacity-25" style="background:rgba(255,255,255,.15);font-size:.78rem" onclick="jsErrRecheckAll()" title="Visit each logged page in the background — auto-removes fixed pages"><i class="fas fa-sync-alt me-1"></i>Re-check Pages</button>';
            $html .= '<button class="btn btn-sm text-white border-white border-opacity-25" style="background:rgba(255,255,255,.15);font-size:.78rem" onclick="jsErrCopyAll()" title="Copy all to clipboard"><i class="fas fa-copy me-1"></i>Copy All</button>';
            $html .= '<button class="btn btn-sm text-white border-white border-opacity-25" style="background:rgba(255,255,255,.15);font-size:.78rem" onclick="jsErrClearAll()" title="Clear all — errors will re-appear when you visit those pages again"><i class="fas fa-trash-alt me-1"></i>Clear Log</button>';
            $html .= '</div>';
            $html .= '<button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal"></button>';
            $html .= '</div>';

            /* info bar — three quick tips, scannable at a glance */
            $html .= '<div class="d-flex flex-wrap align-items-center gap-3 px-3 py-2 border-bottom" style="background:#f8f9fa;font-size:.74rem;color:#555">';
            $html .= '<span><i class="fas fa-external-link-alt me-1 text-success"></i><strong>Visit</strong> — open the page to verify your fix is live</span>';
            $html .= '<span class="vr opacity-25"></span>';
            $html .= '<span><i class="fas fa-sync-alt me-1 text-primary"></i><strong>Re-check Pages</strong> — auto-visits every page in background &amp; clears fixed ones</span>';
            $html .= '<span class="vr opacity-25"></span>';
            $html .= '<span><i class="fas fa-times me-1 text-danger"></i><strong>Dismiss</strong> — remove an entry after deploying the fix</span>';
            $html .= '</div>';

            /* table */
            $html .= '<div class="modal-body p-0" style="border-radius:0 0 12px 12px;overflow:hidden">';
            $html .= '<div class="table-responsive" style="max-height:60vh">';
            $html .= '<table class="table table-hover align-middle mb-0" style="font-size:.82rem">';
            $html .= '<thead style="position:sticky;top:0;z-index:1;background:#f8f9fa;border-bottom:2px solid #dee2e6">';
            $html .= '<tr>';
            $html .= '<th class="text-muted fw-semibold ps-3" style="width:155px">Last Seen</th>';
            $html .= '<th class="text-muted fw-semibold" style="width:185px">Page</th>';
            $html .= '<th class="text-muted fw-semibold" style="width:105px">Type</th>';
            $html .= '<th class="text-muted fw-semibold">Message</th>';
            $html .= '<th class="text-muted fw-semibold text-center" style="width:100px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody id="js-err-tbody"><tr><td colspan="5" class="text-center text-muted py-5"><i class="fas fa-spinner fa-spin me-2"></i>Loading…</td></tr></tbody>';
            $html .= '</table></div></div>';
            $html .= '</div></div></div>';

            $logUrl     = base_url('admincontrol/js_error_log');
            $getUrl     = base_url('admincontrol/js_error_get');
            $clearUrl   = base_url('admincontrol/js_error_clear');
            $dismissUrl = base_url('admincontrol/js_error_dismiss');
            $cleanUrl   = base_url('admincontrol/js_page_clean');

            /* ── Script ── */
            $html .= '<script>(function(){';
            $html .= 'var LOG_URL='.json_encode($logUrl).',GET_URL='.json_encode($getUrl).',CLEAR_URL='.json_encode($clearUrl).',DISMISS_URL='.json_encode($dismissUrl).',CLEAN_URL='.json_encode($cleanUrl).';';
            $html .= _js_error_resource_handling_js();
            $html .= 'var s=new Set(),q=[],_jeLog=[];';
            $html .= 'var TOAST_TITLE='.json_encode($toastTitle).';';
            $html .= 'var _hadError=false;';
            /* true when running inside a re-check iframe — console.error and polling are skipped */
            $html .= 'var _inIframe=false;try{_inIframe=(window.top!==window.self);}catch(e){}';

            /* send error to server (jQuery $.post) */
            $html .= 'function persistError(m,type){';
            $html .= '_hadError=true;';
            $html .= 'if(typeof $==="undefined"){setTimeout(function(){persistError(m,type);},500);return;}';
            $html .= '$.ajax({global:false,url:LOG_URL,type:"POST",data:{msg:m,type:type||"runtime",path:window.location.pathname},success:function(){updateBadge();}});';
            $html .= '}';


            /* fallback toast container */
            $html .= 'function ensureContainer(){';
            $html .= 'if(!document.body)return null;';
            $html .= 'var c=document.getElementById("js-error-container");';
            $html .= 'if(!c){c=document.createElement("div");c.id="js-error-container";c.className="position-fixed bottom-0 end-0 p-3";c.style.zIndex="20000";document.body.appendChild(c);}';
            $html .= 'return c;}';

            /* human-readable labels + toast severity per error type */
            $html .= 'var TYPE_INFO={';
            $html .= '"runtime":["JS Error","error"],';
            $html .= '"resource":["Missing File","warning"],';
            $html .= '"cross-origin":["Cross-Origin Script","warning"],';
            $html .= '"promise":["Promise Error","error"],';
            $html .= '"console":["Console Warning","warning"],';
            $html .= '"config":["Config Notice","info"]';
            $html .= '};';

            /* "View Log" snippet injected into toast message */
            $html .= 'var VIEW_LOG_HTML=\'<br><a href="#" style="font-size:.72rem;opacity:.75;pointer-events:auto" onclick="event.preventDefault();event.stopPropagation();jsErrRenderTable();(new bootstrap.Modal(document.getElementById(\\\'js-err-modal\\\'))).show();">\'';
            $html .= '+\'<i class=\\\'fas fa-list-ul me-1\\\'></i>View Error Log &rarr;</a>\';';

            /* show on current page (toast / fallback alert) */
            $html .= 'function showOnPage(m,type){';
            $html .= 'try{if(!m||s.has(m))return;s.add(m);';
            /* config notices go to log only — no toast, they are not actionable bugs */
            $html .= 'if(type==="config")return;';
            $html .= 'var info=TYPE_INFO[type]||["JS Error","error"];';
            $html .= 'var label=info[0],sev=info[1];';
            $html .= 'var display=m.length>130?m.substring(0,130)+"\u2026":m;';
            $html .= 'if(window.showToast){window.showToast(label,display+VIEW_LOG_HTML,sev,8000);return;}';
            $html .= 'var c=ensureContainer();if(!c){q.push([m,type]);return;}';
            $html .= 'var w=document.createElement("div");w.className="alert alert-"+(sev==="error"?"danger":"warning")+" d-flex align-items-center shadow mb-2";w.setAttribute("role","alert");';
            $html .= 'var b=document.createElement("div");b.className="flex-grow-1";';
            $html .= 'b.innerHTML="<strong>"+label+":</strong> "+display+VIEW_LOG_HTML;';
            $html .= 'var x=document.createElement("button");x.type="button";x.className="btn-close ms-2";x.setAttribute("aria-label","Close");';
            $html .= 'x.onclick=function(){if(w&&w.parentElement)w.parentElement.removeChild(w);};';
            $html .= 'w.appendChild(b);w.appendChild(x);c.appendChild(w);}catch(e){}}';

            /* main entry: persist to server + show toast on current page */
            $html .= 'function showMsg(m,type){';
            $html .= 'if(!m)return;';
            $html .= 'var t=type||"runtime";';
            $html .= 'persistError(String(m),t);';
            $html .= 'showOnPage(String(m),t);}';

            /* flush queue */

            /* on page load: update badge from server + flush queued errors */
            $html .= 'var _lastKnownCount=-1;';
            $html .= 'function flushQueue(){var c=ensureContainer();if(c&&q.length){q.splice(0).forEach(function(item){showOnPage(item[0],item[1]);});}}';
            /* shared helper — switches between footer-strip (0 errors) and floating circle (N errors)
               cw = number of config warnings (CORS etc) — shown as amber strip, not a red circle */
            $html .= 'function jsErrSetBadge(n,cw){';
            $html .= 'cw=cw||0;';
            $html .= 'var wrap=document.getElementById("js-err-badge");';
            $html .= 'var slot=document.getElementById("js-err-footer-slot");';
            $html .= 'var btn=document.getElementById("js-err-badge-btn");';
            $html .= 'var ico=document.getElementById("js-err-badge-ico");';
            $html .= 'var lbl=document.getElementById("js-err-badge-label");';
            $html .= 'var cnt=document.getElementById("js-err-badge-count");';
            $html .= 'if(n){';
            // ── Real errors: pull badge out of footer, float as red circle ──
            $html .= 'if(wrap&&wrap.parentNode===slot){document.body.appendChild(wrap);}';
            $html .= 'if(wrap){wrap.style.cssText="position:fixed;bottom:22px;left:22px;z-index:19998";}';
            $html .= 'if(btn){btn.className="btn btn-danger rounded-circle shadow-lg position-relative";';
            $html .= 'btn.style.cssText="width:48px;height:48px;padding:0";';
            $html .= 'btn.title=(n===1?"1 JS error":n+" JS errors")+" — click to view";}';
            $html .= 'if(ico){ico.className="fas fa-bug";ico.style.fontSize="1.1rem";}';
            $html .= 'if(lbl)lbl.style.display="none";';
            $html .= 'if(cnt){cnt.className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark";';
            $html .= 'cnt.style.cssText="font-size:.6rem;min-width:18px";';
            $html .= 'cnt.textContent=n>99?"99+":String(n);cnt.style.display="";}';
            $html .= '}else{';
            // ── No errors (or just config notices): embed inside footer ──
            $html .= 'if(slot&&wrap&&wrap.parentNode!==slot){slot.appendChild(wrap);}';
            $html .= 'if(wrap){wrap.style.cssText="display:inline-flex";}';
            $html .= 'if(cw){';
            $html .= 'if(btn){btn.className="js-err-footer-btn js-err-footer-btn--warn";';
            $html .= 'btn.style.cssText="";';
            $html .= 'btn.title=(cw===1?"1 configuration notice":""+cw+" configuration notices")+" — click to view";}';
            $html .= 'if(ico){ico.className="fas fa-wrench";ico.style.fontSize="";}';
            $html .= 'if(lbl){lbl.style.display="";lbl.textContent=(cw===1?"1 config notice":cw+" config notices");}';
            $html .= 'if(cnt)cnt.style.display="none";';
            $html .= '}else{';
            $html .= 'if(btn){btn.className="js-err-footer-btn js-err-footer-btn--ok";';
            $html .= 'btn.style.cssText="";';
            $html .= 'btn.title="All clear — no JS errors";}';
            $html .= 'if(ico){ico.className="fas fa-check-circle";ico.style.fontSize="";}';
            $html .= 'if(lbl){lbl.style.display="";lbl.textContent="All clear";}';
            $html .= 'if(cnt)cnt.style.display="none";';
            $html .= '}}}'; 

            $html .= 'function updateBadgeAndNotify(){';
            $html .= 'if(typeof $==="undefined")return;';
            $html .= '$.ajax({url:GET_URL,dataType:"json",global:false,success:function(d){';
            $html .= 'var n=(d.errors||[]).length;';
            $html .= 'var cw=(d.config_warnings||[]).length;';
            $html .= 'jsErrSetBadge(n,cw);';
            // update last-checked timestamp in modal header
            $html .= 'var lc=document.getElementById("js-err-last-check");';
            $html .= 'if(lc){var now=new Date();lc.innerHTML=\'<i class="fas fa-sync-alt me-1"></i>Checked \'+now.toLocaleTimeString();}';
            // only notify about new real errors, not config notices
            $html .= 'if(_lastKnownCount>=0&&n>_lastKnownCount){';
            $html .= 'var newCount=n-_lastKnownCount;';
            $html .= 'if(window.showToast)window.showToast("New JS Error",(newCount===1?"1 new error":""+newCount+" new errors")+" detected — check the error log","error",6000);';
            $html .= '}';
            $html .= '_lastKnownCount=n;';
            $html .= '}});}';
            // replace the old updateBadge so all existing callers get the notify version
            $html .= 'var updateBadge=updateBadgeAndNotify;';
            $html .= 'function safeUpdateBadge(){if(typeof $!=="undefined"){updateBadge();flushQueue();}else{setTimeout(safeUpdateBadge,200);}}';
            // only run badge polling and initial fetch in the real admin window, not inside re-check iframes
            $html .= 'if(!_inIframe){';
            $html .= 'document.addEventListener("DOMContentLoaded",function(){setTimeout(safeUpdateBadge,100);});';
            $html .= 'window.addEventListener("load",function(){safeUpdateBadge();});';
            $html .= 'setInterval(function(){if(typeof $!=="undefined")updateBadge();},30000);}'; // poll every 30 s

            /* runtime JS errors + resource load failures (same filtering as store reporter) */
            $html .= 'window.addEventListener("error",function(e){';
            $html .= 'if(e.target&&e.target!==window&&!e.message){';
            $html .= 'var tag=(e.target.tagName||"").toLowerCase();';
            $html .= 'if(tag!=="script"&&tag!=="link"&&tag!=="img")return;';
            $html .= 'var raw=e.target.src||e.target.href||"";';
            $html .= 'jsErrHandleResErr(showMsg,tag,raw,e.target);';
            $html .= 'return;}';
            $html .= 'if(!e||!e.message)return;';
            $html .= 'var loc=e.filename||"";';
            /* skip errors originating from browser extensions */
            $html .= 'if(loc.indexOf("extension://")!==-1)return;';
            $html .= 'if(e.message==="Script error."||e.message==="Script error"){showMsg("Cross-origin script error — check browser console","cross-origin");return;}';
            $html .= 'if(e.message.indexOf("Non-Error")!==-1)return;';
            $html .= 'var ln=e.lineno||0,cn=e.colno||0;';
            $html .= 'showMsg(e.message+(loc?" ("+loc.split("/").pop()+":"+ln+":"+cn+")":""),"runtime");';
            $html .= '},true);';

            /* unhandled promise rejections */
            $html .= 'window.addEventListener("unhandledrejection",function(e){';
            $html .= 'if(!e||!e.reason)return;';
            $html .= 'var r=e.reason;';
            $html .= 'if(typeof r==="string"&&r.indexOf("Non-Error")!==-1)return;';
            /* route CORS "Origin not allowed" as config notice — not a code bug; needs third-party API domain whitelisting */
            $html .= 'if(typeof r==="string"&&r.indexOf("Origin not allowed")!==-1){showMsg(r,"config");return;}';
            $html .= 'if(r&&r.message&&r.message.indexOf("Origin not allowed")!==-1){showMsg(r.message,"config");return;}';
            /* skip isTrusted-only objects — these are DOM Event objects, not real errors */
            $html .= 'if(r&&typeof r==="object"&&Object.keys(r).length===0)return;';
            $html .= 'if(r&&typeof r==="object"&&r.isTrusted===true&&!r.message&&!r.stack)return;';
            $html .= 'var msg=(r&&r.message)?r.message:(typeof r==="string"?r:null);';
            $html .= 'if(!msg&&r&&typeof r==="object"){try{var j=JSON.stringify(r);if(j==="{}"||j===\'{"isTrusted":true}\')return;msg=j;}catch(x){msg="Promise Rejection";}}';
            $html .= 'showMsg(msg||"Promise Rejection","promise");},true);';

            /* console.error interception */
            $html .= '(function(){var _o=console.error;';
            $html .= 'console.error=function(){';
            $html .= 'try{var a=Array.prototype.slice.call(arguments);';
            $html .= 'var m=a.map(function(x){if(!x)return"";if(x instanceof Error)return x.message;if(typeof x==="string")return x;try{return JSON.stringify(x);}catch(e){return String(x);}}).filter(Boolean).join(" ");';
            $html .= 'var skip=["favicon","[Deprecation]","[Violation]","Non-Error","net::ERR_ABORTED","ResizeObserver","404 (Not Found)","isTrusted","segment","ajs-destination","analytics.min","gtag","googletagmanager"];';
            $html .= 'if(!_inIframe&&m&&!skip.some(function(k){return m.indexOf(k)!==-1;}))showMsg(m,"console");}catch(e){}';
            $html .= 'if(typeof _o==="function")_o.apply(console,arguments);};}());';

            /* ── Modal table renderer (global, loads from server) ── */
            $html .= 'function jsEsc(s){return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");}';

            $html .= 'var TYPE_META={';
            $html .= '"runtime":  {col:"danger",  bdr:"#dc3545",ico:"fa-exclamation-triangle"},';
            $html .= '"resource": {col:"warning", bdr:"#fd7e14",ico:"fa-unlink"},';
            $html .= '"cross-origin":{col:"secondary",bdr:"#6c757d",ico:"fa-globe"},';
            $html .= '"promise":  {col:"info",    bdr:"#0dcaf0",ico:"fa-code"},';
            $html .= '"console":  {col:"secondary",bdr:"#6c757d",ico:"fa-terminal"},';
            $html .= '"config":   {col:"warning", bdr:"#ffc107",ico:"fa-wrench"}';
            $html .= '};';

            $html .= 'function jsErrFillTable(log,cfgLog){';
            $html .= '_jeLog=log||[];';
            $html .= 'var cfgWarnings=cfgLog||[];';
            $html .= 'var tbody=document.getElementById("js-err-tbody");if(!tbody)return;tbody.innerHTML="";';
            $html .= 'if(!_jeLog.length&&!cfgWarnings.length){';
            $html .= 'tbody.innerHTML=\'<tr><td colspan="5" class="text-center py-5">\'';
            $html .= '+\'<div style="font-size:2.5rem;color:#28a745" class="mb-2"><i class="fas fa-check-circle"></i></div>\'';
            $html .= '+\'<div class="fw-semibold text-success fs-6">All clear — no errors recorded</div>\'';
            $html .= '+\'<div class="text-muted mt-2" style="font-size:.8rem">Errors appear automatically when pages are visited.<br>Use <strong>Re-check Pages</strong> above to scan all previously affected pages at once.</div>\'';
            $html .= '+\'</td></tr>\';return;}';

            $html .= 'var now=Date.now();';
            $html .= '_jeLog.forEach(function(entry,idx){';
            $html .= 'var meta=TYPE_META[entry.type]||TYPE_META["runtime"];';
            $html .= 'var lastSeen=new Date(entry.last_seen||entry.time);';
            $html .= 'var firstSeen=new Date(entry.first_seen||entry.time);';
            $html .= 'var ageMs=now-lastSeen.getTime();';
            $html .= 'var isNew=ageMs<3600000;';
            $html .= 'var isStale=ageMs>86400000;';
            $html .= 'var tsLast=lastSeen.toLocaleDateString()+" "+lastSeen.toLocaleTimeString();';
            $html .= 'var tsFirst=firstSeen.toLocaleDateString()+" "+firstSeen.toLocaleTimeString();';
            $html .= 'var cnt=entry.count||1;';
            $html .= 'var rid="jer"+now+idx;';

            /* age dot — purely visual, no misleading label */
            $html .= 'var dotClr=isNew?"#dc3545":"#ced4da";';
            $html .= 'var ageText=isNew?"Logged recently (last hour)":"Logged more than 1 hour ago";';
            $html .= 'var dot=\'<span class="d-inline-block rounded-circle flex-shrink-0" style="width:8px;height:8px;background:\'+dotClr+\';margin-right:7px;vertical-align:middle;margin-top:-1px" title="\'+ageText+\'"></span>\';';

            /* count pill */
            $html .= 'var cntHtml=cnt>1?\'<span class="badge rounded-pill ms-1 align-middle" style="background:#6c757d;font-size:.6rem" title="Triggered \'+cnt+\' times">&times;\'+cnt+\'</span>\':\'\';';

            /* page name + parent path */
            $html .= 'var parts=entry.path.split("/").filter(Boolean);';
            $html .= 'var pgName=parts.length?parts[parts.length-1]:entry.path;';
            $html .= 'var pgParent=parts.length>1?"/"+parts.slice(0,-1).join("/")+"/":"";';
            $html .= 'var pgLink=window.location.origin+entry.path;';

            /* message: short + full in title */
            $html .= 'var shortMsg=entry.msg.length>160?entry.msg.substring(0,160)+"\u2026":entry.msg;';

            $html .= 'var tr=document.createElement("tr");';
            $html .= 'var rowBg=isNew?"#fffcfc":isStale?"#fafafa":"#ffffff";';
            $html .= 'tr.style.cssText="border-left:3px solid "+meta.bdr+";background:"+rowBg+";"+(isStale?"opacity:.65":"");';

            $html .= 'tr.innerHTML=';
            /* Last Seen cell */
            $html .= '\'<td class="ps-3" style="min-width:150px" title="First seen: \'+jsEsc(tsFirst)+\'\\nLast seen: \'+jsEsc(tsLast)+\'">\'+dot';
            $html .= '+\'<span class="fw-medium">\'+jsEsc(tsLast)+\'</span>\'+cntHtml+\'</td>\'';
            /* Page cell */
            $html .= '+\'<td style="min-width:155px;max-width:185px">\'';
            $html .= '+\'<a href="\'+jsEsc(pgLink)+\'" target="_blank" class="text-decoration-none" title="Open: \'+jsEsc(entry.path)+\'">\'';
            $html .= '+\'<span class="fw-semibold text-primary d-block" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">\'+jsEsc(pgName)+\'</span>\'';
            $html .= '+\'<span class="text-muted d-block" style="font-size:.65rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">\'+jsEsc(pgParent)+\'</span>\'';
            $html .= '+\'</a></td>\'';
            /* Type cell */
            $html .= '+\'<td style="min-width:100px"><span class="badge d-inline-flex align-items-center gap-1 bg-\'+meta.col+\'"><i class="fas \'+meta.ico+\'"></i>\'+jsEsc(entry.type)+\'</span></td>\'';
            /* Message cell */
            $html .= '+\'<td class="text-break" style="min-width:210px"><code class="text-dark" style="font-size:.78rem" id="\'+rid+\'" title="\'+jsEsc(entry.msg)+\'">\'+jsEsc(shortMsg)+\'</code></td>\'';
            /* Actions cell: Visit · Copy · Dismiss */
            $html .= '+\'<td class="text-center" style="min-width:100px">\'';
            $html .= '+\'<a href="\'+jsEsc(pgLink)+\'" target="_blank" class="btn btn-sm py-0 px-2 me-1" style="background:#f0fff4;border:1px solid #b7dfc6" title="Open this page in a new tab to verify your fix is live"><i class="fas fa-external-link-alt text-success"></i></a>\'';
            $html .= '+\'<button class="btn btn-sm py-0 px-2 me-1 je-copy" style="background:#f0f0f0;border:1px solid #ddd" title="Copy full error message to clipboard"><i class="fas fa-copy text-secondary"></i></button>\'';
            $html .= '+\'<button class="btn btn-sm py-0 px-2 je-dismiss" style="background:#fff0f0;border:1px solid #ffcccc" title="Dismiss this entry — it will reappear if the error is still present"><i class="fas fa-times text-danger"></i></button>\'';
            $html .= '+\'</td>\';';

            /* wire handlers */
            $html .= '(function(i,r){';
            $html .= 'var cb=tr.querySelector(".je-copy"),db=tr.querySelector(".je-dismiss");';
            $html .= 'if(cb)cb.onclick=function(){jsErrCopyRow(i);};'; /* pass idx so copy gets full entry */
            $html .= 'if(db)db.onclick=function(){jsErrDismiss(i);};';
            $html .= '})(idx,rid);';
            $html .= 'tbody.appendChild(tr);});';
            /* ── Configuration Notices section (appended after real errors) ── */
            $html .= 'if(cfgWarnings.length){';
            /* section separator row */
            $html .= 'var sep=document.createElement("tr");';
            $html .= 'sep.innerHTML=\'<td colspan="5" style="padding:0"><div style="background:#fff8e1;border-top:2px solid #ffc107;border-bottom:1px solid #ffe082;padding:7px 14px;display:flex;align-items:center;gap:8px">\'';
            $html .= '+\'<span style="background:#ffc107;color:#fff;border-radius:50%;width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0"><i class="fas fa-wrench"></i></span>\'';
            $html .= '+\'<span style="font-size:.8rem;font-weight:600;color:#856404">Configuration Notices (\'+cfgWarnings.length+\')</span>\'';
            $html .= '+\'<span style="font-size:.74rem;color:#6c5900;margin-left:4px">— These are not code bugs. Fix them by adding your domain to each third-party service\\\u2019s allowed-origins/allowed-domains list.</span>\'';
            $html .= '+\'</div></td>\';';
            $html .= 'tbody.appendChild(sep);';
            /* one row per config warning */
            $html .= 'cfgWarnings.forEach(function(entry,idx){';
            $html .= 'var lastSeen=new Date(entry.last_seen||entry.time);';
            $html .= 'var tsLast=lastSeen.toLocaleDateString()+" "+lastSeen.toLocaleTimeString();';
            $html .= 'var cnt=entry.count||1;';
            $html .= 'var parts=entry.path.split("/").filter(Boolean);';
            $html .= 'var pgName=parts.length?parts[parts.length-1]:entry.path;';
            $html .= 'var pgParent=parts.length>1?"/"+parts.slice(0,-1).join("/")+"/":"";';
            $html .= 'var pgLink=window.location.origin+entry.path;';
            $html .= 'var cntHtml=cnt>1?\'<span class="badge rounded-pill ms-1 align-middle" style="background:#856404;font-size:.6rem">&times;\'+cnt+\'</span>\':\'\';';
            $html .= 'var tr2=document.createElement("tr");';
            $html .= 'tr2.style.cssText="border-left:3px solid #ffc107;background:#fffdf0;font-size:.81rem";';
            $html .= 'tr2.innerHTML=';
            $html .= '\'<td class="ps-3" style="min-width:150px">\'+jsEsc(tsLast)+cntHtml+\'</td>\'';
            $html .= '+\'<td style="min-width:155px;max-width:185px">\'';
            $html .= '+\'<a href="\'+jsEsc(pgLink)+\'" target="_blank" class="text-decoration-none">\'';
            $html .= '+\'<span class="fw-semibold text-primary d-block" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">\'+jsEsc(pgName)+\'</span>\'';
            $html .= '+\'<span class="text-muted d-block" style="font-size:.65rem">\'+jsEsc(pgParent)+\'</span></a></td>\'';
            $html .= '+\'<td><span class="badge d-inline-flex align-items-center gap-1" style="background:#ffc107;color:#333"><i class="fas fa-wrench"></i>Config</span></td>\'';
            $html .= '+\'<td class="text-break"><code style="font-size:.77rem;color:#5a3e00">\'+jsEsc(entry.msg)+\'</code>\'';
            $html .= '+\'<div style="font-size:.7rem;color:#856404;margin-top:3px"><i class="fas fa-info-circle me-1"></i>Add \'+jsEsc(window.location.hostname)+\' to this API\\\'s allowed-origins / allowed-domains setting.</div></td>\'';
            $html .= '+\'<td class="text-center">\'';
            $html .= '+\'<a href="\'+jsEsc(pgLink)+\'" target="_blank" class="btn btn-sm py-0 px-2" style="background:#fff8e1;border:1px solid #ffe082" title="Open page"><i class="fas fa-external-link-alt" style="color:#856404"></i></a></td>\';';
            $html .= 'tbody.appendChild(tr2);});';
            $html .= '}}';

            $html .= 'window.jsErrRenderTable=function(){';
            $html .= 'var tbody=document.getElementById("js-err-tbody");';
            $html .= 'if(tbody)tbody.innerHTML=\'<tr><td colspan="5" class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading\u2026</td></tr>\';';
            $html .= '$.ajax({url:GET_URL,dataType:"json",global:false,success:function(d){jsErrFillTable(d.errors||[],d.config_warnings||[]);},error:function(){';
            $html .= 'if(tbody)tbody.innerHTML=\'<tr><td colspan="5" class="text-center text-danger py-3">Failed to load error log</td></tr>\';}});};';

            $html .= 'window.jsErrDismiss=function(idx){';
            $html .= 'var entry=_jeLog[idx];if(!entry)return;';
            $html .= '$.ajax({global:false,url:DISMISS_URL,type:"POST",data:{msg:entry.msg,path:entry.path},success:function(){';
            $html .= 's.delete(entry.msg);';
            $html .= 'updateBadge();jsErrRenderTable();';
            $html .= 'if(window.showToast)window.showToast("","Dismissed — will reappear if the error is still present","success",3000);';
            $html .= '},error:function(){if(window.showToast)window.showToast("","Failed to dismiss","error",2000);}});};';

            $html .= 'window.jsErrCopyRow=function(idx){';
            $html .= 'var entry=_jeLog[idx];if(!entry)return;';
            $html .= 'var lines=[';
            $html .= '"Page    : "+(window.location.origin+entry.path),';
            $html .= '"Type    : "+entry.type,';
            $html .= '"Message : "+entry.msg,';
            $html .= '"Count   : "+(entry.count||1)+" occurrence(s)",';
            $html .= '"First   : "+(entry.first_seen||entry.time),';
            $html .= '"Last    : "+(entry.last_seen||entry.time)';
            $html .= '];';
            $html .= 'var t=lines.join("\n");';
            $html .= 'if(navigator.clipboard){navigator.clipboard.writeText(t);}else{var ta=document.createElement("textarea");ta.value=t;document.body.appendChild(ta);ta.select();document.execCommand("copy");document.body.removeChild(ta);}';
            $html .= 'if(window.showToast)window.showToast("","Copied!","success",1500);};';

            $html .= 'window.jsErrCopyAll=function(){';
            $html .= '$.ajax({url:GET_URL,dataType:"json",global:false,success:function(d){';
            $html .= 'var log=(d.errors||[]).concat(d.config_warnings||[]);';
            $html .= 'var t=log.map(function(e){return"[last:"+(e.last_seen||e.time)+"] ["+e.type+"] (x"+(e.count||1)+") "+e.path+"\n"+e.msg;}).join("\n---\n");';
            $html .= 'if(navigator.clipboard){navigator.clipboard.writeText(t);}else{var ta=document.createElement("textarea");ta.value=t;document.body.appendChild(ta);ta.select();document.execCommand("copy");document.body.removeChild(ta);}';
            $html .= 'if(window.showToast)window.showToast("","All "+log.length+" item(s) copied!","success",2000);}});};';

            $html .= 'window.jsErrClearAll=function(){';
            $html .= '$.ajax({global:false,url:CLEAR_URL,type:"POST",success:function(){';
            $html .= 's.clear();';
            $html .= 'updateBadge();jsErrFillTable([],[]);';
            $html .= 'if(window.showToast)window.showToast("","Log cleared \u2014 errors will re-appear when you visit those pages again","success",4000);';
            $html .= '}});};';

            /* Re-check Pages: load each unique path in a hidden iframe; store reporter auto-clean (≈5s after load) removes fixed paths */
            $html .= 'window.jsErrRecheckAll=function(){';
            $html .= 'var btn=document.getElementById("js-err-recheck-btn");';
            // prevent double-click / concurrent re-checks
            $html .= 'if(btn&&btn.disabled)return;';
            $html .= 'if(!_jeLog||!_jeLog.length){if(window.showToast)window.showToast("","No errors to re-check","info",2000);return;}';
            // collect unique paths
            $html .= 'var seen={},paths=[];';
            $html .= '_jeLog.forEach(function(e){if(e.path&&!seen[e.path]){seen[e.path]=true;paths.push(e.path);}});';
            $html .= 'var total=paths.length,done=0;';
            // disable button for the duration of the re-check
            $html .= 'if(btn){btn.disabled=true;btn.style.opacity="0.55";}';
            $html .= 'function setBtn(t){if(btn)btn.innerHTML=t;}';
            $html .= 'setBtn(\'<i class="fas fa-spinner fa-spin me-1"></i>Checking 0 / \'+total+\'…\');';
            // hidden container for iframes — full 1280×800 viewport so JS components initialise without errors,
            // positioned off-screen so the user never sees it
            $html .= 'var wrap=document.createElement("div");wrap.style.cssText="position:fixed;top:0;left:-9999px;width:1280px;height:800px;overflow:hidden;opacity:0;pointer-events:none;z-index:-1";document.body.appendChild(wrap);';
            // function called when each iframe finishes (load or timeout)
            $html .= 'function oneDone(){done++;setBtn(\'<i class="fas fa-spinner fa-spin me-1"></i>Checking \'+done+\' / \'+total+\'…\');';
            $html .= 'if(done<total)return;';
            // all iframes done — wait for store auto-clean (5s after load) + network; then refresh log
            $html .= 'setTimeout(function(){';
            $html .= 'wrap.remove();';
            $html .= 'setBtn(\'<i class="fas fa-sync-alt me-1"></i>Re-check Pages\');';
            $html .= 'if(btn){btn.disabled=false;btn.style.opacity="";}';
            $html .= 'var prev=_jeLog.length;';
            $html .= 'var tbody=document.getElementById("js-err-tbody");';
            $html .= 'if(tbody)tbody.innerHTML=\'<tr><td colspan="5" class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Refreshing\u2026</td></tr>\';';
            $html .= '$.ajax({url:GET_URL,dataType:"json",global:false,success:function(d){';
            $html .= 'jsErrFillTable(d.errors||[],d.config_warnings||[]);';
            $html .= 'var curr=_jeLog.length,cleared=Math.max(0,prev-curr);';
            $html .= 'jsErrSetBadge(curr,(d.config_warnings||[]).length);';
            $html .= 'if(window.showToast){';
            $html .= 'var msg=curr===0?\'All clear \u2014 no errors remain!\':(cleared>0?cleared+\' error(s) cleared, \'+curr+\' still active\':\'Re-check done \u2014 \'+curr+\' error(s) still active\');';
            $html .= 'window.showToast("",msg,curr===0?"success":"warning",5000);}';
            $html .= '},error:function(){if(window.showToast)window.showToast("","Re-check done \u2014 could not refresh log","warning",3000);}});';
            $html .= '},6500);}';
            // create one iframe per unique path — full viewport size so components render without errors
            $html .= 'paths.forEach(function(path){';
            $html .= 'var fr=document.createElement("iframe");';
            $html .= 'fr.style.cssText="width:1280px;height:800px;border:none";';
            $html .= 'var tid=setTimeout(function(){oneDone();},15000);'; // max 15 s per page
            $html .= 'fr.onload=function(){clearTimeout(tid);setTimeout(oneDone,6200);};'; // after store load + 5s clean + margin
            $html .= 'fr.src=window.location.origin+path;';
            $html .= 'wrap.appendChild(fr);});';
            $html .= '};';

            $html .= 'window.__showJsError=showMsg;';

            /* auto-clean: 4 s after full load with zero errors on this page → clear path from log */
            $html .= 'window.addEventListener("load",function(){';
            $html .= 'setTimeout(function(){';
            $html .= 'if(_hadError)return;';
            $html .= 'if(typeof $!=="undefined")$.ajax({global:false,url:CLEAN_URL,type:"POST",data:{path:window.location.pathname},success:function(){updateBadge();}});';
            $html .= '},4000);});';

            $html .= '})();</script>';

            return $html;
        }
    }

    function dd($debug, $die = true) {
        echo "<pre>";
        try {
            print_r($debug);
        } catch (Exception $e) {
            echo $debug;
        }
        if($die) die();
    }


    function generateRandomAlpahaNemericCode()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomecode=substr(str_shuffle($permitted_chars), 0, 10);
        return $randomecode;
    }

    function print_message($t) { 
        $hasAlerts = false;
        
        if($t->session->flashdata('success')) { 
            $hasAlerts = true;
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo $t->session->flashdata('success'); ?>
                <span class="countdown-timer ms-2 text-muted small">(5)</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php $t->session->unset_tempdata('success'); 
        }

        if($t->session->flashdata('error')) { 
            $hasAlerts = true;
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo $t->session->flashdata('error'); ?>
                <span class="countdown-timer ms-2 text-muted small">(5)</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php $t->session->unset_tempdata('error'); 
        }
        
        if($hasAlerts) { ?>
            <script>
                var countdown = 5;
                var timer = setInterval(function() {
                    countdown--;
                    var timers = document.querySelectorAll('.countdown-timer');
                    timers.forEach(function(timer) {
                        timer.textContent = '(' + countdown + ')';
                    });
                    
                    if (countdown <= 0) {
                        clearInterval(timer);
                        var alerts = document.querySelectorAll('.alert');
                        alerts.forEach(function(alert) {
                            var bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        });
                    }
                }, 1000);
            </script>
        <?php }
    }

    function file_upload_max_size() {
        $upload_max = ini_get('upload_max_filesize');
        return $upload_max;
    }

    function duplicate_entry($table, $field, $id, $primaryKey, $overwrite = []){
        $CI =& get_instance();
        $CI->db->where($field, $id); 
        $query = $CI->db->get($table);

        foreach ($query->result() as $row){   
            foreach($row as $key=>$val){        
                if($key != $primaryKey){
                    if(isset($overwrite[$key])){
                        $CI->db->set($key, $overwrite[$key]);
                    } else{
                        $CI->db->set($key, $val);
                    }
                }
            }
        }

        $CI->db->insert($table); 
        return $CI->db->insert_id();
    }

    function parseIntegrationType($type) {
        switch ($type) {
            case 'single_action':
            return __('admin.single_action_integration');
            break;
            case 'action':
            return __('admin.multi_action_integration');
            break;
            case 'general_click':
            return __('admin.click_integration');
            break;
            break;
            case 'program':
            return __('admin.sale_integration');
            break;
            default:
            return __('admin.unknown');
            break;
        }
    }

        function withdrwal_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-light text-dark border">'.__('admin.received').'</span>'; break;
                case 13: $label = '<span class="badge bg-warning text-dark">'.__('admin.pending').'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'.__('admin.paid').'</span>'; break;
                case 2: $label = '<span class="badge bg-warning text-dark">'.__('admin.total_not_match').'</span>'; break;
                case 3: $label = '<span class="badge bg-danger text-white">'.__('admin.denied').'</span>'; break;
                case 4: $label = '<span class="badge bg-secondary text-white">'.__('admin.expired').'</span>'; break;
                case 5: $label = '<span class="badge bg-danger text-white">'.__('admin.failed').'</span>'; break;
                case 6: $label = '<span class="badge bg-warning text-dark">'.__('admin.pending').'</span>'; break;
                case 7: $label = '<span class="badge bg-primary text-white">'.__('admin.processed').'</span>'; break;
                case 8: $label = '<span class="badge bg-info text-white">'.__('admin.refunded').'</span>'; break;
                case 9: $label = '<span class="badge bg-dark text-white">'.__('admin.reversed').'</span>'; break;
                case 10: $label = '<span class="badge bg-secondary text-white">'.__('admin.voided').'</span>'; break;
                case 11: $label = '<span class="badge bg-info text-white">'.__('admin.cancel_reversal').'</span>'; break;
                case 12: $label = '<span class="badge bg-warning text-dark">'.__('admin.waiting_for_payment').'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'.__('admin.unknown').'</span>'; break;
            }

            return $label;
        }


        function membership_withdrwal_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'.__('admin.pending').'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'.__('admin.active').'</span>'; break;
                case 2: $label = '<span class="badge bg-warning text-dark">'.__('admin.total_not_match').'</span>'; break;
                case 3: $label = '<span class="badge bg-danger text-white">'.__('admin.denied').'</span>'; break;
                case 4: $label = '<span class="badge bg-secondary text-white">'.__('admin.expired').'</span>'; break;
                case 5: $label = '<span class="badge bg-danger text-white">'.__('admin.failed').'</span>'; break;
                case 7: $label = '<span class="badge bg-primary text-white">'.__('admin.processed').'</span>'; break;
                case 8: $label = '<span class="badge bg-info text-white">'.__('admin.refunded').'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'.__('admin.unknown').'</span>'; break;
            }

            return $label;
        }


        function store_withdrwal_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'.__('admin.waiting_for_payment').'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'.__('admin.complete').'</span>'; break;
                case 2: $label = '<span class="badge bg-warning text-dark">'.__('admin.total_not_match').'</span>'; break;
                case 3: $label = '<span class="badge bg-danger text-white">'.__('admin.denied').'</span>'; break;
                case 4: $label = '<span class="badge bg-secondary text-white">'.__('admin.expired').'</span>'; break;
                case 5: $label = '<span class="badge bg-danger text-white">'.__('admin.failed').'</span>'; break;
                case 6: $label = '<span class="badge bg-warning text-dark">'.__('admin.pending').'</span>'; break;
                case 7: $label = '<span class="badge bg-primary text-white">'.__('admin.processed').'</span>'; break;
                case 8: $label = '<span class="badge bg-info text-white">'.__('admin.refunded').'</span>'; break;
                case 9: $label = '<span class="badge bg-dark text-white">'.__('admin.reversed').'</span>'; break;
                case 10: $label = '<span class="badge bg-secondary text-white">'.__('admin.voided').'</span>'; break;
                case 11: $label = '<span class="badge bg-info text-white">'.__('admin.cancel_reversal').'</span>'; break;
                case 12: $label = '<span class="badge bg-warning text-dark">'.__('admin.waiting_for_payment').'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'.__('admin.unknown').'</span>'; break;
            }

            return $label;
        }


        function ads_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'. __('admin.draft') .'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'. __('admin.public') .'</span>'; break;
                case 2: $label = '<span class="badge bg-info text-white">'. __('admin.in_review') .'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'.__('admin.unknown').'</span>'; break;
            }
            return $label;
        }


        function ads_security_status($status, $postback_status = null, $integration_method = 'js_pixel') {
            $label = '';
            $method = $integration_method ?: 'js_pixel';
            
            switch ((int)$status) {
                case 0:
                    if (in_array($method, ['s2s', 's2s_direct', 'conversion_api'])) {
                        $tooltip = __('admin.intg_tooltip_pending_api');
                    } else {
                        $tooltip = __('admin.intg_tooltip_pending_js');
                    }
                    $label = '<span class="badge bg-info text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($tooltip) . '">' . __('admin.pending_integration') . '</span>';
                    break;
                case 1:
                    if ($method === 's2s') {
                        $text = __('admin.intg_status_s2s_verified');
                        $tooltip = __('admin.intg_tooltip_s2s_verified');
                    } elseif ($method === 's2s_direct') {
                        $text = __('admin.intg_status_mobile_verified');
                        $tooltip = __('admin.intg_tooltip_mobile_verified');
                    } elseif ($method === 'conversion_api') {
                        $text = __('admin.intg_status_conv_api_verified');
                        $tooltip = __('admin.intg_tooltip_conv_api_verified');
                    } else {
                        $text = __('admin.approved');
                        $tooltip = __('admin.intg_tooltip_approved_js');
                    }
                    $label = '<span class="badge bg-success text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($tooltip) . '">' . $text . '</span>';
                    break;
                case 2:
                    $tooltip = __('admin.intg_tooltip_postback');
                    $label = '<span class="badge bg-primary text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($tooltip) . '">' . __('admin.postback') . '</span>';
                    break;
                default: 
                    $label = '<span class="badge bg-secondary text-white">' . __('admin.unknown') . '</span>';
                    break;
            }
            
            return $label;
        }

        function ads_running_status($status){
            $label = '';

            switch ((int)$status) {
                case 0: $label = 'warning'; break;
                case 1: $label = 'success'; break;
                default: $label = 'warning'; break;
            }

            return $label;
        }
        function ads_google_status($status){
            $label = '';

            switch ((int)$status) {
                case 1: $label =  __('admin.side_bar_top'); break;
                case 2: $label = __('admin.side_bar_bottom'); break;
                case 3: $label = __('admin.footer'); break;
                case 4: $label = __('admin.right_side'); break;
                case 5: $label = __('admin.center_page'); break;
                default: $label = __('admin.right_side'); break;
            }

            return $label;
        }

        function program_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'. __('admin.in_review') .'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'. __('admin.approved') .'</span>'; break;
                case 2: $label = '<span class="badge bg-danger text-white">'. __('admin.denied') .'</span>'; break;
                case 3: $label = '<span class="badge bg-warning text-dark">'. __('admin.ask_to_edit') .'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'. __('admin.unknow') .'</span>'; break;
            }
            return $label;
        }


        function product_status_on_store($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-danger text-white">'. __('admin.not_displayed') .'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'. __('admin.displayed') .'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'. __('admin.unknow') .'</span>'; break;
            }
            return $label;
        }


        function product_status_on_store_admin($status, $product_by) {
            $label = '';
            $statusClass = (int)$status === 0 ? 'bg-danger' : 'bg-success';
            $statusText = (int)$status === 0 ? __('admin.draft') : __('admin.displayed');
            $textClass = (int)$status === 0 ? 'text-white' : 'text-white';
            $label = '<span class="badge '. $statusClass .' '. $textClass .'">'. $statusText .'</span>';
            return $label;
        }


        function product_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'. __('admin.in_review') .'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'. __('admin.approved') .'</span>'; break;
                case 2: $label = '<span class="badge bg-danger text-white">'. __('admin.denied') .'</span>'; break;
                case 3: $label = '<span class="badge bg-warning text-dark">'. __('admin.ask_to_edit') .'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">'. __('admin.unknow') .'</span>'; break;
            }
            return $label;
        }


        function form_status($status) {
            $label = '';
            switch ((int)$status) {
                case 0: $label = '<span class="badge bg-warning text-dark">'. __('admin.in_review') .'</span>'; break;
                case 1: $label = '<span class="badge bg-success text-white">'. __('admin.approved') .'</span>'; break;
                case 2: $label = '<span class="badge bg-danger text-white">'. __('admin.denied') .'</span>'; break;
                case 3: $label = '<span class="badge bg-warning text-dark">'. __('admin.ask_to_edit') .'</span>'; break;
                default: $label = '<span class="badge bg-secondary text-white">Unknow</span>'; break;
            }
            return $label;
        }


        function cycle_details($total_recurring,$next_transaction,$endtime = false,$total_recurring_amount = false ){
            $str =  'Runs '. (int)$total_recurring;

            if($next_transaction != ''){
                $str .= " | Next At : ". date("d-m-Y H:i",strtotime($next_transaction));
            }
            if($endtime != ''){
                $str .= " | Endtime : ". date("d-m-Y H:i",strtotime($endtime));
            }
            if($total_recurring_amount){
                $str .= " | Total Amount : ". c_format($total_recurring_amount);
            }

            return $str;
        }

        function dateFormat($date , $f = "d-m-Y H:i:s"){
            return date($f,strtotime($date));
        }
        function timetosting($minutes){
            $day = floor ($minutes / 1440);
            $hour = floor (($minutes - $day * 1440) / 60);
            $minute = $minutes - ($day * 1440) - ($hour * 60);

            $str = '';
            if($day > 0) $str .= "{$day} day ";
            if($hour > 0) $str .= "{$hour} hour ";
            if($minute > 0) $str .= "{$minute} minute ";

            return $str;
        }
        if (!function_exists('legacy_asset_url')) {
            function legacy_asset_url() {
                echo base_url() . 'assets/';
            }
        }
        function pr($data) {
            echo '<pre>'; print_r($data); echo '</pre>';
        }
        function flashMsg($flash) { 
            if (isset($flash['error'])) {
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['error']. '</div>';
            }
            if (isset($flash['success'])) {
                echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['success'] . '</div>';
            }
        }
    function DOCROOT($file, $from) {
        if ($from == 'full') {
            return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/' . $file;
        } elseif ($from == 'thumb') {
            return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/thumb/' . $file;
        }
    }

    function set_default_currency(){
        ___construct(1);
    }

    function is_rtl()
    {
        $CI =& get_instance();
        $lang = $_SESSION['userLang'];
        $lang = $CI->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();

        if ($lang['is_rtl']) {
            return true;
        } 

        return false;
    }

    global $language; 
    function __($key){
        global $language;
        $userLang = $_SESSION['userLang'];
        if($userLang == ''){
            $CI =& get_instance();
            $default_language = $CI->db->query("SELECT * FROM language WHERE status=1 AND is_default=1")->row_array();
            if($default_language){
                $userLang = $_SESSION['userLang'] = $default_language['id'];
            }
        }
        if(!$language){
            fillLang($userLang);
        }

        return isset($language[$key]) ? $language[$key] : $key;
    }

    if (!function_exists('market_promotion_http_block')) {
        /**
         * HTML 403 for blocked market links (health / award), aligned with server-side affiliate gates.
         */
        function market_promotion_http_block() {
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: text/html; charset=UTF-8');
            $msg = function_exists('__') ? __('user.market_promotion_blocked_public') : 'This promotion link is not active.';
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</title></head><body><p style="font-family:system-ui,sans-serif;padding:2rem;max-width:36rem;">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p></body></html>';
            exit;
        }
    }

    function fillLang($id){
        global $language;
        $language = array();
        $lang_files = ['admin','client','store','user','front','template_simple'];


        foreach ($lang_files as $file) {
            if(is_file(APPPATH.'/language/default/'. $file .'.php')){
                require  APPPATH.'/language/default/'. $file .'.php';
                foreach ($lang as $key => $value) {
                    $language[$file . '.'.$key] = $value;
                }
            }
            $lang = array();
        }

        if($id != 1){
            foreach ($lang_files as $file) {
                if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){
                    require  APPPATH.'/language//'. $id .'//'. $file .'.php';
                    foreach ($lang as $key => $value) {
                        if($value) $language[$file . '.'.$key] = $value;
                    }
                }
                $lang = array();
            }
        }
    }

    function recurse_copy($src,$dst) { 
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst, 0777, true);
        }
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    function lang_copy($src,$dst, $defaultLangPath = null){
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst, 0777, true);
        }

        $lang_files = ['admin','client','store','user','front','template_simple'];
        foreach ($lang_files as $file) {

            if($defaultLangPath != null && is_file($defaultLangPath .'/'. $file .'.php')) {
                $src = $defaultLangPath;
                $copy_translation = true;
            }


            if(is_file($src .'/'. $file .'.php')){
                $lang = array();

                require  $src .'/'. $file .'.php';

                $path = $dst."/".$file.".php";

                $file_content = '<?php '.PHP_EOL;

                foreach ($lang as $key => $value) {
                    if(isset($copy_translation)) {
                        $file_content .= '$lang[\''. $key .'\'] = \''. str_replace('"','\"', str_replace("'","\'", $value)) .'\';' .PHP_EOL;
                    } else {
                        $file_content .= '$lang[\''. $key .'\'] = \'\';' .PHP_EOL;
                    }
                }

                file_put_contents($path, $file_content);
            }
            $lang = array();
        }
    }
    function langCount($id){
        $id = $id == "1" ? 'default' : $id;

        $count      = ['all' => 0, 'missing' => 0];
        $lang_files = ['admin', 'client', 'store', 'user', 'front', 'template_simple'];
        $src_dir    = APPPATH . 'language/default/';
        $dst_dir    = APPPATH . 'language/' . $id . '/';

        foreach ($lang_files as $file) {
            $src_path = $src_dir . $file . '.php';
            if (!is_file($src_path)) continue;

            // Use parse_lang_file_admin which properly isolates the $lang variable
            $source      = parse_lang_file_admin($src_path);
            $destination = parse_lang_file_admin($dst_dir . $file . '.php');

            foreach ($source as $key => $en_val) {
                if (trim((string)$en_val) === '') continue;
                $count['all']++;
                $dst_val = $destination[$key] ?? '';
                if (trim((string)$dst_val) === '') {
                    $count['missing']++;
                }
            }
        }

        return $count;
    }

    /**
     * Returns the Google Translate API language code for an ISO code.
     * Google uses legacy codes for some languages (iw=Hebrew, jw=Javanese, zh-CN=Chinese).
     */
    function get_gt_code_map() {
        return [
            'zh'    => 'zh-CN',
            'he'    => 'iw',
            'jv'    => 'jw',
        ];
    }

    /**
     * Parse a CodeIgniter language PHP file into a key => value array.
     * Handles single and double quoted values with escape sequences.
     */
    function parse_lang_file_admin(string $path): array {
        if (!file_exists($path)) return [];
        $content = file_get_contents($path);
        $lang    = [];
        preg_match_all(
            '/\$lang\[[\'"]([\w]+)[\'"]\]\s*=\s*\'((?:[^\'\\\\]|\\\\.)*)\'\s*;/',
            $content, $ms
        );
        preg_match_all(
            '/\$lang\[[\'"]([\w]+)[\'"]\]\s*=\s*"((?:[^"\\\\]|\\\\.)*)"\s*;/',
            $content, $md
        );
        foreach ($ms[1] as $i => $key) $lang[$key] = stripcslashes($ms[2][$i]);
        foreach ($md[1] as $i => $key) $lang[$key] = stripcslashes($md[2][$i]);
        return $lang;
    }

    /**
     * Call Google Translate free (gtx) API.
     * Protects sprintf placeholders (%s, %d, %f, %u) from being mangled.
     * Returns array of translated strings, or false on failure.
     */
    function call_translate_api_admin(array $strings, string $target_lang) {
        if (empty($strings)) return [];

        $base = 'https://translate.googleapis.com/translate_a/t?client=gtx&sl=en&tl=' . urlencode($target_lang);
        $body = '';
        foreach ($strings as $s) {
            $s_safe = preg_replace('/%([sdfu])/', '__PCT$1__', $s);
            $body .= '&q=' . urlencode($s_safe);
        }
        $body = ltrim($body, '&');

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $base,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $body,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_HTTPHEADER     => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
            ]);
            $raw = curl_exec($ch);
            $err = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($err || $raw === false) {
                if ($attempt < 3) { usleep(600000); continue; }
                return false;
            }
            // Rate limited or server error — back off and retry
            if ($http_code === 429 || $http_code >= 500) {
                if ($attempt < 3) { usleep(1000000 * $attempt); continue; }
                return false;
            }
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                if ($attempt < 3) { usleep(600000); continue; }
                return false;
            }

            $result = [];
            foreach ($data as $item) {
                $t = is_array($item) ? ($item[0] ?? '') : (string)$item;
                $t = preg_replace('/__PCT([sdfu])__/', '%$1', $t);
                $result[] = $t;
            }
            return $result;
        }
        return false;
    }

    /**
     * Translate a single batch of missing keys for one file and write to disk.
     * Called repeatedly by the client (one AJAX per batch) for live progress.
     * Uses a temp JSON accumulator so mid-translation writes don't corrupt detection.
     *
     * @param  int    $lang_id   DB id / folder name
     * @param  string $lang_code ISO code (e.g. 'be')
     * @param  string $file_php  Filename with extension, e.g. 'admin.php'
     * @param  int    $limit     Keys per batch (default 100)
     * @return array  { success, translated, remaining, is_done }
     */
    function translate_missing_lang_batch($lang_id, $lang_code, $file_php, $limit = 100) {
        $gt_map   = get_gt_code_map();
        $gt_code  = $gt_map[$lang_code] ?? $lang_code;
        $file     = preg_replace('/\.php$/', '', $file_php);
        $src_path = APPPATH . 'language/default/' . $file . '.php';
        $dst_dir  = APPPATH . 'language/' . $lang_id . '/';
        $dst_path = $dst_dir . $file . '.php';
        // Temp accumulator: holds only genuinely translated keys so "missing" detection stays accurate
        $tmp_path = $dst_dir . $file . '.trans_progress.json';

        if (!file_exists($src_path)) {
            return ['success' => true, 'translated' => 0, 'remaining' => 0, 'is_done' => true];
        }

        $source = parse_lang_file_admin($src_path);

        // Load accumulated translations: prefer temp file (mid-session), else existing dst
        if (file_exists($tmp_path)) {
            $accumulated = json_decode(file_get_contents($tmp_path), true) ?: [];
        } else {
            // First batch for this file — seed from any already-translated keys in dst
            $existing    = parse_lang_file_admin($dst_path);
            $accumulated = [];
            foreach ($existing as $k => $v) {
                // Only carry forward non-empty values that differ from the English source
                // (i.e. they were previously translated, not just English fallback)
                if (trim($v) !== '' && $v !== ($source[$k] ?? '')) {
                    $accumulated[$k] = $v;
                }
            }
        }

        // Collect keys STILL missing from the accumulator
        $missing_keys = [];
        $missing_vals = [];
        foreach ($source as $key => $en_val) {
            if (trim($en_val) === '') continue;
            if (!isset($accumulated[$key]) || trim($accumulated[$key]) === '') {
                $missing_keys[] = $key;
                $missing_vals[] = $en_val;
            }
        }

        $remaining = count($missing_keys);
        if ($remaining === 0) {
            // Nothing left — write final PHP file and remove temp
            _write_final_lang_file($source, $dst_dir, $dst_path, $accumulated, $tmp_path);
            return ['success' => true, 'translated' => 0, 'remaining' => 0, 'is_done' => true];
        }

        // Translate the first $limit still-missing keys
        $batch_keys = array_slice($missing_keys, 0, $limit);
        $batch_vals = array_slice($missing_vals, 0, $limit);

        $api_result       = call_translate_api_admin($batch_vals, $gt_code);
        $translated_count = 0;

        if ($api_result === false) {
            // API failed — store English as fallback so keys aren't left blank
            foreach ($batch_keys as $i => $key) {
                $accumulated[$key] = $batch_vals[$i];
                $translated_count++;
            }
        } else {
            foreach ($batch_keys as $i => $key) {
                $val = trim($api_result[$i] ?? '') !== '' ? $api_result[$i] : $batch_vals[$i];
                $accumulated[$key] = $val;
                $translated_count++;
            }
        }

        $new_remaining = max(0, $remaining - $translated_count);

        if (!is_dir($dst_dir)) mkdir($dst_dir, 0777, true);

        if ($new_remaining === 0) {
            // Last batch — write final PHP file and clean up temp
            _write_final_lang_file($source, $dst_dir, $dst_path, $accumulated, $tmp_path);
        } else {
            // Save intermediate progress to temp JSON (NOT to the live PHP file)
            file_put_contents($tmp_path, json_encode($accumulated, JSON_UNESCAPED_UNICODE));
        }

        return [
            'success'    => true,
            'translated' => $translated_count,
            'remaining'  => $new_remaining,
            'is_done'    => $new_remaining === 0,
        ];
    }

    /** Write the final PHP lang file from the accumulated array, then delete the temp progress file. */
    function _write_final_lang_file($source, $dst_dir, $dst_path, $accumulated, $tmp_path) {
        if (!is_dir($dst_dir)) mkdir($dst_dir, 0777, true);
        $content = "<?php \n";
        foreach ($source as $key => $en_val) {
            $val     = isset($accumulated[$key]) && trim($accumulated[$key]) !== '' ? $accumulated[$key] : $en_val;
            $escaped = str_replace(["\\", "'"], ["\\\\", "\\'"], $val);
            $content .= "\$lang['{$key}'] = '{$escaped}';\n";
        }
        file_put_contents($dst_path, $content);
        if (file_exists($tmp_path)) @unlink($tmp_path);
    }

    /**
     * Translate all missing (empty) keys for a language and write results to disk.
     * Uses the same Google Translate logic as the lang-sync dev-center tool.
     *
     * @param  int|string $lang_id    DB id of the language (used as folder name)
     * @param  string     $lang_code  ISO language code, e.g. 'fr', 'ar'
     * @return array      { translated: int, skipped: int, files: array, errors: array }
     */
    function translate_missing_lang_keys($lang_id, $lang_code, $single_file = null) {
        @set_time_limit(0);

        $gt_map    = get_gt_code_map();
        $gt_code   = $gt_map[$lang_code] ?? $lang_code;
        $src_dir   = APPPATH . 'language/default/';
        $dst_dir   = APPPATH . 'language/' . $lang_id . '/';
        $all_files  = ['admin', 'client', 'store', 'user', 'front', 'template_simple'];
        $lang_files = $single_file ? [preg_replace('/\.php$/', '', $single_file)] : $all_files;
        $batch_size = 50;

        $result = ['translated' => 0, 'skipped' => 0, 'files' => [], 'errors' => []];

        foreach ($lang_files as $file) {
            $src_path = $src_dir . $file . '.php';
            $dst_path = $dst_dir . $file . '.php';

            if (!file_exists($src_path)) continue;

            $source   = parse_lang_file_admin($src_path);
            $existing = parse_lang_file_admin($dst_path);

            // Collect keys that are missing or have empty values in the target
            $missing_keys = [];
            $missing_vals = [];
            foreach ($source as $key => $en_val) {
                if (empty($existing[$key]) && trim($en_val) !== '') {
                    $missing_keys[] = $key;
                    $missing_vals[] = $en_val;
                }
            }

            if (empty($missing_keys)) {
                $result['files'][$file] = ['count' => 0, 'status' => 'up_to_date'];
                $result['skipped']++;
                continue;
            }

            // Translate in batches of 100
            $translated    = $existing;
            $file_count    = 0;
            $key_batches   = array_chunk($missing_keys, $batch_size);
            $val_batches   = array_chunk($missing_vals, $batch_size);

            foreach ($key_batches as $bi => $batch_keys) {
                $batch_vals = $val_batches[$bi];
                $api_result = call_translate_api_admin($batch_vals, $gt_code);

                if ($api_result === false) {
                    $result['errors'][] = "API failed for {$file} batch " . ($bi + 1) . " — English fallback used";
                    foreach ($batch_keys as $bki => $bkey) {
                        $translated[$bkey] = $batch_vals[$bki];
                    }
                } else {
                    foreach ($batch_keys as $bki => $bkey) {
                        // If API returned empty string, fall back to English so the key is never stored blank
                        $translated_val = trim($api_result[$bki] ?? '') !== '' ? $api_result[$bki] : $batch_vals[$bki];
                        $translated[$bkey] = $translated_val;
                        $file_count++;
                    }
                }
                usleep(200000);
            }

            // Rebuild the PHP file preserving source key order
            // Never downgrade a non-empty existing value to empty
            if (!is_dir($dst_dir)) mkdir($dst_dir, 0777, true);
            $content = "<?php \n";
            foreach ($source as $key => $en_val) {
                $val = $translated[$key] ?? '';
                // If we ended up with empty, use the English source as a safe fallback
                if (trim($val) === '') $val = $en_val;
                $escaped = str_replace(["\\", "'"], ["\\\\", "\\'"], $val);
                $content .= "\$lang['{$key}'] = '{$escaped}';\n";
            }
            file_put_contents($dst_path, $content);

            $result['translated']           += $file_count;
            $result['files'][$file] = ['count' => $file_count, 'status' => 'translated'];
        }

        return $result;
    }

    function wallet_paid_status($status){
        $html = '';
        switch ($status) {
            case '0': return "<span class='badge bg-secondary text-white'>".__('admin.not_paid')."</span>"; break;
            case '1': return "<span class='badge bg-secondary text-white'>".__('admin.not_paid')."</span>"; break;
            case '2': return "<span class='badge bg-primary text-white'>".__('admin.in_request')."</span>"; break;
            case '3': return "<span class='badge bg-success text-white'>".__('admin.paid')."</span>"; break;
            case '4': return "<span class='badge bg-danger text-white'>".__('admin.declined')."</span>"; break;
            default: return "<span></span>"; break;
        }

    }

    function commission_status($status){
        $html = '';
        switch ($status) {
            case '1': return "<span class='badge bg-warning text-dark'>".__('admin.canceled')."</span>"; break;
            case '2': return "<span class='badge bg-danger text-white'>".__('admin.trashed')."</span>"; break;
            default: return "<span></span>"; break;
        }

    }

    function set_tmp_cache(){
        ___construct(1);
    }

    function wallet_whos_commission($trans,$userid=""){
        if($trans['type'] == 'external_click_comm_pay'){
            if($trans['from_user_id'] == '1'){ return "Pay to admin"; }
            else { return __('admin.pay_to_affiliate'); }
        }

        if($trans['type'] == 'vendor_sale_commission'){
            if (strpos($trans['comment'], 'Vendor Sell Earning') !== false) {
                return __('admin.vendor_earning');
            }
        }

        if($trans['type'] == 'vendor_shipping_reimbursement'){
            return __('admin.vendor_shipping_reimbursement');
        }

        if($trans['type'] == 'admin_shipping_reimbursement'){
            return __('admin.admin_shipping_reimbursement');
        }

        if($trans['comm_from'] == 'ex'){
            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){
                return __('admin.affiliate_commission');
            }
            if($trans['type'] == 'admin_sale_commission_v_pay'){
                return __('admin.pay_to_admin');
            }
            if($trans['type'] == 'sale_commission_vendor_pay'){
                return __('admin.pay_to_affiliate');
            }
            if($trans['type'] == 'external_click_commission'){
                return __('admin.affiliate_commission');
            }
            if($trans['type'] == 'click_commission' || $trans['type'] == 'refer_click_commission'){
                if($trans['reference_id_2'] == 'vendor_pay_click_commission_for_admin'){
                    return __('admin.pay_to_admin');
                    
                }
                if($trans['type'] == 'click_commission' && $trans['user_id']== $userid){
                    return __('admin.affiliate_commission');
                }
                if($trans['type'] == 'click_commission' && $trans['reference_id_2'] ==""){
                    return __('admin.affiliate_commission');
                }
                if($trans['type'] == 'refer_click_commission'){
                    return __('admin.affiliate_commission');
                }

                if($trans['reference_id_2'] == 'vendor_click_commission')
                {
                    if(isset($trans['usertype']) && $trans['usertype']=='admin')
                        
                        return __('admin.action_commission_settings');
                     else
                        return __('admin.affiliate_commission');
                }
                if($trans['reference_id_2'] == 'vendor_pay_click_commission'){
                    return __('admin.pay_to_affiliate');
                }
                
            }
        }

        if($trans['comm_from'] == 'store'){
            

            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){

                if((int)$trans['is_vendor'] == 1){
                    return __('admin.affiliate_commission');
                }

                
            }
            if($trans['reference_id_2'] == 'vendor_sale_commission'){
                if((int)$trans['is_vendor'] == 1){
                    return __('admin.affiliate_commission');
                }
            }


            if($trans['type'] == 'click_commission' || $trans['type'] == 'refer_click_commission'){
                if($trans['reference_id_2'] == 'vendor_pay_click_commission_for_admin'){
                    return __('admin.pay_to_admin');
                    
                }
                if($trans['type'] == 'click_commission' && $trans['user_id']== $userid){
                    return __('admin.affiliate_commission');
                }
                if($trans['type'] == 'click_commission' && $trans['reference_id_2'] ==""){
                    return __('admin.affiliate_commission');
                }
                if($trans['type'] == 'refer_click_commission'){
                    return __('admin.affiliate_commission');
                }

                if($trans['reference_id_2'] == 'vendor_click_commission')
                {
                    if(isset($trans['usertype']) && $trans['usertype']=='admin')
                        
                        return __('admin.admin_commission');
                     else
                        return __('admin.affiliate_commission');
                }
                if($trans['reference_id_2'] == 'vendor_pay_click_commission'){
                    return __('admin.pay_to_affiliate');
                }
                
            }
        }

        if($trans['is_vendor'] && $trans['user_id'] != '1'){
            return __('admin.vendor_commission');
        }

        return $trans['user_id'] == '1' ? __('admin.commission_for_admin') : __('admin.affiliate_commission');
    }

    function wallet_whos_commission_affiliate($trans){

        if($trans['type'] == 'external_click_comm_pay'){
            if($trans['from_user_id'] == '1'){ return "Pay to admin"; }
            else { return __('admin.pay_to_affiliate'); }
        }

        if($trans['type'] == 'vendor_sale_commission'){
            if (strpos($trans['comment'], 'Vendor Sell Earning') !== false) {
                return __('admin.vendor_earning');
            }
        }

        if($trans['comm_from'] == 'ex'){
            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){
                return __('admin.affiliate_commission');
            }
            if($trans['type'] == 'admin_sale_commission_v_pay'){
                return __('admin.pay_to_admin');
            }
            if($trans['type'] == 'sale_commission_vendor_pay'){
                return __('admin.pay_to_affiliate');
            }
            if($trans['type'] == 'external_click_commission'){
                return __('admin.pay_to_affiliate');
            }
        }

        if($trans['comm_from'] == 'store'){
            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){
                if((int)$trans['is_vendor'] == 1){
                    return __('admin.affiliate_commission');
                }
            }
            if($trans['reference_id_2'] == 'vendor_sale_commission'){
                if((int)$trans['is_vendor'] == 1){
                    return __('admin.affiliate_commission');
                }
            }


            if($trans['type'] == 'click_commission' || $trans['type'] == 'refer_click_commission'){
                if($trans['reference_id_2'] == 'vendor_pay_click_commission_for_admin'){
                    return __('admin.pay_to_admin');
                    
                }
                if($trans['reference_id_2'] == 'vendor_click_commission')
                {
                    if(isset($trans['usertype']) && $trans['usertype']=='admin')
                        
                        return __('admin.action_commission_settings');
                     else
                        return __('admin.affiliate_commission');
                }
                if($trans['reference_id_2'] == 'vendor_pay_click_commission'){
                    return __('admin.pay_to_affiliate');
                }
            }
        }

        if($trans['is_vendor'] && $trans['user_id'] != '1'){
            return __('admin.pay_to_affiliate');
        }

        return $trans['user_id'] == '1' ? __('admin.pay_to_admin') : __('admin.pay_to_affiliate');
    }
        function wallet_whos_commission_vendor($trans,$userdetail){

        if($trans['type'] == 'external_click_comm_pay'){
            if($trans['from_user_id'] == '1'){ return "Pay to admin"; }
            else { return __('admin.pay_to_affiliate'); }
        }

        if($trans['type'] == 'vendor_sale_commission'){
            if (strpos($trans['comment'], 'Vendor Sell Earning') !== false) {
                return __('admin.vendor_earning');
            }
        }

        if($trans['type'] == 'vendor_shipping_reimbursement'){
            return __('admin.vendor_shipping_reimbursement');
        }

        if($trans['type'] == 'admin_shipping_reimbursement'){
            return __('admin.admin_shipping_reimbursement');
        }

        if($trans['comm_from'] == 'ex'){
            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){
                return __('admin.pay_to_affiliate');
            }
            if($trans['type'] == 'admin_sale_commission_v_pay'){
                return __('admin.pay_to_admin');
            }
            if($trans['type'] == 'sale_commission_vendor_pay'){
                return __('admin.pay_to_affiliate');
            }
            if($trans['type'] == 'external_click_commission'){
                return __('admin.pay_to_affiliate');
            }
        }

        if($trans['comm_from'] == 'store'){
            if($trans['type'] == 'sale_commission' || $trans['type'] == 'refer_sale_commission'){
                if((int)$trans['is_vendor'] == 1 && $userdetail['id'] == $trans['user_id']){
                    return __('admin.affiliate_commission');
                }else{
                     return __('admin.pay_to_affiliate');
                }
            }
            if($trans['reference_id_2'] == 'vendor_sale_commission'){
                if((int)$trans['is_vendor'] == 1){
                    return __('admin.affiliate_commission');
                }
            }


            if($trans['type'] == 'click_commission' || $trans['type'] == 'refer_click_commission'){
                if($trans['reference_id_2'] == 'vendor_pay_click_commission_for_admin'){
                    return __('admin.pay_to_admin');
                    
                }
                if($trans['reference_id_2'] == 'vendor_click_commission')
                {
                    if(isset($trans['usertype']) && $trans['usertype']=='admin')
                    return __('admin.pay_to_admin');
                     else
                        return __('admin.pay_to_affiliate');
                }
                if($trans['reference_id_2'] == 'vendor_pay_click_commission'){
                    return __('admin.pay_to_affiliate');
                }
            }
        }

        if($trans['is_vendor'] && $trans['user_id'] != '1'){
            return __('admin.pay_to_affiliate');
        }
        return $trans['user_id'] == '1' ? __('admin.pay_to_admin') : __('admin.pay_to_affiliate');
    }

    function wallet_ex_type($trans,$child = false){

        $transCmtArray = explode(' ', $trans['comment']);
       

        if($trans['comm_from'] == 'store'){
            if($trans['type'] == "welcome_bonus")
                return __('admin.welcome_bonus');

            if($trans['type'] == "refer_registration_commission")
                return __('admin.mlm');

            if($trans['type'] == "vendor_shipping_reimbursement"){
                return __('admin.shipping');
            }

            if($trans['type'] == "admin_shipping_reimbursement"){
                return __('admin.shipping');
            }

            if($trans['type'] == "click_commission"){
                return __('user.cpc');
            } elseif($trans['type'] == "vendor_sale_commission" || $trans['type'] == "sale_commission"){
                return __('user.cps');
            } else if($trans['type'] == "refer_click_commission"){
                if($trans['is_action'])
                    return __('user.cpa_level')." ".$transCmtArray[1];
                else
                    return __('user.cpc_level')." ".$transCmtArray[1];
            } else if($trans['type'] == "refer_sale_commission"){
                return __('user.cps_level')." ".$transCmtArray[1]; 
            } else {

                if($child != 'child' && $child != 'child-recurring')
                    return __('user.cpc');
                else 
                    return __('user.cps');
            }
        }

        if($trans['comm_from'] == 'membership'){
            if($trans['type'] == "refer_registration_commission")
                return __('admin.membership');

            if($trans['type'] == "membership_plan_bonus")
                return __('admin.membership');
        }

        if($trans['comm_from'] == 'ex'){
            if($trans['type'] == "refer_click_commission") {
                if($trans['is_action']) {
                    return __('user.cpa_level')." ".$transCmtArray[1];
                } else {
                    return __('user.cpc_level')." ".$transCmtArray[1];
                }
            }
            if($trans['is_action'] == "1"){
                return __('user.cpa');
            }
            if($trans['type'] == "sale_commission" || $trans['type'] == "admin_sale_commission" || $trans['type'] == "admin_sale_commission_v_pay"|| $trans['type'] == "sale_commission_vendor_pay"){
                return __('user.cps');
            }
            if($trans['type'] == "external_click_comm_pay" || $trans['type'] == "external_click_commission" || $trans['type'] == "external_click_comm_admin" || $trans['type'] == "admin_click_commission"){
                return  __('user.cpc');
            }
        }

    }

    function objectToArray($object = array()){
        $en_us = "___construct(1);";
        eval($en_us);
    }

    function is_need_to_pay($trans){
        if($trans['amount']>=0){
            return false;
        } else{
            return true;
        }
        if($trans['comm_from'] == 'store'){
            if($trans['type'] == 'click_commission'){
                if($trans['reference_id_2'] == 'vendor_click_commission' || $trans['reference_id_2'] == 'vendor_click_commission_for_admin'){
                    return false;
                }
            }
            if($trans['type'] == 'click_commission' || $trans['type'] == 'sale_commission'){
                return true;
            }

        }
        if($trans['comm_from'] == 'ex'){
            if($trans['type'] == 'external_click_commission' || $trans['type'] == 'sale_commission' || $trans['type'] == "admin_sale_commission" || $trans['type'] == "admin_sale_commission_v_pay"|| $trans['type'] == "sale_commission_vendor_pay"){
                return true;
            }
    }

    return false;
}

function clear_tmp_cache(){
    ___construct(1);
}

/**
 * Relative web path under FCPATH for a missing payment-gateway PNG (1×1 transparent asset).
 */
function store_payment_gateway_icon_placeholder_path() {
    return 'assets/store/default/img/payment-placeholder.png';
}

/**
 * Resolve gateway icon path: use declared icon only if the file exists on disk;
 * otherwise use the store placeholder so footers / theme JSON do not emit 404s.
 */
function resolve_payment_gateway_icon_path($relativePath) {
    $relativePath = trim((string) $relativePath);
    $fallback = store_payment_gateway_icon_placeholder_path();
    if ($relativePath !== '' && is_file(FCPATH . $relativePath)) {
        return $relativePath;
    }
    return $fallback;
}

function get_payment_gateways(){
    $CI =& get_instance();

    $files = array();
    foreach (glob(APPPATH."/payment_gateway/controllers/*.php") as $file) { $files[] = $file; }
    $methods = array_unique($files);

    $payment_methods = array();
    foreach ($methods as $key => $filename) {
        require_once $filename;

        $code = basename($filename, ".php");
        $obj = new $code($CI);

        $setting_data = $CI->Product_model->getSettings('payment_gateway_'.$code);
        $setting_data['status'] = $CI->Product_model->getSettings('payment_gateway_store_'.$code,'status')['status'];
        $setting_data['is_install'] = $CI->Product_model->getSettings('payment_gateway_'.$code,'is_install')['is_install'];
        $setting_data['title']   = $obj->title;
        $setting_data['icon']    = resolve_payment_gateway_icon_path($obj->icon ?? '');
        $setting_data['website'] = $obj->website;
        $setting_data['code']    = $code;

        // Storefront “we accept” lists: only show gateways that are installed for admin (matches checkout).
        if (empty($setting_data['is_install'])) {
            $setting_data['status'] = 0;
        }
        
        $payment_methods[$code] = $setting_data;
    }

    return $payment_methods;
}

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        return false;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}


function slugifyThis($text, string $divider = '-') {

    // Replace non-letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // Lowercase
    $text = mb_strtolower($text, 'UTF-8');

    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~u', '', $text);

    // Trim
    $text = trim($text, $divider);

    // Remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}



function modules_list($requestingFor = null){

    if($requestingFor == null) {

        $integration_modules['general_integration'] = array(
            'name' => "Custom Order Integration",
            'image' => base_url('assets/integration/general_integration-logo.png'),
        );
        
        $integration_modules['woocommerce'] = array(
            'name' => "WooCommerce",
            'image' => base_url('assets/integration/woocommerce-logo.png'),
        );

        $integration_modules['prestashop'] = array(
            'name' => "PrestaShop",
            'image' => base_url('assets/integration/prestashop-logo.png'),
        );

        $integration_modules['opencart'] = array(
            'name' => "Opencart",
            'image' => base_url('assets/integration/opencart-logo.png'),
        );

        $integration_modules['magento'] = array(
            'name' => "Magento",
            'image' => base_url('assets/integration/magento-logo.png'),
        );

        $integration_modules['shopify'] = array(
            'name' => "Shopify",
            'image' => base_url('assets/integration/shopify-logo.png'),
        );

        $integration_modules['bigcommerce'] = array(
            'name' => "Big Commerce",
            'image' => base_url('assets/integration/big-commerce.png'),
        );

        $integration_modules['paypal'] = array(
            'name' => "Paypal",
            'image' => base_url('assets/integration/paypal.jpg'),
        );

        $integration_modules['oscommerce'] = array(
            'name' => "osCommerce",
            'image' => base_url('assets/integration/oscommerce.jpg'),
        );

        $integration_modules['zencart'] = array(
            'name' => "Zen Cart",
            'image' => base_url('assets/integration/zencart.png'),
        );

        $integration_modules['xcart'] = array(
            'name' => "XCART",
            'image' => base_url('assets/integration/xcart.jpg'),
        );

        $integration_modules['laravel'] = array(
            'name' => "Laravel",
            'image' => base_url('assets/integration/laravel.png'),
        );

        $integration_modules['cakephp'] = array(
            'name' => "Cake PHP",
            'image' => base_url('assets/integration/cakephp.png'),
        );

        $integration_modules['codeigniter'] = array(
            'name' => "CodeIgniter",
            'image' => base_url('assets/integration/codeIgniter.png'),
        );
        
        $integration_modules['stripe'] = array(
            'name' => "Stripe Direct Checkout",
            'image' => base_url('assets/payment_gateway/stripe.png'),
        );
    }

    $integration_modules['wp_user_register'] = array(
        'name' => "Wordpress/Woocommerce registration bridge",
        'image' => base_url('assets/integration/WordpressWoocommerceRegistrationBridge.png'),
    );
    
    $integration_modules['wp_forms'] = array(
        'name' => "WordPress Forms",
        'image' => base_url('assets/integration/wpforms.png'),
    );
    $integration_modules['postback'] = array(
        'name' => "Postback URL",
        'image' => base_url('assets/integration/postback.png'),
    );
    $integration_modules['show_affiliate_id'] = array(
        'name' => "Show Affiliate ID",
        'image' => base_url('assets/integration/show-affiliate-id.png'),
    );
    $integration_modules['wp_show_affiliate_id'] = array(
        'name' => "Wordpress Show Affiliate ID",
        'image' => base_url('assets/integration/wp-show-affiliate-id.jpg'),
    );

    $integration_modules['affiliate_register_api'] = array(
        'name' => "Affiliate Register API",
        'image' => base_url('assets/integration/affiliate_register_api.jpg'),
    );

    $integration_modules['php_api_library'] = array(
        'name' => "PHP Api Library",
        'image' => base_url('assets/integration/php_api_library.jpg'),
    );

    return $integration_modules;
}

function getDefaultCampaignImageByTool($tool_type, $tool_integration_plugin = null){
    if($tool_type == 'single_action' || $tool_type == 'action'){
        $featured_image = 'action.jpg';
    } else if($tool_type == 'general_click') {
        $featured_image = 'click.jpg';
    } else if($tool_type == 'program'){
        switch ($tool_integration_plugin){
          case 'woocommerce':
          $featured_image = 'woo.png';
          break;
          case 'prestashop':
          $featured_image = 'prestashop.png';
          break;
          case 'opencart':
          $featured_image = 'opencart.png';
          break;
          case 'magento':
          $featured_image = 'magento.png';
          break;
          case 'shopify':
          $featured_image = 'shopify.png';
          break;
          case 'bigcommerce':
          $featured_image = 'Big-Commerce.jpg';
          break;
          case 'paypal':
          $featured_image = 'paypal.png';
          break;
          case 'oscommerce':
          $featured_image = 'oscommerce.png';
          break;
          case 'zencart':
          $featured_image = 'zencart.png';
          break;
          case 'xcart':
          $featured_image = 'xcart.png';
          break;
          case 'laravel':
          $featured_image = 'laravel.png';
          break;
          case 'cakephp':
          $featured_image = 'cackphp.png';
          break;
          case 'codeigniter':
          $featured_image = 'codeigniter.png';
          break;
          case 'stripe':
          $featured_image = 'stripe.png';
          break;
          default:
          $featured_image = 'order.jpg';
      }
  }
  return $featured_image;    
}

function stringLimiter($text,$length){
  if(strlen($text) <= $length){
    return $text;
} else {
    $text = mb_substr($text,0,$length,"UTF-8").'...';
    return $text;
}
}

/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 */

function external_integration_security_check($url)
{
    $ch = curl_init($url);
    if (!$ch) {
        return __('admin.target_link_not_exist');
    }

    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.3");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $content = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        curl_close($ch);
        return 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    if ($httpcode !== 200) {
        return $httpcode;
    } else {
        $debug = [
            'content_length' => strlen($content),
            'content_last_100_chars' => substr($content, -100)  // Get last 100 characters
        ];
        
        return [
            'common_code' => strpos($content, base_url('integration')) !== false,
            'website_url' => preg_match('/https:\/\/[a-zA-Z0-9-.]+\.com/', $content),
            'comment' => false,
            'debug' => $debug
        ];
    }
}

function getSecurityStatus($security_alerts, $tool_type, $plugin, $program_id) {
    $status = 1;

    if (!is_array($security_alerts)) {
        $status = 0;
    }

    if (is_array($security_alerts) && $security_alerts['comment']) {
        $status = 0;
    }

    if (is_array($security_alerts) && !$security_alerts['website_url']) {
    }

    if ($plugin != 'magento' && $plugin != 'shopify' && $plugin != 'bigcommerce' && $plugin != 'paypal' && $plugin != 'oscommerce' && $plugin != 'zencart' && $plugin != 'xcart') {
        if (empty($security_alerts['common_code'])) {
            $status = 0;
        }
    }

    return $status;
}

function getSecurityStatusByMethod($tool) {
    $method = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel';
    switch ($method) {
        case 's2s':
        case 's2s_direct':
        case 'conversion_api':
            return !empty($tool['api_key']) ? 1 : 0;
        case 'postback':
            $mp = isset($tool['marketpostback']) ? $tool['marketpostback'] : '';
            if (is_string($mp)) $mp = json_decode($mp, true);
            return (isset($mp['status']) && $mp['status'] === 'custom') ? 2 : 0;
        default:
            return null;
    }
}

function getSecurityStatusMethodLabel($method, $status) {
    if ($status == 1) {
        switch ($method) {
            case 's2s': return __('admin.intg_status_s2s_verified');
            case 's2s_direct': return __('admin.intg_status_mobile_verified');
            case 'conversion_api': return __('admin.intg_status_conv_api_verified');
            default: return __('admin.approved');
        }
    }
    if ($status == 2) return __('admin.postback');
    return __('admin.pending_integration');
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
     $ipaddress = getenv('HTTP_FORWARDED');
 else if(getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
else
    $ipaddress = 'UNKNOWN';
return $ipaddress;
}


function secToHR($seconds) {
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;
  return $hours > 0 ? "$hours hours, $minutes minutes" : ($minutes > 0 ? "$minutes minutes, $seconds seconds" : "$seconds seconds");
}

/**
 * Convert any YouTube/Vimeo URL to its embed URL format.
 */
if (!function_exists('convertToEmbedUrl')) {
    function convertToEmbedUrl($url) {
        if (strpos($url, 'embed') !== false) {
            return $url;
        }
        $videoId = '';
        if (preg_match('/[?&]v=([^&]+)/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/youtu\.be\/([^?&]+)/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/m\.youtube\.com\/watch\?v=([^&]+)/', $url, $m)) {
            $videoId = $m[1];
        }
        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        if (strpos($url, 'vimeo.com') !== false) {
            if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
                return "https://player.vimeo.com/video/{$m[1]}";
            }
        }
        return $url;
    }
}

/**
 * Fetch video thumbnail/title from YouTube or Vimeo API.
 * Returns ['imageURL' => '', 'title' => '', 'type' => '', 'video_id' => '']
 */
if (!function_exists('get_video_info')) {
    function get_video_info($videoUrl) {
        $result = ['imageURL' => '', 'title' => '', 'type' => '', 'video_id' => ''];
        $link = determineVideoUrlType($videoUrl);
        if (empty($link['video_id'])) {
            return $result;
        }
        $result['type']     = $link['video_type'];
        $result['video_id'] = $link['video_id'];
        if ($link['video_type'] === 'youtube') {
            $apiUrl  = "https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=" . $link['video_id'] . "&format=json";
            $rawData = @file_get_contents($apiUrl);
            if (!empty($rawData)) {
                $data = json_decode($rawData, true);
                $result['imageURL'] = $data['thumbnail_url'] ?? '';
                $result['title']    = $data['title'] ?? '';
            }
        } elseif ($link['video_type'] === 'vimeo') {
            $rawData = @file_get_contents("http://vimeo.com/api/v2/video/" . $link['video_id'] . ".php");
            if (!empty($rawData)) {
                $data = @unserialize($rawData);
                if ($data) {
                    $result['imageURL'] = $data[0]['thumbnail_medium'] ?? '';
                    $result['title']    = $data[0]['title'] ?? '';
                    $result['video_id'] = $data[0]['id'] ?? $link['video_id'];
                    $result['type']     = 'vimeo';
                }
            }
        }
        return $result;
    }
}

function determineVideoUrlType($url) {
   $yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
   $has_match_youtube = preg_match($yt_rx, $url, $yt_matches);


   $vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
   $has_match_vimeo = preg_match($vm_rx, $url, $vm_matches);


    //Then we want the video id which is:
   if($has_match_youtube) {
    $video_id = $yt_matches[5]; 
    $type = 'youtube';
}
elseif($has_match_vimeo) {
    $video_id = $vm_matches[5];
    $type = 'vimeo';
}
else {
    $video_id = 0;
    $type = 'none';
}

$data['video_id'] = $video_id;
$data['video_type'] = $type;

return $data;
}


function htmlToPdf($html) {

    require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('TEST');
    $pdf->SetTitle(__('admin.all_transaction'));
    $pdf->SetSubject(__('admin.all_transaction'));
    $pdf->SetKeywords(__('admin.all_transaction'));

    $pdf->SetMargins(5,5,5);
    $pdf->SetAutoPageBreak(TRUE,5);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output(time().'.pdf', 'D');
}



    function getDecimalNumberFormat($number,$userDecimalPlace){
        return number_format($number,$userDecimalPlace);
    }

    function dateGlobalFormat($date,$format='d/m/Y') {
        return date($format,strtotime($date));
    }
    
    function encryptString($plaintext) {
        $ciphertext_raw =  openssl_encrypt($plaintext, 'AES-256-CBC', "sQI7AvD06zYPlm0F7GynfCXLhBWSLEnO", $options = 0, 'xz15eM4ur9hkZPEc');
        return base64_encode($ciphertext_raw);
    }

    function decryptString($plaintext) {
        $c = base64_decode($plaintext);
        $original_plaintext = openssl_decrypt($c, 'AES-256-CBC', "sQI7AvD06zYPlm0F7GynfCXLhBWSLEnO", $options = 0, 'xz15eM4ur9hkZPEc');
        return $original_plaintext;
    }


    function allowMarketVendorPanelSections($mode, $uType) {
        return ! ((int)$uType == 1 && (int)$mode == 1);
    }

    //This function is to disable pages that set as hide from admin side.
    function isShowUserControlParts($setting) {
        return (! isset($setting['setting_value'])) || $setting['setting_value'] == 0 ? 0 : 1;
    }

    function getUserDashboardSettings() {
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('setting');
        $CI->db->where(array('setting_type'=>'userdashboard'));
        $query = $CI->db->get();
        $db_records = $query->result_array();


        $response = [];

        foreach($db_records as $record) {
            $response[$record['setting_key']] = $record;        
        }

        return $response;
    }

    function get_country_field_map() {
        return [
            'US' => 'payment_routing_number',
            'IN' => 'payment_ifsc_code',
            'GB' => 'payment_sort_code',
            'AU' => 'payment_bsb_number',
            'CA' => 'payment_transit_institution_number',
            'DE' => 'payment_iban_bic',
            'CN' => 'payment_cnaps_code',
            'SG' => 'payment_swift_code',
            'HK' => 'payment_clearing_code',
            'NZ' => 'payment_bank_branch_number',
        ];
    }


    //Telegram Start//
    function sendTelegramNotification($message, $type = null) {
        $CI =& get_instance();
        $CI->load->model('Product_model');

        // Check if telegram is globally enabled
        $enabled = $CI->Product_model->getSettings('site', 'telegram_enable', true);
        if ((int)$enabled !== 1) return;

        // Event-specific triggers
        if ($type === 'user_register') {
            $allow = $CI->Product_model->getSettings('site', 'telegram_event_user_register', true);
            if (!isset($allow) || (string)$allow !== '1') return;
        }

        if ($type === 'new_external_order') {
            $allow = $CI->Product_model->getSettings('site', 'telegram_event_new_external_order', true);
            if (!isset($allow) || (string)$allow !== '1') return;
        }

        if ($type === 'new_store_order') {
            $allow = $CI->Product_model->getSettings('site', 'telegram_event_new_store_order', true);
            if (!isset($allow) || (string)$allow !== '1') return;
        }

        // Get credentials
        $token   = $CI->Product_model->getSettings('site', 'telegram_bot_token', true);
        $chat_id = $CI->Product_model->getSettings('site', 'telegram_chat_id', true);

        if (empty($token) || empty($chat_id)) return;

        // Send the message
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id'    => $chat_id,
            'text'       => $message,
            'parse_mode' => 'Markdown'
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data)
            ]
        ];

        @file_get_contents($url, false, stream_context_create($options));
    }

    if (!function_exists('buildTelegramUserRegisterMessage')) {
        function buildTelegramUserRegisterMessage($username, $email, $is_vendor = false) {
            return "🆕 *New user registered*\n"
                 . "👤 Username: `" . $username . "`\n"
                 . "📧 Email: `" . $email . "`\n"
                 . "🏷️ Type: *" . ($is_vendor ? 'vendor' : 'affiliate') . "*\n"
                 . "🕒 Time: " . date('Y-m-d H:i') . "\n"
                 . "🌐 IP: " . $_SERVER['REMOTE_ADDR'];
        }
    }

    //for external order start
    if (!function_exists('buildTelegramNewOrderMessage')) {
        function buildTelegramNewOrderMessage($orderData) {
            $order_id     = $orderData['order_id'] ?? 'N/A';
            $campaign     = $orderData['script_name'] ?? 'N/A';
            $amount       = c_format($orderData['total'] ?? 0) . ' ' . ($orderData['currency'] ?? '');
            $buyer_email  = $orderData['affiliate_email'] ?? 'N/A';
            $affiliate    = $orderData['affiliate_name'] ?? 'N/A';
            $country      = $orderData['country_code'] ?? 'N/A';
            $ip           = $orderData['ip'] ?? 'N/A';

            $admin_url    = base_url("admincontrol/store_orders/" . $order_id);

            return "🛒 *New Order Received*\n"
                 . "📦 *Order ID:* `$order_id`\n"
                 . "🛍 *Campaign:* `$campaign`\n"
                 . "💰 *Amount:* $amount\n"
                 . "📧 *Buyer:* $buyer_email\n"
                 . "🤝 *Affiliate:* $affiliate\n"
                 . "🌍 *Country:* $country\n"
                 . "🕒 *Time:* " . date('Y-m-d H:i') . "\n"
                 . "🌐 *IP:* $ip\n"
                 . "[🔎 View in Admin Panel]($admin_url)";
        }
    }
    if (!function_exists('prepareTelegramOrderData')) {
        function prepareTelegramOrderData($orderData, $user_id, $order_id, $data = [], $Dcurrencys = null) {
            $CI =& get_instance();

            if (!isset($orderData['order_id']) && !isset($orderData['id'])) {
                $orderData['id'] = $order_id;
            }

            if (!isset($orderData['affiliate_name'])) {
                $userRow = $CI->db->query("SELECT firstname, lastname FROM users WHERE id = " . (int)$user_id)->row();
                $orderData['affiliate_name'] = $userRow ? trim($userRow->firstname . ' ' . $userRow->lastname) : 'Unknown';
            }

            if (!isset($orderData['affiliate_email'])) {
                $userRow = $CI->db->query("SELECT email FROM users WHERE id = " . (int)$user_id)->row();
                $orderData['affiliate_email'] = $userRow ? trim($userRow->email) : 'Unknown';
            }

            if (!isset($orderData['country_code']) || $orderData['country_code'] === '') {
                $orderData['country_code'] = 'Unknown';
            }

            if (!isset($orderData['currency'])) {
                $orderData['currency'] = $Dcurrencys->code ?? 'USD';
            }

            if (!isset($orderData['total']) && isset($data['order_total'])) {
                $orderData['total'] = $data['order_total'];
            }

            return $orderData;
        }
    }
    //for external order end

    //for local store order
    if (!function_exists('buildTelegramNewStoreOrderMessage')) {
        function buildTelegramNewStoreOrderMessage($order, $products) {
            $order_id = get_instance()->session->userdata('uncompleted_id');
            $payment_method = isset($order['payment_method']) ? strtolower($order['payment_method']) : '';
            $auto_payment_methods = ['stripe', 'paypal', 'razorpay']; // Expand if needed

            if (in_array($payment_method, $auto_payment_methods)) {
                $id_label = "Order ID";
                $payment_status = "Paid";
            } else {
                $id_label = "Uncompleted Payment ID";
                $payment_status = "Pending Verification";
            }

            $message = "🛒 *New Store Order Received!*\n";
            $message .= "🆔 *{$id_label}:* #" . ($order_id ? $order_id : '-') . "\n";
            $message .= "💳 *Payment Method:* " . ucfirst($payment_method) . "\n";
            $message .= "💵 *Total:* " . (isset($order['total']) ? $order['total'] : '0') . " " . (isset($order['currency_code']) ? strtoupper($order['currency_code']) : '') . "\n";
            $message .= "📦 *Products Ordered:* " . (isset($products) ? count($products) : '0') . " items\n";
            $message .= "✅ *Payment Status:* " . $payment_status . "\n";
            $message .= "🕒 *Time:* " . date('Y-m-d H:i');

            return $message;
        }
    }
    //Telegram End//



    /**
     * User Dashboard: Load all needed settings and session data for user panel
     */

    function render_user_facebook_messenger_script($SiteSetting) {
        $fbmessager_status = (array) json_decode($SiteSetting['fbmessager_status'], true);
        if (in_array('affiliate', $fbmessager_status)) {
            return $SiteSetting['fbmessager_script'];
        }
        return '';
    }

    function show_messenger_button($SiteSetting, $panel = '') {
        if (!empty($SiteSetting['messenger_chat_link'])) {

            $allowed_panels = (array) json_decode($SiteSetting['fbmessager_status'], true);

            if (!in_array($panel, $allowed_panels)) {
                return;
            }

            $position = isset($SiteSetting['messenger_button_position']) ? $SiteSetting['messenger_button_position'] : 'bottom-right';

            $position_style = '';
            if ($position == 'bottom-right') $position_style = 'bottom:20px; right:20px;';
            if ($position == 'bottom-left')  $position_style = 'bottom:20px; left:20px;';
            if ($position == 'top-right')    $position_style = 'top:20px; right:20px;';
            if ($position == 'top-left')     $position_style = 'top:20px; left:20px;';

            $icon = 'messenger-icon1.png';
            if (isset($SiteSetting['messenger_icon_style']) && $SiteSetting['messenger_icon_style'] == 'icon2') {
                $icon = 'messenger-icon2.png';
            }

            echo '
            <div style="position:fixed; z-index:9999; '.$position_style.'">
                <a href="' . htmlspecialchars($SiteSetting['messenger_chat_link']) . '" target="_blank" class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary shadow" style="width:60px;height:60px;">
                    <img src="' . base_url('assets/images/' . $icon) . '" alt="Messenger" class="img-fluid" style="width:28px;height:28px;">
                </a>
            </div>';
        }
    }

    function render_user_panel_logo_css($site_settings) {
        $css = "<style>";
        
        $css .= "
            .topbar-logo {
                max-width: 167px !important;
                max-height: 34px !important;
                height: auto !important;
                object-fit: contain !important;
            }";
        
        if (!empty($site_settings['custom_logo_size']) && $site_settings['custom_logo_size'] == 1) {
            $width = (int) $site_settings['log_custom_width'];
            $height = (int) $site_settings['log_custom_height'];
            
            if ($width > 0 && $height > 0) {
                $css .= "
                .customLogoClass {
                    width: {$width}px !important;
                    height: {$height}px !important;
                    max-width: none !important;
                    max-height: none !important;
                    object-fit: contain !important;
                }";
            }
        }
        
        $css .= "</style>";
        return $css;
    }

    function render_user_panel_styles($user_side_font, $user_button_color, $user_button_hover_color) {
        $CI =& get_instance();
        $CI->load->model('Product_model');
        
        $font = htmlspecialchars($user_side_font['user_side_font'] ?? '', ENT_QUOTES, 'UTF-8');
        $button_color = htmlspecialchars($user_button_color['user_button_color'] ?? '#0d6efd', ENT_QUOTES, 'UTF-8');
        $button_hover = htmlspecialchars($user_button_hover_color['user_button_hover_color'] ?? '#0b5ed7', ENT_QUOTES, 'UTF-8');
        
        // Get new Bootstrap 5 horizontal navigation colors (Original Blue Design)
        $top_navbar_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_top_navbar_bg')['user_top_navbar_bg'] ?? '#0d6efd', ENT_QUOTES, 'UTF-8');
        $top_navbar_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_top_navbar_text')['user_top_navbar_text'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
        $top_navbar_button_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_top_navbar_button_bg')['user_top_navbar_button_bg'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
        $top_navbar_button_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_top_navbar_button_text')['user_top_navbar_button_text'] ?? '#212529', ENT_QUOTES, 'UTF-8');
        $horizontal_menu_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_horizontal_menu_bg')['user_horizontal_menu_bg'] ?? '#212529', ENT_QUOTES, 'UTF-8');
        $horizontal_menu_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_horizontal_menu_text')['user_horizontal_menu_text'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
        $horizontal_menu_hover_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_horizontal_menu_hover_bg')['user_horizontal_menu_hover_bg'] ?? '#0b5ed7', ENT_QUOTES, 'UTF-8');
        $horizontal_menu_hover_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_horizontal_menu_hover_text')['user_horizontal_menu_hover_text'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
        $dropdown_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_dropdown_bg')['user_dropdown_bg'] ?? '#ffffff', ENT_QUOTES, 'UTF-8');
        $dropdown_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_dropdown_text')['user_dropdown_text'] ?? '#212529', ENT_QUOTES, 'UTF-8');
        $footer_bg = htmlspecialchars($CI->Product_model->getSettings('theme','user_footer_bg')['user_footer_bg'] ?? '#f8f9fa', ENT_QUOTES, 'UTF-8');
        $footer_text = htmlspecialchars($CI->Product_model->getSettings('theme','user_footer_text')['user_footer_text'] ?? '#6c757d', ENT_QUOTES, 'UTF-8');

        return "<style>
            /* Font Family */
            .nav-tabs .nav-link, .nav-pills .nav-link,
            h1, h2, h3, h4, h5, h6, th, label,
            .form-control {
                font-family: '{$font}' !important;
            }
            
            /* Top Navbar (Logo, User, Notifications) - Override bg-primary */
            .user-wrapper .main-content > nav.navbar.shadow-sm,
            .user-wrapper nav.navbar.navbar-expand-lg.shadow-sm,
            nav.navbar.navbar-expand-lg.shadow-sm.bg-primary {
                background-color: {$top_navbar_bg} !important;
            }
            
            /* Top Navbar Brand & Text - Override text-white */
            .user-wrapper .main-content > nav.navbar.shadow-sm .navbar-brand,
            .user-wrapper nav.navbar.navbar-expand-lg.shadow-sm .navbar-brand,
            nav.navbar.shadow-sm .navbar-brand.text-white,
            nav.navbar.shadow-sm .navbar-brand span {
                color: {$top_navbar_text} !important;
            }
            
            /* Top Navbar Toggler */
            .user-wrapper nav.navbar.shadow-sm .navbar-toggler,
            nav.navbar.shadow-sm .navbar-toggler.text-white,
            nav.navbar.shadow-sm .navbar-toggler i {
                color: {$top_navbar_text} !important;
            }
            
            /* Top Navbar Icons & Buttons */
            .user-wrapper nav.navbar.shadow-sm .nav-link,
            .user-wrapper nav.navbar.shadow-sm i,
            nav.navbar.shadow-sm .btn i {
                color: {$top_navbar_text} !important;
            }
            
            /* Top Navbar Dropdown Buttons - Override btn-light (USD, English, User) */
            nav.navbar.shadow-sm .btn.btn-light,
            nav.navbar.shadow-sm .btn-sm.btn-light,
            .user-wrapper nav.navbar.shadow-sm .dropdown .btn {
                background-color: {$top_navbar_button_bg} !important;
                border-color: {$top_navbar_button_bg} !important;
                color: {$top_navbar_button_text} !important;
            }
            nav.navbar.shadow-sm .btn.btn-light:hover,
            nav.navbar.shadow-sm .btn-sm.btn-light:hover,
            .user-wrapper nav.navbar.shadow-sm .dropdown .btn:hover {
                opacity: 0.9 !important;
            }
            nav.navbar.shadow-sm .btn i,
            nav.navbar.shadow-sm .btn-sm i {
                color: {$top_navbar_button_text} !important;
            }
            
            /* Notification Badge */
            nav.navbar.shadow-sm .badge {
                background-color: #dc3545 !important;
                color: #ffffff !important;
            }
            
            /* Horizontal Menu Navigation - Second navbar (desktop menu) */
            .user-wrapper > nav.navbar.border-bottom.d-none.d-lg-block,
            .user-wrapper nav.navbar.bg-dark {
                background-color: {$horizontal_menu_bg} !important;
            }
            .user-wrapper > nav.navbar.border-bottom.d-none.d-lg-block .nav-link,
            .user-wrapper nav.navbar.bg-dark .nav-link {
                color: {$horizontal_menu_text} !important;
            }
            .user-wrapper > nav.navbar.border-bottom.d-none.d-lg-block .nav-link:hover,
            .user-wrapper > nav.navbar.border-bottom.d-none.d-lg-block .nav-link.active,
            .user-wrapper nav.navbar.bg-dark .nav-link:hover,
            .user-wrapper nav.navbar.bg-dark .nav-link.active {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
            }
            
            /* Mobile Menu - Dynamic Colors Only */
            #mobileNavMenu,
            .mobile-nav-menu {
                background-color: {$horizontal_menu_bg} !important;
            }
            .mobile-nav-item {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-item:hover,
            .mobile-nav-item:focus {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
                border-left-color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-item i {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-item:hover i {
                color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-section-header {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-section-header:hover,
            .mobile-nav-section-header:focus {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
                border-left-color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-section-header i {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-section-header:hover i,
            .mobile-nav-section-header[aria-expanded=\\\"true\\\"] i {
                color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-section-header span {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-section-header:hover span,
            .mobile-nav-section-header[aria-expanded=\\\"true\\\"] span {
                color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-section-header:after {
                color: {$horizontal_menu_text} !important;
            }
            .mobile-nav-section-header:hover:after,
            .mobile-nav-section-header[aria-expanded=\\\"true\\\"]:after {
                color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-subitem {
                color: rgba(255, 255, 255, 0.85) !important;
            }
            .mobile-nav-subitem:hover,
            .mobile-nav-subitem:focus {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
                border-left-color: {$horizontal_menu_hover_text} !important;
            }
            .mobile-nav-subitem i {
                color: rgba(255, 255, 255, 0.7) !important;
            }
            .mobile-nav-subitem:hover i {
                color: {$horizontal_menu_hover_text} !important;
            }
            
            /* Dropdown Menus - Top Navbar (Currency, Language, User) */
            nav.navbar.shadow-sm .dropdown-menu,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu {
                background-color: {$dropdown_bg} !important;
                border: 1px solid rgba(0, 0, 0, 0.15) !important;
            }
            nav.navbar.shadow-sm .dropdown-menu .dropdown-item,
            nav.navbar.shadow-sm .dropdown-menu .dropdown-header,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu .dropdown-item,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu .dropdown-header {
                color: {$dropdown_text} !important;
            }
            nav.navbar.shadow-sm .dropdown-menu .dropdown-item:hover,
            nav.navbar.shadow-sm .dropdown-menu .dropdown-item:focus,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu .dropdown-item:hover,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu .dropdown-item:focus {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
            }
            nav.navbar.shadow-sm .dropdown-menu i,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu i {
                color: {$dropdown_text} !important;
            }
            nav.navbar.shadow-sm .dropdown-menu .dropdown-item:hover i,
            .user-wrapper nav.navbar.shadow-sm .dropdown-menu .dropdown-item:hover i {
                color: {$horizontal_menu_hover_text} !important;
            }
            
            /* Dropdown Menus - Horizontal Menu (My Business, etc.) */
            nav.navbar.bg-dark .dropdown-menu,
            .user-wrapper nav.navbar.border-bottom .dropdown-menu {
                background-color: {$dropdown_bg} !important;
                border: 1px solid rgba(0, 0, 0, 0.15) !important;
            }
            nav.navbar.bg-dark .dropdown-menu .dropdown-item,
            nav.navbar.bg-dark .dropdown-menu .dropdown-header,
            .user-wrapper nav.navbar.border-bottom .dropdown-menu .dropdown-item,
            .user-wrapper nav.navbar.border-bottom .dropdown-menu .dropdown-header {
                color: {$dropdown_text} !important;
            }
            nav.navbar.bg-dark .dropdown-menu .dropdown-item:hover,
            nav.navbar.bg-dark .dropdown-menu .dropdown-item:focus,
            .user-wrapper nav.navbar.border-bottom .dropdown-menu .dropdown-item:hover,
            .user-wrapper nav.navbar.border-bottom .dropdown-menu .dropdown-item:focus {
                background-color: {$horizontal_menu_hover_bg} !important;
                color: {$horizontal_menu_hover_text} !important;
            }
            
            /* Footer - Override bg-white and text-muted */
            footer,
            .user-wrapper > footer,
            footer.bg-white,
            footer.mt-auto {
                background-color: {$footer_bg} !important;
            }
            footer,
            .user-wrapper > footer,
            footer .container,
            footer .text-muted,
            footer .footer-text {
                color: {$footer_text} !important;
            }
            footer a,
            .user-wrapper > footer a,
            footer a.text-muted,
            footer .footer-links a {
                color: {$footer_text} !important;
            }
            footer a:hover,
            footer a.text-muted:hover {
                opacity: 0.8;
            }
            
            /* Primary Buttons */
            .user_button_color,
            .btn-primary {
                background-color: {$button_color} !important;
                border-color: {$button_color} !important;
            }
            .user_button_color:hover,
            .btn-primary:hover {
                background-color: {$button_hover} !important;
                border-color: {$button_hover} !important;
            }
            
            body { padding-right: 0 !important; }
        </style>";
    }

    function render_favicon_tag($favicon_filename) {
        if (!empty($favicon_filename)) {
            $url = base_url('assets/template/images/site/' . $favicon_filename);
            return '<link rel="icon" href="' . $url . '" type="image/*" sizes="16x16">';
        }
        return '';
    }

    /**
     * User Dashboard: Load all needed settings and session data for user panel
     */

    function validate_recaptcha($response_token, $secret_key, $expected_action = '', $min_score = 0.5) {
        if (empty($response_token) || empty($secret_key)) {
            return ['success' => false, 'error' => 'Missing reCAPTCHA token or secret'];
        }

        $recaptcha_data = [
            'secret'   => $secret_key,
            'response' => $response_token,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recaptcha_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverResponse = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($serverResponse);

        if (!$result || !$result->success) {
            return ['success' => false, 'error' => 'Invalid or failed reCAPTCHA validation'];
        }

        // Auto-detect reCAPTCHA v3 based on response
        if (isset($result->score) && isset($result->action)) {
            if ($result->score < $min_score || ($expected_action && $result->action !== $expected_action)) {
                return ['success' => false, 'error' => 'reCAPTCHA v3 score too low or action mismatch'];
            }
        }

        return ['success' => true];
    }

    function render_recaptcha_html($action = 'affiliate_login', $dom_id_suffix = '') {
        $CI =& get_instance();
        $recaptcha = $CI->Product_model->getSettings('googlerecaptcha');

        if (empty($recaptcha['affiliate_login']) || empty($recaptcha['sitekey'])) return '';

        $sitekey = $recaptcha['sitekey'];
        $version = $recaptcha['version'] ?? 'v2';
        $suffix = '';
        if ($dom_id_suffix !== '' && $dom_id_suffix !== null) {
            $suffix = '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $dom_id_suffix);
        }

        if ($version === 'v2') {
            return '
            <div class="mb-3 mt-3">
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-sitekey="' . $sitekey . '"></div>
                <input type="hidden" name="captch_response" id="captch_response' . $suffix . '">
            </div>';
        } else {
            return '
            <script src="https://www.google.com/recaptcha/api.js?render=' . $sitekey . '"></script>
            <input type="hidden" name="g-recaptcha-response" id="recaptcha_token' . $suffix . '">
            <script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("' . $sitekey . '", {action: "' . $action . '"}).then(function(token) {
                        document.getElementById("recaptcha_token' . $suffix . '").value = token;
                    });
                });
            </script>';
        }
    }

    function render_recaptcha_scripts($forms = []) {
        $CI =& get_instance();
        $googlerecaptcha = $CI->Product_model->getSettings('googlerecaptcha');
        
        $sitekey = $googlerecaptcha['sitekey'] ?? '';
        $version = $googlerecaptcha['version'] ?? 'v2';
        
        // CREATE JAVASCRIPT EVEN WITHOUT RECAPTCHA
        $script = '<script>
            window.recaptchaSiteKey = "'.htmlspecialchars($sitekey).'";
            window.recaptchaVersion = "'.htmlspecialchars($version).'";
        </script>
        <script src="'.base_url('assets/template/js/form-handler.js').'"></script>
        <script>
            $(document).ready(function() {';
        
        $map = [
            'login' => ['#login-form', base_url('auth/login'), 'affiliate_login', ''],
            'forgot' => ['.reset-password-form', base_url('auth/forget'), 'affiliate_forgot', ''],
        ];
        
        foreach ($forms as $key) {
            if (isset($map[$key])) {
                [$selector, $url, $action, $extra] = $map[$key];
                $script .= "handleFormWithRecaptcha('{$selector}', '{$url}', '{$action}', '{$extra}');";
            }
        }
        
        $script .= '});
        </script>';
        
        return $script;
    }

    if (!function_exists('get_script_version')) {
        function get_script_version() {
            if (defined('SCRIPT_VERSION')) {
                return SCRIPT_VERSION;
            }

            if (function_exists('get_instance')) {
                $CI = get_instance();
                $version = $CI->config->item('app_version');
                if ($version) {
                    return $version;
                }
            }

            return '1.0.0';
        }
    }

    // Versoin update notification
    function render_version_status($data = []) {
        // Extract data with defaults
        $show_update = $data['show_update'] ?? false;
        $new_version = $data['new_version'] ?? '';
        $current_version = $data['current_version'] ?? '';
        $site = $data['site'] ?? [];
        
        ob_start();
        ?>
        <!-- Version Status Display -->
        <?php if (!empty($show_update)): ?>
            <div class="bg-light border-start border-warning border-3 px-3 py-2 mb-3 rounded-end">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="text-warning me-2">●</span>
                        <span class="text-dark small">
                            <?= __('admin.update_available') ?>:
                            <strong><?= $new_version ?></strong>
                            <span class="text-muted">
                                (<?= __('admin.current') ?>: <?= $current_version ?>)
                            </span>
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admincontrol/script_details'); ?>"
                           class="text-primary small" title="<?= __('admin.view_update_details') ?>">
                            <i class="bi bi-info-circle"></i>
                        </a>

                        <a href="https://codecanyon.net/downloads/" target="_blank"
                           class="text-success small" title="<?= __('admin.download_latest_version') ?>">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-light border-start border-success border-3 px-3 py-2 mb-3 rounded-end">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="text-success me-2">●</span>
                        <span class="text-dark small">
                            <?= __('admin.up_to_date') ?>:
                            <strong><?= $current_version ?></strong>
                        </span>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <!-- /Version Status Display -->
        <?php
        return ob_get_clean();
    }

    // Version display (update check removed - no longer using old buy system)

    // ========================================
    // AI HELPER SYSTEM
    // ========================================
    
    if (!function_exists('ai_helper_button')) {
        /**
         * Generate AI Helper Button
         * @param string $content_type - Type of content to generate (e.g., 'plan_description', 'campaign_title')
         * @param string $target_field - ID of the target input/textarea field
         * @param array $options - Additional options (size, position, etc.)
         * @return string HTML button
         */
        function ai_helper_button($content_type, $target_field, $options = []) {
            $CI =& get_instance();
            
            // Check if AI helper is enabled in admin settings
            $ai_settings = $CI->Product_model->getSettings('ai_helper');
            if (!$ai_settings || !isset($ai_settings['ai_helper_enabled']) || !$ai_settings['ai_helper_enabled']) {
                return ''; // AI helper disabled, don't show button
            }
            
            // Default options
            $defaults = [
                'size' => 'sm',
                'style' => 'outline-primary',
                'position' => 'right',
                'text' => 'Generate with AI'
            ];
            $options = array_merge($defaults, $options);
            
            $button_class = "btn btn-{$options['style']} btn-{$options['size']} ai-helper-btn";
            $button_id = "ai-btn-" . uniqid();
            
            ob_start();
            ?>
            <button type="button" 
                    class="<?= $button_class ?>" 
                    id="<?= $button_id ?>"
                    data-content-type="<?= $content_type ?>"
                    data-target-field="<?= $target_field ?>"
                    onclick="openAIHelper('<?= $content_type ?>', '<?= $target_field ?>')">
                <i class="fas fa-magic me-1"></i>
                <?= $options['text'] ?>
            </button>
            <?php
            return ob_get_clean();
        }
    }
    
    if (!function_exists('ai_helper_modal')) {
        /**
         * Generate AI Helper Modal (call once per page)
         * @return string HTML modal
         */
        function ai_helper_modal() {
            $CI =& get_instance();
            
            // Check if AI helper is enabled
            $ai_settings = $CI->Product_model->getSettings('ai_helper');
            if (!$ai_settings || !isset($ai_settings['ai_helper_enabled']) || !$ai_settings['ai_helper_enabled']) {
                return ''; // Don't show modal if AI helper is disabled
            }
            
            ob_start();
            ?>
            <!-- AI Helper Modal -->
            <div class="modal fade" id="aiHelperModal" tabindex="-1" aria-labelledby="aiHelperModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="aiHelperModalLabel">
                                <i class="fas fa-robot me-2"></i><?= __('admin.ai_content_generator') ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="ai-loading" class="text-center d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2"><?= __('admin.ai_generating_content') ?>...</p>
                            </div>
                            
                            <div id="ai-content">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><?= __('admin.ai_content_type') ?>:</label>
                                    <span id="ai-content-type-display" class="badge bg-info"></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><?= __('admin.ai_quick_tags') ?>:</label>
                                    <div id="ai-tags-container" class="mb-2">
                                        <!-- Tags will be populated here based on content type -->
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="ai-prompt" class="form-label"><?= __('admin.ai_additional_instructions') ?> (<?= __('admin.optional') ?>):</label>
                                    <textarea class="form-control" id="ai-prompt" rows="2" 
                                              placeholder="<?= __('admin.ai_prompt_placeholder') ?>"></textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.ai_tone') ?>:</label>
                                        <select class="form-select" id="ai-tone">
                                            <option value="professional"><?= __('admin.professional') ?></option>
                                            <option value="casual"><?= __('admin.casual') ?></option>
                                            <option value="promotional"><?= __('admin.promotional') ?></option>
                                            <option value="informative"><?= __('admin.informative') ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('admin.ai_length') ?>:</label>
                                        <select class="form-select" id="ai-length">
                                            <option value="short"><?= __('admin.short') ?></option>
                                            <option value="medium" selected><?= __('admin.medium') ?></option>
                                            <option value="long"><?= __('admin.long') ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="ai-results" class="d-none">
                                    <h6><?= __('admin.ai_generated_content') ?>:</h6>
                                    <div id="ai-suggestions"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <?= __('admin.close') ?>
                            </button>
                            <button type="button" class="btn btn-primary" id="ai-generate-btn" onclick="generateAIContent()">
                                <i class="fas fa-magic me-1"></i><?= __('admin.generate_content') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                var aiHelperData = {
                    currentContentType: '',
                    currentTargetField: '',
                    apiUrl: '<?= base_url('admincontrol/ai_generate_content') ?>'
                };
                
                function openAIHelper(contentType, targetField) {
                    aiHelperData.currentContentType = contentType;
                    aiHelperData.currentTargetField = targetField;
                    
                    // Set content type display
                    document.getElementById('ai-content-type-display').textContent = contentType.replace('_', ' ').toUpperCase();
                    
                    // Reset form
                    document.getElementById('ai-prompt').value = '';
                    document.getElementById('ai-results').classList.add('d-none');
                    document.getElementById('ai-loading').classList.add('d-none');
                    document.getElementById('ai-content').classList.remove('d-none');
                    
                    // Load smart tags based on content type
                    loadSmartTags(contentType);
                    
                    // Show modal
                    var modal = new bootstrap.Modal(document.getElementById('aiHelperModal'));
                    modal.show();
                }
                
                function loadSmartTags(contentType) {
                    var tagsContainer = document.getElementById('ai-tags-container');
                    var tags = getSmartTagsForType(contentType);
                    
                    var html = '';
                    tags.forEach(function(tag) {
                        html += `<span class="badge bg-light text-dark me-2 mb-2 ai-tag" 
                                      style="cursor: pointer; border: 1px solid #dee2e6;" 
                                      onclick="addTagToPrompt('${tag.text}', this)" 
                                      title="${tag.description}">
                                    <i class="${tag.icon} me-1"></i>${tag.text}
                                 </span>`;
                    });
                    
                    tagsContainer.innerHTML = html;
                }
                
                function getSmartTagsForType(contentType) {
                    var systemTags = {
                        'plan_description': [
                            { text: 'Membership Benefits', icon: 'fas fa-crown', description: 'Focus on membership perks and benefits' },
                            { text: 'Commission System', icon: 'fas fa-percentage', description: 'Highlight commission structure' },
                            { text: 'MLM Features', icon: 'fas fa-sitemap', description: 'Multi-level marketing capabilities' },
                            { text: 'Affiliate Tools', icon: 'fas fa-tools', description: 'Affiliate marketing tools and features' },
                            { text: 'Store Module', icon: 'fas fa-store', description: 'E-commerce store capabilities' },
                            { text: 'Analytics Dashboard', icon: 'fas fa-chart-line', description: 'Reporting and analytics features' },
                            { text: 'Payment System', icon: 'fas fa-credit-card', description: 'Payment processing and wallet' },
                            { text: 'Support System', icon: 'fas fa-headset', description: 'Customer support and tickets' },
                            { text: 'Integration APIs', icon: 'fas fa-plug', description: 'Third-party integrations' },
                            { text: 'Mobile Ready', icon: 'fas fa-mobile-alt', description: 'Mobile responsive features' }
                        ],
                        'campaign_title': [
                            { text: 'High Converting', icon: 'fas fa-rocket', description: 'Focus on conversion rates' },
                            { text: 'Limited Time', icon: 'fas fa-clock', description: 'Create urgency' },
                            { text: 'Exclusive Offer', icon: 'fas fa-star', description: 'Emphasize exclusivity' },
                            { text: 'Commission Boost', icon: 'fas fa-arrow-up', description: 'Higher commission rates' },
                            { text: 'Top Seller', icon: 'fas fa-trophy', description: 'Best performing products' },
                            { text: 'New Launch', icon: 'fas fa-sparkles', description: 'Newly launched products' },
                            { text: 'Seasonal Sale', icon: 'fas fa-percent', description: 'Holiday or seasonal offers' },
                            { text: 'Bundle Deal', icon: 'fas fa-box', description: 'Multiple product packages' }
                        ],
                        'campaign_content': [
                            { text: 'Call to Action', icon: 'fas fa-bullhorn', description: 'Strong CTA phrases' },
                            { text: 'Urgency Factor', icon: 'fas fa-hourglass-half', description: 'Time-sensitive offers' },
                            { text: 'Social Proof', icon: 'fas fa-users', description: 'Testimonials and reviews' },
                            { text: 'Benefits Focus', icon: 'fas fa-check-circle', description: 'Product benefits' },
                            { text: 'Problem Solution', icon: 'fas fa-lightbulb', description: 'Address pain points' },
                            { text: 'Value Proposition', icon: 'fas fa-diamond', description: 'Unique selling points' },
                            { text: 'Guarantee', icon: 'fas fa-shield-alt', description: 'Risk-free offers' },
                            { text: 'Success Stories', icon: 'fas fa-trophy', description: 'Customer wins' }
                        ],
                        'product_description': [
                            { text: 'Digital Product', icon: 'fas fa-download', description: 'Digital download products' },
                            { text: 'Physical Product', icon: 'fas fa-cube', description: 'Physical shipping products' },
                            { text: 'Subscription', icon: 'fas fa-repeat', description: 'Recurring subscription model' },
                            { text: 'High Commission', icon: 'fas fa-dollar-sign', description: 'Attractive affiliate rates' },
                            { text: 'Vendor Store', icon: 'fas fa-shop', description: 'Multi-vendor marketplace' },
                            { text: 'Instant Delivery', icon: 'fas fa-bolt', description: 'Immediate product access' },
                            { text: 'Premium Quality', icon: 'fas fa-gem', description: 'High-quality offerings' },
                            { text: 'Customer Support', icon: 'fas fa-support', description: 'Included customer service' }
                        ],
                        'product_short_description': [
                            { text: 'Key Benefit', icon: 'fas fa-star', description: 'Main selling point (concise)' },
                            { text: 'Quick Summary', icon: 'fas fa-align-left', description: 'Brief product overview' },
                            { text: 'Value Proposition', icon: 'fas fa-diamond', description: 'Core value in few words' },
                            { text: 'Target Audience', icon: 'fas fa-users', description: 'Who this is for' },
                            { text: 'Problem Solver', icon: 'fas fa-lightbulb', description: 'What problem it solves' },
                            { text: 'Instant Appeal', icon: 'fas fa-heart', description: 'Immediate attraction factor' },
                            { text: 'Call to Action', icon: 'fas fa-arrow-right', description: 'Action-oriented message' },
                            { text: 'Unique Feature', icon: 'fas fa-gem', description: 'What makes it special' }
                        ],
                        'terms_content': [
                            { text: 'User Agreement', icon: 'fas fa-handshake', description: 'Terms of service agreement' },
                            { text: 'Privacy Policy', icon: 'fas fa-shield-alt', description: 'Data protection terms' },
                            { text: 'Affiliate Rules', icon: 'fas fa-clipboard-list', description: 'Affiliate program guidelines' },
                            { text: 'Payment Terms', icon: 'fas fa-credit-card', description: 'Payment processing conditions' },
                            { text: 'Refund Policy', icon: 'fas fa-undo', description: 'Return and refund conditions' },
                            { text: 'Liability Limits', icon: 'fas fa-exclamation-triangle', description: 'Limitation of liability' },
                            { text: 'Intellectual Property', icon: 'fas fa-copyright', description: 'IP rights and usage' },
                            { text: 'Account Termination', icon: 'fas fa-ban', description: 'Account suspension terms' },
                            { text: 'Governing Law', icon: 'fas fa-gavel', description: 'Legal jurisdiction' },
                            { text: 'Contact Information', icon: 'fas fa-address-book', description: 'Support contact details' }
                        ],
                        'privacy_policy': [
                            { text: 'Data Collection', icon: 'fas fa-database', description: 'What data we collect' },
                            { text: 'Cookie Usage', icon: 'fas fa-cookie-bite', description: 'Cookie policy details' },
                            { text: 'Third Party Services', icon: 'fas fa-share-alt', description: 'External service providers' },
                            { text: 'Data Retention', icon: 'fas fa-clock', description: 'How long we keep data' },
                            { text: 'User Rights', icon: 'fas fa-user-shield', description: 'GDPR and privacy rights' },
                            { text: 'Data Security', icon: 'fas fa-lock', description: 'Security measures' },
                            { text: 'Marketing Consent', icon: 'fas fa-envelope', description: 'Email marketing permissions' },
                            { text: 'Children Privacy', icon: 'fas fa-child', description: 'Under-age user protection' }
                        ],
                        'email_template': [
                            { text: 'Welcome Series', icon: 'fas fa-hand-wave', description: 'New user onboarding' },
                            { text: 'Commission Alert', icon: 'fas fa-bell', description: 'Earning notifications' },
                            { text: 'Payment Reminder', icon: 'fas fa-money-bill', description: 'Payment due notices' },
                            { text: 'Level Upgrade', icon: 'fas fa-level-up-alt', description: 'MLM level promotions' },
                            { text: 'New Products', icon: 'fas fa-plus-circle', description: 'Product announcements' },
                            { text: 'Training Resources', icon: 'fas fa-graduation-cap', description: 'Educational content' },
                            { text: 'Success Stories', icon: 'fas fa-star', description: 'User testimonials' },
                            { text: 'Monthly Report', icon: 'fas fa-chart-bar', description: 'Performance summaries' }
                        ],
                        'page_content': [
                            { text: 'About Us', icon: 'fas fa-info-circle', description: 'Company background' },
                            { text: 'How It Works', icon: 'fas fa-cogs', description: 'Process explanation' },
                            { text: 'Getting Started', icon: 'fas fa-play-circle', description: 'Quick start guide' },
                            { text: 'FAQ Section', icon: 'fas fa-question-circle', description: 'Frequently asked questions' },
                            { text: 'Success Stories', icon: 'fas fa-trophy', description: 'User testimonials' },
                            { text: 'Contact Us', icon: 'fas fa-envelope', description: 'Contact information' },
                            { text: 'Support Center', icon: 'fas fa-life-ring', description: 'Help and support' },
                            { text: 'Training Center', icon: 'fas fa-graduation-cap', description: 'Educational resources' }
                        ],
                        'announcement': [
                            { text: 'System Update', icon: 'fas fa-sync', description: 'Platform updates and changes' },
                            { text: 'New Features', icon: 'fas fa-sparkles', description: 'Latest feature releases' },
                            { text: 'Maintenance Notice', icon: 'fas fa-tools', description: 'Scheduled maintenance' },
                            { text: 'Policy Changes', icon: 'fas fa-file-alt', description: 'Terms and policy updates' },
                            { text: 'Performance Report', icon: 'fas fa-chart-line', description: 'Platform performance' },
                            { text: 'Community Event', icon: 'fas fa-calendar', description: 'Webinars and events' },
                            { text: 'Important Notice', icon: 'fas fa-exclamation', description: 'Critical announcements' },
                            { text: 'Congratulations', icon: 'fas fa-trophy', description: 'Achievement recognition' }
                        ],
                        'profile_bio': [
                            { text: 'Professional Summary', icon: 'fas fa-user-tie', description: 'Career overview' },
                            { text: 'Skills & Expertise', icon: 'fas fa-star', description: 'Key competencies' },
                            { text: 'Experience Level', icon: 'fas fa-chart-line', description: 'Years in industry' },
                            { text: 'Achievements', icon: 'fas fa-medal', description: 'Notable accomplishments' },
                            { text: 'Specializations', icon: 'fas fa-bullseye', description: 'Areas of focus' },
                            { text: 'Contact Preference', icon: 'fas fa-phone', description: 'How to reach' },
                            { text: 'Availability', icon: 'fas fa-clock', description: 'Working hours' },
                            { text: 'Mission Statement', icon: 'fas fa-compass', description: 'Personal mission' }
                        ],
                        'blog_post': [
                            { text: 'Industry Insights', icon: 'fas fa-lightbulb', description: 'Market analysis and trends' },
                            { text: 'How-To Guide', icon: 'fas fa-list-ol', description: 'Step-by-step tutorials' },
                            { text: 'Case Study', icon: 'fas fa-search', description: 'Real-world examples' },
                            { text: 'Tips & Tricks', icon: 'fas fa-magic', description: 'Best practices' },
                            { text: 'News Update', icon: 'fas fa-newspaper', description: 'Industry news' },
                            { text: 'Success Story', icon: 'fas fa-trophy', description: 'User achievements' },
                            { text: 'Expert Opinion', icon: 'fas fa-comment', description: 'Professional insights' },
                            { text: 'Comparison Review', icon: 'fas fa-balance-scale', description: 'Product comparisons' }
                        ],
                        'notification': [
                            { text: 'Payment Received', icon: 'fas fa-money-bill', description: 'Commission notifications' },
                            { text: 'System Alert', icon: 'fas fa-bell', description: 'Important system messages' },
                            { text: 'Level Promotion', icon: 'fas fa-arrow-up', description: 'MLM level upgrades' },
                            { text: 'New Referral', icon: 'fas fa-user-plus', description: 'Referral sign-ups' },
                            { text: 'Goal Achievement', icon: 'fas fa-target', description: 'Target completions' },
                            { text: 'Weekly Summary', icon: 'fas fa-calendar-week', description: 'Performance summaries' },
                            { text: 'Action Required', icon: 'fas fa-exclamation-circle', description: 'Tasks needing attention' },
                            { text: 'Reminder', icon: 'fas fa-clock', description: 'Important reminders' }
                        ]
                    };
                    
                    return systemTags[contentType] || systemTags['plan_description'];
                }
                
                function addTagToPrompt(tagText, element) {
                    var promptField = document.getElementById('ai-prompt');
                    var currentValue = promptField.value.trim();
                    
                    if (currentValue) {
                        promptField.value = currentValue + ', ' + tagText;
                    } else {
                        promptField.value = tagText;
                    }
                    
                    // Add visual feedback (only if element is passed)
                    if (element) {
                        element.style.backgroundColor = '#28a745';
                        element.style.color = 'white';
                        element.style.borderColor = '#28a745';
                        
                        setTimeout(() => {
                            element.style.backgroundColor = '#f8f9fa';
                            element.style.color = '#212529';
                            element.style.borderColor = '#dee2e6';
                        }, 1000);
                    }
                }
                
                function generateAIContent() {
                    var loadingDiv = document.getElementById('ai-loading');
                    var contentDiv = document.getElementById('ai-content');
                    var resultsDiv = document.getElementById('ai-results');
                    var suggestionsDiv = document.getElementById('ai-suggestions');
                    
                    // Show loading
                    contentDiv.classList.add('d-none');
                    loadingDiv.classList.remove('d-none');
                    
                    var formData = {
                        content_type: aiHelperData.currentContentType,
                        prompt: document.getElementById('ai-prompt').value,
                        tone: document.getElementById('ai-tone').value,
                        length: document.getElementById('ai-length').value
                    };
                    
                    fetch(aiHelperData.apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingDiv.classList.add('d-none');
                        contentDiv.classList.remove('d-none');
                        
                        if (data.success) {
                            displayAISuggestions(data.suggestions);
                            resultsDiv.classList.remove('d-none');
                        } else {
                            alert('Error: ' + (data.message || 'Failed to generate content'));
                        }
                    })
                    .catch(error => {
                        loadingDiv.classList.add('d-none');
                        contentDiv.classList.remove('d-none');
                        alert('Error: ' + error.message);
                    });
                }
                
                function displayAISuggestions(suggestions) {
                    var suggestionsDiv = document.getElementById('ai-suggestions');
                    var html = '';
                    
                    suggestions.forEach(function(suggestion, index) {
                        html += `
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title"><?= __('admin.option') ?> ${index + 1}</h6>
                                            <p class="card-text">${suggestion}</p>
                                        </div>
                                        <button class="btn btn-sm btn-success" onclick="useAISuggestion('${suggestion.replace(/'/g, "\\'")}')">
                                            <i class="fas fa-check me-1"></i><?= __('admin.use_this') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    suggestionsDiv.innerHTML = html;
                }
                
                function useAISuggestion(content) {
                    // Check if aiHelperData exists and has currentTargetField
                    if (!aiHelperData || !aiHelperData.currentTargetField) {
                        alert('Error: Target field not specified. Please close and reopen the AI helper.');
                        return;
                    }
                    
                    var targetField = document.getElementById(aiHelperData.currentTargetField);
                    
                    // If not found by ID, try to find by name
                    if (!targetField) {
                        targetField = document.querySelector('[name="' + aiHelperData.currentTargetField + '"]');
                    }
                    
                    if (targetField) {
                        // Check if it's a summernote editor
                        if ($(targetField).hasClass('summernote-img') || $(targetField).data('summernote')) {
                            // For summernote rich text editor
                            $(targetField).summernote('code', content);
                            $(targetField).summernote('focus');
                        } else {
                            // For regular text fields and textareas
                            targetField.value = content;
                            
                            // Trigger change event for any listeners (Angular, etc.)
                            targetField.dispatchEvent(new Event('change'));
                            targetField.dispatchEvent(new Event('input'));
                            
                            // For Angular specifically
                            if (window.angular) {
                                var scope = angular.element(targetField).scope();
                                if (scope) {
                                    scope.$apply();
                                }
                            }
                        }
                        
                        // Close modal
                        var modal = bootstrap.Modal.getInstance(document.getElementById('aiHelperModal'));
                        modal.hide();
                        
                        // Show success message
                        showNotification('<?= __('admin.ai_content_applied') ?>', 'success');
                    } else {
                        // Fallback: try to find field by name or other attributes
                        var fieldByName = document.querySelector(`[name="description"]`) || 
                                         document.querySelector(`textarea[placeholder*="description"]`) ||
                                         document.querySelector('.summernote-img');
                        
                        if (fieldByName) {
                            if ($(fieldByName).hasClass('summernote-img') || $(fieldByName).data('summernote')) {
                                $(fieldByName).summernote('code', content);
                                $(fieldByName).summernote('focus');
                            } else {
                                fieldByName.value = content;
                                fieldByName.dispatchEvent(new Event('change'));
                                fieldByName.dispatchEvent(new Event('input'));
                                
                                // For Angular specifically
                                if (window.angular) {
                                    var scope = angular.element(fieldByName).scope();
                                    if (scope) {
                                        scope.$apply();
                                    }
                                }
                            }
                            
                            var modal = bootstrap.Modal.getInstance(document.getElementById('aiHelperModal'));
                            modal.hide();
                            showNotification('<?= __('admin.ai_content_applied') ?>', 'success');
                        } else {
                            alert('Error: Could not find target field. Field ID: ' + aiHelperData.currentTargetField);
                        }
                    }
                }
                
                function showNotification(message, type) {
                    // Simple notification (can be enhanced)
                    var alert = document.createElement('div');
                    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }
            </script>
            <?php
            return ob_get_clean();
        }
    }

    /**
     * Generate Enhanced QR Code Modal HTML
     * Creates a professional QR code modal with download/print capabilities
     * @return string HTML for QR modal
     */
    if (!function_exists('generate_qr_modal')) {
        function generate_qr_modal() {
            $CI =& get_instance();
            
            ob_start();
            ?>
            <!-- Enhanced QR Code Modal -->
            <div class="modal fade" id="model-codemodal" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="qr-modal-title"><?= __('user.generate_qr_code') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body py-4 text-center">
                    <div id="qr-code-container" class="mb-3 d-flex justify-content-center align-items-center" style="min-height: 200px;"></div>
                    <div id="qr-url-display" class="text-muted small mb-3 px-3" style="word-break: break-all; max-height: 60px; overflow-y: auto;"></div>
                    <div class="text-muted small">
                      <i class="fas fa-info-circle me-1"></i>
                      <?= __('user.scan_with_phone_camera') ?>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      <i class="fas fa-times me-2"></i><?= __('user.close') ?>
                    </button>
                    <button type="button" class="btn btn-primary" id="download-qr-btn">
                      <i class="fas fa-download me-2"></i><?= __('user.download') ?>
                    </button>
                    <button type="button" class="btn btn-success" id="print-qr-btn">
                      <i class="fas fa-print me-2"></i><?= __('user.print') ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <?php
            return ob_get_clean();
        }
    }

    /**
     * Generate Enhanced QR Code JavaScript Functions
     * Creates all JavaScript functionality for QR code generation, download, and print
     * @return string JavaScript code for QR functionality
     */
    if (!function_exists('generate_qr_scripts')) {
        function generate_qr_scripts() {
            $CI =& get_instance();
            
            ob_start();
            ?>
            <script>
            $(document).ready(function() {
                // Enhanced QR Code functionality
                let currentQRCode = null;
                let currentQRUrl = '';

                // QR Code generation
                $(document).on('click', '.qrcode', function() {
                    currentQRUrl = $(this).attr('data-id');
                    
                    // Clear previous QR code and show loading
                    $('#qr-code-container').html(`
                        <div class="d-flex flex-column align-items-center">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden"><?= __('user.loading') ?></span>
                            </div>
                            <small class="text-muted"><?= __('user.generating_qr_code') ?></small>
                        </div>
                    `);
                    
                    // Set modal title and URL display
                    $('#qr-modal-title').text('<?= __('user.generate_qr_code') ?>');
                    $('#qr-url-display').text(currentQRUrl);
                    
                    // Disable buttons initially
                    $('#download-qr-btn, #print-qr-btn').prop('disabled', true);
                    
                    // Show modal
                    $("#model-codemodal").modal("show");
                    
                    // Generate QR code after a short delay to show loading state
                    setTimeout(() => {
                        try {
                            // Clear loading state
                            $('#qr-code-container').empty();
                            
                            // Generate QR code with improved size and mobile optimization
                            const qrSize = window.innerWidth < 768 ? 180 : 220; // Responsive size
                            
                            currentQRCode = new QRCode(document.getElementById("qr-code-container"), {
                                text: currentQRUrl,
                                width: qrSize,
                                height: qrSize,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                            
                            // Enable download and print buttons
                            $('#download-qr-btn, #print-qr-btn').prop('disabled', false);
                            
                        } catch(error) {
                            console.error('QR Code generation failed:', error);
                            $('#qr-code-container').html(`
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?= __('user.qr_generation_failed') ?>
                                </div>
                            `);
                            // Disable download and print buttons
                            $('#download-qr-btn, #print-qr-btn').prop('disabled', true);
                        }
                    }, 300); // 300ms delay to show loading state
                });

                // Download QR Code functionality
                $(document).on('click', '#download-qr-btn', function() {
                    if (!currentQRCode) return;
                    
                    try {
                        const canvas = $('#qr-code-container canvas')[0];
                        if (canvas) {
                            // Create download link
                            const link = document.createElement('a');
                            link.download = 'qrcode-' + Date.now() + '.png';
                            link.href = canvas.toDataURL();
                            link.click();
                        }
                    } catch(error) {
                        console.error('QR Code download failed:', error);
                        alert('<?= __('user.download_failed') ?>');
                    }
                });

                // Print QR Code functionality
                $(document).on('click', '#print-qr-btn', function() {
                    if (!currentQRCode) return;
                    
                    try {
                        const canvas = $('#qr-code-container canvas')[0];
                        if (canvas) {
                            const printWindow = window.open('', '_blank');
                            printWindow.document.write(`
                                <html>
                                    <head>
                                        <title>QR Code - ${currentQRUrl}</title>
                                        <style>
                                            body { text-align: center; font-family: Arial, sans-serif; margin: 40px; }
                                            .qr-container { margin: 20px 0; }
                                            .url-text { margin-top: 20px; word-break: break-all; color: #666; }
                                        </style>
                                    </head>
                                    <body>
                                        <h2><?= __('user.qr_code') ?></h2>
                                        <div class="qr-container">
                                            <img src="${canvas.toDataURL()}" alt="QR Code" />
                                        </div>
                                        <div class="url-text">${currentQRUrl}</div>
                                    </body>
                                </html>
                            `);
                            printWindow.document.close();
                            printWindow.print();
                        }
                    } catch(error) {
                        console.error('QR Code print failed:', error);
                        alert('<?= __('user.print_failed') ?>');
                    }
                });
            });
            </script>
            <?php
            return ob_get_clean();
        }
    }

    /**
     * Complete QR Code Implementation
     * Combines modal and scripts for easy one-line implementation
     * @return string Complete HTML and JavaScript for QR functionality
     */
    if (!function_exists('generate_qr_complete')) {
        function generate_qr_complete() {
            return generate_qr_modal() . generate_qr_scripts();
        }
    }

    /**
     * Complete spam protection with console logging
     * Handles all spam detection logic and console output
     * 
     * @param array $data View data with user_id, form_id, ip
     * @param string $type 'form', 'product', or 'tool'
     * @return array ['blocked' => bool, 'message' => string, 'console_script' => string]
     */
    if (!function_exists('handle_spam_protection')) {
        function handle_spam_protection($data, $type = 'form') {
            $CI =& get_instance();
            
            // Skip if data is invalid
            if (!isset($data['user_id']) || !isset($data['ip'])) {
                $console_script = "<script>console.log('🚫 Spam Protection: Invalid data - missing user_id or ip');</script>";
                return ['blocked' => true, 'message' => 'Invalid data', 'console_script' => $console_script];
            }
            
            // Get the ID field based on type
            $id_field = '';
            switch ($type) {
                case 'form':
                    $id_field = 'form_id';
                    break;
                case 'product':
                    $id_field = 'product_id';
                    break;
                case 'tool':
                    $id_field = 'tools_id';
                    break;
                default:
                    $console_script = "<script>console.log('🚫 Spam Protection: Unknown type - " . $type . "');</script>";
                    return ['blocked' => true, 'message' => 'Unknown type', 'console_script' => $console_script];
            }
            
            if (!isset($data[$id_field])) {
                $console_script = "<script>console.log('🚫 Spam Protection: Missing ID field - " . $id_field . "');</script>";
                return ['blocked' => true, 'message' => 'Missing ID', 'console_script' => $console_script];
            }
            
            // Load Uagent library to get current browser info
            $CI->load->library('Uagent');
            $CI->uagent->init();
            $current_agent = $CI->uagent->string;
            
            if (empty($current_agent)) {
                $console_script = "<script>console.log('⚠️ Spam Protection: Empty user agent - allowing fallback');</script>";
                return ['blocked' => false, 'message' => 'Empty user agent', 'console_script' => $console_script];
            }
            
            // Check if this user has already viewed this item from same IP with different browser
            $cross_browser_check = $CI->db->get_where("product_view_logs", [
                "user_id" => $data['user_id'],
                $id_field => $data[$id_field],
                "ip" => $data['ip'],
                "agent !=" => $current_agent
            ])->row();
            
            if (isset($cross_browser_check)) {
                $console_script = "<script>console.log('🚫 Spam Protection: Cross-browser spam detected - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
                return ['blocked' => true, 'message' => 'Cross-browser spam', 'console_script' => $console_script];
            }
            
            $console_script = "<script>console.log('✅ Spam Protection: No spam detected - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
            return ['blocked' => false, 'message' => 'No spam', 'console_script' => $console_script];
        }
    }

    /**
     * Ultra-minimal spam protection - just one line to use
     * Handles everything: detection, logging, console output
     * 
     * @param array $data View data with user_id, form_id, ip
     * @param string $type 'form', 'product', or 'tool'
     * @return bool True if blocked, False if allowed
     */
    if (!function_exists('spam_protect')) {
        function spam_protect($data, $type = 'form') {
            $CI =& get_instance();
            
            // Skip if data is invalid
            if (!isset($data['user_id']) || !isset($data['ip'])) {
                echo "<script>console.log('🚫 Spam Protection: Invalid data - missing user_id or ip');</script>";
                return true; // Block invalid requests
            }
            
            // Get the ID field based on type
            $id_field = '';
            switch ($type) {
                case 'form':
                    $id_field = 'form_id';
                    break;
                case 'product':
                    $id_field = 'product_id';
                    break;
                case 'tool':
                    $id_field = 'tools_id';
                    break;
                default:
                    echo "<script>console.log('🚫 Spam Protection: Unknown type - " . $type . "');</script>";
                    return true; // Block unknown types
            }
            
            if (!isset($data[$id_field])) {
                echo "<script>console.log('🚫 Spam Protection: Missing ID field - " . $id_field . "');</script>";
                return true; // Block if ID is missing
            }
            
            // Enhanced cross-browser and private mode detection
            // Check for ANY view from same IP in last 24 hours (handles private mode, different browsers, etc.)
            $recent_view = $CI->db->get_where("product_view_logs", [
                $id_field => $data[$id_field],
                "ip" => $data['ip'],
                "created_at >=" => date('Y-m-d H:i:s', strtotime('-24 hours'))
            ])->row();
            
            // Also check for any recent view from same user/IP combination (more aggressive detection)
            $recent_user_view = $CI->db->get_where("product_view_logs", [
                "user_id" => $data['user_id'],
                "ip" => $data['ip'],
                "created_at >=" => date('Y-m-d H:i:s', strtotime('-24 hours'))
            ])->row();
            
            if (isset($recent_view)) {
                // Determine the specific spam method being used
                $spam_method = "Unknown method";
                
                // Check if it's the same user (private mode, different browser, cache clearing)
                if ($recent_view->user_id == $data['user_id']) {
                    // Enhanced detection for cross-browser attempts
                    $current_session_id = $CI->session->session_id ?? '';
                    $recent_session_id = $recent_view->session_id ?? '';
                    $current_user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                    $recent_user_agent = $recent_view->agent ?? '';
                    
                    // Debug session IDs and additional info
                    echo "<script>console.log('🔍 Spam Protection Debug: Current Session ID: \"' + '" . $current_session_id . "' + '\", Recent Session ID: \"' + '" . $recent_session_id . "' + '\", Current User Agent: \"' + '" . $current_user_agent . "' + '\", Recent User Agent: \"' + '" . $recent_user_agent . "' + '\"');</script>";
                    echo "<script>console.log('🔍 Spam Protection Debug: Session ID Match: ' + ('" . $recent_session_id . "' === '" . $current_session_id . "') + ', User Agent Match: ' + ('" . $recent_user_agent . "' === '" . $current_user_agent . "'));</script>";
                    
                    // Check for cross-browser/private mode detection
                    // If session IDs are different OR user agents are different, it's likely a different browser or private mode
                    if ($recent_session_id != $current_session_id || $recent_user_agent != $current_user_agent) {
                        $spam_method = "Private/Incognito mode or different browser";
                    } else {
                        $spam_method = "Same browser refresh";
                    }
                } else {
                    // Different user from same IP (shared network, proxy)
                    $spam_method = "Different user from same IP";
                }
                
                echo "<script>console.log('🚫 Spam Protection: " . $spam_method . " detected (24h rule) - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
                return true; // One view per IP per 24 hours
            }
            
            // Additional check: If we have any recent view from same user/IP, block it (catches Firefox private mode)
            if (isset($recent_user_view)) {
                $current_user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                $recent_user_agent = $recent_user_view->agent ?? '';
                
                echo "<script>console.log('🔍 Spam Protection Debug: Additional Check - Current User Agent: \"' + '" . $current_user_agent . "' + '\", Recent User Agent: \"' + '" . $recent_user_agent . "' + '\"');</script>";
                
                // More aggressive detection: If we have ANY recent view from same user/IP, block it
                // This catches Firefox private mode and other edge cases
                echo "<script>console.log('🚫 Spam Protection: Cross-browser attempt detected (same user/IP within 24h) - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
                return true; // Block any cross-browser attempts
            }
            
            // Final check: Look for ANY recent activity from same user (most aggressive)
            $any_recent_user_activity = $CI->db->get_where("product_view_logs", [
                "user_id" => $data['user_id'],
                "created_at >=" => date('Y-m-d H:i:s', strtotime('-24 hours'))
            ])->num_rows();
            
            if ($any_recent_user_activity > 0) {
                echo "<script>console.log('🚫 Spam Protection: User activity detected within 24h - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
                return true; // Block if user has any recent activity
            }
            
            // Additional protection against proxy/spam services
            // Check for rapid-fire views from same IP (multiple views in short time)
            $rapid_views = $CI->db->get_where("product_view_logs", [
                "ip" => $data['ip'],
                "created_at >=" => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ])->num_rows();
            
            if ($rapid_views >= 10) {
                echo "<script>console.log('🚫 Spam Protection: Rapid-fire attack detected - IP: " . $data['ip'] . " has " . $rapid_views . " views in 1 hour (limit: 10)');</script>";
                return true; // Too many views from same IP in 1 hour
            }
            
            // Check for suspicious user agent patterns (common in spam services)
            $current_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $suspicious_agents = [
                'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget', 'python', 'java',
                'phantom', 'headless', 'selenium', 'automation', 'script'
            ];
            
            foreach ($suspicious_agents as $suspicious) {
                if (stripos($current_agent, $suspicious) !== false) {
                    echo "<script>console.log('🚫 Spam Protection: Bot/automation detected - User agent contains \"' + '" . $suspicious . "' + '\" - IP: " . $data['ip'] . "');</script>";
                    return true; // Suspicious user agent
                }
            }
            
            // VPN and proxy detection
            // Check for multiple IPs from same user in short time (VPN rotation)
            $recent_user_ips = $CI->db->get_where("product_view_logs", [
                "user_id" => $data['user_id'],
                "created_at >=" => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ])->result();
            
            $unique_ips = [];
            foreach ($recent_user_ips as $view) {
                $unique_ips[] = $view->ip;
            }
            $unique_ips = array_unique($unique_ips);
            
            if (count($unique_ips) >= 3) {
                echo "<script>console.log('🚫 Spam Protection: VPN rotation detected - User: " . $data['user_id'] . " used " . count($unique_ips) . " different IPs in 1 hour (limit: 3)');</script>";
                return true; // Too many different IPs from same user
            }
            
            // Check for known VPN/proxy IP ranges (basic check)
            $vpn_indicators = [
                'vpn', 'proxy', 'tor', 'anonymous', 'privacy'
            ];
            
            // Get hostname for IP (basic VPN detection)
            $hostname = gethostbyaddr($data['ip']);
            foreach ($vpn_indicators as $indicator) {
                if (stripos($hostname, $indicator) !== false) {
                    echo "<script>console.log('🚫 Spam Protection: VPN/proxy service detected - IP: " . $data['ip'] . " Hostname contains \"' + '" . $indicator . "' + '\" (" . $hostname . ")');</script>";
                    return true; // VPN/proxy detected
                }
            }
            
            // Enhanced VPN detection for premium services
            // Check if IP is from known datacenter ranges (common for VPNs)
            $datacenter_indicators = [
                'amazon', 'aws', 'google', 'cloudflare', 'digitalocean', 'linode', 'vultr',
                'ovh', 'hetzner', 'leaseweb', 'chicago', 'frankfurt', 'amsterdam', 'london'
            ];
            
            foreach ($datacenter_indicators as $indicator) {
                if (stripos($hostname, $indicator) !== false) {
                    echo "<script>console.log('🚫 Spam Protection: Datacenter/VPN IP detected - IP: " . $data['ip'] . " Hostname contains \"' + '" . $indicator . "' + '\" (" . $hostname . ")');</script>";
                    return true; // Datacenter/VPN IP detected
                }
            }
            
            // Get current user agent directly from server
            $current_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            if (empty($current_agent)) {
                echo "<script>console.log('⚠️ Spam Protection: Empty user agent - allowing fallback');</script>";
                return false; // Allow if user agent is empty (fallback)
            }
            
            echo "<script>console.log('✅ Spam Protection: No spam detected - User: " . $data['user_id'] . ", Type: " . $type . ", ID: " . $data[$id_field] . ", IP: " . $data['ip'] . "');</script>";
            return false; // No spam detected
        }
    }

    /**
     * Ultimate one-line spam protection with logging
     * Handles everything: detection, logging, console output, and returns result
     * 
     * @param array $data View data with user_id, form_id, ip
     * @param string $type 'form', 'product', or 'tool'
     * @return int 1 for success, 2 for blocked
     */
    if (!function_exists('spam_protect_and_log')) {
        function spam_protect_and_log($data, $type = 'form') {
            $CI =& get_instance();
            
            // Check if spam detected
            $is_spam = spam_protect($data, $type);
            
            // Only log to database if NOT spam (successful views)
            // For spam attempts, we'll use a separate lightweight logging system
            if (!$is_spam) {
                // No spam detected - log as normal view
                switch ($type) {
                    case 'form':
                        $CI->load->model("Form_model");
                        return $CI->Form_model->save_view_logs($data);
                    case 'product':
                        $CI->load->model("Product_model");
                        return $CI->Product_model->save_view_logs($data);
                    case 'tool':
                        $CI->load->model("IntegrationModel");
                        return $CI->IntegrationModel->save_view_logs($data);
                    default:
                        $CI->load->model("Form_model");
                        return $CI->Form_model->save_view_logs($data);
                }
            } else {
                // Spam detected - use lightweight logging for admin monitoring
                log_spam_attempt_lightweight($data, $type);
                return 2; // Blocked
            }
        }
    }
    
    /**
     * Lightweight spam attempt logging for admin monitoring
     * Uses file-based storage for persistent admin visibility
     * 
     * @param array $data View data
     * @param string $type Campaign type
     */
    if (!function_exists('log_spam_attempt_lightweight')) {
        function log_spam_attempt_lightweight($data, $type) {
            $CI =& get_instance();
            
            // Create spam attempt record
            $spam_record = [
                'user_id' => $data['user_id'],
                'form_id' => $data['form_id'] ?? null,
                'product_id' => $data['product_id'] ?? null,
                'tools_id' => $data['tools_id'] ?? null,
                'ip' => $data['ip'],
                'created_at' => date('Y-m-d H:i:s'),
                'blocked' => true,
                'spam_type' => $type,
                'fraud_score' => $data['fraud_score'] ?? null,
            ];
            
            // Store in both session (for immediate access) and file (for persistence)
            $spam_logs = $CI->session->userdata('spam_logs') ?: [];
            $spam_logs[] = $spam_record;
            
            // Keep only last 1000 spam records to prevent session bloat
            if (count($spam_logs) > 1000) {
                $spam_logs = array_slice($spam_logs, -1000);
            }
            
            // Store back in session
            $CI->session->set_userdata('spam_logs', $spam_logs);
            
            // Also store in file for admin dashboard access
            $spam_file = APPPATH . 'logs/spam_attempts.json';
            $file_logs = [];
            
            if (file_exists($spam_file)) {
                $file_content = file_get_contents($spam_file);
                if ($file_content) {
                    $file_logs = json_decode($file_content, true) ?: [];
                }
            }
            
            // Add new record to file logs
            $file_logs[] = $spam_record;
            
            // Keep only last 1000 records in file
            if (count($file_logs) > 1000) {
                $file_logs = array_slice($file_logs, -1000);
            }
            
            // Write back to file
            file_put_contents($spam_file, json_encode($file_logs));
            
            // Debug: Log that spam attempt was stored
            error_log("Spam attempt logged to session and file. Total spam logs: " . count($spam_logs));
        }
    }


    if (!function_exists('check_cross_browser_spam')) {
        function check_cross_browser_spam($data, $type = 'form') {
            $result = handle_spam_protection($data, $type);
            return $result['blocked'];
        }
    }

    if (!function_exists('format_filesize')) {
        function format_filesize($bytes) {
            $bytes = (int) $bytes;
            if ($bytes <= 0)         return '0 B';
            if ($bytes < 1024)       return $bytes . ' B';
            if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
            if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
            return round($bytes / 1073741824, 2) . ' GB';
        }
    }

    if (!function_exists('get_pagination_settings')) {
        function get_pagination_settings($custom_settings = []) {
            $defaults = [
                'per_page' => 15,
                'size' => 'sm',
                'alignment' => 'center',
                'show_first_last' => true,
                'show_prev_next' => true,
                'max_links' => 5
            ];
            
            return array_merge($defaults, $custom_settings);
        }
    }

    if (!function_exists('easy_pagination')) {
        function easy_pagination($base_url, $total_rows, $offset = 0, $custom_settings = []) {
            $settings = get_pagination_settings($custom_settings);
            $use_get_params = isset($custom_settings['use_get_params']) ? $custom_settings['use_get_params'] : false;
            $preserve_query = isset($custom_settings['preserve_query']) ? (bool)$custom_settings['preserve_query'] : false;
            $current_get = [];
            if ($preserve_query && function_exists('get_instance')) {
                $ci = get_instance();
                if ($ci && isset($ci->input)) {
                    $current_get = (array)$ci->input->get();
                    if (isset($current_get['page'])) unset($current_get['page']);
                    // Remove empty parameters for cleaner URLs
                    $current_get = array_filter($current_get, function($value) {
                        return $value !== '' && $value !== null;
                    });
                }
            }

            $per_page = $settings['per_page'];
            
            if ($total_rows <= $per_page) {
                return ['html' => '<div class="text-muted small text-center py-2">Total: ' . $total_rows . ' items (Page 1 of 1)</div>', 'per_page' => $per_page];
            }
            
            $total_pages = ceil($total_rows / $per_page);
            $current_page = max(1, min(($offset / $per_page) + 1, $total_pages));
            
            $size_class = $settings['size'] ? ' pagination-' . $settings['size'] : '';
            $alignment_class = ' justify-content-' . $settings['alignment'];
            
            $html = '<ul class="pagination' . $size_class . $alignment_class . '">';
            
            if ($settings['show_first_last'] && $current_page > 1) {
                $html .= '<li class="page-item">';
                if ($use_get_params) {
                    $params = $current_get; $params['page'] = 1;
                    $url = $base_url . '?' . http_build_query($params);
                } else {
                    $qs = ($preserve_query && !empty($current_get)) ? ('?' . http_build_query($current_get)) : '';
                    $url = $base_url . '/1' . $qs;
                }
                $html .= '<a class="page-link" href="' . $url . '">&laquo;</a>';
                $html .= '</li>';
            }
            
            if ($settings['show_prev_next'] && $current_page > 1) {
                $prev_page = $current_page - 1;
                $html .= '<li class="page-item">';
                if ($use_get_params) {
                    $params = $current_get; $params['page'] = $prev_page;
                    $url = $base_url . '?' . http_build_query($params);
                } else {
                    $qs = ($preserve_query && !empty($current_get)) ? ('?' . http_build_query($current_get)) : '';
                    $url = $base_url . '/' . $prev_page . $qs;
                }
                $html .= '<a class="page-link" href="' . $url . '">&lsaquo;</a>';
                $html .= '</li>';
            }
            
            $start = max(1, $current_page - floor($settings['max_links'] / 2));
            $end = min($total_pages, $start + $settings['max_links'] - 1);
            $start = max(1, $end - $settings['max_links'] + 1);
            
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $current_page) {
                    $html .= '<li class="page-item active">';
                    $html .= '<span class="page-link">' . $i . '</span>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="page-item">';
                    if ($use_get_params) {
                        $params = $current_get; $params['page'] = $i;
                        $url = $base_url . '?' . http_build_query($params);
                    } else {
                        $qs = ($preserve_query && !empty($current_get)) ? ('?' . http_build_query($current_get)) : '';
                        $url = $base_url . '/' . $i . $qs;
                    }
                    $html .= '<a class="page-link" href="' . $url . '">' . $i . '</a>';
                    $html .= '</li>';
                }
            }
            
            if ($settings['show_prev_next'] && $current_page < $total_pages) {
                $next_page = $current_page + 1;
                $html .= '<li class="page-item">';
                if ($use_get_params) {
                    $params = $current_get; $params['page'] = $next_page;
                    $url = $base_url . '?' . http_build_query($params);
                } else {
                    $qs = ($preserve_query && !empty($current_get)) ? ('?' . http_build_query($current_get)) : '';
                    $url = $base_url . '/' . $next_page . $qs;
                }
                $html .= '<a class="page-link" href="' . $url . '">&rsaquo;</a>';
                $html .= '</li>';
            }
            
            if ($settings['show_first_last'] && $current_page < $total_pages) {
                $html .= '<li class="page-item">';
                if ($use_get_params) {
                    $params = $current_get; $params['page'] = $total_pages;
                    $url = $base_url . '?' . http_build_query($params);
                } else {
                    $qs = ($preserve_query && !empty($current_get)) ? ('?' . http_build_query($current_get)) : '';
                    $url = $base_url . '/' . $total_pages . $qs;
                }
                $html .= '<a class="page-link" href="' . $url . '">&raquo;</a>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            
            return [
                'html' => $html,
                'per_page' => $per_page
            ];
        }
    }

    if (!function_exists('pagination_summary_html')) {
        function pagination_summary_html($current_page, $per_page, $total_rows, $alignment = 'start', $size = 'sm') {
            $current_page = max(1, (int)$current_page);
            $per_page = max(1, (int)$per_page);
            $total_rows = max(0, (int)$total_rows);
            if ($total_rows === 0) {
                $text = __('admin.no_entries');
            } else {
                $start = (($current_page - 1) * $per_page) + 1;
                if ($start > $total_rows) {
                    $current_page = (int)ceil($total_rows / $per_page);
                    $start = (($current_page - 1) * $per_page) + 1;
                }
                $end = min($start + $per_page - 1, $total_rows);
                $tpl = __('admin.showing_entries');
                $text = str_replace(['{start}','{end}','{total}'], [$start, $end, $total_rows], $tpl);
            }
            $alignClass = 'text-start';
            if ($alignment === 'center') $alignClass = 'text-center';
            if ($alignment === 'end' || $alignment === 'right') $alignClass = 'text-end';
            $sizeClass = $size === 'lg' ? 'fs-6' : ($size === 'sm' ? 'small' : '');
            return '<div class="text-muted '.$sizeClass.' '.$alignClass.'">'.$text.'</div>';
        }
    }

    if (!function_exists('ajax_pagination')) {
        function ajax_pagination($total_rows, $current_page = 1, $custom_settings = []) {
            $settings = get_pagination_settings($custom_settings);
            $per_page = $settings['per_page'];
            $js_function = isset($custom_settings['js_function']) ? $custom_settings['js_function'] : 'universalPagination';
            
            if ($total_rows <= $per_page) {
                return ['html' => '<div class="text-muted small text-center py-2">Total: ' . $total_rows . ' items (Page 1 of 1)</div>', 'per_page' => $per_page];
            }
            
            $total_pages = ceil($total_rows / $per_page);
            $current_page = max(1, min($current_page, $total_pages));
            
            $size_class = $settings['size'] ? ' pagination-' . $settings['size'] : '';
            $alignment_class = ' justify-content-' . $settings['alignment'];
            
            $html = '<ul class="pagination' . $size_class . $alignment_class . '">';
            
            if ($settings['show_first_last'] && $current_page > 1) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="javascript:void(0)" onclick="' . $js_function . '(1)">&laquo;</a>';
                $html .= '</li>';
            }
            
            if ($settings['show_prev_next'] && $current_page > 1) {
                $prev_page = $current_page - 1;
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="javascript:void(0)" onclick="' . $js_function . '(' . $prev_page . ')">&lsaquo;</a>';
                $html .= '</li>';
            }
            
            $start = max(1, $current_page - floor($settings['max_links'] / 2));
            $end = min($total_pages, $start + $settings['max_links'] - 1);
            $start = max(1, $end - $settings['max_links'] + 1);
            
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $current_page) {
                    $html .= '<li class="page-item active">';
                    $html .= '<span class="page-link">' . $i . '</span>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="page-item">';
                    $html .= '<a class="page-link" href="javascript:void(0)" onclick="' . $js_function . '(' . $i . ')">' . $i . '</a>';
                    $html .= '</li>';
                }
            }
            
            if ($settings['show_prev_next'] && $current_page < $total_pages) {
                $next_page = $current_page + 1;
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="javascript:void(0)" onclick="' . $js_function . '(' . $next_page . ')">&rsaquo;</a>';
                $html .= '</li>';
            }
            
            if ($settings['show_first_last'] && $current_page < $total_pages) {
                $html .= '<li class="page-item">';
                $html .= '<a class="page-link" href="javascript:void(0)" onclick="' . $js_function . '(' . $total_pages . ')">&raquo;</a>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            
            return [
                'html' => $html,
                'per_page' => $per_page,
                'js_script' => generate_universal_pagination_script()
            ];
        }
    }

    if (!function_exists('generate_universal_pagination_script')) {
        function generate_universal_pagination_script() {
            return '
            <script>
            if (typeof universalPagination === "undefined") {
                function universalPagination(page) {
                    var currentUrl = window.location.pathname;
                    var formData = new FormData();
                    formData.append("page", page);
                    
                    // Collect all form inputs on the page
                    $("form input, form select, form textarea").each(function() {
                        if ($(this).attr("name") && $(this).val()) {
                            formData.append($(this).attr("name"), $(this).val());
                        }
                    });
                    
                    $.ajax({
                        url: currentUrl,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        beforeSend: function() {
                            $(".pagination-container, .wallet-table-container, .transaction-content, .user-table tbody").html(
                                "<div class=\"d-flex justify-content-center py-5\"><div class=\"spinner-border text-primary\" role=\"status\"></div></div>"
                            );
                        },
                        success: function(response) {
                            if (response.table) {
                                $(".pagination-container, .wallet-table-container, .transaction-content, .user-table tbody").html(response.table);
                            }
                            if (response.pagination) {
                                $(".pagination-wrapper, .pagination-container").html(response.pagination);
                            }
                        },
                        error: function() {
                            if (typeof showToast !== "undefined") {
                                showToast("Error", "Failed to load page data", "error", 3000);
                            } else {
                                alert("Failed to load page data");
                            }
                        }
                    });
                }
            }
            </script>';
        }
    }

    if (!function_exists('render_universal_pagination')) {
        function render_universal_pagination($total_rows, $current_page = 1, $custom_settings = []) {
            $pagination_data = ajax_pagination($total_rows, $current_page, $custom_settings);
            return $pagination_data['html'] . $pagination_data['js_script'];
        }
    }

    /**
     * Generate Admin Theme CSS Variables
     * Keeps header.php clean by centralizing theme color logic
     */
    if (!function_exists('generate_admin_theme_css')) {
        function generate_admin_theme_css() {
            // Get CodeIgniter instance to access models
            $CI =& get_instance();
            $CI->load->model('Product_model');
            
            // Load all theme settings from database
            $theme_settings = [
                'admin_side_font' => $CI->Product_model->getSettings('site','admin_side_font')['admin_side_font'] ?? 'PT Sans',
                'admin_topbar_bg' => $CI->Product_model->getSettings('theme','admin_topbar_bg')['admin_topbar_bg'] ?? '#34495e',
                'admin_topbar_text' => $CI->Product_model->getSettings('theme','admin_topbar_text')['admin_topbar_text'] ?? '#ffffff',
                'admin_dropdown_bg' => $CI->Product_model->getSettings('theme','admin_dropdown_bg')['admin_dropdown_bg'] ?? '#ffffff',
                'admin_dropdown_text' => $CI->Product_model->getSettings('theme','admin_dropdown_text')['admin_dropdown_text'] ?? '#212529',
                'admin_dropdown_hover_bg' => $CI->Product_model->getSettings('theme','admin_dropdown_hover_bg')['admin_dropdown_hover_bg'] ?? '#e3f2fd',
                'admin_dropdown_hover_text' => $CI->Product_model->getSettings('theme','admin_dropdown_hover_text')['admin_dropdown_hover_text'] ?? '#1976d2',
                'admin_horizontal_dropdown_bg' => $CI->Product_model->getSettings('theme','admin_horizontal_dropdown_bg')['admin_horizontal_dropdown_bg'] ?? '#34495e',
                'admin_horizontal_dropdown_text' => $CI->Product_model->getSettings('theme','admin_horizontal_dropdown_text')['admin_horizontal_dropdown_text'] ?? '#ffffff',
                'admin_horizontal_dropdown_hover_bg' => $CI->Product_model->getSettings('theme','admin_horizontal_dropdown_hover_bg')['admin_horizontal_dropdown_hover_bg'] ?? '#e3f2fd',
                'admin_horizontal_dropdown_hover_text' => $CI->Product_model->getSettings('theme','admin_horizontal_dropdown_hover_text')['admin_horizontal_dropdown_hover_text'] ?? '#ffffff',
                'admin_menu_bg' => $CI->Product_model->getSettings('theme','admin_menu_bg')['admin_menu_bg'] ?? '#667eea',
                'admin_menu_text' => $CI->Product_model->getSettings('theme','admin_menu_text')['admin_menu_text'] ?? '#495057',
                'admin_menu_active' => $CI->Product_model->getSettings('theme','admin_menu_active')['admin_menu_active'] ?? '#0d6efd',
                'admin_menu_hover' => $CI->Product_model->getSettings('theme','admin_menu_hover')['admin_menu_hover'] ?? '#0d6efd',
                'admin_dropdown_scrollbar' => $CI->Product_model->getSettings('theme','admin_dropdown_scrollbar')['admin_dropdown_scrollbar'] ?? '#666666',
                'admin_footer_bg' => $CI->Product_model->getSettings('theme','admin_footer_bg')['admin_footer_bg'] ?? '#1a252f',
                'admin_footer_text' => $CI->Product_model->getSettings('theme','admin_footer_text')['admin_footer_text'] ?? '#ffffff'
            ];
            
            // Generate CSS variables
            $css = "<style>\n    :root {\n";
            $css .= "        --admin-font-family: {$theme_settings['admin_side_font']};\n";
            $css .= "        --admin-topbar-bg: {$theme_settings['admin_topbar_bg']};\n";
            $css .= "        --admin-topbar-text: {$theme_settings['admin_topbar_text']};\n";
            $css .= "        --admin-dropdown-bg: {$theme_settings['admin_dropdown_bg']};\n";
            $css .= "        --admin-dropdown-text: {$theme_settings['admin_dropdown_text']};\n";
            $css .= "        --admin-dropdown-hover-bg: {$theme_settings['admin_dropdown_hover_bg']};\n";
            $css .= "        --admin-dropdown-hover-text: {$theme_settings['admin_dropdown_hover_text']};\n";
            $css .= "        --admin-horizontal-dropdown-bg: {$theme_settings['admin_horizontal_dropdown_bg']};\n";
            $css .= "        --admin-horizontal-dropdown-text: {$theme_settings['admin_horizontal_dropdown_text']};\n";
            $css .= "        --admin-horizontal-dropdown-hover-bg: {$theme_settings['admin_horizontal_dropdown_hover_bg']};\n";
            $css .= "        --admin-horizontal-dropdown-hover-text: {$theme_settings['admin_horizontal_dropdown_hover_text']};\n";
            $css .= "        --admin-menu-bg: {$theme_settings['admin_menu_bg']};\n";
            $css .= "        --admin-menu-text: {$theme_settings['admin_menu_text']};\n";
            $css .= "        --admin-menu-active: {$theme_settings['admin_menu_active']};\n";
            $css .= "        --admin-menu-hover: {$theme_settings['admin_menu_hover']};\n";
            $css .= "        --admin-dropdown-scrollbar: {$theme_settings['admin_dropdown_scrollbar']};\n";
            $css .= "        --admin-footer-bg: {$theme_settings['admin_footer_bg']};\n";
            $css .= "        --admin-footer-text: {$theme_settings['admin_footer_text']};\n";
            $css .= "    }\n</style>";
            
            return $css;
        }
    }

    if (!function_exists('render_performance_indicator')) {
        function render_performance_indicator($elementId = 'performance-indicator', $autoHide = true) {
            $hideScript = $autoHide ? "
                setTimeout(() => {
                    if (perfIndicator) {
                        perfIndicator.style.opacity = '0.7';
                        setTimeout(() => {
                            if (perfIndicator) perfIndicator.style.display = 'none';
                        }, 3000);
                    }
                }, 2000);
            " : "";

            return "
            <script>
            window.PerformanceMonitor = {
                start: function(elementId) {
                    const perfIndicator = document.getElementById(elementId || 'performance-indicator');
                    if (perfIndicator) {
                        perfIndicator.style.display = 'inline-block';
                    }
                    return performance.now();
                },
                
                end: function(startTime, elementId) {
                    const endTime = performance.now();
                    const loadTime = Math.round(endTime - startTime);
                    const perfIndicator = document.getElementById(elementId || 'performance-indicator');
                    
                    if (perfIndicator) {
                        let statusText, statusIcon, badgeClass;
                        
                        if (loadTime < 50) {
                            statusText = 'Excellent';
                            statusIcon = 'fa-rocket';
                            badgeClass = 'badge bg-success ms-2';
                        } else if (loadTime < 100) {
                            statusText = 'Fast';
                            statusIcon = 'fa-tachometer-alt';
                            badgeClass = 'badge bg-success ms-2';
                        } else if (loadTime < 300) {
                            statusText = 'Good';
                            statusIcon = 'fa-clock';
                            badgeClass = 'badge bg-info ms-2';
                        } else if (loadTime < 500) {
                            statusText = 'Slow';
                            statusIcon = 'fa-hourglass-half';
                            badgeClass = 'badge bg-warning ms-2';
                        } else {
                            statusText = 'Very Slow';
                            statusIcon = 'fa-exclamation-triangle';
                            badgeClass = 'badge bg-danger ms-2';
                        }
                        
                        perfIndicator.innerHTML = '<i class=\"fa ' + statusIcon + '\"></i> ' + statusText + ' (' + loadTime + 'ms)';
                        perfIndicator.className = badgeClass;
                        perfIndicator.title = 'Page loaded in ' + loadTime + 'ms - ' + statusText + ' performance';
                        
                        " . $hideScript . "
                    }
                    
                    return loadTime;
                }
            };
            </script>";
        }
    }

    if (!function_exists('performance_indicator_html')) {
    function performance_indicator_html($elementId = 'performance-indicator', $classes = 'badge bg-success text-white shadow-lg px-3 py-2', $floating = true) {
        $floatingWrapper = $floating ? '<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">' : '';
        $floatingWrapperEnd = $floating ? '</div>' : '';
        
        return $floatingWrapper . '<span class="' . $classes . '" id="' . $elementId . '" style="display: none;">
            <i class="fa fa-spinner fa-spin"></i> Loading...
        </span>' . $floatingWrapperEnd;
    }
}

if (!function_exists('license_easy_get_local_data')) {
    function license_easy_get_local_data() {
        $file = APPPATH . 'license-easy-data-affiliateporsaas.json';
        if (!file_exists($file)) {
            return [];
        }
        $contents = @file_get_contents($file);
        $data = json_decode($contents, true);
        return is_array($data) ? $data : [];
    }
}

if (!function_exists('aff_license_compliance_status')) {
    /**
     * Centralised CodeCanyon Regular vs Extended compliance check for the
     * Membership module. This function never blocks any feature - it only
     * provides flags so the UI can display non-intrusive Envato-policy
     * notices and an upgrade call to action.
     *
     * @return array {
     *   bool   is_regular              license_type contains "regular"
     *   bool   membership_enabled      membership module status > 0
     *   bool   should_warn             is_regular AND membership_enabled
     *   string license_type            raw license_type from License Easy
     *   int    dismissed_at            unix ts of last banner dismissal
     *   bool   is_currently_dismissed  true if dismissed_at within last 7 days
     *   int    acknowledged_at         unix ts of one-time acknowledgement
     *   bool   needs_acknowledgement   should_warn AND not yet acknowledged
     *   string upgrade_url             link to the author's CodeCanyon page
     * }
     */
    function aff_license_compliance_status() {
        $details = function_exists('license_easy_fetch_details') ? license_easy_fetch_details() : null;
        $license_type = '';
        if (is_array($details) && isset($details['license_type'])) {
            $license_type = (string)$details['license_type'];
        }
        $is_regular = ($license_type !== '' && stripos($license_type, 'regular') !== false);

        $membership_enabled = false;
        $CI =& get_instance();
        if ($CI && isset($CI->Product_model)) {
            $membership_setting = $CI->Product_model->getSettings('membership', 'status');
            if (is_array($membership_setting) && isset($membership_setting['status'])) {
                $membership_enabled = ((int)$membership_setting['status'] > 0);
            }
        }

        $dismissed_at = 0;
        $acknowledged_at = 0;
        if ($CI && isset($CI->Product_model)) {
            $dismiss_row = $CI->Product_model->getSettings('license_compliance', 'regular_dismissed_at');
            if (is_array($dismiss_row) && isset($dismiss_row['regular_dismissed_at'])) {
                $dismissed_at = (int)$dismiss_row['regular_dismissed_at'];
            }
            $ack_row = $CI->Product_model->getSettings('license_compliance', 'extended_acknowledged_at');
            if (is_array($ack_row) && isset($ack_row['extended_acknowledged_at'])) {
                $acknowledged_at = (int)$ack_row['extended_acknowledged_at'];
            }
        }

        $is_currently_dismissed = ($dismissed_at > 0 && (time() - $dismissed_at) < (7 * 86400));
        $should_warn = ($is_regular && $membership_enabled);
        $needs_acknowledgement = ($should_warn && $acknowledged_at <= 0);

        $upgrade_url = '';
        if ($CI) {
            $cfg_url = $CI->config->item('license_upgrade_url');
            if ($cfg_url) { $upgrade_url = $cfg_url; }
        }
        if ($upgrade_url === '') {
            $upgrade_url = 'https://codecanyon.net/licenses/standard';
        }

        return array(
            'is_regular'              => $is_regular,
            'membership_enabled'      => $membership_enabled,
            'should_warn'             => $should_warn,
            'license_type'            => $license_type,
            'dismissed_at'            => $dismissed_at,
            'is_currently_dismissed'  => $is_currently_dismissed,
            'acknowledged_at'         => $acknowledged_at,
            'needs_acknowledgement'   => $needs_acknowledgement,
            'upgrade_url'             => $upgrade_url,
        );
    }
}

if (!function_exists('license_easy_fetch_details')) {
    function license_easy_fetch_details($force_refresh = false) {
        $local = license_easy_get_local_data();
        $license_key = defined('CODECANYON_LICENCE') ? CODECANYON_LICENCE : '';
        if (!$license_key && isset($local['license_key'])) {
            $license_key = $local['license_key'];
        }
        if (!$license_key) {
            return false;
        }
        
        if (!$force_refresh && !empty($local) && isset($local['status']) && $local['status'] === 'active') {
            $local['license_key'] = $license_key;
            return $local;
        }
        
        $domain = function_exists('base_url') ? base_url() : '';
        $payload = array(
            'purchase_code' => $license_key,
            'product_slug' => 'affiliateporsaas',
            'domain' => $domain
        );
        $ch = curl_init('https://affiliatepro.org/index.php?rest_route=/license-easy/v1/validate');
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_TIMEOUT => 20,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        if (!$response) {
            return !empty($local) ? $local : false;
        }
        $decoded = json_decode($response, true);
        if (!is_array($decoded) || !isset($decoded['status']) || $decoded['status'] !== 'success') {
            return !empty($local) ? $local : false;
        }
        $decoded['license_key'] = $license_key;
        
        $file = APPPATH . 'license-easy-data-affiliateporsaas.json';
        @file_put_contents($file, json_encode($decoded, JSON_PRETTY_PRINT));
        
        return $decoded;
    }
}