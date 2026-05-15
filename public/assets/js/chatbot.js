// public/assets/js/chatbot.js
// Cliente del Chatbot con IA para Municipio Gaspar Marcano

document.addEventListener('DOMContentLoaded', function() {
    // Crear el widget del chatbot
    crearWidgetChatbot();
    
    // Cargar sugerencias
    cargarSugerencias();
});

function crearWidgetChatbot() {
    const widgetHTML = `
        <div id="chatbot-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
            <!-- Botón flotante -->
            <button id="chatbot-toggle" style="background-color: #0A2472; border: none; border-radius: 50%; width: 60px; height: 60px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: all 0.3s;">
                <i class="bi bi-robot" style="font-size: 28px; color: white;"></i>
            </button>
            
            <!-- Ventana del chat (oculta por defecto) -->
            <div id="chatbot-window" style="display: none; position: absolute; bottom: 75px; right: 0; width: 350px; height: 500px; background: white; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); overflow: hidden; flex-direction: column;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #0A2472, #00247D); color: white; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i class="bi bi-robot"></i>
                        <strong> Asistente Virtual</strong>
                        <small style="display: block; font-size: 10px;">Municipio Gaspar Marcano</small>
                    </div>
                    <button id="chatbot-close" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
                </div>
                
                <!-- Mensajes -->
                <div id="chatbot-messages" style="flex: 1; overflow-y: auto; padding: 16px; background: #f5f5f5;">
                    <div class="message bot">
                        <div style="background: #0A2472; color: white; padding: 8px 12px; border-radius: 18px; max-width: 85%; margin-bottom: 8px; display: inline-block;">
                            🤖 ¡Hola! Soy el asistente virtual del Municipio Gaspar Marcano. ¿En qué puedo ayudarte?
                        </div>
                    </div>
                </div>
                
                <!-- Sugerencias -->
                <div id="chatbot-sugerencias" style="padding: 8px 12px; background: white; border-top: 1px solid #eee; display: flex; flex-wrap: wrap; gap: 6px;">
                    <span class="sugerencia-chat" style="background: #e9ecef; padding: 4px 10px; border-radius: 16px; font-size: 11px; cursor: pointer;">Historia</span>
                    <span class="sugerencia-chat" style="background: #e9ecef; padding: 4px 10px; border-radius: 16px; font-size: 11px; cursor: pointer;">Playas</span>
                    <span class="sugerencia-chat" style="background: #e9ecef; padding: 4px 10px; border-radius: 16px; font-size: 11px; cursor: pointer;">Inversiones</span>
                </div>
                
                <!-- Input -->
                <div style="display: flex; padding: 12px; background: white; border-top: 1px solid #ddd;">
                    <input type="text" id="chatbot-input" placeholder="Escribe tu pregunta..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 24px; outline: none;">
                    <button id="chatbot-send" style="background: #FFCD00; border: none; border-radius: 50%; width: 40px; height: 40px; margin-left: 8px; cursor: pointer;">
                        <i class="bi bi-send" style="color: #0A2472;"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', widgetHTML);
    
    // Eventos
    const toggleBtn = document.getElementById('chatbot-toggle');
    const windowChat = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const sendBtn = document.getElementById('chatbot-send');
    const inputField = document.getElementById('chatbot-input');
    const messagesContainer = document.getElementById('chatbot-messages');
    
    toggleBtn.addEventListener('click', () => {
        if (windowChat.style.display === 'none' || windowChat.style.display === '') {
            windowChat.style.display = 'flex';
        } else {
            windowChat.style.display = 'none';
        }
    });
    
    closeBtn.addEventListener('click', () => {
        windowChat.style.display = 'none';
    });
    
    sendBtn.addEventListener('click', enviarMensaje);
    inputField.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') enviarMensaje();
    });
    
    // Eventos para sugerencias
    document.querySelectorAll('.sugerencia-chat').forEach(btn => {
        btn.addEventListener('click', () => {
            inputField.value = btn.textContent;
            enviarMensaje();
        });
    });
    
    function enviarMensaje() {
        const pregunta = inputField.value.trim();
        if (!pregunta) return;
        
        // Mostrar mensaje del usuario
        agregarMensaje('user', pregunta);
        inputField.value = '';
        
        // Mostrar indicador de escritura
        const typingId = agregarIndicadorEscritura();
        
        // Enviar a la API
        fetch('/gasparmarcano-sitioweb/public/chatbot/api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pregunta: pregunta })
        })
        .then(res => res.json())
        .then(data => {
            // Remover indicador de escritura
            document.getElementById(typingId)?.remove();
            
            if (data.respuesta) {
                agregarMensaje('bot', data.respuesta);
            } else {
                agregarMensaje('bot', 'Lo siento, hubo un error. Por favor, intenta de nuevo.');
            }
        })
        .catch(error => {
            document.getElementById(typingId)?.remove();
            agregarMensaje('bot', 'Error de conexión. Por favor, intenta más tarde.');
        });
    }
    
    function agregarMensaje(tipo, texto) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${tipo}`;
        messageDiv.style.marginBottom = '12px';
        messageDiv.style.textAlign = tipo === 'user' ? 'right' : 'left';
        
        const bubble = document.createElement('div');
        bubble.style.display = 'inline-block';
        bubble.style.maxWidth = '85%';
        bubble.style.padding = '8px 12px';
        bubble.style.borderRadius = '18px';
        bubble.style.backgroundColor = tipo === 'user' ? '#FFCD00' : '#0A2472';
        bubble.style.color = tipo === 'user' ? '#0A2472' : 'white';
        bubble.innerHTML = (tipo === 'bot' ? '🤖 ' : '👤 ') + texto;
        
        messageDiv.appendChild(bubble);
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    function agregarIndicadorEscritura() {
        const id = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'message bot';
        div.style.marginBottom = '12px';
        div.innerHTML = '<div style="background: #ccc; padding: 8px 12px; border-radius: 18px; display: inline-block;"><i class="bi bi-three-dots"></i> Escribiendo...</div>';
        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        return id;
    }
    
    function cargarSugerencias() {
        fetch('/gasparmarcano-sitioweb/public/chatbot/api.php?sugerencias=1')
            .then(res => res.json())
            .then(data => {
                if (data.sugerencias) {
                    const sugerenciasContainer = document.getElementById('chatbot-sugerencias');
                    sugerenciasContainer.innerHTML = '';
                    data.sugerencias.forEach(sug => {
                        const span = document.createElement('span');
                        span.className = 'sugerencia-chat';
                        span.style.cssText = 'background: #e9ecef; padding: 4px 10px; border-radius: 16px; font-size: 11px; cursor: pointer;';
                        span.textContent = sug;
                        span.addEventListener('click', () => {
                            inputField.value = sug;
                            enviarMensaje();
                        });
                        sugerenciasContainer.appendChild(span);
                    });
                }
            });
    }
}