<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>
<fieldset>
    <h1>Tela de Cadastro</h1>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="processa.php">
        <label>Nome:</label>
        <input type="text" name="nome" placeholder="Digite o nome completo" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Digite seu e-mail" required><br><br>

        <label>Sexo:</label>
        <input type="radio" name="sexo" value="M"> Masculino
        <input type="radio" name="sexo" value="F"> Feminino<br><br>

        <label>Telefone:</label>
        <input type="tel" name="telefone" placeholder="Digite seu telefone" required><br><br>

        <label>Endereço:</label>
        <input type="text" name="endereco" placeholder="Digite seu endereço" required><br><br>

        <label>Cidade:</label>
        <input type="text" name="cidade" placeholder="Digite sua cidade" required><br><br>

        <label>Estado:</label>
        <input type="text" name="estado" placeholder="Digite seu estado" required><br><br>

        <label>Bairro:</label>
        <input type="text" name="bairro" placeholder="Digite seu bairro" required><br><br>

        <label>País:</label>
        <input type="text" name="pais" placeholder="Digite seu país" required><br><br>

        <label>Login:</label>
        <input type="text" name="login" placeholder="Digite seu login" required><br><br>

        <label>Senha:</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required><br><br>

        <button type="submit">Cadastrar</button>
        <button type="reset">Limpar campos</button><br><br>
        <a href="index.php">Ir para login</a>
    </form>
</fieldset>
</body>
</html>