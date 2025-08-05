const https = require('https');
const http = require('http');
const fs = require('fs');
const path = require('path');

// Genera certificati self-signed temporanei
const selfsigned = require('selfsigned');
const attrs = [{ name: 'commonName', value: 'slamin.local' }];
const pems = selfsigned.generate(attrs, { days: 365 });

// Salva i certificati
fs.writeFileSync('server.key', pems.private);
fs.writeFileSync('server.crt', pems.cert);

// Proxy HTTPS
const httpsServer = https.createServer({
    key: pems.private,
    cert: pems.cert
}, (req, res) => {
    // Reindirizza a HTTP locale
    const options = {
        hostname: '127.0.0.1',
        port: 80,
        path: req.url,
        method: req.method,
        headers: req.headers
    };

    const proxyReq = http.request(options, (proxyRes) => {
        res.writeHead(proxyRes.statusCode, proxyRes.headers);
        proxyRes.pipe(res);
    });

    req.pipe(proxyReq);
});

httpsServer.listen(443, () => {
    console.log('Proxy HTTPS attivo su https://slamin.local:443');
    console.log('Reindirizza a http://127.0.0.1:80');
    console.log('IMPORTANTE: Aggiungi slamin.local al file hosts');
});

// Gestione errori
httpsServer.on('error', (err) => {
    if (err.code === 'EACCES') {
        console.error('Errore: Porta 443 richiede privilegi di amministratore');
        console.error('Esegui: sudo node https-proxy.js');
    } else {
        console.error('Errore:', err);
    }
}); 