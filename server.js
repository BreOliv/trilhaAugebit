// node server js


const express = require('express');
const path = require('path');

const app = express();
const PORT = 3000;
const HOST = '10.136.23.91'; // IP específico

// Serve a pasta "arquivos" como raiz do site
app.use(express.static(path.join(__dirname, 'sistema-web')));

// Rota fallback para o index.html (opcional para SPAs ou fallback geral)
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'sistema-web', 'index.html'));
});

// Inicia o servidor no IP e porta especificados
app.listen(PORT, HOST, () => {
  console.log(`Servidor rodando em http://${HOST}:${3000}`);
});