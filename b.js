const sugestoes = [
  "Revise o conteúdo de Matemática!",
  "Tente fazer um flashcard sobre Biologia.",
  "Releia suas anotações da última aula.",
  "Estude por 25 minutos e faça uma pausa de 5!",
  "Pesquise sobre a Revolução Industrial.",
  "Assista a um vídeo de Química no YouTube.",
  "Resolva uma questão do ENEM agora!"
];

let sugestaoAtual = "";

function trocarSugestao() {
  let novaSugestao = sugestaoAtual;
  while (novaSugestao === sugestaoAtual) {
    novaSugestao = sugestoes[Math.floor(Math.random() * sugestoes.length)];
  }
  sugestaoAtual = novaSugestao;

  const botao = document.getElementById('sugestao-btn');
  if (botao) {
    botao.innerText = sugestaoAtual;
  }
}

function verificarEnter(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    const inputElem = event.target;

    const pergunta = inputElem.value.trim();

    if (pergunta !== "") {
      inputElem.value = ""; // limpa
      const id = Date.now().toString();
      let conversas = JSON.parse(localStorage.getItem("conversas") || "[]");
      conversas.push({ id, texto: pergunta });
      localStorage.setItem("conversas", JSON.stringify(conversas));
      window.location.href = `resposta.html?id=${id}`;
    }
  }
}

// Quando a página carregar
window.addEventListener("DOMContentLoaded", () => {
  trocarSugestao();
  setInterval(trocarSugestao, 3000); // 30 segundos

  const botao = document.getElementById("sugestao-btn");
  if (botao) {
    botao.addEventListener("click", trocarSugestao);
  }

  // Limpa todos os inputs com class fala-input
  const inputs = document.querySelectorAll(".fala-input");
  inputs.forEach(input => input.value = "");
});

// Recarregar se voltar para a página
window.addEventListener('pageshow', function (event) {
  if (event.persisted) {
    window.location.reload();
  }
});
