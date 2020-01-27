<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';
class MyDB extends SQLite3 {
    function __construct() {
        $this->open('../participants.db');
    }
}
$db = new MyDB();
if(!$db) {
    echo $db->lastErrorMsg();
    exit();
}

$app = new \Slim\App;
$app->get(
  '/hello/{name}', 
  function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
  }
);
$app->get(
    '/api/participants',
    function (Request $request, Response $response, array $args) use ($db) {
        $sql = "SELECT id, firstname, lastname FROM participant";
        $ret = $db->query($sql);
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
            $participants[] = $row;
        }
        $db->close();
    }
);
$app->run();


