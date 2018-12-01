// Require the packages we will use:
var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");

// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function (request, response) {
	if (request.url === "/style.css") {
	  fs.readFile("style.css", function(err, data){
		if (err) return response.writeHead(500);
		response.writeHead(200, {"Content-Type": "text/css"});
		response.end(data);
	  })
	}
	else {
	  response.writeHead(200, {"Content-Type": "text/html"});
	  fs.readFile("client.html", function(err, data){
		if(err) return response.writeHead(500);
		response.writeHead(200);
		response.end(data);
	  });
	}
  })
  app.listen(3456);

let users = new Set();
let rooms = {"Lobby": undefined}; 		//key: room name	value: room creator(user)
let locations = {}; 	//key: user			value: room
let banned_users = {} 	//key: room name	value: set of users that are banned
let private_rooms = {} 	//key: room name	value: password
let room_colors = {"Lobby": "white"} 	//key: room name	value: color


// Do the Socket.IO magic:
let io = socketio.listen(app);
io.sockets.on("connection", function(socket){
	// This callback runs when a new Socket.IO connection is established.

	socket.on("add_user", function(data) {
		if (users.has(data["user"])){
			io.sockets.emit("username_taken");
		}
		else {
			users.add(data["user"]);
			locations[data["user"]] = "Lobby";
			io.sockets.emit("login_update_appearance", {rooms_keys:Object.keys(rooms), users: Array.from(users), locations:locations, locations_keys: Object.keys(locations), rooms:rooms, banned_users: banned_users});
		}
	});

	socket.on("create_room", function(data){
		let color = data["color"];
		if (color == undefined) {
			color = "white";
		}
		if(data["user"] == undefined){
			io.sockets.emit("not_logged_in");
		}
		else if(data["room"] in rooms){
			io.sockets.emit("room_name_taken");
		}
		else {
			rooms[data["room"]] = data["user"];
			locations[data["user"]] = data["room"];
			room_colors[data["room"]] = data["color"];
			console.log("ROOMS: ", rooms);
			console.log("LOCATIONS:", locations);
			console.log("COLOR: ", data["color"]);
			io.sockets.emit("room_created", {room: data["room"], locations_keys: Object.keys(locations), locations: locations, rooms: rooms, rooms_keys: Object.keys(rooms), banned_users: banned_users, room_colors:room_colors});
		}
	});


	socket.on("send_private_message", function(data){
		io.sockets.emit("sent_private_message", {from_user: data["from_user"], to_user: data["to_user"], message:data["message"]});
	});


	socket.on("create_private_room", function(data){
		private_rooms[data["room"]] = data["password"];
		console.log("PRIVATE ROOMS: ", private_rooms);
	});



	socket.on("join_room", function(data){
		if(data["user"] == undefined){
			io.sockets.emit("not_logged_in");
		}
		else {
			if(data["room"] in private_rooms){
				io.sockets.emit("verify_password", {room: data["room"], password: private_rooms[data["room"]]});
			}
			else {
				locations[data["user"]] = data["room"];
				io.sockets.emit("joined_room", {
					room: data["room"],
					locations_keys: Object.keys(locations),
					locations: locations,
					rooms: rooms,
					rooms_keys: Object.keys(rooms),
					banned_users: banned_users,
					room_colors:room_colors
				});
			}
			console.log("LOCATIONS", locations);
		}
	})

	socket.on("kick_user", function(data){
		if(locations[data["user_to_kick"]] == data["room"] && rooms[data["room"]] == data["user"]){
			locations[data["user_to_kick"]] = "Lobby";
			io.sockets.emit("kicked_user", {room: data["room"], locations_keys: Object.keys(locations), locations: locations, rooms: rooms, rooms_keys: Object.keys(rooms), banned_users: banned_users, room_colors:room_colors});
		}
		else{
			io.sockets.emit("cannot_kick");
		}
	});

	socket.on("ban_user", function(data){
		if(rooms[data["room"]] == data["user"] && users.has(data["user_to_ban"])) {
			if(locations[data["user_to_ban"]] == data["room"]) {
				locations[data["user_to_ban"]] = "Lobby";
			}
			if(data["room"] in banned_users) {
				banned_users[data["room"]].push(data["user_to_ban"]);
			}
			else {
				banned_users[data["room"]] = new Array([data["user_to_ban"]]);
			}
			io.sockets.emit("banned_user", {room: data["room"], locations_keys: Object.keys(locations), locations: locations, rooms: rooms, rooms_keys: Object.keys(rooms), banned_users: banned_users, room_colors:room_colors});
		}
		else {
			io.sockets.emit("cannot_ban");
		}
		console.log("BANNED_USERS: ", banned_users)
	});

	socket.on("password_worked", function(data){
		locations[data["user"]] = data["room"];
		io.sockets.emit("joined_room", {
			room: data["room"],
			locations_keys: Object.keys(locations),
			locations: locations,
			rooms: rooms,
			rooms_keys: Object.keys(rooms),
			banned_users: banned_users,
			room_colors: room_colors
		});
		console.log("LOCATIONS", locations);
	});

	socket.on("unban_user", function(data){
		console.log("USERS: ", users);
		console.log("ROOMS: ", rooms);
		console.log("data-room", data["room"]);
		console.log("data-user", data["user"]);
		if (users.has(data["user_to_unban"]) && rooms[data["room"]] == data["user"]) {
			let index = banned_users[data["room"]].indexOf(data["user_to_unban"]);
			banned_users[data["room"]].splice(index, 1);
			console.log("BANNED_USERS", banned_users);
			io.sockets.emit("unbanned_user", {room: data["room"], locations_keys: Object.keys(locations), locations: locations, rooms: rooms, rooms_keys: Object.keys(rooms), banned_users: banned_users, room_colors:room_colors});
		}
		else {
			io.sockets.emit("cannot_unban");
		}
	})

	socket.on('message_to_server', function(data) {
		// This callback runs when the server receives a new message from the client.
		if(data["user"] == undefined) {
			io.sockets.emit("not_logged_in");
		}

		io.sockets.emit("message_to_client",{message:data["message"], user:data["user"], bold:data["bold"], italics:data["italics"], room: data["room"]}) // broadcast the message to other users
	});
});
