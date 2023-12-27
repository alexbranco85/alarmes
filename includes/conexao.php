<?php
define('host', 'localhost');
define('username', 'root');
define('password', 'SequelizeAccessDeniedError');
define('database', 'sistema_alarme');

$conn = new mysqli(host, username, password, database);

if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}
?>