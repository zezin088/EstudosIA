
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>📘 Anotações</title>
  <style>
    /* Barra toda */
::-webkit-scrollbar {
  width: 12px; /* largura da barra vertical */
  height: 12px; /* altura da barra horizontal */
}

/* Fundo da barra */
::-webkit-scrollbar-track {
  background: #f0f0f0; /* cor do fundo da barra */
  border-radius: 10px;
}

/* Parte que se move (thumb) */
::-webkit-scrollbar-thumb {
  background: #3f7c72; /* cor do "polegar" */
  border-radius: 10px;
  border: 3px solid #f0f0f0; /* dá efeito de espaçamento */
}

/* Thumb ao passar o mouse */
::-webkit-scrollbar-thumb:hover {
  background: #2a5c55;
}
            @font-face {
      font-family: 'SimpleHandmade';
      src: url(/fonts/SimpleHandmade.ttf);
    }
    * {
      box-sizing: border-box;
    }
    body {
      background-color: #3f7c72ff;
      font-family:'Roboto',sans-serif;
      color: #3f7c72ff;
      padding: 40px;
      text-align: center;
      position: relative;
      min-height: 100vh;
    }
    /* Header */
    header {
  position: fixed; top:0; left:0; width:100%; height:70px;
  background:#ffffffcc; display:flex; justify-content:space-between; align-items:center;
  padding:0 2rem; box-shadow:0 2px 5px rgba(0,0,0,0.1); z-index:1000;
}
    header .logo img{height:450px;width:auto;display:block; margin-left: -85px;}


    nav ul{list-style:none; display:flex; align-items:center; gap:20px; margin:0;}
nav ul li a{ text-decoration:none; color:black;  padding:5px 10px; border-radius:8px; transition:.3s;}

    h1 {
      margin-top: 95;
      font-size: 50px;
      margin-bottom: 20px;
      font-family: 'SimpleHandmade';
      color: #ffffff;
    }

    .toolbar {
      margin-bottom: 20px;
    }

    .toolbar select,
    .toolbar input[type="color"],
    .toolbar button {
      font-family: 'SimpleHandmade';
      font-size: 20px;
      padding: 6px 10px;
      border: 1px solid #ffffff;
      border-radius: 8px;
      margin: 5px;
      background-color: #2a5c55;
      cursor: pointer;
      color: #ffffff;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .toolbar button:hover {
      background-color: #1e3834ff;
      color: #ffffff;
    }

    .caderno {
      background-color: #bdebe3ff;
      border: 2px solid #1e3834ff;
      border-radius: 10px;
      width: 90%;
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: left;
      position: relative;
      z-index: 1;
    }

    .editor {
      background-image: repeating-linear-gradient(#ffffff, #ffffff 2px, transparent 2px, transparent 32px);
      min-height: 400px;
      line-height: 32px;
      padding: 20px 30px;
      outline: none;
      font-size: 16px;
      color: #2a5c55;
      white-space: pre-wrap;
      position: relative;
      z-index: 1;
    }

    .placeholder-text {
      position: absolute;
      left: 50px;
      top: 60px;
      color: #2a5c55;
      opacity: 0.5;
      font-size: 16px;
      pointer-events: none;
      text-align: left;
      width: calc(100% - 80px);
      z-index: 2;
    }

    /* Botão voltar e salvar com estilo igual */
    .btn-salvar {
      position: fixed;
      background-color: #2a5c55;
      color: #fff;
      padding: 12px 25px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      border: none;
      transition: background 0.3s;
      user-select: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }


    .btn-salvar {
      bottom: 40px;
      left: 50%;
      transform: translateX(-50%);
    }

    .btn-salvar:hover {
      background-color: #1e3834ff;
      color: #fff;
    }
  </style>
</head>
<body>
<header>
    <div class="logo"><img src="/imagens/logoatual.png" alt="Logo"></div>
    <nav>
      <ul>
        <li><a href="/anotacoes/index.php">Voltar</a></li>
      </ul>
    </nav>
  </header>

  <h1>Caderno de Anotações</h1>

<div class="toolbar">
  <button onclick="document.execCommand('bold')"><b>Negrito</b></button>
  <select onchange="document.execCommand('fontSize', false, this.value)">
    <option value="">Tamanho</option>
    <option value="2">Pequeno</option>
    <option value="3">Normal</option>
    <option value="5">Grande</option>
    <option value="7">Gigante</option>
  </select>
  <input type="color" onchange="document.execCommand('foreColor', false, this.value)">
</div>

<div class="caderno">
  <div id="placeholder" class="placeholder-text">Escreva suas ideias, lembretes, pensamentos fofos aqui...</div>
  <div class="editor" contenteditable="true" id="editor"><?php echo $conteudoSalvo; ?></div>
</div>

<button class="btn-salvar" onclick="salvarAnotacoes()">💾 Salvar</button>

<script>
const editor = document.getElementById("editor");
const placeholder = document.getElementById("placeholder");

function atualizarPlaceholder() {
  placeholder.style.display = editor.innerText.trim().length === 0 ? "block" : "none";
}

editor.addEventListener("input", atualizarPlaceholder);
editor.addEventListener("focus", atualizarPlaceholder);
editor.addEventListener("blur", atualizarPlaceholder);
atualizarPlaceholder();

function salvarAnotacoes() {
  const conteudo = editor.innerHTML;
  const form = document.createElement('form');
  form.method = 'POST';
  form.style.display = 'none';
  const input = document.createElement('input');
  input.name = 'conteudo';
  input.value = conteudo;
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
}
</script>

</body>
<script>
const editor = document.getElementById("editor");
const placeholder = document.getElementById("placeholder");

  function atualizarPlaceholder() {
    if (editor.innerText.trim().length === 0) {
      placeholder.style.display = "block";
    } else {
      placeholder.style.display = "none";
    }
  }

editor.addEventListener("input", atualizarPlaceholder);
editor.addEventListener("focus", atualizarPlaceholder);
editor.addEventListener("blur", atualizarPlaceholder);
atualizarPlaceholder();
</script>
</html>
