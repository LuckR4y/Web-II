<?php 
session_start(); // Iniciar a sessão

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

// Criando a conexão
$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) die("Falha na conexão: " . $mysqli->connect_error);

// --- Login ---
// Verifica se o formulário de login foi submetido
if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Prepara a consulta para buscar o usuário no banco de dados
    $stmt = $mysqli->prepare("SELECT id, nome, senha FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario_bd = $result->fetch_assoc();

    // Verifica se o usuário existe e se a senha é válida
    if ($usuario_bd && $usuario_bd['senha'] == $senha) {
        // Armazena os dados do usuário na sessão
        $_SESSION['usuario_id'] = $usuario_bd['id'];
        $_SESSION['usuario_nome'] = $usuario_bd['nome'];

        // Redireciona para a página hotel.php após o login bem-sucedido
        header("Location: hotel.php");
        exit();
    } else {
        echo "<p>Usuário ou senha inválidos.</p>";
    }
}

// --- Logout ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: hotel.php"); // Redireciona para a página hotel.php após logout
    exit();
}
?>

<!-- Formulário de Login -->
<h2>Login</h2>
<form method="post" action="index.php">
    Usuário: <input type="text" name="usuario" required><br>
    Senha: <input type="password" name="senha" required><br>
    <input type="submit" name="login" value="Entrar">
</form>
