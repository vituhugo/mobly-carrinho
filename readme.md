## Requisitos Obrigatórios

1. [OK] Os produtos devem estar em um banco de dados relacional nos quais devem ser exibidos na lista;
1. [OK] Os produtos devem possuir os seguintes atributos : nome, descriçao, imagem, preço, categoria(um produto pode estar em mais de uma categoria) , caracteristicas (devem ser pré-definidas e associadas ao produto);
1. [OK] Não é necessário criar um layout elaborado;
1. [OK] O carrinho deve ser mantido mesmo se o usuário navegar em outra pagina (nova busca / listagem / ou detalhe do produto );
1. [OK] O pedido deve ser salvo no banco de dados contemplando todos os itens do carrinho e os dados do usuário ( Pessoais e de entrega );
1. [OK] Não é necessário integração de nenhuma forma de pagamento, apenas gerar o registro do pedido no banco de dados;

#### Requisitos Opcionais

- [OK] KeyValueDB (Redis/Memcache);
- Motores de pesquisa (Solr/Sphinx/ElasticSearch/etc); 
- Queue (RabbitMQ / ApacheMQ / Gearman);
- [OK] Testes Unitários ;
- [OK] Front End : Angular / Bootstrap / React / Vue;
- [OK] Docker

#### Diferenciais

- Restfull API
- Calculo de frete
- Seeders e Factories para Testes


# Setup


### Pré-requisitos
- PHP-7.2
- Mysql-5.7
- Composer
- Docker (Opcional)
- Porta 8080 e 33061 livres.

O setup deste projeto é bem simples. Principalmente se for utilizado o docker.

Após baixar o projeto do github, a primeira coisa a se fazer é ir até a raiz do projeto. 

Copie o arquivo .env.example e renomeio para .env

Em seguida rode o composer:
    
```composer install```


## Com Docker

```docker-compose up -d --build```

```docker-compose exec app /bin/bash```

```php artisan migrate --seed```

Pronto, você já vai ter uma instância do projeto em http://localhost:8080

## Sem o Docker

Caso você não tenha o docker, vai ser necessário configurar o banco de dados com o a applicação, e para isso será necessário mudar as variáveis de ambiente do .env
não é nenhum bixo de 7 cabeças, elas são bem visiveis e utilizao o prefixo "DB_".

Depois de o banco estiver configurado, va na raiz, rode o composer para adicionar o vendor, e execute o comando:

```php artisan migrate --seed```

em seguida rode o comando

```php artisan serve```

Este comando é o servidor embutido do Laravel, ele vai ativar uma instância da aplicação no endereço http://localhost:3000

##### Redis (Sem o docker)

Caso não esteja utilizando o docker e não esteja habituado a configurar o Redis, aconselho a desativar ele.

Para isso, vá no .env e altere a opção da seguinte forma:

```CACHE_DRIVER=file``` 
