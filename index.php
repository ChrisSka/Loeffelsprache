<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Löffelsprache – Spaß-Übersetzer</title>
  <style>
    /* ---- THEME TOKENS ---------------------------------------------------- */
    :root{ /* Dark (default) */
      --bg1: #0f1020;
      --bg2: #1a1c3a;
      --card: #121326cc;
      --text: #f6f7fb;
      --muted: #c9cbe3;
      --accent: #8be9fd;
      --accent-2: #bd93f9;
      --ok: #50fa7b;
      --warn: #ffb86c;
      --err: #ff5555;
      --radius: 16px;
      --shadow: 0 10px 30px rgba(0,0,0,.35);
    }
    :root.light{ /* Light override (per Switch) */
      --bg1:#f6f7fb; --bg2:#eaeefc; --card:#ffffffdd; --text:#0c0d1b; --muted:#535a75;
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; color:var(--text);
      background:
        radial-gradient(1200px 800px at 10% -10%, #2a2e72 0%, transparent 70%),
        radial-gradient(1200px 800px at 110% 10%, #3b1c66 0%, transparent 60%),
        linear-gradient(160deg,var(--bg1),var(--bg2));
      font: 16px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
      overflow-x: hidden;
    }
    header{display:flex;align-items:center;gap:16px;margin-bottom:20px}
    .logo{
      width:60px;height:60px;border-radius:14px;
      background: linear-gradient(160deg,#ffd166,#ef476f);
      box-shadow: var(--shadow); display:grid;place-items:center;
    }
    .logo svg{width:38px;height:38px;filter: drop-shadow(0 2px 8px rgba(0,0,0,.35))}
    h1{margin:0;font-size:clamp(22px,3.6vw,34px);letter-spacing:.3px}
    .sub{color:var(--muted);margin-top:4px}

    .grid{display:grid;gap:18px;grid-template-columns: 1fr 1fr;}
    @media (max-width:1000px){.grid{grid-template-columns:1fr}}
    .card{
      background: var(--card); border:1px solid rgba(255,255,255,.08);
      backdrop-filter: blur(6px);
      border-radius: var(--radius); box-shadow: var(--shadow);
      padding:16px; position:relative; overflow:hidden;
    }
    .card h2{margin:0 0 10px 0; font-size:18px}
    textarea{
      width:100%; min-height:220px; resize:vertical;
      background:#0c0d1b; color:var(--text); border:1px solid rgba(255,255,255,.08);
      border-radius:12px; padding:12px; outline:none;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,.2);
    }
    :root.light textarea{background:#fff;border-color:rgba(0,0,0,.08);box-shadow: inset 0 0 0 1px rgba(0,0,0,.06)}
    .toolbar{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin:10px 0 6px;}
    .btn{
      border:1px solid rgba(255,255,255,.14);
      background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
      color:var(--text); padding:8px 12px; border-radius:12px; cursor:pointer;
      display:inline-flex;align-items:center;gap:8px;transition:transform .06s ease, border-color .2s ease;
      user-select:none;
    }
    .btn:active{transform:translateY(1px)}
    .btn:hover{border-color: rgba(255,255,255,.28)}
    :root.light .btn{border-color:rgba(0,0,0,.15);background:linear-gradient(180deg, rgba(0,0,0,.04), rgba(0,0,0,.02))}
    :root.light .btn:hover{border-color:rgba(0,0,0,.35)}
    .btn.accent{border-color:transparent;background:linear-gradient(180deg,var(--accent),#5ed2ed); color:#082530}
    .btn.purple{border-color:transparent;background:linear-gradient(180deg,var(--accent-2),#8a67f3)}
    .btn.ghost{background:transparent}

    .chip{font-size:12px;color:#0a1a12;background:linear-gradient(180deg,#b4f5c5,#7af0a0);padding:4px 10px;border-radius:999px;margin-left:auto}
    .stats{font-size:12px;color:var(--muted)}
    .row{display:flex;gap:10px;align-items:center;justify-content:space-between}
    .settings{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
    select, .toggle{
      border:1px solid rgba(255,255,255,.14);
      background:#0d0f22;color:var(--text);border-radius:12px;padding:8px 12px
    }
    :root.light select, :root.light .toggle{background:#fff;border-color:rgba(0,0,0,.15);color:#0c0d1b}
    .toggle{display:inline-flex;align-items:center;gap:10px;padding:6px 10px}
    .switch{
      --w:42px; --h:22px;
      inline-size: var(--w); block-size: var(--h); border-radius: 999px; position:relative;
      background:#0a0b18;border:1px solid rgba(255,255,255,.18); cursor:pointer;
      transition:background .2s ease, border-color .2s ease
    }
    .switch::after{
      content:""; position:absolute; inset:2px; inline-size: calc(var(--h) - 4px); block-size: calc(var(--h) - 4px);
      border-radius:999px; background:linear-gradient(180deg,#fff,#cfd5ff);
      transform: translateX(0); transition: transform .2s ease;
      box-shadow: 0 1px 6px rgba(0,0,0,.45);
    }
    .switch[data-on="true"]{background:#193a2b;border-color:#56e69b}
    .switch[data-on="true"]::after{transform: translateX(calc(var(--w) - var(--h)))}

    .footer-note{color:var(--muted); font-size:12px; margin-top:12px}
    .toasts{position:fixed; right:18px; bottom:18px; display:flex; flex-direction:column; gap:8px; z-index:9999}
    .toast{
      background:#0d0f22cc; border:1px solid rgba(255,255,255,.18); backdrop-filter: blur(6px);
      color:var(--text); padding:10px 12px; border-radius:12px; box-shadow: var(--shadow); font-size:14px
    }

    /* Deko */
    .bg-spoons{
    position: fixed;   /* bleibt am Viewport, scrollt nicht mit */
    top: -40px;
    right: -60px;
    width: min(80vw, 900px);  /* skaliert sauber */
    opacity: .75;               /* Sichtbarkeit steuern wir am SVG */
    pointer-events: none;
    user-select: none;
    z-index: 0;               /* hinter dem Inhalt */
    }
    .bg-spoons svg{
    width: 100%;
    height: auto;
    opacity: .15;             /* leichte Transparenz */
    }
    .wrap{max-width:1100px;margin:40px auto;padding:24px}
    .mode{margin-left:auto; display:flex; align-items:center; gap:10px}
    .link{color:var(--accent)}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <div class="logo" aria-hidden="true" title="Löffel-Logo">
        <!-- Spoon SVG -->
        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5">
          <path fill="#fff" d="M12.9 2.2c2.7 0 4.9 2.2 4.9 4.9 0 1.4-.6 2.7-1.6 3.6L9.6 17.2c-.3.3-.5.6-.6.9l-.7 3c-.1.6-.7 1-1.3.9-.6-.1-1-.7-.9-1.3l.7-3c.1-.7.5-1.4 1-1.9l6.4-6.5c.6-.5 1-1.3 1-2.2 0-1.6-1.3-2.9-2.9-2.9-1 0-1.8.5-2.4 1.2-.5.6-.9 1.5-.9 2.4 0 .6-.4 1-1 1s-1-.4-1-1c0-1.4.5-2.7 1.3-3.7 1-1.1 2.4-1.8 4-1.8Z"/>
        </svg>
      </div>
      <div>
        <h1>Löffelsprache Übersetzer</h1>
        <div class="sub">Zwei Spielarten: „löffel/loeffel“ oder „lew“ (Vokal-Cluster). Spaß garantiert 🥄</div>
      </div>
      <div class="mode">
        <span class="stats">Dark-Mode</span>
        <div class="switch" id="modeSwitch" role="switch" aria-checked="true" tabindex="0" data-on="true"></div>
      </div>
    </header>

    <div class="card" style="margin-bottom:18px">
      <div class="row">
        <div class="settings">
          <label>Variante:&nbsp;
            <select id="variant">
              <option value="löffel">löffel (klassisch)</option>
              <option value="loeffel">loeffel (ASCII)</option>
              <option value="lew" selected>lew (Vokal-Cluster)</option>
            </select>
          </label>
          <!-- Y-Option entfernt -->
          <label class="toggle">
            <span>Live-Modus</span>
            <div class="switch" id="liveSwitch" role="switch" aria-checked="true" tabindex="0" data-on="true"></div>
          </label>
        </div>
        <span class="chip">Tipp: <kbd>Ctrl/Cmd</kbd> + <kbd>Enter</kbd> → Kodieren</span>
      </div>
    </div>

    <div class="grid">
      <section class="card">
        <h2>Klartext</h2>
        <div class="toolbar">
          <button class="btn accent" id="encodeBtn">→ In Löffelsprache</button>
          <button class="btn ghost" id="clearLeft">Leeren</button>
          <button class="btn" id="exampleBtn">Beispiel einfügen</button>
          <span class="stats" id="leftStats">0 Zeichen · 0 Wörter</span>
        </div>
        <textarea id="plain" placeholder="Tippe hier deinen Text …&#10;Beispiel: Hallo, wie geht’s?"></textarea>
      </section>

      <section class="card">
        <h2>Löffeltext</h2>
        <div class="toolbar">
          <button class="btn purple" id="decodeBtn">← Zurück übersetzen</button>
          <button class="btn" id="copyRight">Kopieren</button>
          <button class="btn ghost" id="clearRight">Leeren</button>
          <span class="stats" id="rightStats">0 Zeichen · 0 Wörter</span>
        </div>
        <textarea id="spoon" placeholder="Hier erscheint der Löffeltext …"></textarea>
      </section>
    </div>

    <div class="footer-note">
      Regeln je nach <strong>Variante</strong>:
      <ul>
        <li><em>löffel/loeffel:</em> Nach jedem <em>einzelnen Vokal</em> „löffel/loeffel“ + derselbe Vokal. <code>Hallo → Halöffelallo</code></li>
        <li><em>lew:</em> Nach jedem <em>Vokal-Cluster</em> „lew“ + derselbe Cluster. <code>Tisch → Tilewisch</code>, <code>Morgen → Moleworgen</code></li>
      </ul>
    </div>
  </div>

  <!-- Deko -->
  <div class="bg-spoons" aria-hidden="true">
    <svg viewBox="0 0 1200 300" fill="none">
        <g stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M40 40c50 0 90 40 90 90 0 25-10 48-29 65L41 255" />
        <path d="M240 40c50 0 90 40 90 90 0 25-10 48-29 65L241 255" />
        <path d="M440 40c50 0 90 40 90 90 0 25-10 48-29 65L441 255" />
        <path d="M640 40c50 0 90 40 90 90 0 25-10 48-29 65L641 255" />
        <path d="M840 40c50 0 90 40 90 90 0 25-10 48-29 65L841 255" />
        <path d="M1040 40c50 0 90 40 90 90 0 25-10 48-29 65L1041 255" />
        </g>
    </svg>
    </div>

  <div class="toasts" id="toasts" aria-live="polite"></div>

  <script>
    // --- Helpers -------------------------------------------------------------
    const $ = sel => document.querySelector(sel);
    const stats = (txt) => {
      const ch = txt.length;
      const w = (txt.trim().match(/\S+/g) || []).length;
      return `${ch} Zeichen · ${w} Wörter`;
    };
    const toast = (msg) => {
      const t = document.createElement('div');
      t.className = 'toast'; t.textContent = msg;
      $('#toasts').appendChild(t);
      setTimeout(()=>{ t.style.opacity='.0' }, 1600);
      setTimeout(()=>{ t.remove() }, 2100);
    };
    const setSwitch = (sel, on) => {
      const el = $(sel);
      el.dataset.on = on ? 'true' : 'false';
      el.setAttribute('aria-checked', on ? 'true' : 'false');
    };
    const toggleSwitch = (el) => {
      const on = el.dataset.on !== 'true';
      setSwitch('#'+el.id, on);
      if(el.id==='modeSwitch') applyMode();
      save();
      if(el.id==='liveSwitch' && on) encodeNow();
    };

    // Persist
    const save = () => {
      localStorage.setItem('loeffel_state', JSON.stringify({
        plain: $('#plain').value,
        spoon: $('#spoon').value,
        variant: $('#variant').value,
        live: $('#liveSwitch').dataset.on === 'true',
        dark: $('#modeSwitch').dataset.on !== 'false'
      }));
    };
    const load = () => {
      try{
        const s = JSON.parse(localStorage.getItem('loeffel_state')||'{}');
        if(s.variant) $('#variant').value = s.variant;
        setSwitch('#liveSwitch', s.live ?? true);
        setSwitch('#modeSwitch', s.dark ?? true);
        $('#plain').value = s.plain || '';
        $('#spoon').value = s.spoon || '';
      }catch(e){}
      refreshStats();
      applyMode();
    };

    // --- THEME --------------------------------------------------------------
    function applyMode(){
      const dark = $('#modeSwitch').dataset.on !== 'false'; // true => dark
      document.documentElement.classList.toggle('light', !dark);
      document.documentElement.style.colorScheme = dark ? 'dark':'light';
    }

    // --- CORE: Varianten ----------------------------------------------------
    const VOWELS = 'AEIOUÄÖÜaeiouäöü'; // y/y nicht enthalten

    function encode_text(text, variant){
      if(variant === 'lew'){
        // Cluster: z. B. "a", "ei", "oo"
        const re = new RegExp('['+VOWELS+']+', 'g');
        return text.replace(re, (cluster) => cluster + 'lew' + cluster);
      }else{
        // klassisch: pro Einzelvokal
        const re = new RegExp('['+VOWELS+']', 'g');
        const insert = variant; // 'löffel' oder 'loeffel'
        return text.replace(re, ch => ch + insert + ch);
      }
    }

    function decode_text(text, variant){
      if(variant === 'lew'){
        const re = new RegExp('(['+VOWELS+']+)lew\\1', 'g');
        return text.replace(re, '$1');
      }else{
        const re = new RegExp('(['+VOWELS+'])' + escapeReg(variant) + '\\1','g');
        return text.replace(re, '$1');
      }
    }

    // robustes Decoding: falls der gewählte Modus nichts ändert, probiere die anderen
    function robust_decode(text, preferredVariant){
      const all = ['löffel','loeffel','lew'];
      let out = decode_text(text, preferredVariant);
      if(out !== text) return out;
      for(const v of all){
        if(v === preferredVariant) continue;
        const test = decode_text(text, v);
        if(test !== text) return test;
      }
      return text; // nichts erkannt
    }

    // utils
    const escapeReg = (s) => s.replace(/[-/\\^$*+?.()|[\]{}]/g, (m) => '\\' + m);

    // --- UI actions ----------------------------------------------------------
    function refreshStats(){
      $('#leftStats').textContent = stats($('#plain').value);
      $('#rightStats').textContent = stats($('#spoon').value);
    }
    function currentVariant(){ return $('#variant').value; }
    function liveOn(){ return $('#liveSwitch').dataset.on === 'true'; }

    function encodeNow(){
      $('#spoon').value = encode_text($('#plain').value, currentVariant());
      refreshStats(); save();
    }
    function decodeNow(){
      $('#plain').value = robust_decode($('#spoon').value, currentVariant());
      refreshStats(); save();
    }

    // --- Events --------------------------------------------------------------
    $('#encodeBtn').addEventListener('click', encodeNow);
    $('#decodeBtn').addEventListener('click', decodeNow);
    $('#clearLeft').addEventListener('click', ()=>{ $('#plain').value=''; if(liveOn()) encodeNow(); refreshStats(); save(); });
    $('#clearRight').addEventListener('click', ()=>{ $('#spoon').value=''; refreshStats(); save(); });

    $('#copyRight').addEventListener('click', async ()=>{
      try{ await navigator.clipboard.writeText($('#spoon').value); toast('Löffeltext kopiert!'); }catch(e){ toast('Kopieren nicht erlaubt'); }
    });
    $('#exampleBtn').addEventListener('click', ()=>{
      $('#plain').value = 'Hallo, wie geht’s? Tisch, Morgen, Miete, Boot.';
      if(liveOn()) encodeNow(); refreshStats(); save();
    });

    ['plain','spoon'].forEach(id=>{
      $('#'+id).addEventListener('input', ()=>{
        if(id==='plain' && liveOn()) encodeNow(); else { refreshStats(); save(); }
      });
      $('#'+id).addEventListener('keyup', (e)=>{
        if((e.metaKey||e.ctrlKey) && e.key==='Enter'){ encodeNow(); }
        if((e.shiftKey) && e.key==='Enter'){ decodeNow(); }
      });
    });

    $('#variant').addEventListener('change', ()=>{ if(liveOn()) encodeNow(); save(); });

    ['liveSwitch','modeSwitch'].forEach(id=>{
      const el = $('#'+id);
      el.addEventListener('click', ()=> toggleSwitch(el));
      el.addEventListener('keydown', e=>{ if(e.key===' '||e.key==='Enter'){ e.preventDefault(); toggleSwitch(el);} });
    });

    // Persist & init
    load();

    // Wenn rechts editiert wird, Live aus lassen
    $('#spoon').addEventListener('input', ()=>{
      if(liveOn()) setSwitch('#liveSwitch', false);
    });
  </script>
</body>
</html>

