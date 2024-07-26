<?php
session_start();
require_once("conexao.php");

if (isset($_SESSION['dados']) && isset($_POST['coluna_telefone']) && isset($_POST['mensagem'])) {
    $data = $_SESSION['dados'];
    $coluna_telefone = $_POST['coluna_telefone'];
    $mensagem = $_POST['mensagem']; 

    $codigo_pais = '55';

    // Garantir que a mensagem está em UTF-8
    $mensagem = mb_convert_encoding($mensagem, 'UTF-8', 'auto');

    // Remover caracteres de controle da mensagem
    $mensagem = preg_replace('/[\x00-\x1F\x7F]/u', '', $mensagem);

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    foreach ($data as $index => $row) {
        if ($index == 0) continue;

        if (isset($row[$coluna_telefone])) {
            $telefone = $row[$coluna_telefone];

            // Dividir múltiplos números, se houver
            $numeros = preg_split('/\s*\/\s*/', $telefone);

            foreach ($numeros as $numero) {
                // Remover qualquer texto não numérico e manter apenas números
                $telefone_formatado = preg_replace('/^\(\d+°\)\s*/', '', $numero);
                $telefone_formatado = preg_replace('/\s+/', '', $telefone_formatado);
                
                // Adicionar o código do país
                if (strlen($telefone_formatado) >= 10) { // Verifica se tem ao menos um DDD + número local
                    $telefone_formatado = $codigo_pais . $telefone_formatado;
                }

                // Verificar se o número formatado não está vazio
                if (!empty($telefone_formatado)) {
                    // Inserir telefone no banco de dados
                    $stmt = $conn->prepare("INSERT INTO telefones (telefone, mensagem) VALUES (?, ?)");
                    $stmt->bind_param("ss", $telefone_formatado, $mensagem);
                    
                    if ($stmt->execute()) {
                        echo "Telefone $telefone_formatado inserido com sucesso.<br>";
                    } else {
                        echo "Erro ao inserir telefone $telefone_formatado: " . $stmt->error . "<br>";
                    }

                    $stmt->close();
                } else {
                    echo "Número inválido ou vazio encontrado.<br>";
                }
            }
        }
    }
    
    $conn->close();
} else {
    echo "Erro: dados necessários não encontrados na sessão.";
}
?>
