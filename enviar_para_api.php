<?php
session_start();

if (isset($_SESSION['dados']) && isset($_POST['coluna_telefone']) && isset($_POST['mensagem'])) {
    $data = $_SESSION['dados'];
    $coluna_telefone = $_POST['coluna_telefone'];
    $mensagem = $_POST['mensagem']; 

    $codigo_pais = '55';

    
    $mensagem = mb_convert_encoding($mensagem, 'UTF-8', 'auto');

    $mensagem = preg_replace('/[\x00-\x1F\x7F]/u', '', $mensagem);

    $url = "http://api.wordmensagens.com.br/send-text";

    $instance = "XXXXXXX"; 
    $token = "XXXXXXXXX"; 

    $resultados = array(); 
    $contagem_envios = 0; 

    foreach ($data as $index => $row) {
        if ($index == 0) continue;

        if (isset($row[$coluna_telefone])) {
            $telefone = $row[$coluna_telefone];

            $numeros = preg_split('/\s*\/\s*/', $telefone);



            foreach ($numeros as $numero) {

                $telefone_formatado = preg_replace('/^\(\d+°\)\s*/', '', $numero);
                $telefone_formatado = preg_replace('/\D/', '', $telefone_formatado);

                if (strlen($telefone_formatado) >= 10) { // Verifica se tem ao menos um DDD + número local
                    $telefone_formatado = $codigo_pais . $telefone_formatado;
                }

                if (!empty($telefone_formatado) && strlen($telefone_formatado) >= 12) { // Código país (2) + DDD (2) + número local (8 ou 9)
                    // Configurar os dados para enviar para a API
                    $data_api = array(
                        'instance' => $instance,
                        'to' => $telefone_formatado,
                        'token' => $token,
                        'message' => $mensagem 
                    );

                    $options = array(
                        'http' => array(
                            'method'  => 'POST',
                            'header'  => 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                            'content' => http_build_query($data_api, '', '&', PHP_QUERY_RFC3986) // encodeURIComponent
                        )
                    );
                    $context = stream_context_create($options);

                    $result = file_get_contents($url, false, $context);

                    $res123 = json_decode($result);

                    $status_envio = 'false';
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $erro = isset($res123->erro) ? $res123->erro : false;
                        $status_envio = $erro ? 'Erro ao Enviar' : 'Enviado com Sucesso';
                    }

                    $resultados[] = array(
                        'id' => $id++,
                        'telefone' => $telefone_formatado,
                        'status' => $status_envio,
                        'resposta' => htmlspecialchars($result)
                    );

                    $contagem_envios++;
                } else {
                    // Armazenar o resultado de número inválido com ID
                    $resultados[] = array(
                        'id' => $id++,
                        'telefone' => $numero,
                        'status' => 'Número inválido ou vazio',
                        'resposta' => 'Número inválido ou vazio'
                    );
                }
            }
        } else {
            // Armazenar o resultado de coluna não encontrada com ID
            $resultados[] = array(
                'id' => $id++,
                'telefone' => 'N/A',
                'status' => 'Coluna de telefone não encontrada para o índice ' . $index,
                'resposta' => 'Coluna de telefone não encontrada para o índice ' . $index
            );
        }
    }

    echo '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Status de Envio</title>
        <style>
            body { font-family: Arial, sans-serif; }
            #status { margin: 20px; font-size: 1.2em; color: #007BFF; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
            th { background-color: #f4f4f4; }
            .button { 
                display: inline-block; 
                padding: 10px 20px; 
                margin: 20px 0; 
                font-size: 1em; 
                color: #fff; 
                background-color: #007BFF; 
                border: none; 
                border-radius: 5px; 
                text-decoration: none; 
                text-align: center; 
            }
            .button:hover { background-color: #0056b3; }
        </style>
    </head>
    <body>
            <a href="index.php" class="button">Voltar para a Página Inicial</a>
            <table>
                <tr><th>ID</th><th>Telefone</th><th>Status</th></tr>';

    foreach ($resultados as $resultado) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($resultado['id']) . '</td>';
        echo '<td>' . htmlspecialchars($resultado['telefone']) . '</td>';
        echo '<td>' . htmlspecialchars($resultado['status']) . '</td>';
        // echo '<td>' . htmlspecialchars($resultado['resposta']) . '</td>';
        echo '</tr>';
    }
    
    echo '</table></div>
    </body>
    </html>';
} else {
    echo "Erro: dados necessários não encontrados na sessão.";
}
?>
