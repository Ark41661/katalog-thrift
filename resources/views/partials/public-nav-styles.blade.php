.topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
.topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
.topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;color:var(--text);flex-shrink:0;}
.topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
.topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap;}
.topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
.topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
.btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
.btn-login:hover{color:var(--text);border-color:#9ca3af;}
.btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
@media(max-width:900px){.topbar-nav{display:none;}}
