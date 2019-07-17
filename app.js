require('dotenv').config();

const mariadb = require('mariadb');
const express = require('express');

const app = express();

mariadb.createConnection({
  user: process.env.DB_USER,
  password: process.env.DB_SENHA,
  database: process.env.DB_NAME,
});

app.get('/', (req, res) => {
  res.render('index.hbs', {
    pageTitle: 'Home Page',
    welcomeMessage: 'Welcome to my blog',
  });
});

app.listen(3000, () => {
  // eslint-disable-next-line no-console
  console.log('Servidor subiu na porta 3000');
});
