const express = require("express");
const WebSocket = require("ws");
require("dotenv").config();

const app = express();
const PORT = process.env.PORT || 3001;

// HTTP API - for health check
app.get("/", (req, res) => {
  res.send("Chat API is running!");
});

// Boost API server
const server = app.listen(PORT, () => {
  console.log(`API server running on port ${PORT}`);
});

// Create WebSocket Server(chat core)
const wss = new WebSocket.Server({ server });

// Handle user connection events
wss.on("connection", (ws) => {
  console.log("😎 Oh my, look who's here love U❤");

  // Core feature of chatroom - message broadcasting
  ws.on("message", (message) => {
    console.log("Message received:", message.toString());

    // Post message to all connected clients
    wss.clients.forEach((client) => {
      if (client.readyState === WebSocket.OPEN) {
        client.send(message.toString());
      }
    });
  });

  ws.on("close", () => {
    console.log("❣🤠 Miss you❤");
  });
});
