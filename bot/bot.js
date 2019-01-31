var scribe = require('scribe-js')(),
    app = require('express')(),
    server = require('http').Server(app),
    io = require('socket.io')(server),
    requestify = require('requestify');
var schedule = require('node-schedule');
server.listen(8080);

add_online();
setInterval(add_online, 60000);
activebots();


io.sockets.on('connection', function (socket) {
	updateOnline();
	getDrops();
	
	socket.on('newDrop', function(id){
		requestify.post('http://localhost/api/getGame', { id: id})
		.then(function (response) {
		data = JSON.parse(response.body);
		io.sockets.emit('nd', data.text);
		}, function (err) {
			console.log(err);
        });
		
	});
	
    socket.on('disconnect', function () {
        updateOnline();
    });
	
});
function updateOnline(){
	io.sockets.emit('updateonline', Number(Object.keys(io.sockets.adapter.rooms).length) + Number(global.online));
    console.info('Connected ' + Object.keys(io.sockets.adapter.rooms).length + ' clients');
	
}


function getDrops(){
	requestify.post('http://localhost/api/getdrops', {})
	.then(function (response) {
		data = JSON.parse(response.body);
		io.emit('welcomedrop', data.text);
		console.log("stats");
	}, function (err) {
		console.log(err);
	});
};

function activebots()
{
	console.log("\x1b[32m", "[NVBOT] Функция ботов сработала!");
	requestify.post('http://localhost/api/playbot', {})
	.then(function (response) {
		data = JSON.parse(response.body);
		var time = data.time;
		console.log("\x1b[32m", "[NVBOT] Фейк-Игра сыграна! ID: "+data.game+" Время: "+ Number(data.time)+"мс");
		requestify.post('http://localhost/api/getGame', { id: data.game})
		.then(function (response) {
		data = JSON.parse(response.body);
		io.sockets.emit('nd', data.text);
		
		
		setTimeout(function() {
		  activebots();
		}, time);

		}, function (err) {
			console.log(err);
			setTimeout(activebots, 50000);
        });
		
		
	}, function (err) {
		console.log("\x1b[31m", "[NVBOT] Ошибка запроса");
		setTimeout(activebots, 50000);
	});
}

function add_online()
{
	requestify.post('http://localhost/api/getonline', {})
	.then(function (response) {
		data = JSON.parse(response.body);
		global.online = data.online;
		console.log("\x1b[32m", "[NVBOT] Фейк онлайн обновлен! (+" + data.online + ")");
		updateOnline();
	}, function (err) {
		console.log("\x1b[31m", "[NVBOT] Ошибка получения фейкового онлайна");
	});
}