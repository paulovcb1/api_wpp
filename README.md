# Envio de Mensagens para Contatos via API

Este projeto PHP permite carregar uma planilha de contatos, selecionar uma coluna com números de telefone e enviar mensagens em massa via uma API externa. Ele utiliza a biblioteca PHPSpreadsheet para manipular arquivos `.xlsx` e gerenciar o envio de mensagens de forma organizada.

## Funcionalidades

- **Upload de Arquivo**: Carregue um arquivo `.xlsx` com a lista de contatos.
- **Seleção de Coluna**: Escolha a coluna que contém os números de telefone.
- **Envio de Mensagens**: Envie uma mensagem personalizada para os números de telefone selecionados usando uma API de envio de mensagens.
- **Exibição do Status**: Após o envio, o status de cada número (enviado com sucesso, número inválido, etc.) é exibido em uma tabela HTML.

## Requisitos

- PHP 7.4 ou superior
- Composer
- Biblioteca PHPSpreadsheet

## Estrutura do Projeto

```plaintext
├── index.php                # Página inicial do projeto para upload de planilha
├── processar_planilha.php   # Lida com o upload e processamento da planilha, salvando dados na sessão
├── selecionar_coluna.php    # Interface para selecionar a coluna e inserir a mensagem
├── enviar_para_api.php      # Processa o envio das mensagens e exibe o status
├── style/
│   └── style.css            # Estilos CSS para a interface do usuário
└── README.md                # Documentação do projeto
