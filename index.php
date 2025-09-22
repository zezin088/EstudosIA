<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>EstudosIA</title>
  <style>
    @font-face {
      font-family: 'HelloMorgan';
      src: url(/fonts/HelloMorgan.ttf);
    }
    @font-face {
      font-family: 'Jojoba';
      src: url(/fonts/Jojoba.otf);
    }
    @font-face {
      font-family: 'KGMissKindergarten';
      src: url(/fonts/KGMissKindergarten.ttf);
    }
    @font-face {
      font-family: 'Papernotes';
      src: url(/fonts/Papernotes.otf);
    }
    @font-face {
      font-family: 'RougeVintage';
      src: url(/fonts/RougeVintage.ttf);
    }
    @font-face {
      font-family: 'SimpleHandmade';
      src: url(/fonts/SimpleHandmade.ttf);
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", Arial, sans-serif;
      scroll-behavior: smooth;
    }

    body {
      background: #fff;
      color: #000000ff;
    }
header .logo img {
  max-height: 100%; /* a imagem nunca ultrapassa a altura do header */
  width: auto;      /* mantém proporção */
  display: block;
  margin-left: -90px;
}
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 70px; /* altura fixa do header */
  background: #ffffff85;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0px; /* padding só horizontal */
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  z-index: 1000;
  overflow: hidden; /* evita que a imagem faça o header crescer */
}

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
      margin-left: -450px;

    }

    nav ul li {
      position: relative;
      
    }

    nav ul li a {
      text-decoration: none;
      color: #000000ff;
      font-size: 1em;
      padding-bottom: 5px;
      transition: color 0.3s;
    }

    nav ul li a.active::after,
    nav ul li a:hover::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      background: #bdebe3ff;
      border-radius: 2px;
      transition: 0.3s;
    }

    nav ul li a.active,
    nav ul li a:hover {
      color: #3f7c72ff;
    }

#home {
  min-height: 100vh;
  display: flex;
  flex-direction: row; /* garante que fique em linha */
  align-items: center;
  padding: 0 150px; /* pouco padding lateral */
  background: linear-gradient(135deg, #fff 60%, #f9f4ef 100%);
  flex-wrap: nowrap; /* evita quebra de linha */
}


.home-text {
  max-width: 500px;
  text-align: center;
  
}

.home-text h1 {
  font-family: 'SimpleHandmade';
  font-size: 5em;
  color: #3f7c72ff;
  margin-bottom: 0px;
}
.home-text h2 {
  font-family: 'SimpleHandmade';
  text-align: center;
  align-items: center;
  justify-content: center;
  font-size: 2.8em;
  color: #3f7c72ff;
  margin-bottom: 20px;
}
.home-text p {
  font-size: 1.2em;
  margin-bottom: 30px;
  line-height: 1.6;
  color: #555;
  
}

.btn {
  text-align: center;
  align-items: center;
  justify-content: center;
  display: inline-block;
  padding: 14px 30px;
  background: #3f7c72ff;
  color: white;
  border-radius: 30px;
  text-decoration: none;
  font-weight: bold;
  font-size: 1.1em;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: 0.3s ease;
}
.btn:hover {
  background: #1e3834ff;
  transform: translateY(-2px);
}
.home-img {
  margin-left: auto; /* empurra a imagem para a direita */
}
.home-img img {
  width: 520px;
  max-width: 100%;
  height: auto;
  border-radius: 20px 0 0 20px;
  display: block;
}


.home-img img:hover {
  transform: scale(1.05);
}

    /* SOBRE */
    section {
      min-height: 100vh;
      padding: 100px 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
    }

    #sobre h2,
    #projeto h2 {
      font-family: 'SimpleHandmade';
      font-size: 2.2em;
      color: #3f7c72ff;
      margin-bottom: 20px;
    }

    #sobre p,
    #projeto p {
      max-width: 800px;
      font-size: 1.1em;
      line-height: 1.6;
      color: #444;
    }

    #projeto {
        background: linear-gradient(135deg, #fffbfbff 60%, #f9f4ef 100%);
    }

