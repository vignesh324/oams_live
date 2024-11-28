<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebSocket Example</title>
</head>
<body>
<div id="result"></div>
<input type="text" id="valueInput1">
<input type="text" id="valueInput2">
<button onclick="sendMessage()">Send Message</button>

<script>
var ws = new WebSocket("ws://localhost:9000");

ws.onopen = function() {
    console.log("WebSocket connected.");
};

ws.onmessage = function(event) {
    var message = event.data;
    document.getElementById("result").textContent = message;
};

ws.onerror = function(event) {
    console.error("WebSocket error:", event);
};

ws.onclose = function() {
    console.log("WebSocket connection closed.");
};

function sendMessage() {
    var value1 = document.getElementById("valueInput1").value;
    var value2 = document.getElementById("valueInput2").value;

    // Create an object with multiple values
    var message = {
        value1: value1,
        value2: value2
    };
    ws.send(JSON.stringify(message));
}
</script>
</body>
</html>
