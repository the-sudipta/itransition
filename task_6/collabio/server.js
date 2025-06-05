const http = require('http');
const WebSocket = require('ws');
const { setupWSConnection } = require('y-websocket/utils');

const port = 1234;
const server = http.createServer();
const wss = new WebSocket.Server({ server });

wss.on('connection', (conn, req) => {
    setupWSConnection(conn, req);
});

server.listen(port, () => {
    console.log(`🟢 y-websocket server running at ws://localhost:${port}`);
});
