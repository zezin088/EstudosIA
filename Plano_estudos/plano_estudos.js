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
    " Biologia - 1h/dia",
    " Português - 1h/dia",
    " Redação - 3 textos",
    "Filosofia - 1h/dia",
    " Revisão geral",
    "Simulado final"
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
  
    fetch('salvar_dados.php', {
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
  const excluir = document.createElement('button');
excluir.textContent = 'Excluir';
excluir.className = 'botao-acao';
excluir.onclick = () => {
    if(!semanaAtual) return alert('Selecione uma semana.');

    if(confirm('Deseja realmente excluir todos os itens da semana?')){
        // Remove do front-end
        const container = document.getElementById('conteudo-semanal');
        container.innerHTML = '';
        planos[semanaAtual] = [];

        // Envia para o backend
        fetch('plano_estudos.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({excluir: true, semana: semanaAtual})
        })
        .then(res => res.text())
        .then(msg => alert(msg))
        .catch(err => alert('Erro ao excluir plano: ' + err));
    }
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

function salvarPlano(){
  if(!semanaAtual) return alert('Selecione uma semana.');

  const container = document.getElementById('conteudo-semanal');
  const textareas = container.querySelectorAll('textarea.item-plano');
  const itens = Array.from(textareas).map(t => t.value);

  fetch('plano_estudos.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({semana: semanaAtual, itens: itens})
  })
  .then(res => res.text())
  .then(msg => {
      alert(msg);
      planos[semanaAtual] = itens; // atualiza o JS
  })
  .catch(err => alert('Erro ao salvar plano: ' + err));
}

function salvarPlano() {
  if (!semanaAtual) return alert('Selecione uma semana.');
  const container = document.getElementById('conteudo-semanal');
  const textareas = container.querySelectorAll('textarea.item-plano');
  const itens = Array.from(textareas).map(t => t.value.trim()).filter(t => t);

  if (itens.length === 0) return alert('Não há itens para salvar.');

  fetch('estudo.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ semana: semanaAtual, itens: itens })
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    planos[semanaAtual] = itens; // atualiza plano local
  })
  .catch(err => alert('Erro ao salvar plano: ' + err));
}
