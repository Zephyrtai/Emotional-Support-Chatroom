<?php
/*
Template Name: Chatroom
*/

get_header(); ?>

<style>
/* Change as needed */
.chat-wrap { max-width: 820px; margin: 32px auto; border: 1px solid #ddd; border-radius: 8px; padding: 16px; background:#fff; }
.chat-box { height: 360px; overflow-y: auto; border: 1px solid #eee; padding: 12px; margin-bottom: 12px; background:#fafafa; }
.chat-msg { margin: 6px 0; }
.chat-msg .meta { font-size: 12px; color: #666; margin-bottom:4px; }
.chat-input { display:flex; gap:8px; }
.chat-input input[type="text"] { flex:1; padding:10px; border:1px solid #ccc; border-radius:4px; }
.chat-input button { padding:10px 14px; border-radius:4px; border:0; background:#0073aa; color:white; cursor:pointer; }
.system { color:#888; font-style:italic; }
</style>

<div class="chat-wrap" id="chatroom-root">
  <h3> 🥰 Emotional Support Chatroom</h2>

  <div id="chatBox" class="chat-box" aria-live="polite"></div>

  <div class="chat-input">
    <input id="chatInput" type="text" placeholder="A kind word warms three winters, A harsh word chills even in June." autocomplete="off" />
    <button id="sendBtn">Send</button>
  </div>
</div>

<script>
(function(){
  // Preventing XXS by escaping HTML
  function escapeHtml (unsafe) {
    return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  const chatBox = document.getElementById('chatBox');
  const chatInput = document.getElementById('chatInput');
  const sendBtn = document.getElementById('sendBtn');

  // Create a temporary nickname (anonymous)
  const nickname = 'User' + Math.floor(Math.random()*9000 + 1000);

  // Construct ws URL: use same host/port, via /chat path (Nginx proxy)
  const scheme = (location.protocol === 'https:') ? 'wss' : 'ws';
  const wsUrl = scheme + '://' + location.host + '/chat/';
  let ws;

  function addSystem(text) {
    const div = document.createElement('div');
    div.className = 'system';
    div.textContent = text;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function addMessage(nick, text) {
    const wrap = document.createElement('div');
    wrap.className = 'chat-msg';

    const meta = document.createElement('div');
    meta.className = 'meta';
    meta.innerHTML = '<strong>' + escapeHtml(nick) + '</strong> • ' + new Date().toLocaleTimeString();

    const body = document.createElement('div');
    body.className = 'body';
    body.innerHTML = escapeHtml(text);

    wrap.appendChild(meta);
    wrap.appendChild(body);
    chatBox.appendChild(wrap);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function connect() {
    addSystem('Connecting to chat server...');
    try {
      ws = new WebSocket(wsUrl);
    } catch (e) {
      addSystem('WebSocket constructor failed: ' + e.message);
      return;
    }

    ws.addEventListener('open', function () {
      addSystem('Connected as ' + nickname);
      // Send join notification (optional)
      ws.send(JSON.stringify({type: 'join', nick: nickname}));
    });

    ws.addEventListener('message', function (evt) {
      // The agreed message format is simple JSON: { type: 'msg'|'join'|'leave', nick: 'User123', text: '...' }
      let payload = evt.data;
      try {
        const obj = JSON.parse(payload);
        if (obj.type === 'msg') {
          addMessage(obj.nick || 'Anon', obj.text || '');
        } else if (obj.type === 'join') {
          addSystem((obj.nick || 'Someone') + ' joined the chat');
        } else if (obj.type === 'leave') {
          addSystem((obj.nick || 'Someone') + ' left the chat');
        } else {
          // Non-JSON legacy format: Display directly
          addMessage('Server', payload);
        }
      } catch (err) {
        // Non-JSON text displayed directly
        addMessage('Server', payload);
      }
    });

    ws.addEventListener('close', function () {
      addSystem('Disconnected from chat server. Reconnecting in 3s...');
      setTimeout(connect, 3001);
    });

    ws.addEventListener('error', function (err) {
      addSystem('WebSocket error (see console).');
      console.error('WS error', err);
    });
  }

  // Send message fuction
  function send() {
    if (!ws || ws.readyState !== WebSocket.OPEN) {
      addSystem('Not connected - retrying connection...');
      connect();
      return;
    }
    const txt = chatInput.value.trim();
    if (!txt) return;
    const payload = { type: 'msg', nick: nickname, text: txt };
    ws.send(JSON.stringify(payload));
    chatInput.value = '';
  }

  sendBtn.addEventListener('click', send);
  chatInput.addEventListener('keydown', function(e){
    if (e.key === 'Enter') send();
  });

  // launch connection
  connect();
})();
</script>

<?php get_footer(); ?>
