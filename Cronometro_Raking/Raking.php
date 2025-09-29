<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Joguinho Cronômetro - Protótipo (Auto-start & Pixel aprimorado)</title>
<style>
  :root{
    --bg1: rgb(243,228,201);
    --accent: rgb(192,98,98);
    --brown-dark: rgb(139,80,80);
    --white: #ffffff;
    --panel: #efe6d8;
    --glass: rgba(255,255,255,0.6);
  }
  body{
    margin:0;
    font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    background: linear-gradient(180deg, var(--bg1), #d9d0bf 120%);
    color: var(--brown-dark);
    display:flex;
    min-height:100vh;
    align-items:center;
    justify-content:center;
    padding:20px;
    box-sizing:border-box;
  }

  .wrap{
    width:100%;
    max-width:1100px;
    background: linear-gradient(180deg, var(--panel), #f7efe1);
    border-radius:16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    padding:18px;
    display:grid;
    grid-template-columns: 1fr 380px;
    gap:18px;
  }

  header{
    grid-column: 1/3;
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:8px;
  }
  header h1{
    margin:0;
    font-size:20px;
    letter-spacing:0.2px;
    color:var(--brown-dark);
  }
  header .brand{
    display:flex;
    gap:10px;
    align-items:center;
  }
  .controls{
    padding:12px;
    background: rgba(255,255,255,0.6);
    border-radius:10px;
    display:flex;
    gap:8px;
    align-items:center;
    flex-wrap:wrap;
    justify-content:flex-end;
  }
  label{ font-size:13px; display:flex; gap:6px; align-items:center; color:var(--brown-dark)}

  input[type="number"], select, input[type="text"]{
    padding:6px 8px;
    border-radius:8px;
    border:1px solid rgba(0,0,0,0.08);
    background: white;
    font-size:14px;
  }

  button{
    padding:8px 12px;
    border-radius:10px;
    border: none;
    cursor:pointer;
    background: var(--accent);
    color:var(--white);
    font-weight:600;
  }
  button.ghost{
    background:transparent;
    border:1px solid rgba(0,0,0,0.06);
    color:var(--brown-dark);
  }

  /* Canvas area */
  .game-area{
    background: linear-gradient(180deg,#cfe6cf 0%, #a7d1a0 100%);
    border-radius:12px;
    padding:10px;
    display:flex;
    flex-direction:column;
    gap:8px;
    align-items:center;
  }

  canvas{
    background: linear-gradient(180deg, rgba(0,0,0,0.02), rgba(0,0,0,0.00));
    border-radius:8px;
    width:100%;
    height:auto;
    image-rendering: pixelated;
  }

  .sidebar{
    display:flex;
    flex-direction:column;
    gap:12px;
  }

  .panel{
    background:var(--glass);
    padding:12px;
    border-radius:12px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
  }

  .stats{
    display:flex;
    gap:12px;
    align-items:center;
  }
  .big{
    font-size:28px;
    font-weight:700;
    color:var(--brown-dark);
    margin:0;
  }

  .ranking-list{
    max-height:260px;
    overflow:auto;
    display:flex;
    flex-direction:column;
    gap:8px;
  }
  .rank-item{
    background: rgba(255,255,255,0.8);
    padding:8px;
    border-radius:8px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:14px;
  }

  /* Overlay question modal */
  .overlay{
    position:fixed;
    left:0; right:0; top:0; bottom:0;
    display:none;
    align-items:center;
    justify-content:center;
    background: rgba(0,0,0,0.45);
    z-index:40;
  }
  .card{
    background:white;
    padding:18px;
    border-radius:12px;
    width:90%;
    max-width:520px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
  }
  .question{ font-size:18px; margin:0 0 12px 0; color:var(--brown-dark) }
  .options{ display:flex; flex-direction:column; gap:8px }
  .opt{
    padding:10px;
    border-radius:8px;
    border:1px solid rgba(0,0,0,0.06);
    cursor:pointer;
    background:#fafafa;
  }

  footer.small{ font-size:12px; color:rgba(0,0,0,0.45); text-align:center; margin-top:8px }

  @media (max-width:980px){
    .wrap{ grid-template-columns: 1fr; }
    .sidebar{ order:2 }
  }
</style>
</head>
<body>
  <div class="wrap" role="application">
    <header>
      <div class="brand">
        <svg width="40" height="40" viewBox="0 0 24 24" style="filter:drop-shadow(0 4px 6px rgba(0,0,0,0.12))">
          <rect width="24" height="24" rx="6" fill="var(--accent)"></rect>
          <text x="12" y="16" text-anchor="middle" font-size="12" fill="white" font-weight="700">TG</text>
        </svg>
        <h1>Tudo sobre nossos Gatilhos — Cronômetro Gamificado (Protótipo)</h1>
      </div>

      <div class="controls">
        <label>Tempo (min)
          <input id="timeInput" type="number" min="1" max="240" value="25" />
        </label>

        <label>Matéria
          <select id="subject">
            <option value="Matemática">Matemática</option>
            <option value="História">História</option>
            <option value="Biologia">Biologia</option>
            <option value="Português">Português</option>
          </select>
        </label>

        <label>Arma
          <select id="weapon">
            <option value="sword">Espada</option>
            <option value="axe">Machado</option>
            <option value="wand">Varinha</option>
          </select>
        </label>

        <button id="startBtn">Iniciar</button>
        <button id="pauseBtn" class="ghost">Pausar</button>
        <button id="resetBtn" class="ghost">Reset</button>
      </div>
    </header>

    <main class="game-area">
      <div style="width:100%; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <p style="margin:0; font-size:13px; color:rgba(0,0,0,0.6)">Tempo restante</p>
          <p id="timeDisplay" class="big">25:00</p>
        </div>
        <div style="text-align:right">
          <p style="margin:0; font-size:13px; color:rgba(0,0,0,0.6)">Estado</p>
          <p id="stateDisplay" style="margin:0; font-weight:700">Pronto</p>
        </div>
      </div>

      <canvas id="gameCanvas" width="900" height="360"></canvas>
      <p class="small" style="margin:0">Protótipo — pixel art gerada por código. Salve e personalize perguntas no JS.</p>
    </main>

    <aside class="sidebar">
      <div class="panel">
        <h3 style="margin:0 0 8px 0">Placar / Ranking</h3>
        <div class="ranking-list" id="rankingList">
          <!-- ranking items -->
        </div>
        <div style="display:flex; gap:8px; margin-top:8px;">
          <button id="clearRanking" class="ghost">Limpar ranking</button>
          <button id="exportRanking" class="ghost">Exportar JSON</button>
        </div>
      </div>

      <div class="panel">
        <h3 style="margin:0 0 8px 0">Configurações rápidas</h3>
        <p style="margin:0 0 6px 0; font-size:13px">Digite um conteúdo extra (opcional) para combinar com as perguntas:</p>
        <input id="contentInput" type="text" placeholder="Ex: Frações - operações básicas" />
        <p style="margin:10px 0 0 0; font-size:13px">Nome para o ranking (será pedido no fim):</p>
        <input id="playerName" type="text" placeholder="Seu nome (opcional)" />
      </div>

    </aside>
  </div>

  <!-- Overlay pergunta -->
  <div id="overlay" class="overlay" role="dialog" aria-modal="true">
    <div class="card">
      <h3 class="question" id="qText">Pergunta aparece aqui</h3>
      <div class="options" id="qOptions"></div>
      <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:12px;">
        <button id="skipBtn" class="ghost">Pular</button>
      </div>
    </div>
  </div>

<script>
/* ============================
   Protótipo atualizado:
   - Pixel-art do personagem mais detalhada
   - Personagem inicia automaticamente na esquerda e caminha pra direita
   ============================ */

/* ------------------------------
   Perguntas (edite aqui)
   ------------------------------ */
const QUESTION_BANK = {
  "Matemática": [
    {q:"Quanto é 2/3 × 1/4 × 1/50?", opts:["1/300","1/600","1/150","2/3"], a:1},
    {q:"Qual é a forma decimal de 1/8?", opts:["0.125","0.25","0.5","0.875"], a:0}
  ],
  "História": [
    {q:"Em que ano começou a Revolução Francesa?", opts:["1789","1815","1701","1804"], a:0},
    {q:"Quem foi o imperador do Brasil em 1822?", opts:["Pedro I","Pedro II","João VI","D. Miguel"], a:0}
  ],
  "Biologia":[
    {q:"Qual organela é responsável pela respiração celular?", opts:["Mitocôndria","Cloroplasto","Ribossomo","Lisossomo"], a:0},
    {q:"Qual molécula carrega informação genética?", opts:["DNA","RNA","ATP","Lípido"], a:0}
  ],
  "Português":[
    {q:"Qual é a classe da palavra 'feliz'?", opts:["Adjetivo","Substantivo","Verbo","Conjunção"], a:0},
    {q:"Assinale o sujeito na frase: 'Choveu bastante ontem.'", opts:["Sujeito indeterminado","Sujeito simples","Sujeito composto","Não tem sujeito"], a:3}
  ]
};

/* ------------------------------
   Utils
   ------------------------------ */
function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }
function fmtTime(sec){
  sec = Math.max(0, Math.floor(sec));
  const m = Math.floor(sec/60), s = sec%60;
  return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}
function pickRandom(arr){ return arr[Math.floor(Math.random()*arr.length)]; }

/* ------------------------------
   Canvas setup
   ------------------------------ */
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d', { alpha: false });
// pixel scale: menor valor = mais detalhes; aumente para blocky maior
const PIXEL = 3; // base pixel size (3 dá mais detalhe que 4)
const CANVAS_W = canvas.width;
const CANVAS_H = canvas.height;

/* ------------------------------
   Game state
   ------------------------------ */
const state = {
  running: true,    // auto-start ativado
  paused: false,
  timeLeft: 25*60, // overwritten by timeInput change event
  totalTime: 25*60,
  player: {
    x: 40, // começa já na esquerda (auto-start)
    y: CANVAS_H - 80,
    speed: 1.0,
    frame: 0,
    frameTick:0,
    weapon: 'sword',
    anim: 'walking',
    facing: 1
  },
  monster: {
    active: false,
    hp: 1,
    x: CANVAS_W - 220,
    y: CANVAS_H - 80,
    alpha:1
  },
  overlay: false,
  subject: 'Matemática'
};

/* ------------------------------
   DOM elements
   ------------------------------ */
const timeInput = document.getElementById('timeInput');
const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');
const resetBtn = document.getElementById('resetBtn');
const subjectSel = document.getElementById('subject');
const weaponSel = document.getElementById('weapon');
const timeDisplay = document.getElementById('timeDisplay');
const stateDisplay = document.getElementById('stateDisplay');
const overlay = document.getElementById('overlay');
const qText = document.getElementById('qText');
const qOptions = document.getElementById('qOptions');
const skipBtn = document.getElementById('skipBtn');
const rankingList = document.getElementById('rankingList');
const contentInput = document.getElementById('contentInput');
const playerName = document.getElementById('playerName');
const clearRanking = document.getElementById('clearRanking');
const exportRanking = document.getElementById('exportRanking');

/* ------------------------------
   Ranking storage
   ------------------------------ */
function loadRanking(){ 
  try{ return JSON.parse(localStorage.getItem('tccGameRanking')||'[]'); }catch(e){ return []; }
}
function saveRanking(arr){ localStorage.setItem('tccGameRanking', JSON.stringify(arr)); }
function addRanking(item){
  const arr = loadRanking();
  arr.push(item);
  arr.sort((a,b)=> b.score - a.score);
  saveRanking(arr);
  renderRanking();
}
function renderRanking(){
  const arr = loadRanking();
  rankingList.innerHTML = '';
  if(arr.length===0){ rankingList.innerHTML = '<div style="color:rgba(0,0,0,0.45)">Nenhuma partida ainda</div>'; return; }
  arr.slice(0,50).forEach((r, idx)=>{
    const el = document.createElement('div'); el.className = 'rank-item';
    el.innerHTML = `<div><strong>#${idx+1} ${r.name}</strong><div style="font-size:12px;color:rgba(0,0,0,0.6)">${r.subject} • ${r.time}m</div></div><div style="text-align:right"><div>${r.result}</div><div style="font-size:12px;color:rgba(0,0,0,0.6)">${r.date}</div></div>`;
    rankingList.appendChild(el);
  });
}
clearRanking.addEventListener('click', ()=>{ if(confirm('Limpar ranking?')){ saveRanking([]); renderRanking(); }});
exportRanking.addEventListener('click', ()=>{ const data = JSON.stringify(loadRanking(), null, 2); const w = window.open("about:blank","export"); w.document.write(`<pre>${data}</pre>`); });

renderRanking();

/* ------------------------------
   Timer controls
   ------------------------------ */
timeInput.addEventListener('change', ()=>{
  const mins = clamp(parseInt(timeInput.value)||25, 1, 240);
  timeInput.value = mins;
  state.totalTime = mins*60;
  state.timeLeft = mins*60;
  updateTimeDisplay();
});
subjectSel.addEventListener('change', ()=> state.subject = subjectSel.value);
weaponSel.addEventListener('change', ()=> state.player.weapon = weaponSel.value);

startBtn.addEventListener('click', ()=>{
  state.running = true;
  state.paused = false;
  state.player.anim = 'walking';
  state.totalTime = parseInt(timeInput.value||25)*60;
  if(state.timeLeft > state.totalTime || state.timeLeft===0) state.timeLeft = state.totalTime;
  updateTimeDisplay();
});

pauseBtn.addEventListener('click', ()=>{
  if(!state.running) return;
  state.paused = !state.paused;
  if(state.paused){
    state.player.anim = 'sleeping';
    stateDisplay.textContent = 'Pausado';
  } else {
    state.player.anim = 'walking';
    stateDisplay.textContent = 'Andando';
  }
});

resetBtn.addEventListener('click', ()=>{
  if(confirm('Resetar tempo e estado do jogo?')){
    state.running = false;
    state.paused = false;
    state.timeLeft = parseInt(timeInput.value||25)*60;
    state.player.anim = 'idle';
    state.monster.active = false;
    state.monster.alpha = 1;
    state.player.x = 40; // volta para a esquerda
    updateTimeDisplay();
    stateDisplay.textContent = 'Pronto';
  }
});

function updateTimeDisplay(){ timeDisplay.textContent = fmtTime(state.timeLeft); }

/* ------------------------------
   Questions overlay
   ------------------------------ */
let currentQuestion = null;
function pickQuestionFor(subject){
  const bank = QUESTION_BANK[subject] || [];
  if(bank.length===0) return null;
  return JSON.parse(JSON.stringify(pickRandom(bank)));
}

function showQuestionOverlay(questionObj){
  currentQuestion = questionObj;
  if(!questionObj) return;
  qText.textContent = questionObj.q;
  qOptions.innerHTML = '';
  questionObj.opts.forEach((opt, idx)=>{
    const b = document.createElement('div');
    b.className = 'opt';
    b.textContent = opt;
    b.addEventListener('click', ()=> submitAnswer(idx));
    qOptions.appendChild(b);
  });
  overlay.style.display = 'flex';
  state.overlay = true;
}

function hideOverlay(){ overlay.style.display = 'none'; state.overlay = false; }

skipBtn.addEventListener('click', ()=> submitAnswer(-1));

function submitAnswer(idx){
  hideOverlay();
  const correct = (idx === currentQuestion.a);
  if(correct){
    state.player.anim = 'attacking';
    state.monster.hp = 0;
    state.monster.active = true;
    setTimeout(()=> onResult(true), 900);
  } else {
    state.player.anim = 'dead';
    setTimeout(()=> onResult(false), 900);
  }
}

/* ------------------------------
   Result handling (save ranking)
   ------------------------------ */
function onResult(victory){
  const mins = Math.round(state.totalTime/60);
  const name = (playerName.value && playerName.value.trim()) ? playerName.value.trim() : 'Anônimo';
  const item = {
    name,
    subject: state.subject,
    time: mins,
    result: victory ? 'Vitória' : 'Derrota',
    date: new Date().toLocaleString(),
    score: victory ? 100 + mins : mins
  };
  addRanking(item);

  state.running = false;
  state.paused = false;
  state.player.anim = victory ? 'idle' : 'dead';
  state.monster.active = false;
  state.monster.alpha = 1;
  state.timeLeft = state.totalTime;
  updateTimeDisplay();
  alert(victory ? 'Você derrotou o monstro! 🎉' : 'Errado... o monstro venceu 😵‍💫');
  stateDisplay.textContent = 'Pronto';
}

/* ------------------------------
   Pixel art - personagem aprimorado
   - sprite maior (mais detalhes)
   - 4 frames de caminhada mais suaves
   ------------------------------ */

function getCharacterPixelsDetailed(){
  // We'll use a 12x16 grid for better detail
  // Codes:
  // 0 transparent
  // 1 dark armor
  // 2 metal highlight
  // 3 scarf (red)
  // 4 ear/helmet accent (blue)
  // 5 eye glow
  // 6 leather/belt
  const framesRaw = [];

  // base standing
  const base = [
    [0,0,0,0,1,1,1,1,0,0,0,0],
    [0,0,0,1,1,2,2,1,1,0,0,0],
    [0,0,1,1,2,2,2,2,1,1,0,0],
    [0,1,1,2,2,2,2,2,2,1,1,0],
    [0,1,1,2,3,3,2,2,2,1,1,0],
    [1,1,2,2,2,2,2,2,2,2,1,0],
    [1,1,2,2,6,6,6,2,2,2,1,0],
    [0,1,1,2,6,6,6,2,2,1,0,0],
    [0,0,1,1,1,1,1,1,1,0,0,0],
    [0,0,0,1,0,0,0,0,1,0,0,0],
    [0,0,0,1,0,0,0,0,1,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0],
    [0,0,0,4,0,0,0,0,4,0,0,0],
    [0,0,0,0,0,5,5,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0]
  ];

  // walk poses (shift legs/arms)
  const frm1 = JSON.parse(JSON.stringify(base));
  frm1[8][4]=0; frm1[9][4]=1; frm1[10][4]=1; // left step
  frm1[6][9]=1; frm1[7][9]=1; // arm move

  const frm2 = JSON.parse(JSON.stringify(base));
  frm2[8][6]=0; frm2[9][6]=1; frm2[10][6]=1; // right step
  frm2[6][3]=1; frm2[7][3]=1;

  const frm3 = JSON.parse(JSON.stringify(base));
  frm3[8][5]=0; frm3[9][5]=1; frm3[10][5]=1;
  frm3[6][8]=1; frm3[7][8]=1;

  framesRaw.push(frm1, frm2, frm3, base);

  // sleeping (lying)
  const sleeping = [
    [0,0,0,0,0,1,1,1,1,0,0,0,0],
    [0,0,0,0,1,2,2,2,1,0,0,0,0],
    [0,0,0,1,2,2,3,2,2,1,0,0,0],
    [0,0,1,2,2,2,2,2,2,1,0,0,0],
    [0,1,1,2,2,2,2,2,1,1,0,0,0],
    [1,1,2,2,6,6,6,2,2,2,1,0,0],
    [0,1,1,1,1,1,1,1,1,1,0,0,0],
    [0,0,1,1,1,1,1,1,1,0,0,0,0]
  ];

  // attack (raised arm)
  const attack = JSON.parse(JSON.stringify(base));
  attack[5][10] = 1;
  attack[4][10] = 1;
  attack[3][10] = 0;
  attack[2][10] = 0;
  attack[6][10] = 1;

  // dead
  const dead = [
    [0,0,0,0,0,1,1,1,0,0,0,0],
    [0,0,0,0,1,2,2,1,0,0,0,0],
    [0,0,0,1,2,0,0,2,1,0,0,0],
    [0,0,1,2,2,0,0,2,1,0,0,0],
    [0,1,1,2,2,2,2,1,0,0,0,0],
    [1,1,2,2,6,6,6,2,1,0,0,0],
    [0,1,1,1,1,1,1,1,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0]
  ];

  function mapColors(matrix){
    const h = matrix.length, w = matrix[0].length;
    const out = [];
    for(let y=0;y<h;y++){
      out[y]=[];
      for(let x=0;x<w;x++){
        const v = matrix[y][x];
        if(v===0) out[y][x] = null;
        else if(v===1) out[y][x] = '#2f3b45'; // dark metal
        else if(v===2) out[y][x] = '#7ea3ab'; // metal highlight
        else if(v===3) out[y][x] = '#9c2b2b'; // scarf
        else if(v===4) out[y][x] = '#7fb4d9'; // ear accent
        else if(v===5) out[y][x] = '#c76bd8'; // eye glow
        else if(v===6) out[y][x] = '#6b4f3e'; // leather belt
        else out[y][x] = '#000';
      }
    }
    return out;
  }

  return {
    w: framesRaw[0][0].length,
    h: framesRaw[0].length,
    frames: framesRaw.map(mapColors),
    sleeping: mapColors(sleeping),
    attack: mapColors(attack),
    dead: mapColors(dead)
  };
}

/* Monster pixel art (mantive, mas com sombreamento) */
function getMonsterPixels(){
  const m = [
    [0,0,0,0,4,4,4,0,0,0,0],
    [0,0,0,4,5,5,5,4,0,0,0],
    [0,0,4,5,6,6,5,5,4,0,0],
    [0,4,5,6,6,6,6,5,5,4,0],
    [4,5,6,6,6,6,6,6,5,5,4],
    [0,4,5,6,6,6,6,5,5,4,0],
    [0,0,4,5,5,5,5,4,4,0,0],
    [0,0,0,4,0,0,4,0,0,0,0]
  ];
  const cmap = {4:'#275d2f',5:'#54a85a',6:'#8fe085'};
  return { w: m[0].length, h: m.length, mat: m.map(r=> r.map(v=> v? cmap[v]: null)) };
}

/* ------------------------------
   Drawing helpers
   ------------------------------ */
const charCache = {};
function drawCharacter(ctx, x, y, scale, weapon, anim, frameIdx){
  if(!charCache.detailed) charCache.detailed = getCharacterPixelsDetailed();
  const chr = charCache.detailed;

  let mat;
  if(anim === 'sleeping') mat = chr.sleeping;
  else if(anim === 'attacking') mat = chr.attack;
  else if(anim === 'dead') mat = chr.dead;
  else mat = chr.frames[frameIdx % chr.frames.length];

  const px = PIXEL * scale;
  for(let j=0;j<mat.length;j++){
    for(let i=0;i<mat[j].length;i++){
      const col = mat[j][i];
      if(!col) continue;
      ctx.fillStyle = col;
      ctx.fillRect(x + i*px, y + j*px, px, px);
    }
  }

  // desenhar arma com mais detalhe (sobre a mão direita)
  const handX = x + (mat[0].length - 3)*px;
  const handY = y + (4*px);
  ctx.save();
  if(weapon === 'sword'){
    // cabo
    ctx.fillStyle = '#5b4132';
    ctx.fillRect(handX, handY+px, px, px*3);
    // lâmina com brilho
    ctx.fillStyle = '#dfe7ea';
    ctx.fillRect(handX - px*3, handY, px*3, px);
    ctx.fillRect(handX - px*2, handY - px, px, px);
  } else if(weapon === 'axe'){
    ctx.fillStyle = '#5b4132';
    ctx.fillRect(handX, handY+px, px, px*3);
    ctx.fillStyle = '#e0d3d3';
    ctx.fillRect(handX - px*3, handY - px, px*4, px*3);
  } else if(weapon === 'wand'){
    ctx.fillStyle = '#7d58c6';
    ctx.fillRect(handX, handY, px, px*4);
    // ponta brilhante
    ctx.fillStyle = '#ffd86b';
    ctx.fillRect(handX - px, handY - px, px*2, px);
    // pequena partícula mágica
    ctx.fillRect(handX - px*2, handY - px*2, px, px);
  }
  ctx.restore();

  if(anim === 'sleeping'){
    ctx.fillStyle = 'rgba(0,0,0,0.5)';
    ctx.font = `${px*3}px sans-serif`;
    ctx.fillText('Z z', x + px*(mat[0].length+1), y - px*2);
  }
}

/* Monster draw */
const monsterCache = getMonsterPixels();
function drawMonster(ctx, x, y, scale, alpha=1){
  ctx.save();
  ctx.globalAlpha = alpha;
  const px = PIXEL * scale;
  for(let j=0;j<monsterCache.h;j++){
    for(let i=0;i<monsterCache.w;i++){
      const col = monsterCache.mat[j][i];
      if(!col) continue;
      ctx.fillStyle = col;
      ctx.fillRect(x + i*px, y + j*px, px, px);
    }
  }
  ctx.restore();
}

/* Background parallax */
function drawBackground(ctx, t){
  ctx.fillStyle = '#b6e0b4';
  ctx.fillRect(0,0,CANVAS_W, CANVAS_H - 48);
  // ground
  ctx.fillStyle = '#2a6e2d';
  ctx.fillRect(0, CANVAS_H - 48, CANVAS_W, 48);

  // trees parallax
  const tree1 = '#0f5f2f', tree2 = '#186b38';
  const s1 = (t*0.02) % (CANVAS_W);
  const s2 = (t*0.04) % (CANVAS_W);
  for(let i=-2;i<6;i++){
    const bx = ((i*200) + s2) % (CANVAS_W) - 180;
    ctx.fillStyle = tree2;
    ctx.fillRect(bx, CANVAS_H - 170, 80, 130);
    ctx.fillStyle = tree1;
    ctx.fillRect(bx+20, CANVAS_H - 240, 40, 90);
  }

  // small decorative sparkles
  ctx.fillStyle = '#fff59d';
  for(let i=0;i<6;i++){
    const sx = (i*143 + Math.floor(t)%500) % CANVAS_W;
    const sy = 80 + (i*34)%120;
    ctx.fillRect(sx, sy, 2,2);
  }
}

/* ------------------------------
   Game loop
   ------------------------------ */
let lastTS = performance.now();
function gameLoop(ts){
  const dt = (ts - lastTS) / 1000.0;
  lastTS = ts;

  // update time when running
  if(state.running && !state.paused && !state.overlay){
    state.timeLeft = Math.max(0, state.timeLeft - dt);
    updateTimeDisplay();
    // movement: compute movement speed so actor crosses scene in totalTime
    const total = state.totalTime || 1;
    const moveSpeed = (CANVAS_W - 300) / total; // px per second
    state.player.x += moveSpeed * dt;
  }

  // animate frames
  if(state.player.anim === 'walking'){
    state.player.frameTick += dt;
    if(state.player.frameTick > 0.14){
      state.player.frame = (state.player.frame + 1) % 4;
      state.player.frameTick = 0;
    }
    stateDisplay.textContent = 'Andando';
  } else if(state.player.anim === 'sleeping'){
    state.player.frame = 0;
    stateDisplay.textContent = 'Dormindo';
  } else if(state.player.anim === 'attacking'){
    state.player.frame = 0;
    stateDisplay.textContent = 'Atacando';
  } else if(state.player.anim === 'dead'){
    state.player.frame = 0;
    stateDisplay.textContent = 'Derrotado';
  } else {
    stateDisplay.textContent = 'Pronto';
  }

  // when reaches right edge, loop to left for continuous demo
  if(state.player.x > CANVAS_W - 240) state.player.x = 40;

  // when timer ends -> spawn monster + question
  if(state.running && state.timeLeft <= 0 && !state.overlay){
    state.running = false;
    state.monster.active = true;
    const q = pickQuestionFor(state.subject);
    if(!q){
      alert('Sem perguntas para essa matéria — customize o QUESTION_BANK no código.');
      onResult(true);
    } else {
      setTimeout(()=> showQuestionOverlay(q), 500);
    }
  }

  // render
  ctx.clearRect(0,0,CANVAS_W,CANVAS_H);
  drawBackground(ctx, ts);

  // decorative stones
  for(let i=0;i<CANVAS_W;i+=36){
    ctx.fillStyle = '#4a4a4a';
    ctx.fillRect(i, CANVAS_H - 22, 6, 6);
  }

  // player draw (scale chosen to keep good size)
  drawCharacter(ctx, state.player.x, state.player.y - 64, 2.2, state.player.weapon, state.player.anim, state.player.frame);

  // monster
  if(state.monster.active){
    if(state.monster.hp > 0){
      drawMonster(ctx, state.monster.x, state.monster.y - 64, 2.2, state.monster.alpha);
    } else {
      state.monster.alpha -= dt * 0.9;
      if(state.monster.alpha < 0) state.monster.alpha = 0;
      drawMonster(ctx, state.monster.x, state.monster.y - 64, 2.2, state.monster.alpha);
    }
  }

  requestAnimationFrame(gameLoop);
}

/* ------------------------------
   Inicialização e atalhos
   ------------------------------ */
requestAnimationFrame(gameLoop);

// Sync initial UI
timeInput.dispatchEvent(new Event('change'));
subjectSel.dispatchEvent(new Event('change'));
weaponSel.dispatchEvent(new Event('change'));

// Auto-start UI indicators (porque state.running já true)
state.player.anim = 'walking';
stateDisplay.textContent = 'Andando';

// Keyboard
window.addEventListener('keydown', (e)=>{
  if(e.key === ' '){
    if(state.running) pauseBtn.click();
  } else if(e.key === 's') startBtn.click();
});

</script>
</body>
</html>
