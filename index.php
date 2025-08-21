<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="./style.css?v=<?= filemtime('./style.css') ?>">
  <title>Löffelsprache – Spaß-Übersetzer</title>
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

  <script src="./script.js"></script>
</body>
</html>
