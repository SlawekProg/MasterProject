<?php

require __DIR__ . "/vendor/autoload.php";

use Framework\Database;
use Dotenv\Dotenv;
use App\Config\Paths;

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$db = new Database($_ENV['DB_DRIVER'], [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME']
], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);

// try {
//     $db->connection->beginTransaction();

//     $db->connection->query("INSERT INTO products VALUES(10,'Gloves')");

//     $search = "Hats";

//     $query = "SELECT * FROM products WHERE name=:name";
//     $stmt = $db->connection->prepare($query);
//     $stmt->bindValue('name', 'Gloves', PDO::PARAM_STR);

//     $stmt->execute();

//     var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));

//     $db->connection->commit();
// } catch (Exception $error) {
//     if ($db->connection->inTransaction()) {
//         $db->connection->rollBack();
//     }

//     echo "Transaction failed!";
// }
