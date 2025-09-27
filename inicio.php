  <?php
session_start();
include('conexao.php');

$avatar = 'imagens/usuarios/default.jpg'; // default
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT foto FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $stmt->bind_result($foto_usuario);
    $stmt->fetch();
    $stmt->close();

    if (!empty($foto_usuario) && file_exists($foto_usuario)) {
        $avatar = $foto_usuario;
    }
}
?>
  <!DOCTYPE html> <html lang="pt-BR"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Estudos IA</title> <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;500&display=swap" rel="stylesheet"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <style> * { margin: 0; padding: 0; box-sizing: border-box; } body { font-family: 'Roboto', sans-serif; background: white; color: #333; line-height: 1.6; } 
header .logo img { max-height: 100%; /* a imagem nunca ultrapassa a altura do header */ width: auto; /* mantém proporção */ display: block; margin-left: -90px; }
  header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 70px;
  background: #ffffffcc;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  z-index: 1000;
}
  /* Navegação */
nav ul {
  list-style: none;
  display: flex;
  gap: 20px;
  align-items: center;
  margin: 0;
} 
  nav ul li { position: relative; } 
  nav ul li a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 5px 10px;
  border-radius: 8px;
  transition: background 0.3s;
} 
  nav ul li a:hover {
  background: #f0f0f0;
}
.avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #3f7c72;
}

