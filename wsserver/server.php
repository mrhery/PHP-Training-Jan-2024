<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients;
	private $users = [], $conn = null;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
		
		$this->conn = mysqli_connect("127.0.0.1", "root", "", "test");
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
		$this->users[$conn->resourceId] = $conn;
		
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
		$obj = json_decode($msg);
		
		$q = mysqli_query($this->conn, "SELECT * FROM users WHERE u_email = '{$obj->from}'");
		$n = mysqli_num_rows($q);
		
		if($n > 0){
			foreach($this->users as $user){
				$user->send($msg);
			}
		}		
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
		
		if(isset($this->users[$conn->resourceId])){
			unset($this->users[$conn->resourceId]);
		}

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
	new HttpServer(
		new WsServer(
			new Chat()
		)
	),
	8080
);

$server->run();