/* Só footer#contato e .logo — nada em html/body */
footer#contato{
  --logo-width: 600px;     /* largura desejada da imagem */
  --logo-overlap: 80px;    /* quanto a logo invade visualmente (ajuste se quiser) */

  position: relative;
  background: #bdebe3ff;
  color: black;
  text-align: center;
  box-sizing: border-box;

  /* espaço no topo para que a logo sobreposta não cubra o texto */
  padding: calc(var(--logo-overlap) + 12px) 20px 12px;
  margin: 0;               /* garante que o footer não acrescente gap */
  overflow: hidden;  /* evita que a imagem faça o footer crescer */
}

/* contêiner absoluto centralizado da logo */
footer#contato .logo{
  position: absolute;
  top: 0;
  left: 50%;
  transform: translate(-50%, -40%); /* ajuste vertical fino: -40% está ok por padrão */
  width: var(--logo-width);
  max-width: calc(100% - 40px);
  pointer-events: none;

  /* LIMITA a altura para NÃO criar espaço embaixo (evita que imagem gere scroll extra) */
  max-height: calc(100vh - 100px); /* não deixa a logo ultrapassar a viewport */
  overflow: hidden;
}

/* imagem em si: mantém proporção e respeita a max-height do pai */
footer#contato .logo img{
  width: 100%;
  height: auto;
  display: block;       /* evita gaps inline */
  max-height: 100%;     /* respeita o max-height do container .logo */
  object-fit: contain;
  border: 0;
}

/* textos do footer — margens controladas */
footer#contato p{
  margin: 8px 0 0;
  font-size: 0.9em;
  line-height: 1.2;
}
footer#contato p:last-child{
  margin-bottom: 0;
}
  </style>
</head>
<body>
  <header>
    <div class="logo"><img src="/imagens/logoatual.png" alt=""></div>
    <nav>
      <ul>
        <li><a href="#home" class="active">Home</a></li>
        <li><a href="#sobre">Sobre o Site</a></li>
        <li><a href="#projeto">Por que o Projeto</a></li>
        <li><a href="#contato">Contato</a></li>
      </ul>
    </nav>
  </header>

<!-- HOME -->
<section id="home">
  <div class="home-text">
    <h1>Seja bem-vindo(a)</h1>
    <h2>ao Estudos IA!</h2>
    <p>Um cantinho criado para você estudar com leveza, foco e tecnologia. Aqui você encontra ferramentas inteligentes para organizar sua rotina e aprender de forma mais simples.</p>
    <a href="/login.php" class="btn">Começar Agora</a>
  </div>
  <div class="home-img">
    <img src="/videos/robofb.gif" alt="Imagem de estudo aconchegante">
  </div>
</section>

  <!-- SOBRE O SITE -->
  <section id="sobre">
    <h2>Sobre o Site</h2>
    <p>O EstudosIA foi pensado para ser um apoio diário nos seus estudos. Nosso objetivo é oferecer um ambiente digital que combina inteligência artificial com ferramentas práticas.</p>
  </section>

  <!-- PORQUE O PROJETO -->
  <section id="projeto">
    <h2>Por que decidimos fazer o Projeto</h2>
    <p>Sabemos que estudar pode ser cansativo e, às vezes, solitário. Criamos este projeto para trazer mais leveza e praticidade ao processo de aprendizado, ajudando estudantes a se sentirem acompanhados e motivados.</p>
  </section>

<!-- CONTATO -->
<footer id="contato">
  <div class="logo">
    <img src="/imagens/logoatual.png" alt="EstudosIA">
  </div>

  <p>Contate-nos: contato@estudosia.com</p>
  <p>&copy; 2025 EstudosIA. Todos os direitos reservados.</p>
</footer>

  <script>
    // Marcação do menu conforme rolagem
    const sections = document.querySelectorAll("section, footer");
    const navLinks = document.querySelectorAll("nav ul li a");

    window.addEventListener("scroll", () => {
      let current = "";
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        if (pageYOffset >= sectionTop) {
          current = section.getAttribute("id");
        }
      });

      navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href").includes(current)) {
          link.classList.add("active");
        }
      });
    });
  </script>
</body>
</html>
