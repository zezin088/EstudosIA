const express = require('express');
const multer = require('multer');
const path = require('path');
const app = express();

app.use(express.static('public')); // pasta para frontend

// Configuração do multer para salvar arquivos em 'uploads'
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, 'uploads/');
  },
  filename: function (req, file, cb) {
    // Mantém o nome original
    cb(null, file.originalname);
  }
});
const upload = multer({ 
  storage,
  fileFilter: (req, file, cb) => {
    // aceita só PDFs
    if (file.mimetype === 'application/pdf') {
      cb(null, true);
    } else {
      cb(new Error('Só arquivos PDF são permitidos!'));
    }
  }
});

// Simulando um banco simples na memória
let arquivos = [];

// Endpoint para upload
app.post('/upload', upload.single('file'), (req, res) => {
  if (!req.file) {
    return res.status(400).json({ error: 'Nenhum arquivo enviado.' });
  }
  
  // Salva os dados do arquivo na "base"
  arquivos.push({
    nome: req.file.originalname,
    aberto: new Date().toLocaleDateString(),
    proprietario: "Usuário Exemplo",
    atividade: "Arquivo enviado"
  });

  res.json({ message: 'Arquivo enviado com sucesso!' });
});

// Endpoint para listar arquivos
app.get('/arquivos', (req, res) => {
  res.json(arquivos);
});

// Para servir os arquivos enviados
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

app.listen(3000, () => console.log('Servidor rodando na porta 3000'));
