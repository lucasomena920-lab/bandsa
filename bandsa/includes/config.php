<?php
// Configuração da base de dados
$host = 'localhost';
$dbname = 'eclipse_db';
$username = 'root';  
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES utf8');
} catch(PDOException $e) {
    die("ERRO na conexão: " . $e->getMessage());
}

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>