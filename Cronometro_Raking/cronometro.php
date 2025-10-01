<?php
// Conexão com o banco
$mysqli = new mysqli("localhost", "root", "", "bd_usuarios");
if ($mysqli->connect_errno) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

session_start();
$idUsuario = $_SESSION['id'] ?? 1; // teste com 1


?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cronômetro com Amizades</title>
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
/* Fonte */
@font-face { font-family: 'SimpleHandmade'; src: url(/fonts/SimpleHandmade.ttf); }

/* Reset básico */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Roboto', sans-serif; background: #f9f9f9; color:#333; }

/* Robo dormindo */
.robo-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.robo-container img {
    max-width: 300px;
    width: 100%;
}

/* Cronômetro */
.cronometro-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}
#tempo {
    font-family: 'SimpleHandmade';
    font-size: 3rem;
    background: #3f7c72;
    color: white;
    padding: 15px 30px;
    border-radius: 15px;
    margin-right: 20px;
}
button {
    padding: 10px 20px;
    margin-right: 10px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    background: #3f7c72;
    color: white;
}
button:hover { background: #2a5c55; }

/* Painel de amizades */
.tabela-amizades {
    position: fixed;
    top: 80px;
    left: 20px;
    width: 220px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 10px;
    z-index: 1000;
}

.tabela-amizades h3 {
    color: #3f7c72;
    font-size: 16px;
    margin-bottom: 5px;
    border-bottom: 1px solid #bdebe3;
    padding-bottom: 3px;
}

.usuario {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.foto-perfil {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3f7c72;
}
header {
  position: fixed; top:0; left:0; width:100%; height:70px;
  background:#ffffffcc; display:flex; justify-content:space-between; align-items:center;
  padding:0 2rem; box-shadow:0 2px 5px rgba(0,0,0,0.1); z-index:1000;
}
    header .logo img{height:450px;width:auto;display:block; margin-left: -85px;}


    nav ul{list-style:none; display:flex; align-items:center; gap:20px; margin:0;}
nav ul li a{ text-decoration:none; color:black;  padding:5px 10px; border-radius:8px; transition:.3s;}

</style>
</head>
<body>
  <header>
    <div class="logo"><img src="/imagens/logoatual.png" alt="Logo"></div>
    <nav>
      <ul>
          <li><a href="/inicio.php">Voltar</a></li>
      </ul>
    </nav>
  </header>
<!-- Robo dormindo -->
<div class="robo-container">
    <img src="/videos/Robo_dormindo.gif" alt="Robo Dormindo">
</div>

<!-- Cronômetro -->
<div class="cronometro-container">
    <div id="tempo">00:00:00</div>
    <button onclick="startTimer()">Iniciar</button>
    <button onclick="stopTimer()">Parar</button>
    <button onclick="resetTimer()">Resetar</button>
</div>

<!-- Painel de amizades -->
<div class="tabela-amizades">
    <h3>Sugestões de Amizade</h3>
    <?php while($row = $resultSugestoes->fetch_assoc()): ?>
        <div class="usuario">
            <img src="/videos/<?php echo $row['foto']; ?>" alt="Foto" class="foto-perfil">
            <span><?php echo $row['nome']; ?></span>
        </div>
    <?php endwhile; ?>

    <h3>Amizades</h3>
    <?php while($row = $resultAmizades->fetch_assoc()): ?>
        <div class="usuario">
            <img src="/videos/<?php echo $row['foto']; ?>" alt="Foto" class="foto-perfil">
            <span><?php echo $row['nome']; ?></span>
        </div>
    <?php endwhile; ?>
</div>

<script>
let segundos = 0;
let timer;

function updateTimer() {
    segundos++;
    let hrs = String(Math.floor(segundos/3600)).padStart(2,'0');
    let mins = String(Math.floor((segundos%3600)/60)).padStart(2,'0');
    let secs = String(segundos%60).padStart(2,'0');
    document.getElementById('tempo').textContent = `${hrs}:${mins}:${secs}`;
}

function startTimer() {
    if(!timer) timer = setInterval(updateTimer, 1000);
}
function stopTimer() {
    clearInterval(timer);
    timer = null;
}
function resetTimer() {
    stopTimer();
    segundos = 0;
    document.getElementById('tempo').textContent = '00:00:00';
}
</script>

</body>
</html>
