require('dotenv').config();

const express = require('express');

const app = express();

app.set('view engine', 'hbs');
app.use(express.static('./public'));

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
