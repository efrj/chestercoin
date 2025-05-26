# Chestercoin (CHC) - Simulador de Blockchain em PHP

![Chestercoin Logo](public/assets/img/logo.png)

> O futuro das transações descentralizadas - um sistema de blockchain simulado e educacional.

O **Chestercoin (CHC)** é uma simulação didática de uma criptomoeda desenvolvida com **PHP 8.1+**, usando apenas arquivos locais (sem banco de dados), ideal para estudo e compreensão básica de como funcionam sistemas blockchain e carteiras digitais. 

Esse projeto é um meme coin. 

---

## Sobre o Projeto

Este projeto simula os seguintes conceitos de criptomoedas:
- Geração de **chaves pública e privada**
- **Autenticação com chave privada**
- **Transferência de moedas entre carteiras**
- **Exportação/importação de carteira (.json)**
- Histórico de login por usuário
- Gráficos interativos de saldo e fluxo de moedas
- Interface visual do **Blockchain público** 

Tudo isso sem usar banco de dados. Os dados são armazenados localmente em arquivos `.json`.

---

## Como Rodar o Projeto

Na raiz do projeto, execute:

```bash
php -S localhost:8000 -t public/ 
```

Acesse no navegador: http://localhost:8000 

Ou use um servidor de sua preferência (Apache, Nginx, etc), onde o diretório root seja a pasta public.

## Pastas e Arquivos Importantes 

- data/ - Armazena carteiras, transações e histórico de login
- public/ - Ponto de entrada do site (index.php)
- app/views/ - Páginas dinâmicas (HTML renderizado)
- functions.php - Funções centrais do sistema
- config.php - Definições de caminhos e constantes 

## Observações de Segurança 
- Este é um projeto educacional , não destinado à produção.
- As "carteiras" são simuladas com hash SHA256 (não criptografia real). 
- Os dados são armazenados localmente em formato .json. 
- O sistema não usa criptografia SSL nem proteção contra CSRF. 

## Quer contribuir? 
- Para dúvidas ou melhorias, abra uma issue no repositório ou entre em contato! 
