var 
	io = require("socket.io"),
	express = require('express'),
	app = express(),
	server = require('http').createServer(app),
	io = io.listen(server),
	fs = require("fs");

server.listen(1337);

function path(to) {
	return __dirname + "/public/" + to;
}

app.configure(function() {
	app.use(express.bodyParser());
});

app.get('/', function (req, res) {
	res.sendfile(path('index.html'));
});

var speak = function(socket, message) {
	socket.emit("echo", {
		message : message
	});
};

io.sockets.on('connection', function (socket) {

	socket.on('echo', function (data) {
		console.log(data);
		speak(socket, "The socket has spoken!");
		speak(socket, data.message || "No message was received.");
	});

	socket.on("socketconnected", function(data) {
		console.log("A socket has connected");
		socket.broadcast.emit("socketconnected");
	});
});