var inputValue = '';
var isLoading = false;

var mockResponses = {
  'default': 'ALPHA is analyzing your request. Based on current trends, we recommend checking out our latest tech arrivals or our summer fashion collection.',
  'tech': "Our trending tech products include the new Quantum X Smartphone, Alpha Buds Pro, and the Ultra-wide Curved Monitor. Would you like a detailed comparison?",
  'outfit': "For a beach party, I'd suggest our Linen Blend Relaxed Shirt paired with the Alpha Sport Chinos. Don't forget our polarized aviators for that extra style!",
  'smartphone': 'Comparing the top flagships: The Alpha Pro 5G offers the best camera (108MP), while the Neo S24 leads in battery life (2 days). Both are in stock.',
  'gadgets': 'Our highest-rated gadgets this month are the AI-Smart Home Hub and the Pulse Fitness Tracker. Both have a 4.9/5 rating.',
  'fashion': 'There are currently price drops of up to 40% on our Winter Clearance collection and a 15% early-bird discount on New Spring items.'
};

function findMockResponse(text) {
  var keys = Object.keys(mockResponses);
  var lower = text.toLowerCase();
  var key = keys.find(function(k){ return lower.includes(k); }) || 'default';
  return mockResponses[key];
}

function syncInput(val) {
  inputValue = val;
  document.getElementById('submit-welcome').disabled = !val.trim();
  var sc = document.getElementById('submit-conv');
  if(sc) sc.disabled = !val.trim() || isLoading;
}

function handleKeydown(e, type) {
  if(e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    if(type === 'input') submitFromWelcome();
    else handleSubmit();
  }
}

function submitFromWelcome() {
  if(!inputValue.trim()) return;
  showConversation();
  handleSubmit();
}

function handleSuggestion(text) {
  inputValue = text;
  showConversation();
  doSubmit(text);
}

function showConversation() {
  document.getElementById('welcome-screen').style.display = 'none';
  document.getElementById('conversation-screen').style.display = 'flex';
}

function handleSubmit() {
  if(!inputValue.trim() || isLoading) return;
  doSubmit(inputValue);
}

function doSubmit(text) {
  var userText = text;
  inputValue = '';
  document.getElementById('chat-input').value = '';
  var ta = document.getElementById('chat-textarea');
  if(ta) ta.value = '';
  
  appendMessage('user', userText);
  isLoading = true;
  
  // Appel à l'API backend (PHP)
  fetch('../../index.php?action=chatbot', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ message: userText })
  })
  .then(response => response.json())
  .then(data => {
    isLoading = false;
    appendMessage('ai', data.response);
  })
  .catch(error => {
    console.error('Error:', error);
    isLoading = false;
    appendMessage('ai', "Désolé, une erreur s'est produite lors de la connexion à l'IA.");
  });
}

function appendMessage(type, content) {
  var msgs = document.getElementById('messages');
  var div = document.createElement('div');
  div.className = 'chatbot__message chatbot__message--' + type;

  if(type === 'ai') {
    div.innerHTML = '<div class="chatbot__message-icon"><div class="chatbot__icon chatbot__icon--gradient chatbot__icon--small">' + headsetSVG() + '</div></div>' +
      '<div class="chatbot__message-content">' + escapeAndFormat(content) + '</div>';
  } else {
    div.innerHTML = '<div class="chatbot__message-content">' + escapeAndFormat(content) +
      '<div class="chatbot__message-bubble"></div><div class="chatbot__message-bubble chatbot__message-bubble--end"></div></div>';
  }
  msgs.appendChild(div);
  var s = document.getElementById('scroller');
  s.scrollTo({top: s.scrollHeight, behavior:'smooth'});
}

function escapeAndFormat(text) {
  var escaped = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  escaped = escaped.replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
  var lines = escaped.split('\n');
  var html = '';
  
  lines.forEach(function(line){
    if(line.match(/^- \[[ x]\]/) || line.match(/^- /)){
      html += '<div class="chatbot__list-item">' + line.replace(/^- /,'') + '</div>';
    } else {
      if(line.trim() === '') return;
      html += '<div class="chatbot__message-text">' + line + '</div>';
    }
  });
  return html;
}

function headsetSVG() {
  return '<svg class="chatbot__icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>';
}
