import requests
from bs4 import BeautifulSoup
import json

# Exemplo de site de questões (pode trocar pela fonte desejada)
URL = "https://www.qconcursos.com/questoes-de-vestibular/materias/enem"

response = requests.get(URL)
soup = BeautifulSoup(response.text, "html.parser")

questoes = []

# Exemplo de seleção de perguntas (ajustar conforme a estrutura do site escolhido)
for item in soup.select(".questions-list .question-card")[:5]:  # pega só as 5 primeiras
    pergunta = item.select_one(".question-enunciation").get_text(strip=True)

    alternativas = [alt.get_text(strip=True) for alt in item.select(".alternatives .item")]

    # Geração fake da alternativa correta (já que alguns sites escondem a resposta)
    correta = 0 if alternativas else None

    questoes.append({
        "pergunta": pergunta,
        "alternativas": alternativas,
        "correta": correta
    })

# Salva no JSON
with open("questoes.json", "w", encoding="utf-8") as f:
    json.dump(questoes, f, indent=2, ensure_ascii=False)

print("✅ Arquivo 'questoes.json' gerado com sucesso!")
