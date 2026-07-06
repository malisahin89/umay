<?php
declare(strict_types=1);
use Core\Csp;

/**
 * Umay Profiler Toolbar (v2)
 *
 * Variables: $token, $totalMs, $memory, $qCount, $qTime, $lCount, $vCount,
 *            $evCount, $caches, $cHits, $mCount, $eCount, $route, $authUser,
 *            $session, $statusCode, $timeColor, $memColor, $qColor, $stColor,
 *            $lColor, $role, $profilerUrl, $n1Count, $mwCount
 */
$id = '__fdb';
$cTotal = count($caches);
$cMisses = count(array_filter($caches, fn ($c) => ! $c['hit'] && $c['type'] === 'get'));
$nonce = Csp::nonce();
$nonceAttr = $nonce ? ' nonce="'.htmlspecialchars($nonce).'"' : '';
$n1Count = $n1Count ?? 0;
$mwCount = $mwCount ?? 0;
?>
<style<?= $nonceAttr?>>
/* ═══════════════════════════════════════════════════════════════════════════
   Umay Debugbar
   ═══════════════════════════════════════════════════════════════════════════ */
:root{
  --fdb-bg:#1e1e2e;--fdb-bg-alt:#181825;--fdb-bg-panel:#11111b;
  --fdb-border:#313244;--fdb-border-light:#45475a;
  --fdb-text:#cdd6f4;--fdb-text-muted:#a0a4b8;--fdb-text-dim:#6c7086;
  --fdb-accent:#cba6f7;--fdb-accent-bg:rgba(203,166,247,.08);
  --fdb-red:#f38ba8;--fdb-green:#a6e3a1;--fdb-yellow:#f9e2af;--fdb-blue:#89b4fa;
  --fdb-peach:#fab387;--fdb-teal:#94e2d5;--fdb-pink:#f5c2e7;--fdb-sky:#89dceb;
  --fdb-bar-h:34px;--fdb-font:'Segoe UI',system-ui,-apple-system,sans-serif;
  --fdb-mono:'Cascadia Code','Fira Code','JetBrains Mono','Consolas',monospace;
}
#<?= $id?>{position:fixed;bottom:0;left:0;right:0;z-index:2147483647;font-family:var(--fdb-font);font-size:12px;line-height:1.4;direction:ltr;text-align:left;color:var(--fdb-text)}
#<?= $id?> *{box-sizing:border-box;margin:0;padding:0;transition: color 0.2s, background 0.2s, border-color 0.2s, box-shadow 0.2s;}

/* ── Bottom Bar (Solid Opaque) ──────────────────────────────────────────── */
#<?= $id?>_bar{display:flex;align-items:center;height:var(--fdb-bar-h);background:#1a1a2e;border-top:1px solid #2d2d4a;user-select:none;overflow-x:auto;overflow-y:hidden;box-shadow:0 -2px 12px rgba(0,0,0,.5)}
#<?= $id?>_bar::-webkit-scrollbar{height:0}

