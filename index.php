<?php
session_start();
include_once("conexao.php");

if (isset($_POST['entrar'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha, tipo FROM clientes WHERE login=?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash_senha, $tipo);
        $stmt->fetch();

        if (password_verify($senha, $hash_senha)) {
            $_SESSION['id'] = $id;
            $_SESSION['tipo'] = $tipo;

            // Se for admin, vai direto pro relatório
            if ($tipo == 'admin') {
                header("Location: relatorio.php");
            } else {
                header("Location: produtos.php");
            }
            exit();
        } else {
            $_SESSION['msg'] = "Login ou senha incorretos!";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['msg'] = "Login ou senha incorretos!";
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>

    <body>
        <fieldset>
            
            <h1>Tela de Login</h1>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

            <form method="POST">
                <label>Login:</label>
                <input type="text" name="login" placeholder="Digite seu login" required><br><br>
                <label>Senha:</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required><br><br>
                <button type="submit" name="entrar">Entrar</button><br><br>
                <a href="cadastro.php">Cadastre-se</a>
            </form>
        </fieldset>
    </body>
</html>