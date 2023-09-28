const express = require('express');
const app = express();
const https = require('https');
const fs = require('fs');

const options = {
  key: fs.readFileSync('http://localhost/year3csev/'),
  cert: fs.readFileSync('path_to_your_ssl_certificate.pem'),
};

app.use(express.static(__dirname)); // Serve static files from your project directory

app.get('/', (req, res) => {
  res.sendFile(__dirname + '/index.html');
});

const server = https.createServer(options, app); // Create an HTTPS server

const port = 3000; // Choose a port number

server.listen(port, () => {
  console.log(`Server running on https://localhost:${port}`);
});
