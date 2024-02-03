<?php
session_start();

if(isset($_SESSION["login"])){
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>PHP Web Chatting</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
html, body {
	height: 100%;
	margin: 0;
	padding: 0;
}

.container-fluid {
	height: 100%;
}

.chat-bubble {
	border-radius: 20px;
	padding: 10px;
} 

.chat-rcpt {
	background-color: #0003b8;
	color: white;
}

.chat-sender {
	background-color: #66ff8f;
}
</style>
</head>
<body>

<div class="container-fluid pt-2">
	<div class="row" style="height:100%;">
		<div class="col-md-12">
			<div class="card" style="height: calc(100% - 20px);">
				<div class="card-header">
					PHP Web Chatting System
					(<a href="logout.php">
						Logout
					</a>)
				</div>
				
				<div class="card-body" id="chat-body">
					<div class="row">
						<div class="col-md-8">
							<div class="chat-bubble chat-rcpt mb-2">
								Hello mr hery!
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4"></div>
						
						<div class="col-md-8">
							<div class="chat-bubble chat-sender mb-2">
								Ya hi there!
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-footer">
					<div class="row">
						<div class="col-8">
							<input id="message" type="text" class="form-control" placeholder="Message..." />
						</div>
						
						<div class="col-2">
							<label for="file" style="width: 100%" class="btn btn-block btn-info">
								<input style="display: none;" type="file" id="file" />
								Attachment
							</label>
						</div>
						
						<div class="col-2">
							<button id="send" class="btn btn-block btn-primary" style="width: 100%;">
								Send
							</button>
						</div>
					</div>
					
				</div>
			</div>
			
		</div>
	</div>
</div>

<script>
var ws = new WebSocket("ws://localhost:8080");

ws.onopen = function(){
	console.log("Connected to server");
	$("#chat-body").html("");
};

ws.onmessage = function(data){
	var obj = JSON.parse(data.data);
	
	var message = "";
	
	switch(obj.type){
		case "text":
			message = obj.message;
		break;
		
		case "image":
			message = "<img class='img img-fluid' src='"+ obj.message +"' />";
		break;
		
		case "video":
			message = "<video controls src='"+ obj.message +"' style='width: 100%;'></video>";
		break;
	}
	
	if(obj.from == "<?= $_SESSION["login"]->u_email ?>"){
		$("#chat-body").append('\
			<div class="row">\
				<div class="col-4"></div>\
				\
				<div class="col-8">\
					<div class="chat-bubble chat-sender mb-2">\
						'+ message +'\
					</div>\
				</div>\
			</div>\
		');
	}else{
		$("#chat-body").append('\
			<div class="row">\
				<div class="col-8">\
					<div class="chat-bubble chat-rcpt mb-2">\
						<strong>'+ obj.name +'</strong><br />\
						'+ message +'\
					</div>\
				</div>\
			</div>\
		');
	}
	
};

ws.onclose = function(){
	console.log("Disconnected to server");
};

ws.onerror = function(){
	console.log("Connection Error");
};

$("#send").on("click", function(){
	var message = $("#message").val();
	
	message = JSON.stringify({
		message: message,
		from: "<?= $_SESSION["login"]->u_email ?>",
		name: "<?= $_SESSION["login"]->u_name ?>",
		type: "text"
	});
	
	ws.send(message);
	$("#message").val("");
});

$("#message").on("keyup", function(e){
	if(e.keyCode == 13){
		var message = $("#message").val();
		
		message = JSON.stringify({
			message: message,
			from: "<?= $_SESSION["login"]->u_email ?>",
			name: "<?= $_SESSION["login"]->u_name ?>",
			type: "text"
		});
		
		ws.send(message);
		$("#message").val("");
	}
});

$("#file").on("change", function(){
	var file = $("#file")[0].files;
	
	if(file.length > 0){
		var f = file[0];
		console.log(f);
		
		var reader = new FileReader();
		reader.readAsDataURL(f);
		
		reader.onload = function () {
			var message = JSON.stringify({
				message: reader.result,
				from: "<?= $_SESSION["login"]->u_email ?>",
				name: "<?= $_SESSION["login"]->u_name ?>",
				type: f.type.split("/")[0]
			});
			ws.send(message);
		};
		
		reader.onerror = function (error) {
			console.log('Error: ', error);
		};
	}else{
		alert("File not selected");
	}
});

</script>
</body>
</html> 








<?php
}else{
	header("Location: index.php");
}