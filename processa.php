<?php
session_start();
include_once("conexao.php");

$nome     = $_POST['nome'];
$email    = $_POST['email'];
$sexo     = $_POST['sexo'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$cidade   = $_POST['cidade'];
$estado   = $_POST['estado'];
$bairro   = $_POST['bairro'];
$pais     = $_POST['pais'];
$login    = $_POST['login'];
$senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT); // criptografia
$tipo     = 'cliente'; // padrão cliente

$sql = "INSERT INTO clientes (nome,email,sexo,telefone,endereco,cidade,estado,bairro,pais,login,senha,tipo)
        VALUES ('$nome','$email','$sexo','$telefone','$endereco','$cidade','$estado','$bairro','$pais','$login','$senha','$tipo')";

if(mysqli_query($conn, $sql)){
    $_SESSION['msg'] = "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
    header("Location: cadastro.php");
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar: ".mysqli_error($conn)."</p>";
    header("Location: cadastro.php");
}
?>


produtos.php


<?php
session_start();
if(!isset($_SESSION['id'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Produtos</title>
</head>
<body>
<fieldset>
<h1>Lista de Produtos</h1>
<form action="confirmacao.php" method="post">

<p>Arroz 5kg (R$ 25,00)
<input type="number" name="qtd[0]" value="0" min="0">
<input type="hidden" name="nome[0]" value="Arroz 5kg">
<input type="hidden" name="valor[0]" value="25"></p>

<p>Feijão 1kg (R$ 8,00)
<input type="number" name="qtd[1]" value="0" min="0">
<input type="hidden" name="nome[1]" value="Feijão 1kg">
<input type="hidden" name="valor[1]" value="8"></p>

<p>Macarrão 500g (R$ 5,00)
<input type="number" name="qtd[2]" value="0" min="0">
<input type="hidden" name="nome[2]" value="Macarrão 500g">
<input type="hidden" name="valor[2]" value="5"></p>

<p>Leite 1L (R$ 4,50)
<input type="number" name="qtd[3]" value="0" min="0">
<input type="hidden" name="nome[3]" value="Leite 1L">
<input type="hidden" name="valor[3]" value="4.5"></p>

<p>Pão Francês 1kg (R$ 12,00)
<input type="number" name="qtd[4]" value="0" min="0">
<input type="hidden" name="nome[4]" value="Pão Francês 1kg">
<input type="hidden" name="valor[4]" value="12"></p>

<button type="submit">Continuar</button>
<button type="button" onclick="window.location.href='logout.php'">Encerrar</button>

</form>
</fieldset>
</body>
</html>




relatorio.php


<?php
session_start();
include_once("conexao.php");

if(!isset($_SESSION['id'])){
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Relatório de Vendas</title>
</head>
<body>
<fieldset>
<h1>Relatório de Vendas</h1>

<?php
if($tipo_usuario == 'admin'){
    $sql = "SELECT comp.id as compra_id, cli.nome as cliente, cli.email, cli.telefone,
            cli.endereco, cli.bairro, cli.cidade, cli.estado, cli.pais,
            p.nome as produto, comp.quantidade, comp.subtotal, comp.data_compra
            FROM compras comp
            INNER JOIN clientes cli ON comp.cliente_id = cli.id
            INNER JOIN produtos p ON comp.produto_id = p.id
            ORDER BY comp.data_compra DESC";
} else {
    $sql = "SELECT comp.id as compra_id, cli.nome as cliente, cli.email, cli.telefone,
            cli.endereco, cli.bairro, cli.cidade, cli.estado, cli.pais,
            p.nome as produto, comp.quantidade, comp.subtotal, comp.data_compra
            FROM compras comp
            INNER JOIN clientes cli ON comp.cliente_id = cli.id
            INNER JOIN produtos p ON comp.produto_id = p.id
            WHERE cli.id = $usuario_id
            ORDER BY comp.data_compra DESC";
}

$result = $conn->query($sql);

if($result->num_rows>0){
    echo "<table border='1' cellpadding='5'>
    <tr>
        <th>Cliente</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Endereço</th>
        <th>Produto</th>
        <th>Quantidade</th>
        <th>Subtotal</th>
        <th>Data/Hora</th>
    </tr>";

    while($row = $result->fetch_assoc()){
        echo "<tr>
            <td>".$row['cliente']."</td>
            <td>".$row['email']."</td>
            <td>".$row['telefone']."</td>
            <td>".$row['endereco'].", ".$row['bairro'].", ".$row['cidade']." - ".$row['estado'].", ".$row['pais']."</td>
            <td>".$row['produto']."</td>
            <td>".$row['quantidade']."</td>
            <td>R$ ".number_format($row['subtotal'],2,",",".")."</td>
            <td>".$row['data_compra']."</td>
        </tr>";
    }
    echo "</table>";
}else{
    echo "<p>Nenhuma compra registrada.</p>";
}

echo "<br><button onclick=\"window.location.href='produtos.php'\">Voltar aos Produtos</button> ";
echo "<button onclick=\"window.location.href='logout.php'\">Sair</button>";
?>
</fieldset>
</body>
</html>