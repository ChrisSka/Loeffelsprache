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