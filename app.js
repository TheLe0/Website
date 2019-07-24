const express = require('express');
const hbs = require('hbs');

const app = express();
const porta = process.env.PORT || 8080;

hbs.registerPartials(__dirname + '/views/partials')
app.set('view engine', 'hbs');
app.use(express.static('./public'));

hbs.registerHelper('getCurrentYear', () => {
  return new Date().getFullYear();
});

app.get('/', function(req, res){
  res.render('index.hbs', {
    pageTitle: 'Welcome',
    welcomeMessage: 'Home is where the coffee is',
  });
});

app.get('*', function(req, res){
  res.redirect('/');
});

app.listen(porta, () => {
  console.log(`Servidor subiu na porta ${porta}`);
});
