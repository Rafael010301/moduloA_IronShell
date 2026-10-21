<!-- Componente Flutuante do Chatbot (RF13) -->
<div id="senn-chat-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050;">
    <!-- Botão de Abertura do Chat -->
    <button id="senn-chat-toggle" class="btn btn-dark rounded-circle shadow-lg p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
        <span style="font-size: 24px; font-weight: bold;">AI</span>
    </button>

    <!-- Janela do Chat (Oculta por padrão) -->
    <div id="senn-chat-box" class="card shadow-lg border-0 d-none" style="width: 350px; height: 450px; bottom: 70px; right: 0; position: absolute;">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold small">Assistente Virtual SENN AI-X</span>
            <button id="senn-chat-close" class="btn-close btn-close-white btn-sm"></button>
        </div>
        <div id="senn-chat-messages" class="card-body overflow-auto small bg-light" style="height: 330px; display: flex; flex-direction: column; gap: 10px;">
            <div class="p-2 bg-white rounded shadow-sm align-self-start border" style="max-width: 85%;">
                Olá! Sou o assistente virtual do SENN AI-X. Como posso ajudar com a operação logística hoje?
            </div>
        </div>
        <div class="card-footer bg-white p-2 border-top">
            <form id="senn-chat-form" class="input-group">
                <input type="text" id="senn-chat-input" class="form-control form-control-sm" placeholder="Digite sua dúvida..." required>
                <button type="submit" class="btn btn-dark btn-sm fw-bold">Enviar</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('senn-chat-toggle').addEventListener('click', function() {
    const box = document.getElementById('senn-chat-box');
    box.classList.toggle('d-none');
});

document.getElementById('senn-chat-close').addEventListener('click', function() {
    document.getElementById('senn-chat-box').classList.add('d-none');
});

document.getElementById('senn-chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('senn-chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    const messagesContainer = document.getElementById('senn-chat-messages');

    // Adicionar mensagem do utilizador
    messagesContainer.innerHTML += `<div class="p-2 bg-primary text-white rounded shadow-sm align-self-end" style="max-width: 85%;">${msg}</div>`;
    input.value = '';
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Requisição AJAX para processar a resposta no backend
    fetch('index.php?route=chatbot_process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'pergunta=' + encodeURIComponent(msg)
    })
    .then(response => response.text())
    .then(respostaHtml => {
        messagesContainer.innerHTML += `<div class="p-2 bg-white rounded shadow-sm align-self-start border" style="max-width: 85%;">${respostaHtml}</div>`;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
});
</script>