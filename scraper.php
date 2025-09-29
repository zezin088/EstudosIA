<?php
$url = "https://www.qconcursos.com/questoes-de-vestibular/materias/enem";

$html = file_get_contents($url);
$doc = new DOMDocument();
@$doc->loadHTML($html);

$xpath = new DOMXPath($doc);

$questoes = [];

// Pegando questões
$perguntas = $xpath->query("//div[contains(@class, 'question-enunciation')]");
$alternativasNodes = $xpath->query("//div[contains(@class, 'alternatives')]");

for ($i = 0; $i < min(5, $perguntas->length); $i++) {
    $pergunta = trim($perguntas->item($i)->textContent);
    $alternativas = [];

    if ($i < $alternativasNodes->length) {
        foreach ($alternativasNodes->item($i)->getElementsByTagName("li") as $alt) {
            $alternativas[] = trim($alt->textContent);
        }
    }

    $questoes[] = [
        "pergunta" => $pergunta,
        "alternativas" => $alternativas,
        "correta" => 0 // Placeholder (ajustar se conseguir puxar a correta)
    ];
}

file_put_contents("questoes.json", json_encode($questoes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo "✅ Arquivo 'questoes.json' criado!";
