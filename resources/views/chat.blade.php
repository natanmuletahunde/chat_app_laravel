<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #chat {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .message strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div id="chat">
        <h1>Real-Time Chat App</h1>
        <div id="messages"></div>
        <form id="chat-form">
            <input type="text" id="username" placeholder="Your name" required>
            <input type="text" id="message" placeholder="Type your message" required>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        const messagesDiv = document.getElementById('messages');
        const chatForm = document.getElementById('chat-form');
        const usernameInput = document.getElementById('username');
        const messageInput = document.getElementById('message');

        // Fetch messages
        axios.get('/messages').then(response => {
            response.data.forEach(message => {
                addMessage(message.username, message.message);
            });
        });

        // Send message
        chatForm.addEventListener('submit', e => {
            e.preventDefault();

            const message = {
                username: usernameInput.value,
                message: messageInput.value
            };

            axios.post('/messages', message);

            addMessage(message.username, message.message);
            messageInput.value = '';
        });

        // Pusher setup
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });

        const channel = pusher.subscribe('chat');
        channel.bind('App\\Events\\MessageSent', data => {
            addMessage(data.message.username, data.message.message);
        });

        function addMessage(username, message) {
            const div = document.createElement('div');
            div.classList.add('message');
            div.innerHTML = `<strong>${username}</strong>: ${message}`;
            messagesDiv.appendChild(div);
        }
    </script>
</body>
</html>
