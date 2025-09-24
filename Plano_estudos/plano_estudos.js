// Dados do plano de estudos
const planos = {
  1: [
    "📘 Matemática - 1h/dia",
    "📗 Português - 45min/dia",
    "✍️ Redação - 3x por semana",
    "📕 História - 1h/dia",
    "🔁 Revisão - sábado",
    "📝 Simulado - domingo"
  ],
  2: [
    "📘 Física - 1h/dia",
    "📗 Gramática - 45min/dia",
    "✍️ Redação - tema novo",
    "📕 Geografia - 1h/dia",
    "🔁 Revisão - sábado",
    "📝 Simulado - domingo"
  ],
  3: [
    "📘 Química - 1h/dia",
    "📗 Literatura - 1h/dia",
    "✍️ Redação - correção",
    "📕 Sociologia - 1h/dia",
    "🔁 Revisão - sábado",
    "📝 Simulado - domingo"
  ],
  4: [
    "📘 Biologia - 1h/dia",
    "📗 Português - 1h/dia",
    "✍️ Redação - 3 textos",
    "📕 Filosofia - 1h/dia",
    "🔁 Revisão geral",
    "📝 Simulado final"
  ]
};

// Função para mostrar o plano da semana
function mostrarSemana(semana) {
  const container = document.getElementById("conteudo-semanal");
  container.innerHTML = ""; // limpa conteúdo anterior

  // Cria textarea para cada item do plano
  planos[semana].forEach(item => {
    const textarea = document.createElement("textarea");
    textarea.value = item;
    textarea.rows = 2;
    textarea.classList.add("item-plano");
    container.appendChild(textarea);
  });

  // Container para botões
  const botoesContainer = document.createElement("div");
  botoesContainer.classList.add("acoes-container");

  // Botão Salvar
  botaoSalvar.onclick = () => {
    const caixas = container.querySelectorAll("textarea");
    const dados = Array.from(caixas).map(caixa => caixa.value);
  
    fetch('salvar_plano.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        usuario_id: 1, // aqui coloque a variável PHP do usuário logado
        semana: semanaAtual,
        itens: JSON.stringify(dados)
      })
    })
    .then(response => response.json())
    .then(data => {
      alert("Plano " + data.status + " com sucesso!");
    })
    .catch(error => {
      console.error("Erro ao salvar plano:", error);
    });
  };
  // Botão Excluir
  const botaoExcluir = document.createElement("button");
  botaoExcluir.type = "button";
  botaoExcluir.textContent = "Excluir";
  botaoExcluir.classList.add("botao-acao");
  botaoExcluir.onclick = () => {
    container.innerHTML = "";
    const mensagem = document.createElement("p");
    mensagem.classList.add("mensagem-plano");
    mensagem.textContent = "Plano apagado. Selecione novamente uma semana.";
    container.appendChild(mensagem);
  };

  // Botão Adicionar
  const botaoAdicionar = document.createElement("button");
  botaoAdicionar.type = "button";
  botaoAdicionar.textContent = "➕ Adicionar Item";
  botaoAdicionar.classList.add("botao-acao");
  botaoAdicionar.onclick = () => adicionarItem(semana);

  // Adiciona botões no container e no DOM
  botoesContainer.appendChild(botaoSalvar);
  botoesContainer.appendChild(botaoExcluir);
  botoesContainer.appendChild(botaoAdicionar);
  container.appendChild(botoesContainer);
}

// Função para adicionar um novo item
function adicionarItem(semana) {
  const novoItem = prompt("Digite o novo item para o plano de estudos:");
  if (novoItem && novoItem.trim() !== "") {
    planos[semana].push(novoItem.trim());
    mostrarSemana(semana); // Recarrega a lista com o novo item
  }
}