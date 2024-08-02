<?php
session_start();
require 'vendor/autoload.php'; // Carregar a biblioteca PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $_SESSION['dados'] = $sheetData;

    header('Location: selecionar_coluna.php');
    exit();
} else {
    echo "Erro: arquivo de planilha não fornecido.";
}
?>
