<?php
require_once "../../model/estoque/estoque.class.php";

$estoque = new Estoque();

$data = $estoque->listarProdutos();
print_r($data);

//header("Location: ../../view/produto/produtos.Main.php");
