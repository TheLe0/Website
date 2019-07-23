const express = require('express');

const app = express();
const porta = process.env.PORT || 8080;

app.set('view engine', 'hbs');
app.use(express.static('./public'));

app.get('/', function(req, res){
  res.render('index.hbs', {
    pageTitle: 'Home Page',
    welcomeMessage: 'Welcome to my Website',
  });
});

app.get('*', function(req, res){
  res.redirect('/');
});

app.listen(porta, () => {
  console.log(`Servidor subiu na porta ${porta}`);
});
