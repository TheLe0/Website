const express = require('express');
const i18n = require('i18n');
const hbs = require('hbs');

i18n.configure({
  locales:['en', 'pt'],
  defaultLocale : "en",
  directory: __dirname + '/config/locales'
});

const app = express();
const porta = process.env.PORT || 8080;

app.set('view engine', 'hbs');
app.use(express.static('./public'));
app.use(i18n.init);

hbs.registerHelper('__', function () {
  return i18n.__.apply(this, arguments);
});
hbs.registerHelper('__n', function () {
  return i18n.__n.apply(this, arguments);
});

app.get('*', function(req, res){
  res.render('index.hbs', {
    pageTitle: 'Home Page',
    welcomeMessage: 'Welcome to my Website',
  });
});

app.listen(porta, () => {
  console.log(`Servidor subiu na porta ${porta}`);
});