/* Logo pill */
.fdb-logo{padding:0 10px;display:flex;align-items:center;gap:6px;cursor:pointer;border-right:1px solid #2d2d4a;flex-shrink:0;background:#16162a;height:100%}
.fdb-logo:hover{background:#22223a}
.fdb-logo img{height:var(--fdb-bar-h);width:auto;object-fit:contain;flex-shrink:0;filter:brightness(0) invert(1)}

/* Tab button */
.fdb-tab{padding:4px 10px;margin:0 4px;display:flex;align-items:center;justify-content:center;flex:1 1 auto;gap:6px;cursor:pointer;border-radius:6px;white-space:nowrap;height:26px;font-size:11px;color:#a0a4b8;position:relative}
.fdb-tab:hover{background:#25254a;color:#e0e0f0}
.fdb-tab.active{background:#2e2e5a;color:var(--fdb-accent);box-shadow:inset 0 0 0 1px rgba(203,166,247,.3)}
.fdb-tab svg{width:14px;height:14px;opacity:0.7;flex-shrink:0}
.fdb-tab:hover svg,.fdb-tab.active svg{opacity:1}
.fdb-tab-val{font-weight:700;font-size:11px}
.fdb-tab-lbl{font-size:10px;color:#7a7e96;margin-left:2px}
.fdb-tab .fdb-badge{position:absolute;top:0;right:0;width:6px;height:6px;border-radius:50%;background:var(--fdb-red);box-shadow:0 0 6px var(--fdb-red)}

/* Right controls */
.fdb-controls{margin-left:auto;display:flex;align-items:center;padding:0 6px;border-left:1px solid #2d2d4a;flex-shrink:0;height:100%}
.fdb-ctrl-btn{width:24px;height:24px;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#a0a4b8;margin-left:4px}
.fdb-ctrl-btn:hover{color:#e0e0f0;background:#25254a}
.fdb-ctrl-btn.fdb-close-btn:hover{color:var(--fdb-red);background:rgba(243,139,168,.15)}
.fdb-ctrl-btn svg{width:14px;height:14px}

/* ── Panel Area ─────────────────────────────────────────────────────────── */
#<?= $id?>_panels{display:none;background:var(--fdb-bg-panel);border-top:1px solid var(--fdb-border);height:340px;overflow:hidden;position:relative;box-shadow:0 -4px 20px rgba(0,0,0,.4)}

/* Resize */
.fdb-resize{position:absolute;top:0;left:0;right:0;height:4px;cursor:ns-resize;z-index:10;transition:background .15s}
.fdb-resize:hover,.fdb-resize:active{background:var(--fdb-accent)}

/* Content Wrapper */
#<?= $id?>_pcontent{height:100%;width:100%;display:flex;flex-direction:column}


/* Panel wrapper */
.fdb-panel{display:none;height:100%;flex-direction:column}
.fdb-panel.active{display:flex}

/* Panel header */
.fdb-ph{display:flex;align-items:center;gap:10px;padding:6px 14px;border-bottom:1px solid var(--fdb-border);flex-shrink:0;background:var(--fdb-bg-alt);min-height:32px}
.fdb-ph-icon{color:var(--fdb-accent);display:flex;align-items:center}
.fdb-ph-icon svg{width:14px;height:14px}
.fdb-ph-title{font-size:11px;font-weight:600;color:var(--fdb-accent);letter-spacing:.3px}
.fdb-ph-info{font-size:11px;color:var(--fdb-text-muted);margin-left:auto}

/* Body */
.fdb-body{overflow-y:auto;overflow-x:hidden;flex:1;scroll-behavior:smooth}
.fdb-body::-webkit-scrollbar{width:5px}
.fdb-body::-webkit-scrollbar-track{background:transparent}
.fdb-body::-webkit-scrollbar-thumb{background:var(--fdb-border);border-radius:3px}

/* ── Table ──────────────────────────────────────────────────────────────── */
.fdb-t{width:100%;border-collapse:collapse;font-size:11.5px}
.fdb-t th{text-align:left;color:var(--fdb-text-dim);padding:5px 12px;border-bottom:1px solid var(--fdb-border);font-size:10px;text-transform:uppercase;letter-spacing:.5px;position:sticky;top:0;background:var(--fdb-bg-panel);z-index:1;font-weight:600}
.fdb-t td{padding:4px 12px;border-bottom:1px solid rgba(49,50,68,.4);color:var(--fdb-text);vertical-align:top;word-break:break-word}
.fdb-t tr:hover td{background:rgba(203,166,247,.03)}
.fdb-t tr.fdb-slow td{background:rgba(243,139,168,.05)}

/* ── Tags / Badges ──────────────────────────────────────────────────────── */
.fdb-tag{display:inline-flex;align-items:center;padding:1px 6px;border-radius:3px;font-size:10px;font-weight:600;letter-spacing:.2px}
.fdb-tag-method{background:rgba(137,180,250,.12);color:var(--fdb-blue)}
.fdb-tag-err{background:rgba(243,139,168,.12);color:var(--fdb-red)}
.fdb-tag-warn{background:rgba(249,226,175,.12);color:var(--fdb-yellow)}
.fdb-tag-info{background:rgba(137,180,250,.12);color:var(--fdb-blue)}
.fdb-tag-ok{background:rgba(166,227,161,.12);color:var(--fdb-green)}
.fdb-tag-dbg{background:rgba(108,112,134,.12);color:var(--fdb-text-muted)}
.fdb-tag-mw{background:rgba(203,166,247,.1);color:var(--fdb-accent);margin:1px 2px}

/* ── SQL ────────────────────────────────────────────────────────────────── */
.fdb-sql{font-family:var(--fdb-mono);font-size:11px;color:var(--fdb-sky);line-height:1.6;word-break:break-all}
.fdb-sql b{color:var(--fdb-red);font-weight:400}
.fdb-sql i{color:var(--fdb-peach);font-style:normal}
.fdb-caller{font-size:10px;color:var(--fdb-text-dim);margin-top:2px}
.fdb-caller em{color:var(--fdb-accent);font-style:normal;margin-right:4px}
.fdb-copy{font-size:9px;padding:1px 5px;background:var(--fdb-bg);border:1px solid var(--fdb-border);color:var(--fdb-text-muted);border-radius:3px;cursor:pointer;margin-left:6px;transition:all .12s}
.fdb-copy:hover{color:var(--fdb-text);border-color:var(--fdb-accent)}
.fdb-ms{font-size:10.5px;font-weight:600;text-align:right;font-family:var(--fdb-mono)}

/* ── Key-Value ──────────────────────────────────────────────────────────── */
.fdb-kv-row{display:grid;grid-template-columns:160px 1fr;border-bottom:1px solid rgba(49,50,68,.4);font-size:11.5px}
.fdb-kv-row:hover{background:rgba(203,166,247,.03)}
.fdb-kv-k{color:var(--fdb-text-muted);padding:5px 12px;font-weight:500}
.fdb-kv-v{color:var(--fdb-text);padding:5px 12px;word-break:break-all;font-family:var(--fdb-mono);font-size:11px}

/* ── Timeline ───────────────────────────────────────────────────────────── */
.fdb-tl-row{display:grid;grid-template-columns:140px 1fr 55px;gap:8px;align-items:center;padding:4px 12px;border-bottom:1px solid rgba(49,50,68,.3)}
.fdb-tl-row:hover{background:rgba(203,166,247,.03)}
.fdb-tl-lbl{color:var(--fdb-text-muted);font-size:11px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.fdb-bar-wrap{background:var(--fdb-bg);border-radius:2px;height:8px;position:relative;overflow:hidden}
.fdb-bar-fill{height:100%;border-radius:2px;min-width:2px;transition:width .3s ease}

/* ── View accordion ─────────────────────────────────────────────────────── */
.fdb-vw-row{border-bottom:1px solid rgba(49,50,68,.4)}
.fdb-vw-hd{display:flex;align-items:center;gap:8px;padding:5px 12px;cursor:pointer;color:var(--fdb-text);transition:background .12s;font-size:11.5px}
.fdb-vw-hd:hover{background:rgba(203,166,247,.03)}
.fdb-vw-hd svg{transition:transform .15s;flex-shrink:0;color:var(--fdb-text-dim)}
.fdb-vw-tpl{color:var(--fdb-blue);font-family:var(--fdb-mono);font-size:11px}
.fdb-vw-body{display:none;padding:4px 12px 8px 28px;background:rgba(17,17,27,.6)}
.fdb-vw-body.open{display:block}
.fdb-vd-t{width:100%;font-size:10.5px;border-collapse:collapse}
.fdb-vd-t td{padding:2px 6px;border-bottom:1px solid rgba(49,50,68,.3);vertical-align:top}

/* ── Auth card ──────────────────────────────────────────────────────────── */
.fdb-auth{margin:12px;background:var(--fdb-bg);border:1px solid var(--fdb-border);border-radius:8px;padding:14px;display:flex;align-items:center;gap:12px}
.fdb-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--fdb-accent),var(--fdb-pink));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;flex-shrink:0}

/* ── Model pills ────────────────────────────────────────────────────────── */
.fdb-pill{display:inline-flex;align-items:center;gap:4px;background:var(--fdb-bg);border:1px solid var(--fdb-border);border-radius:4px;padding:2px 7px;font-size:10.5px;margin:2px}
.fdb-pill-cnt{background:var(--fdb-accent);color:var(--fdb-bg-panel);border-radius:3px;padding:0 5px;font-weight:700;font-size:9px}

/* ── Exception ──────────────────────────────────────────────────────────── */
.fdb-exc{margin:8px 12px;background:rgba(243,139,168,.05);border:1px solid rgba(243,139,168,.2);border-radius:6px;padding:10px 12px}
.fdb-exc-cls{color:var(--fdb-red);font-weight:700;font-size:12px}
.fdb-exc-msg{color:var(--fdb-yellow);font-size:11px;margin-top:3px}
.fdb-exc-loc{color:var(--fdb-text-dim);font-size:10px;margin-top:2px;font-family:var(--fdb-mono)}
.fdb-exc-tr{margin-top:6px;font-size:10px;color:var(--fdb-text-dim);white-space:pre-wrap;max-height:100px;overflow-y:auto;background:var(--fdb-bg-panel);padding:6px;border-radius:4px;font-family:var(--fdb-mono);border:1px solid var(--fdb-border)}

/* ── Empty state ────────────────────────────────────────────────────────── */
.fdb-empty{color:var(--fdb-text-dim);padding:24px 12px;font-size:11px;text-align:center}

/* ── Loading ────────────────────────────────────────────────────────────── */
.fdb-loading{display:flex;align-items:center;justify-content:center;padding:40px;color:var(--fdb-text-dim);gap:8px;font-size:12px}
.fdb-spinner{width:14px;height:14px;border:2px solid var(--fdb-border);border-top-color:var(--fdb-accent);border-radius:50%;animation:fdb-spin .5s linear infinite}
@keyframes fdb-spin{to{transform:rotate(360deg)}}

/* ── Search / Filter ──────────────────────────────────────────────────── */
.fdb-search{display:flex;align-items:center;gap:6px;padding:4px 12px;border-bottom:1px solid var(--fdb-border);flex-shrink:0;background:var(--fdb-bg-alt)}
.fdb-search input{flex:1;background:var(--fdb-bg);border:1px solid var(--fdb-border);color:var(--fdb-text);padding:3px 8px;border-radius:4px;font-size:11px;font-family:var(--fdb-font);outline:none}
.fdb-search input:focus{border-color:var(--fdb-accent);box-shadow:0 0 0 2px rgba(203,166,247,.15)}
.fdb-search input::placeholder{color:var(--fdb-text-dim)}
.fdb-search-count{font-size:10px;color:var(--fdb-text-dim);white-space:nowrap}

/* ── N+1 Warning ──────────────────────────────────────────────────────── */
.fdb-n1-warn{margin:8px 12px;background:rgba(249,226,175,.08);border:1px solid rgba(249,226,175,.25);border-radius:6px;padding:8px 12px;display:flex;align-items:center;gap:8px}
.fdb-n1-icon{color:var(--fdb-yellow);flex-shrink:0}
.fdb-n1-text{font-size:11px;color:var(--fdb-yellow)}
.fdb-n1-text b{color:var(--fdb-red)}

/* ── Middleware timing ────────────────────────────────────────────────── */
.fdb-mw-bar{display:flex;align-items:center;gap:8px;padding:3px 12px;border-bottom:1px solid rgba(49,50,68,.3)}
.fdb-mw-bar:hover{background:rgba(203,166,247,.03)}
.fdb-mw-name{color:var(--fdb-accent);font-size:11px;width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:var(--fdb-mono)}
.fdb-mw-barwrap{flex:1;background:var(--fdb-bg);border-radius:2px;height:6px;overflow:hidden}
.fdb-mw-fill{height:100%;border-radius:2px;min-width:2px;background:var(--fdb-accent)}
.fdb-mw-ms{font-size:10px;color:var(--fdb-text-muted);font-family:var(--fdb-mono);width:55px;text-align:right}

/* ── Theme toggle & Export ───────────────────────────────────────────── */
.fdb-ctrl-btn.fdb-theme-btn:hover{color:var(--fdb-yellow)}
.fdb-ctrl-btn.fdb-export-btn:hover{color:var(--fdb-green)}

/* ── Light Theme ─────────────────────────────────────────────────────── */
#<?= $id?>.fdb-light{--fdb-bg:#f8f9fa;--fdb-bg-alt:#e9ecef;--fdb-bg-panel:#ffffff;--fdb-border:#dee2e6;--fdb-border-light:#ced4da;--fdb-text:#212529;--fdb-text-muted:#495057;--fdb-text-dim:#868e96;--fdb-accent:#7c3aed;--fdb-accent-bg:rgba(124,58,237,.08)}
#<?= $id?>.fdb-light #<?= $id?>_bar{background:#f0f0f5;border-top:1px solid #d0d0e0;box-shadow:0 -2px 8px rgba(0,0,0,.12)}
#<?= $id?>.fdb-light .fdb-logo{background:#e4e4ee;border-right-color:#d0d0e0}
#<?= $id?>.fdb-light .fdb-logo:hover{background:#d8d8e8}
#<?= $id?>.fdb-light .fdb-tab{color:#495057}
#<?= $id?>.fdb-light .fdb-tab:hover{background:#e0e0ee;color:#212529}
#<?= $id?>.fdb-light .fdb-tab.active{background:rgba(124,58,237,.12);color:#7c3aed}
#<?= $id?>.fdb-light .fdb-tab-lbl{color:#868e96}
#<?= $id?>.fdb-light .fdb-controls{border-left-color:#d0d0e0}
#<?= $id?>.fdb-light .fdb-ctrl-btn{color:#495057}
#<?= $id?>.fdb-light .fdb-ctrl-btn:hover{color:#212529;background:#e0e0ee}

/* ── Minimized state ────────────────────────────────────────────────────── */
#<?= $id?>.fdb-minimized #<?= $id?>_bar{display:none}
#<?= $id?>.fdb-minimized #<?= $id?>_panels{display:none!important}
#<?= $id?>_mini{display:none;position:fixed;bottom:4px;right:4px;z-index:2147483647;width:28px;height:28px;border-radius:50%;background:var(--fdb-accent);color:var(--fdb-bg-panel);cursor:pointer;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.4);transition:transform .15s;font-size:12px;border:none}
#<?= $id?>_mini:hover{transform:scale(1.15)}
#<?= $id?>.fdb-minimized #<?= $id?>_mini{display:flex}

/* ── Mobile responsive ──────────────────────────────────────────────── */
@media(max-width:768px){
  #<?= $id?>_bar{height:auto;flex-wrap:wrap;padding:2px 0}
  .fdb-tab{padding:3px 6px;font-size:10px;flex:0 0 auto}
  .fdb-tab-lbl{display:none}
  .fdb-logo img{height:28px}
  #<?= $id?>_panels{height:260px}
  .fdb-kv-row{grid-template-columns:100px 1fr}
}
</style>

<div id="<?= $id?>" data-profiler-token="<?= htmlspecialchars($token)?>" data-profiler-url="<?= htmlspecialchars($profilerUrl)?>">

  <!-- ═══════════ PANELS ═══════════ -->
  <div id="<?= $id?>_panels">
    <div class="fdb-resize" id="<?= $id?>_resize"></div>
    <div id="<?= $id?>_pcontent">
      <div class="fdb-loading" id="<?= $id?>_loading">
        <div class="fdb-spinner"></div>
        <span>Profiler verisi yükleniyor…</span>
      </div>
    </div>
  </div>

  <!-- ═══════════ TOOLBAR BAR ═══════════ -->
  <div id="<?= $id?>_bar">

    <!-- Logo -->
    <!-- Logo -->
    <div class="fdb-logo" id="<?= $id?>_logo" title="Umay Profiler">
      <img src="/umay.png" alt="Umay" width="20" height="20">
    </div>

    <?php
    // ── Tab definitions ───────────────
    // ── Tab tanımları ───────────────
    // [id, icon_svg, value, label, color, title, has_badge]
    $tabs = [];

// Timeline // Zaman Çizelgesi
$tabs[] = ['timeline',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    $totalMs.'ms', null, $timeColor, 'Request süresi', false];

// Route // Rota
$tabs[] = ['route',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 17l5-5-5-5M6 17l5-5-5-5"/></svg>',
    ($route['method'] ?? 'GET').' <span class="fdb-tab-lbl">'.htmlspecialchars(str_limit($route['uri'] ?? '/', 25, '…')).'</span>',
    null, $stColor, ($route['controller'] ?? '-').'@'.($route['action'] ?? '-'), false];

// Queries // Sorgular
$tabs[] = ['queries',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
    (string) $qCount, $qTime.'ms', $qColor, $qCount.' sorgu · '.$qTime.'ms', $qCount > 20];

// Views // Görünümler
$tabs[] = ['views',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
    (string) $vCount, 'views', '#8b949e', $vCount.' şablon', false];

// Events // Olaylar
$tabs[] = ['events',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
    (string) $evCount, null, '#8b949e', $evCount.' event', false];

// Cache // Önbellek
$tabs[] = ['cache',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><circle cx="6" cy="6" r="1" fill="currentColor"/><circle cx="6" cy="18" r="1" fill="currentColor"/></svg>',
    $cHits.'/'.$cTotal, null, $cHits > 0 ? '#a6e3a1' : '#8b949e', 'Cache '.$cHits.' hit / '.$cMisses.' miss', false];

// Mails // E-postalar
if ($mCount > 0) {
    $tabs[] = ['mails',
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        (string) $mCount, 'mail', '#f9e2af', $mCount.' mail gönderildi', true];
}

// Logs // Loglar
$tabs[] = ['logs',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
    (string) $lCount, null, $lColor, $lCount.' log kayıt', $hasErr];

// Auth // Kimlik Doğrulama
$tabs[] = ['auth',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    $authUser ? htmlspecialchars(str_limit($authUser['name'], 10, '…')) : 'guest',
    null, $authUser ? '#a6e3a1' : '#6c7086', $authUser ? 'Authenticated' : 'Guest', false];

// Session // Oturum
$tabs[] = ['session',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
    (string) count($session), null, '#8b949e', count($session).' session key', false];

// Exceptions // İstisnalar
if ($eCount > 0) {
    $tabs[] = ['exceptions',
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        (string) $eCount, null, '#f38ba8', $eCount.' exception!', true];
}

// Request // İstek
$tabs[] = ['request',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
    ($_SERVER['REQUEST_METHOD'] ?? 'GET'), null, '#89dceb', 'Request / Response bilgisi', false];

// PHP // PHP Bilgisi
$tabs[] = ['php',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
    'PHP '.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION,
    round($memory, 1).'MB', $memColor, 'PHP '.PHP_VERSION.' · '.round($memory, 1).' MB', false];

// History // Geçmiş
$tabs[] = ['history',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>',
    'Geçmiş', null, '#8b949e', 'Geçmiş request profilleri', false];

foreach ($tabs as [$tid, $icon, $val, $lbl, $clr, $ttl, $badge]) { ?>
    <div class="fdb-tab" title="<?= htmlspecialchars($ttl)?>" data-panel="<?= $tid?>">
      <?= $icon?>
      <span class="fdb-tab-val" style="color:<?= $clr?>"><?= $val?></span>
      <?php if ($lbl) { ?><span class="fdb-tab-lbl"><?= $lbl?></span><?php } ?>
      <?php if ($badge) { ?><span class="fdb-badge"></span><?php } ?>
    </div>
    <?php } ?>

    <!-- Right controls -->
    <!-- Sağ kontroller -->
    <div class="fdb-controls">
      <div class="fdb-ctrl-btn fdb-export-btn" id="<?= $id?>_btn_exp" title="JSON Export">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      </div>
      <div class="fdb-ctrl-btn fdb-theme-btn" id="<?= $id?>_btn_theme" title="Tema değiştir">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </div>
      <div class="fdb-ctrl-btn" id="<?= $id?>_btn_min" title="Küçült">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
      </div>
      <div class="fdb-ctrl-btn fdb-close-btn" id="<?= $id?>_btn_cls" title="Kapat (Ctrl+Shift+D)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </div>
    </div>
  </div>

  <!-- Minimized FAB -->
  <!-- Küçültülmüş FAB -->
  <button id="<?= $id?>_mini" title="Debugbar'ı aç">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
  </button>
</div>

<script<?= $nonceAttr?>>
(function(){
  var id='<?= $id?>',cur=null,data=null;
  var url='<?= htmlspecialchars($profilerUrl)?>';

  var FDB={
    toggle:function(name){
      var panels=document.getElementById(id+'_panels');
      var tabs=document.querySelectorAll('#'+id+' .fdb-tab');
      if(cur===name&&panels.style.display!=='none'){
        panels.style.display='none';cur=null;
        tabs.forEach(function(t){t.classList.remove('active')});return;
      }
      panels.style.display='block';cur=name;
      tabs.forEach(function(t){t.classList.toggle('active',t.getAttribute('data-panel')===name)});
      if(!data){this._load(function(){FDB._render(name)});}else{this._render(name);}
    },

    _load:function(cb){
      var ld=document.getElementById(id+'_loading');
      if(ld)ld.style.display='flex';
      fetch(url,{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){return r.json()})
        .then(function(d){data=d;if(ld)ld.style.display='none';if(cb)cb()})
        .catch(function(e){if(ld)ld.innerHTML='<span style="color:var(--fdb-red)">Yüklenemedi: '+e.message+'</span>'});
    },

    _render:function(name){
      if(!data)return;
      var c=document.getElementById(id+'_pcontent');
      try {
        var fn=this['_p_'+name];
        c.innerHTML=fn?fn(data):'<div class="fdb-empty">Panel bulunamadı: '+esc(name)+'</div>';
      } catch(e) {
        c.innerHTML='<div class="fdb-panel active"><div class="fdb-ph"><span class="fdb-ph-title">Render Error ('+esc(name)+')</span></div><div class="fdb-body" style="padding:15px;color:red;font-family:monospace;white-space:pre-wrap">'+esc(e.stack||e.message)+'</div></div>';
      }
    },

    // ── PANELS ──────────────────────────────────────────────────────────
    _p_timeline:function(d){
      var m=d.timeline||[],s=d._summary||{};
      var h=PH('Timeline','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',m.length+' ölçüm · <b style="color:'+cMs(s.total_ms)+'">'+s.total_ms+' ms</b>');
      if(!m.length)return h+'<div class="fdb-body"><p class="fdb-empty">Ölçüm kaydı yok.</p></div></div>';
      h+='<div class="fdb-body">';
      var mx=0;m.forEach(function(x){if(x.end>mx)mx=x.end});if(mx<=0)mx=0.001;
      m.forEach(function(x){
        var l=mx>0?(x.start/mx*100):0,w=Math.max(mx>0?((x.end-x.start)/mx*100):1,0.5);
        var c=x.ms>100?'var(--fdb-red)':(x.ms>30?'var(--fdb-yellow)':'var(--fdb-accent)');
        h+='<div class="fdb-tl-row"><span class="fdb-tl-lbl" title="'+esc(x.label)+'">'+esc(x.label)+'</span><div class="fdb-bar-wrap"><div class="fdb-bar-fill" style="margin-left:'+l.toFixed(1)+'%;width:'+w.toFixed(1)+'%;background:'+c+'"></div></div><span class="fdb-ms" style="color:'+c+'">'+x.ms+'ms</span></div>';
      });
      return h+'</div></div>';
    },

    _p_route:function(d){
      var r=d.route||{},s=d._summary||{},mwt=d.middleware_timing||[];
      var sc=s.status>=500?'var(--fdb-red)':(s.status>=400?'var(--fdb-yellow)':'var(--fdb-green)');
      var h=PH('Route','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 17l5-5-5-5M6 17l5-5-5-5"/></svg>',esc(r.controller||'-')+'@'+esc(r.action||'-'));
      h+='<div class="fdb-body"><div>';
      var kv=[
        ['Method','<span class="fdb-tag fdb-tag-method">'+(r.method||'GET')+'</span>'],
        ['URI',esc(r.uri||'/')],
        ['Route Name',esc(r.name||'-')],
        ['Controller','<span style="color:var(--fdb-blue)">'+esc(r.controller||'-')+'</span>'],
        ['Action',esc(r.action||'-')],
        ['Middleware',(r.middleware||[]).map(function(m){return '<span class="fdb-tag fdb-tag-mw">'+esc(m)+'</span>'}).join(' ')||'—'],
        ['Status','<span style="color:'+sc+';font-weight:700">'+s.status+'</span>'],
        ['IP',esc(d.__meta?d.__meta.ip:'-')]
      ];
      kv.forEach(function(p){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+p[0]+'</div><div class="fdb-kv-v">'+p[1]+'</div></div>'});
      if(mwt.length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">Middleware Timing</div>';
        var mwMax=0;mwt.forEach(function(m){if(m.ms>mwMax)mwMax=m.ms});if(mwMax<=0)mwMax=1;
        mwt.forEach(function(m){
          var w=Math.max((m.ms/mwMax)*100,1);
          var c=m.ms>50?'var(--fdb-red)':(m.ms>10?'var(--fdb-yellow)':'var(--fdb-accent)');
          h+='<div class="fdb-mw-bar"><span class="fdb-mw-name">'+esc(m.name)+'</span><div class="fdb-mw-barwrap"><div class="fdb-mw-fill" style="width:'+w.toFixed(1)+'%;background:'+c+'"></div></div><span class="fdb-mw-ms" style="color:'+c+'">'+m.ms.toFixed(2)+'ms</span></div>';
        });
      }
      return h+'</div></div></div>';
    },

    _p_queries:function(d){
      var q=d.queries||[],ms=d.model_stats||{},n1=d.n_plus_one||[];
      var qt=0;q.forEach(function(x){qt+=x.time||0});qt=qt.toFixed(2);
      var qc=q.length>20?'var(--fdb-red)':(q.length>10?'var(--fdb-yellow)':'var(--fdb-green)');
      var pills='';Object.keys(ms).forEach(function(m){pills+='<span class="fdb-pill">'+esc(m)+'<span class="fdb-pill-cnt">'+ms[m]+'</span></span>'});
      var n1b=n1.length?' <span class="fdb-tag fdb-tag-warn" style="margin-left:6px">\u26a0 N+1: '+n1.length+'</span>':'';
      var h=PH('Queries','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>','<span style="color:'+qc+'">'+q.length+'</span> sorgu \u00b7 '+qt+'ms'+pills+n1b);
      if(n1.length){n1.forEach(function(w){h+='<div class="fdb-n1-warn"><span class="fdb-n1-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span><span class="fdb-n1-text">N+1 Tespit: <b>'+esc(w.model)+'</b> modeli i\u00e7in <b>'+w.count+'x</b> tekrarl\u0131 sorgu</span></div>';});}
      h+='<div class="fdb-search"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input type="text" class="fdb-search-input" placeholder="SQL ara..."><span class="fdb-search-count"></span></div>';
      h+='<div class="fdb-body">';
      if(!q.length)return h+'<p class="fdb-empty">Bu request\'te sorgu çalışmadı.</p></div></div>';
      h+='<table class="fdb-t"><tr><th style="width:28px">#</th><th>SQL</th><th style="width:65px">Süre</th></tr>';
      q.forEach(function(x,i){
        var qm=(x.time||0).toFixed(2),qmc=qm>100?'var(--fdb-red)':(qm>20?'var(--fdb-yellow)':'var(--fdb-green)');
        var sql_raw = x.sql||'';
        var sql=hlSql(intSql(sql_raw,x.bindings||[]));
        var slow=parseFloat(qm)>50?' fdb-slow':'';
        h+='<tr class="'+slow+'"><td style="color:var(--fdb-text-dim);font-size:10px">'+(i+1)+'</td><td><span class="fdb-sql">'+sql+'</span><button class="fdb-copy" data-fdb-copy="'+esc(JSON.stringify(sql_raw))+'">copy</button>';
        if(x.caller||x.model)h+='<div class="fdb-caller">'+(x.model?'<em>'+esc(x.model)+'</em>':'')+(x.caller?'→ '+esc(x.caller):'')+'</div>';
        h+='</td><td class="fdb-ms" style="color:'+qmc+'">'+qm+'ms</td></tr>';
      });
      return h+'</table></div></div>';
    },

    _p_views:function(d){
      var v=d.views||[];
      var h=PH('Views','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',v.length+' şablon');
      h+='<div class="fdb-body">';
      if(!v.length)return h+'<p class="fdb-empty">View render edilmedi.</p></div></div>';
      v.forEach(function(x,i){
        var dk=x.data?Object.keys(x.data):[];
        h+='<div class="fdb-vw-row"><div class="fdb-vw-hd" data-fdb-vt="'+i+'"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.5" id="'+id+'_vic_'+i+'" style="transform:rotate(-90deg)"><polyline points="2,3 5,7 8,3"/></svg><span class="fdb-vw-tpl">'+esc(x.template)+'.php</span><span style="color:var(--fdb-text-dim);font-size:10px;margin-left:auto">'+esc(x.time||'')+'</span>';
        if(dk.length)h+='<span class="fdb-tag fdb-tag-dbg" style="margin-left:6px">'+dk.length+' var</span>';
        h+='</div>';
        if(dk.length){
          h+='<div class="fdb-vw-body" id="'+id+'_vb_'+i+'"><table class="fdb-vd-t">';
          dk.forEach(function(k){var vv=x.data[k]||{};h+='<tr><td style="color:var(--fdb-accent);width:120px">$'+esc(k)+'</td><td style="color:var(--fdb-text-dim);width:70px">'+esc(vv.type||'')+'</td><td style="color:var(--fdb-text);font-family:var(--fdb-mono)">'+esc(vv.preview||'')+'</td></tr>'});
          h+='</table></div>';
        }
        h+='</div>';
      });
      return h+'</div></div>';
    },

    _p_events:function(d){
      var ev=d.events||[];
      var h=PH('Events','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',ev.length+' event');
      h+='<div class="fdb-body">';
      if(!ev.length)return h+'<p class="fdb-empty">Event tetiklenmedi.</p></div></div>';
      h+='<table class="fdb-t"><tr><th>Event</th><th>Payload</th><th style="width:50px">Saat</th></tr>';
      ev.forEach(function(x){h+='<tr><td style="color:var(--fdb-accent)">'+esc(x.class||'')+'</td><td style="color:var(--fdb-text-muted);font-family:var(--fdb-mono);font-size:10.5px">'+esc(JSON.stringify(x.payload||{}))+'</td><td style="color:var(--fdb-text-dim)">'+esc(x.time||'')+'</td></tr>'});
      return h+'</table></div></div>';
    },

    _p_cache:function(d){
      var c=d.cache||[];
      var hi=c.filter(function(x){return x.hit}).length;
      var mi=c.filter(function(x){return !x.hit&&x.type==='get'}).length;
      var h=PH('Cache','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/></svg>',c.length+' işlem · <span style="color:var(--fdb-green)">'+hi+' hit</span> · <span style="color:var(--fdb-red)">'+mi+' miss</span>');
      h+='<div class="fdb-body">';
      if(!c.length)return h+'<p class="fdb-empty">Cache işlemi yapılmadı.</p></div></div>';
      h+='<table class="fdb-t"><tr><th style="width:60px">Tür</th><th>Anahtar</th><th style="width:60px">Sonuç</th><th style="width:65px">Saat</th></tr>';
      c.forEach(function(x){
        var tc='fdb-tag-dbg';if(x.type==='set')tc='fdb-tag-ok';if(x.type==='forget')tc='fdb-tag-err';
        h+='<tr><td><span class="fdb-tag '+tc+'">'+(x.type||'').toUpperCase()+'</span></td><td style="color:var(--fdb-sky);font-family:var(--fdb-mono);font-size:11px">'+esc(x.key)+'</td><td>'+(x.type==='get'?'<span style="color:var(--fdb-'+(x.hit?'green':'red')+');">'+(x.hit?'HIT':'MISS')+'</span>':'—')+'</td><td style="color:var(--fdb-text-dim)">'+esc(x.time||'')+'</td></tr>'});
      return h+'</table></div></div>';
    },

    _p_mails:function(d){
      var m=d.mails||[];
      var h=PH('Mails','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',m.length+' mail');
      h+='<div class="fdb-body">';
      if(!m.length)return h+'<p class="fdb-empty">Mail gönderilmedi.</p></div></div>';
      h+='<table class="fdb-t"><tr><th>Sınıf</th><th>Konu</th><th>Alıcı</th><th style="width:50px">Driver</th></tr>';
      m.forEach(function(x){h+='<tr><td style="color:var(--fdb-accent)">'+esc(x.class||'-')+'</td><td>'+esc(x.subject||'-')+'</td><td style="color:var(--fdb-blue)">'+esc(x.to||'-')+'</td><td><span class="fdb-tag fdb-tag-info">'+esc(x.driver||'log')+'</span></td></tr>'});
      return h+'</table></div></div>';
    },

    _p_logs:function(d){
      var l=d.logs||[];
      var h=PH('Messages','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',l.length+' kayıt');
      h+='<div class="fdb-search"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input type="text" class="fdb-search-input" placeholder="Log ara..."><span class="fdb-search-count"></span></div>';
      h+='<div class="fdb-body">';
      if(!l.length)return h+'<p class="fdb-empty">Log kaydı yok.</p></div></div>';
      h+='<table class="fdb-t"><tr><th style="width:50px">Saat</th><th style="width:65px">Seviye</th><th>Mesaj</th><th style="width:180px">Bağlam</th></tr>';
      l.forEach(function(x){
        var cls=x.level==='ERROR'?'fdb-tag-err':(x.level==='WARNING'?'fdb-tag-warn':(x.level==='INFO'?'fdb-tag-info':'fdb-tag-dbg'));
        h+='<tr><td style="color:var(--fdb-text-dim)">'+esc(x.time||'')+'</td><td><span class="fdb-tag '+cls+'">'+x.level+'</span></td><td>'+esc(x.message||'')+'</td><td style="color:var(--fdb-text-dim);font-family:var(--fdb-mono);font-size:10px">'+esc(JSON.stringify(x.context||{}))+'</td></tr>'});
      return h+'</table></div></div>';
    },

    _p_auth:function(d){
      var u=d.auth,role=u?u.role||'member':'guest';
      var h=PH('Auth','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',u?'Authenticated':'Guest');
      h+='<div class="fdb-body"><div class="fdb-auth"><div class="fdb-avatar"'+(u?'':' style="background:var(--fdb-border);color:var(--fdb-text-dim)"')+'>'+esc((u?u.name:'G').charAt(0).toUpperCase())+'</div><div style="flex:1"><div style="font-weight:600;font-size:12px">'+(u?esc(u.name):'Misafir')+'</div><div style="color:var(--fdb-text-dim);font-size:11px;margin-top:2px">'+(u?esc(u.email):'Giriş yapılmamış')+'</div></div><span class="fdb-tag fdb-tag-'+(role==='admin'?'info':(role==='guest'?'dbg':'ok'))+'">'+role+'</span></div>';
      if(u){var kv=[['User ID',u.id],['Ad Soyad',u.name],['E-posta',u.email],['Rol',role]];kv.forEach(function(p){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+p[0]+'</div><div class="fdb-kv-v">'+esc(String(p[1]))+'</div></div>'})};
      return h+'</div></div>';
    },

    _p_session:function(d){
      var s=d.session||{},k=Object.keys(s);
      var h=PH('Session','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',k.length+' anahtar');
      h+='<div class="fdb-body">';
      if(!k.length)return h+'<p class="fdb-empty">Session boş.</p></div></div>';
      h+='<table class="fdb-t"><tr><th style="width:150px">Anahtar</th><th style="width:60px">Tür</th><th>Değer</th></tr>';
      k.forEach(function(key){
        var v=s[key],t=typeof v==='object'?(Array.isArray(v)?'array':'object'):typeof v;
        h+='<tr><td style="color:var(--fdb-accent);font-family:var(--fdb-mono)">'+esc(key)+'</td><td style="color:var(--fdb-text-dim)">'+t+'</td><td style="font-family:var(--fdb-mono);font-size:11px">'+esc(typeof v==='object'?JSON.stringify(v):String(v))+'</td></tr>'});
      return h+'</table></div></div>';
    },

    _p_exceptions:function(d){
      var ex=d.exceptions||[];
      var h=PH('Exceptions','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>','<span style="color:var(--fdb-red)">'+ex.length+' exception</span>');
      h+='<div class="fdb-body">';
      if(!ex.length)return h+'<p class="fdb-empty">Exception yok.</p></div></div>';
      ex.forEach(function(x){h+='<div class="fdb-exc"><div class="fdb-exc-cls">'+esc(x.class||'')+'</div><div class="fdb-exc-msg">'+esc(x.message||'')+'</div><div class="fdb-exc-loc">'+esc(x.file||'')+':'+x.line+'</div><div class="fdb-exc-tr">'+esc(x.trace||'')+'</div></div>'});
      return h+'</div></div>';
    },

    _p_php:function(d){
      var p=d.php_info||{},s=d._summary||{};
      var mc=s.memory_mb>32?'var(--fdb-red)':(s.memory_mb>16?'var(--fdb-yellow)':'var(--fdb-green)');
      var ec=p.environment==='production'?'fdb-tag-err':'fdb-tag-ok';
      var h=PH('PHP / Environment','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>','PHP '+esc(p.version||''));
      h+='<div class="fdb-body"><div>';
      [['PHP Version',p.version],['Environment','<span class="fdb-tag '+ec+'">'+esc(p.environment||'local')+'</span>'],['Peak Memory','<span style="color:'+mc+'">'+s.memory_mb+' MB</span>'],['Timezone',p.timezone],['OPcache',p.opcache_enabled?'<span style="color:var(--fdb-green)">Active</span>':'<span style="color:var(--fdb-text-dim)">Inactive</span>'],['HTTPS',p.https?'<span style="color:var(--fdb-green)">Yes</span>':'<span style="color:var(--fdb-text-dim)">No</span>'],['Server',p.server_software],['Session Driver',p.session_driver],['Extensions',(p.extensions||[]).join(', ')]].forEach(function(pa){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+pa[0]+'</div><div class="fdb-kv-v">'+(typeof pa[1]==='string'&&pa[1].indexOf('<')===0?pa[1]:esc(String(pa[1]||'-')))+'</div></div>'});
      return h+'</div></div></div>';
    },

    // ── Request / Response panel ────────────────────────────────────────
    _p_request:function(d){
      var r=d.request||{},s=d._summary||{};
      var h=PH('Request / Response','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>','<span class="fdb-tag fdb-tag-method">'+(r.method||'GET')+'</span> '+esc(r.uri||'/'));
      h+='<div class="fdb-body"><div>';
      // General
      [['Method',r.method],['URL',r.url],['IP',r.ip],['Content-Type',r.content_type],['Status','<span style="color:'+(s.status>=500?'var(--fdb-red)':(s.status>=400?'var(--fdb-yellow)':'var(--fdb-green)'))+'">'+s.status+'</span>']].forEach(function(p){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+p[0]+'</div><div class="fdb-kv-v">'+(typeof p[1]==='string'&&p[1].indexOf('<')===0?p[1]:esc(String(p[1]||'-')))+'</div></div>'});
      // Request Headers
      var rh=r.request_headers||{};if(Object.keys(rh).length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">Request Headers</div>';
        Object.keys(rh).forEach(function(k){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+esc(k)+'</div><div class="fdb-kv-v">'+esc(rh[k])+'</div></div>'});
      }
      // Response Headers
      var rsh=r.response_headers||{};if(Object.keys(rsh).length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">Response Headers</div>';
        Object.keys(rsh).forEach(function(k){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+esc(k)+'</div><div class="fdb-kv-v">'+esc(rsh[k])+'</div></div>'});
      }
      // Query Params
      var qp=r.query_params||{};if(Object.keys(qp).length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">Query Parameters</div>';
        Object.keys(qp).forEach(function(k){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+esc(k)+'</div><div class="fdb-kv-v">'+esc(typeof qp[k]==='object'?JSON.stringify(qp[k]):String(qp[k]))+'</div></div>'});
      }
      // POST Data
      var pd=r.post_data||{};if(Object.keys(pd).length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">POST Data</div>';
        Object.keys(pd).forEach(function(k){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+esc(k)+'</div><div class="fdb-kv-v">'+esc(typeof pd[k]==='object'?JSON.stringify(pd[k]):String(pd[k]))+'</div></div>'});
      }
      // JSON Body
      if(r.json_body){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">JSON Body</div>';
        h+='<div style="padding:8px 12px;font-family:var(--fdb-mono);font-size:11px;color:var(--fdb-sky);white-space:pre-wrap">'+esc(JSON.stringify(r.json_body,null,2))+'</div>';
      }
      // Cookies
      var ck=r.cookies||{};if(Object.keys(ck).length){
        h+='<div style="padding:6px 12px;color:var(--fdb-accent);font-weight:600;font-size:10px;text-transform:uppercase;border-bottom:1px solid var(--fdb-border);margin-top:4px">Cookies</div>';
        Object.keys(ck).forEach(function(k){h+='<div class="fdb-kv-row"><div class="fdb-kv-k">'+esc(k)+'</div><div class="fdb-kv-v">'+esc(String(ck[k]))+'</div></div>'});
      }
      return h+'</div></div></div>';
    },

    // ── History panel ───────────────────────────────────────────────────
    _p_history:function(d){
      var h=PH('Request History','<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>','Son request profilleri');
      h+='<div class="fdb-body" id="'+id+'_hist_body"><div class="fdb-loading"><div class="fdb-spinner"></div><span>Geçmiş yükleniyor…</span></div></div></div>';
      // Load history asynchronously
      // Geçmişi asenkron yükle
      fetch('/_profiler?limit=30',{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){return r.json()})
        .then(function(resp){
          var hb=document.getElementById(id+'_hist_body');if(!hb)return;
          var tokens=resp.tokens||[];
          if(!tokens.length){hb.innerHTML='<p class="fdb-empty">Geçmiş profil bulunamadı.</p>';return}
          var t='<table class="fdb-t"><tr><th style="width:50px">#</th><th>URI</th><th style="width:60px">Method</th><th style="width:60px">Status</th><th style="width:140px">Tarih</th></tr>';
          tokens.forEach(function(tk,i){
            var m=tk.meta||{};
            var sc=m.status>=500?'var(--fdb-red)':(m.status>=400?'var(--fdb-yellow)':'var(--fdb-green)');
            t+='<tr style="cursor:pointer" data-fdb-hist="'+esc(tk.token)+'"><td style="color:var(--fdb-text-dim);font-size:10px">'+(i+1)+'</td><td style="color:var(--fdb-sky);font-family:var(--fdb-mono);font-size:11px">'+esc(m.uri||'-')+'</td><td><span class="fdb-tag fdb-tag-method">'+esc(m.method||'GET')+'</span></td><td style="color:'+sc+';font-weight:600">'+esc(m.status||200)+'</td><td style="color:var(--fdb-text-dim);font-size:10px">'+esc(m.date||'-')+'</td></tr>';
          });
          hb.innerHTML=t+'</table>';
          // Click handler for history rows
          // Geçmiş satırları için tıklama işleyicisi
          hb.querySelectorAll('[data-fdb-hist]').forEach(function(row){
            row.addEventListener('click',function(){
              var tk=this.getAttribute('data-fdb-hist');
              fetch('/_profiler/'+tk,{headers:{'Accept':'application/json'}})
                .then(function(r){return r.json()})
                .then(function(nd){data=nd;FDB._render(cur||'timeline')});
            });
          });
        })
        .catch(function(e){
          var hb=document.getElementById(id+'_hist_body');
          if(hb)hb.innerHTML='<p class="fdb-empty" style="color:var(--fdb-red)">Yüklenemedi: '+esc(e.message)+'</p>';
        });
      return h;
    },

    // ── View toggle ─────────────────────────────────────────────────────
    vt:function(i){
      var b=document.getElementById(id+'_vb_'+i),ic=document.getElementById(id+'_vic_'+i);
      if(!b)return;var o=b.classList.toggle('open');if(ic)ic.style.transform=o?'rotate(0)':'rotate(-90deg)';
    },

    // ── Controls ────────────────────────────────────────────────────────
    close:function(){document.getElementById(id).style.display='none'},
    minimize:function(){document.getElementById(id).classList.add('fdb-minimized')},
    restore:function(){document.getElementById(id).classList.remove('fdb-minimized')},

    // ── Theme toggle ────────────────────────────────────────────────────
    toggleTheme:function(){
      var el=document.getElementById(id);
      el.classList.toggle('fdb-light');
      try{localStorage.setItem('fdb_theme',el.classList.contains('fdb-light')?'light':'dark')}catch(e){}
    },

    // ── Export JSON ─────────────────────────────────────────────────────
    exportJSON:function(){
      if(!data){FDB._load(function(){FDB.exportJSON()});return}
      var blob=new Blob([JSON.stringify(data,null,2)],{type:'application/json'});
      var a=document.createElement('a');
      a.href=URL.createObjectURL(blob);
      a.download='profiler_'+url.split('/').pop()+'.json';
      a.click();URL.revokeObjectURL(a.href);
    }
  };

  // ── Restore theme from localStorage ──────────────────────────────────
  // ── Temayı localStorage'dan geri yükle ───────────────────────────────
  try{if(localStorage.getItem('fdb_theme')==='light')document.getElementById(id).classList.add('fdb-light')}catch(e){}

  // ── Helpers ───────────────────────────────────────────────────────────
  function esc(s){if(!s&&s!==0)return '';var d=document.createElement('div');d.textContent=String(s);return d.innerHTML}
  function cMs(ms){return ms>200?'var(--fdb-red)':(ms>80?'var(--fdb-yellow)':'var(--fdb-green)')}
  function PH(title,icon,info){return '<div class="fdb-panel active"><div class="fdb-ph"><span class="fdb-ph-icon">'+icon+'</span><span class="fdb-ph-title">'+title+'</span><span class="fdb-ph-info">'+info+'</span></div>'}
  function intSql(sql,b){if(!b||!b.length)return sql;b.forEach(function(v){var q=v===null?'NULL':(typeof v==='number'?v:"'"+String(v).replace(/'/g,"\\\'")+  "'");sql=sql.replace('?',q)});return sql}
  function hlSql(sql){
    sql=esc(sql);
    ['SELECT','FROM','WHERE','JOIN','LEFT JOIN','RIGHT JOIN','INNER JOIN','ON','AND','OR','NOT','IN','IS NULL','IS NOT NULL','AS','ORDER BY','GROUP BY','HAVING','LIMIT','OFFSET','INSERT INTO','VALUES','UPDATE','SET','DELETE','CREATE','ALTER','DROP','DISTINCT','COUNT','SUM','AVG','MAX','MIN','UNION','EXISTS','BETWEEN','LIKE','NULL'].forEach(function(k){sql=sql.replace(new RegExp('\\b('+k.replace(/ /g,'\\s+')+')\\b','gi'),'<b>$1</b>')});
    sql=sql.replace(/'[^']*'/g,'<i>$&</i>');return sql;
  }

  // ── Resize ────────────────────────────────────────────────────────────
  (function(){
    var h=document.getElementById(id+'_resize'),p=document.getElementById(id+'_panels');
    if(!h||!p)return;var sy,sh;
    h.addEventListener('mousedown',function(e){e.preventDefault();sy=e.clientY;sh=p.offsetHeight;document.addEventListener('mousemove',mv);document.addEventListener('mouseup',up)});
    function mv(e){p.style.height=Math.max(80,sh+(sy-e.clientY))+'px'}
    function up(){document.removeEventListener('mousemove',mv);document.removeEventListener('mouseup',up)}
  })();

  // ── Keyboard ──────────────────────────────────────────────────────────
  document.addEventListener('keydown',function(e){
    if(e.ctrlKey&&e.shiftKey&&(e.key==='D'||e.key==='d')){
      e.preventDefault();var el=document.getElementById(id);
      if(el.classList.contains('fdb-minimized')){FDB.restore();return}
      el.style.display=el.style.display==='none'?'':'none';
    }
  });

  // ── Event Delegation ──────────────────────────────────────────────────
  document.getElementById(id+'_logo').addEventListener('click', function() { FDB.toggle('timeline'); });
  document.getElementById(id+'_btn_min').addEventListener('click', function() { FDB.minimize(); });
  document.getElementById(id+'_btn_cls').addEventListener('click', function() { FDB.close(); });
  document.getElementById(id+'_mini').addEventListener('click', function() { FDB.restore(); });
  document.getElementById(id+'_btn_theme').addEventListener('click', function() { FDB.toggleTheme(); });
  document.getElementById(id+'_btn_exp').addEventListener('click', function() { FDB.exportJSON(); });
  
  document.querySelectorAll('#'+id+' .fdb-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
      FDB.toggle(this.getAttribute('data-panel'));
    });
  });

  document.getElementById(id+'_panels').addEventListener('click', function(e) {
    // Accordion toggle
    // Akordiyon aç/kapat
    var hd = e.target.closest('.fdb-vw-hd');
    if (hd) {
      FDB.vt(hd.getAttribute('data-fdb-vt'));
      return;
    }
    // Copy SQL button
    // SQL kopyalama butonu
    var btn = e.target.closest('.fdb-copy');
    if (btn && navigator.clipboard) {
      var raw = btn.getAttribute('data-fdb-copy');
      try {
        var str = JSON.parse(raw);
        navigator.clipboard.writeText(str).then(function(){
          var old = btn.innerText; btn.innerText = 'copied!';
          setTimeout(function(){ btn.innerText = old; }, 1000);
        });
      } catch(err) {}
    }
  });

  // ── Search/Filter handler (event delegation) ─────────────────────────
  document.getElementById(id+'_panels').addEventListener('input', function(e) {
    if (!e.target.classList.contains('fdb-search-input')) return;
    var term = e.target.value.toLowerCase();
    var panel = e.target.closest('.fdb-panel');
    if (!panel) return;
    var rows = panel.querySelectorAll('.fdb-t tbody tr, .fdb-t tr:not(:first-child)');
    var shown = 0;
    rows.forEach(function(row) {
      if (row.querySelector('th')) return; // skip header // başlığı atla
      var text = row.textContent.toLowerCase();
      var match = !term || text.indexOf(term) !== -1;
      row.style.display = match ? '' : 'none';
      if (match) shown++;
    });
    var counter = e.target.parentNode.querySelector('.fdb-search-count');
    if (counter) counter.textContent = shown + ' / ' + rows.length;
  });

  window.FDB=FDB;
})();
</script>
<?php
