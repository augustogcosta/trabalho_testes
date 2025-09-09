<?php
session_start();
include_once("conexao.php");

if(!isset($_SESSION['id'])){
    header("Location: index.php");
    exit();
}

$qtd   = $_POST['qtd'];
$nome  = $_POST['nome'];
$valor = $_POST['valor'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Confirmação da Compra</title>
</head>
<body>
<fieldset>
<h1>Confirmação da Compra</h1>

<?php
for($i=0;$i<count($qtd);$i++){
    if($qtd[$i]>0){
        $subtotal = $qtd[$i]*$valor[$i];
        $total += $subtotal;

        // Verifica se produto existe
        $stmt_prod = $conn->prepare("SELECT id FROM produtos WHERE nome=?");
        $stmt_prod->bind_param("s",$nome[$i]);
        $stmt_prod->execute();
        $stmt_prod->bind_result($produto_id);
        $stmt_prod->fetch();
        $stmt_prod->close();

        if($produto_id===null){
            $stmt_insert = $conn->prepare("INSERT INTO produtos (nome, valor) VALUES (?,?)");
            $stmt_insert->bind_param("sd",$nome[$i],$valor[$i]);
            $stmt_insert->execute();
            $produto_id = $stmt_insert->insert_id;
            $stmt_insert->close();
        }

        // Insere compra
        $stmt_comp = $conn->prepare("INSERT INTO compras (cliente_id, produto_id, quantidade, subtotal) VALUES (?,?,?,?)");
        $stmt_comp->bind_param("iiid",$_SESSION['id'],$produto_id,$qtd[$i],$subtotal);
        $stmt_comp->execute();
        $stmt_comp->close();

        echo "<p><b>".$nome[$i]."</b> - Quantidade: ".$qtd[$i]." - Preço unitário: R$ ".number_format($valor[$i],2,",",".")." - Subtotal: R$ ".number_format($subtotal,2,",",".")."</p>";
    }
}

echo "<h3>Total da Compra: R$ ".number_format($total,2,",",".")."</h3>";

// Botões
echo "<button onclick=\"window.location.href='produtos.php'\">Voltar aos Produtos</button> ";
echo "<button onclick=\"window.location.href='relatorio.php'\">Relatório de Vendas</button> ";
echo "<button onclick=\"window.location.href='logout.php'\">Encerrar</button>";
?>

</fieldset>
</body>
</html>


logout.php


<?php
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
