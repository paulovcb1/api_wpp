<?php
session_start();
$data = $_SESSION['dados'];
$coluna_telefone = $_POST['coluna_telefone'];
$mensagem = $_POST['mensagem'];


$url = "http://api.wordmensagens.com.br/send-text";

$data = array('instance' => "APY250724024529OWN802",
              'to' => 5561998450867,
              'token' => "0U18P-Z2N-0493S",
              'message' => $mensagem );

$options = array('http' => array(
               'method' => 'POST',
               'content' => http_build_query($data)
));

$stream = stream_context_create($options);

$result = file_get_contents($url, false, $stream);

// Inicio da Verificação de Envio
$res123 = json_decode($result);
$erro = $res123->erro;

if ($erro == true) {
  $status_envio = 'true';
} else {
  $status_envio = 'false';
}
// Fim da Verificação de Envio

//Retorno Completo do Status
echo $status_envio;

?>