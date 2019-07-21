require('dotenv').config();

const mariadb = require('mariadb');
const express = require('express');

const app = express();

app.set('view engine', 'hbs');
app.use(express.static(`${__dirname}/public`));

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

app.listen(process.env.PORT, () => {
  // eslint-disable-next-line no-console
  console.log(`Servidor subiu na porta ${process.env.PORT}`);
});