/* Últimos itens à direita */
nav ul li:last-child {
  margin-left: 20px;
}
  footer#contato{ --logo-width: 600px; /* largura desejada da imagem */ --logo-overlap: 80px; /* quanto a logo invade visualmente (ajuste se quiser) */ position: relative; background: #bdebe3ff; color: black; text-align: center; box-sizing: border-box; /* espaço no topo para que a logo sobreposta não cubra o texto */ padding: calc(var(--logo-overlap) + 12px) 20px 12px; margin: 0; /* garante que o footer não acrescente gap */ overflow: hidden; /* evita que a imagem faça o footer crescer */ } /* contêiner absoluto centralizado da logo */ 
  footer#contato .logo{ position: absolute; top: 0; left: 50%; transform: translate(-50%, -40%); /* ajuste vertical fino: -40% está ok por padrão */ width: var(--logo-width); max-width: calc(100% - 40px); pointer-events: none; /* LIMITA a altura para NÃO criar espaço embaixo (evita que imagem gere scroll extra) */ max-height: calc(100vh - 100px); /* não deixa a logo ultrapassar a viewport */ overflow: hidden; } /* imagem em si: mantém proporção e respeita a max-height do pai */ 
  footer#contato .logo img{ width: 100%; height: auto; display: block; /* evita gaps inline */ max-height: 100%; /* respeita o max-height do container .logo */ object-fit: contain; border: 0; } /* textos do footer — margens controladas */ 
  footer#contato p{ margin: 8px 0 0; font-size: 0.9em; line-height: 1.2; } footer#contato p:last-child{ margin-bottom: 0; } /* Botão */ .btn { display: inline-block; padding: 0.6rem 1.4rem; background: #3f7c72; color: white; border-radius: 25px; font-weight: 500; transition: 0.3s; text-decoration: none; } .btn:hover { background: #2a5c55; } /* Banner */ 
  .banner { margin-top: 5%; width: 100%; min-height:30vh; background-color: #3f7c72; /* cor de fundo sólida */ display: flex; justify-content: center; /* centraliza conteúdo horizontalmente */ align-items: center; /* centraliza conteúdo verticalmente */ padding: 2rem 0; } .banner-conteudo { display: flex; justify-content: space-between; align-items: center; width: 90%; /* mantém um pequeno espaçamento lateral */ max-width: 1200px; gap: 2rem; flex-wrap: wrap; /* adapta em telas menores */ } .banner-texto { color: white; max-width: 600px; } .banner-texto h1 { font-family: 'Pacifico', cursive; font-size: 3rem; margin-bottom: 1rem; } .banner-texto p { font-size: 1.2rem; margin-bottom: 1.5rem; } .banner .btn { background: white; color: #3f7c72; font-weight: bold; padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; transition: 0.3s; } .banner .btn:hover { background: #bdebe3; color: #2a5c55; } .banner-img img { width: 300px; max-width: 100%; border-radius: 15px; } @media(max-width: 768px) { .banner-conteudo { flex-direction: column; text-align: center; } .banner-texto h1 { font-size: 2.2rem; } .banner-img img { width: 200px; } } /* Seções */ section { padding: 4rem 2rem; margin: 0 auto; } section h2 { font-size: 2rem; text-align: center; color: #3f7c72; margin-bottom: 2rem; } /* Cards */ .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; } .card { background: #fff; border-radius: 20px; padding: 2rem; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 1px solid #bdebe3; transition: transform 0.3s ease, box-shadow 0.3s ease; text-decoration: none; color: inherit; } .card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); } .card i { font-size: 2rem; color: #3f7c72; margin-bottom: 1rem; } .card h3 { color: #3f7c72; margin-bottom: 1rem; } .cronometro-icone { position: fixed; bottom: 20px; right: 20px; background: #3f7c72; color: white; padding: 1rem; border-radius: 50%; font-size: 1.5rem; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.3s; text-decoration: none; z-index: 1000; } .cronometro-icone:hover { background: #2a5c55; } /* Footer */ footer { background: #3f7c72; color: white; text-align: center; padding: 2rem; margin-top: 3rem; } </style> 
  </head> <body> <!-- Header --> <header>
  <div class="logo"><img src="/imagens/logoatual.png" alt="Logo"></div>
  <nav>
    <ul>
      <li>
        <a href="/editar_usuario.php" class="user-link">
          <img src="<?php echo $avatar; ?>" alt="Avatar" class="avatar">
          Editar Usuário
        </a>
      </li>
      <li>
        <a href="/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
      </li>
    </ul>
  </nav>
</header> <!-- Banner Retangular --> <section class="banner"> <div class="banner-conteudo"> <div class="banner-texto"> <h1>Bem-vindo ao Estudos IA</h1> <p>Seu espaço inteligente para aprender, se organizar e evoluir</p> <a href="#funcoes" class="btn">Explorar Funções</a> </div> <div class="banner-img"> <img src="https://i.pinimg.com/originals/a0/ce/6b/a0ce6ba41bf31c32fbced60d9070b0fe.gif" alt="Robôzinho IA"> </div> </div> </section> <!-- Funções --> <section id="funcoes"> <h2>Funções Principais</h2> <div class="cards"> <a href="anotacoes.php" class="card"> <i class="fa-solid fa-pen-to-square"></i> <h3>Anotações</h3> <p>Crie e organize suas anotações de estudo de forma prática.</p> </a> <a href="flashcards.php" class="card"> <i class="fa-solid fa-clone"></i> <h3>Flashcards</h3> <p>Revise conteúdos com cartões interativos para melhorar sua memória.</p> </a> <a href="plano_estudos.php" class="card"> <i class="fa-solid fa-calendar-days"></i> <h3>Plano de Estudos</h3> <p>Monte seu cronograma personalizado e nunca perca prazos.</p> </a> </div> </section> <!-- Rede Social --> <section id="social"> <h2>Rede Social</h2> <div class="cards"> <a href="rede.php" class="card"> <i class="fa-solid fa-users"></i> <h3>Comunidade</h3> <p>Conecte-se, compartilhe conquistas e troque experiências.</p> </a> <a href="feed.php" class="card"> <i class="fa-solid fa-comments"></i> <h3>Interação</h3> <p>Participe do feed, curta, comente e incentive outros estudantes.</p> </a> </div> </section> <!-- Ícone Cronômetro --> <a href="cronometro.php" class="cronometro-icone" title="Ir para Cronômetro"> <i class="fa-solid fa-stopwatch"></i> </a> <!-- Footer --> <footer> <p>&copy; 2025 Estudos IA. Todos os direitos reservados.</p> </footer> <!-- Script Cronômetro --> <script> let segundos = 0, minutos = 0, horas = 0; let intervalo; function doisDigitos(digito) { return digito < 10 ? '0' + digito : digito; } function atualizar() { document.getElementById('tempo').innerText = doisDigitos(horas) + ":" + doisDigitos(minutos) + ":" + doisDigitos(segundos); } function iniciar() { if (!intervalo) { intervalo = setInterval(() => { segundos++; if (segundos == 60) { segundos = 0; minutos++; if (minutos == 60) { minutos = 0; horas++; } } atualizar(); }, 1000); } } function pausar() { clearInterval(intervalo); intervalo = null; } function zerar() { clearInterval(intervalo); intervalo = null; segundos = 0; minutos = 0; horas = 0; atualizar(); } atualizar(); </script> </body> </html>