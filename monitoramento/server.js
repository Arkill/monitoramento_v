const express = require('express');
const mongoose = require('mongoose');
const dotenv = require('dotenv');
const bodyParser = require('body-parser');

// Carregar variáveis de ambiente do arquivo .env
dotenv.config();

const app = express();

// Middleware para analisar o corpo das requisições
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Conectar ao MongoDB (sem usar opções obsoletas)
mongoose.connect(process.env.DB_URI)
  .then(() => {
    console.log("Conectado ao banco de dados MongoDB");
  })
  .catch((err) => {
    console.log("Erro ao conectar ao banco de dados: ", err);
  });

// Definir as rotas
const authRoutes = require('./routes/authRoutes'); 
app.use('/api/auth', authRoutes); 

// Definir a porta do servidor
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Servidor rodando na porta ${PORT}`);
});

// Função para alternar a visibilidade do dropdown de configurações
function toggleSettingsDropdown() {
  var dropdown = document.getElementById("settings-dropdown-menu");
  dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}

// Fechar o dropdown se clicar fora dele
document.addEventListener("click", function(event) {
  var dropdown = document.getElementById("settings-dropdown-menu");
  var button = document.querySelector(".btn-group button");
  if (!dropdown.contains(event.target) && !button.contains(event.target)) {
      dropdown.style.display = "none";
  }
});
