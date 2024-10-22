<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple AI Chat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box {
            height: 450px;
            overflow-y: scroll;
            background-color: #f1f1f1;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .message {
            margin-bottom: 10px;
        }
        .username {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Chat Application</h1>
    <div id="chat-box" class="mb-3">
        <!-- Chat messages will be loaded here -->
    </div>
    <div class="row">
        <div class="col mb-3">
            <span class="text-muted">Model used: <b>{{ $model }}</b></span>
        </div>
    </div>
    <form id="chat-form">
        <div class="input-group mb-3">
            <input type="text" id="message" class="form-control" placeholder="Enter message..." required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Send</button>
            </div>
        </div>
    </form>
    <button class="btn btn-danger" id="clear-chat">Clear Chat</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Load chat messages every 10 seconds
        // setInterval(loadChat, 10000);

        // Send a message using AJAX when the form is submitted
        $('#chat-form').submit(function(e) {
            e.preventDefault();
            let message = $('#message').val();
            if (message.trim() !== '') {
                $.ajax({
                    url: '/chat/message',
                    method: 'POST',
                    data: {
                        message: message,
                    },
                    success: function(response) {
                        $('#message').val(''); // Clear input field
                        loadChat(); // Reload chat messages
                    }
                });
            }
        });

        // Clear chat messages
        $('#clear-chat').click(function() {
            $.ajax({
                url: '/chat/clear',
                method: 'POST',
                success: function(response) {
                    loadChat(); // Reload chat messages to show empty box
                }
            });
        });

        // Function to load chat messages
        function loadChat() {
            $.ajax({
                url: '/chat/message',
                method: 'GET',
                success: function(response) {
                    $('#chat-box').html(response.messages); // Update chat-box with messages
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll to the bottom
                }
            });
        }

        // Load chat immediately when the page is ready
        loadChat();
    });
</script>
</body>
</html>
