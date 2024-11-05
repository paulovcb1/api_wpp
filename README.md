# Envio de Mensagens em Massa via Planilha Excel e API

Este projeto PHP permite que o usuário carregue uma planilha `.xlsx` contendo uma lista de contatos, selecione a coluna com números de telefone e envie uma mensagem personalizada para esses contatos via uma API de envio de mensagens.

## Funcionalidades

- **Upload de Arquivo `.xlsx`**: Carregue um arquivo Excel com os dados dos contatos.
- **Seleção de Coluna**: Escolha a coluna que contém os números de telefone.
- **Envio de Mensagem em Massa**: Insira uma mensagem que será enviada para cada número na coluna selecionada.
- **Exibição do Status de Envio**: Após o envio, o status de cada mensagem é exibido, indicando sucesso ou erro.

## Requisitos

- **PHP** 7.4 ou superior.
- **Composer** para instalação da biblioteca PHPSpreadsheet.
- **Biblioteca PHPSpreadsheet** para manipulação de arquivos Excel.

## Configuração do Projeto

1. **Clonar o Repositório**

   Clone este repositório para seu ambiente local:
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   cd nome-do-repositorio
