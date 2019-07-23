const express = require('express');
const i18n = require('i18n');

i18n.configure({
  locales:['en', 'pt-br'],
  directory: __dirname + '/config/locales'
});

const app = express();
const porta = process.env.PORT || 8080;

app.set('view engine', 'hbs');
app.use(express.static('./public'));

app.get('/', (req, res) => {
  res.render('index.hbs', {
    pageTitle: 'Home Page',
    welcomeMessage: 'Welcome to my blog',
  });
});

app.listen(porta, () => {
  console.log(`Servidor subiu na porta ${porta}`);
});
