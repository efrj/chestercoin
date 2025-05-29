# Chestercoin (CHC) - Simulador de Blockchain em PHP

![Chestercoin Logo](public/assets/img/logo.png) 

O **Chestercoin (CHC)** é uma simulação didática de uma criptomoeda desenvolvida com **PHP 8.1+**, usando apenas arquivos locais (sem banco de dados), ideal para estudo e compreensão básica de como funcionam sistemas blockchain e carteiras digitais. 

Esse projeto é um meme coin. 

---

## Sobre o Projeto

Este projeto simula os seguintes conceitos de criptomoedas:
- Geração de **chaves pública e privada** (realizada pela classe `` `Wallet` ``).
- **Autenticação com chave privada** (gerenciada pela classe `` `Wallet` ``).
- **Transferência de moedas entre carteiras** (orquestrada pela classe `` `Transaction` ``, utilizando a classe `` `Wallet` `` para manipulação de saldos).
- **Exportação/importação de carteira (.json)** (funcionalidades da classe `` `Wallet` ``).
- Histórico de login por usuário (registrado e acessado através de mecanismos internos da aplicação).
- Gráficos interativos de saldo e fluxo de moedas (gerados a partir de dados processados pelas classes `` `Transaction` `` e `` `Wallet` ``).
- Interface visual do **Blockchain público** (apresentando transações obtidas através da classe `` `Transaction` ``).

Tudo isso sem usar banco de dados. Os dados são armazenados localmente em arquivos `` `.json` ``, gerenciados pelas respectivas classes de modelo.

---

## Como Rodar o Projeto

Na raiz do projeto, execute:

```bash
php -S localhost:8000 -t public/ 
```

Acesse no navegador: http://localhost:8000 

Ou use um servidor de sua preferência (Apache, Nginx, etc), onde o diretório root seja a pasta public.

## Pastas e Arquivos Importantes 

- `` `data/` `` - Armazena carteiras, transações e histórico de login
- `` `public/` `` - Ponto de entrada do site (`` `index.php` ``)
- `` `app/core/` `` - Contém o núcleo da aplicação, como o roteador (`` `Router.php` ``) e a classe base de visualização (`` `View.php` ``).
- `` `app/models/` `` - Contém as classes que representam os dados da aplicação, como `` `Wallet.php` `` para gerenciar carteiras e `` `Transaction.php` `` para transações.
- `` `app/views/` `` - Contém os arquivos de template (HTML) para apresentar os dados aos usuários.
- `` `config.php` `` - Definições de caminhos e constantes 

## Observações de Segurança 
- Este é um projeto educacional , não destinado à produção.
- As "carteiras" são simuladas com hash SHA256 (não criptografia real). 
- Os dados são armazenados localmente em formato `` `.json` ``. 
- O sistema não usa criptografia SSL nem proteção contra CSRF. 

## Quer contribuir? 
- Para dúvidas ou melhorias, abra uma issue no repositório ou entre em contato! 
