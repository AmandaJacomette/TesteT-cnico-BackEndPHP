# Teste Técnico - BackEnd PHP
## Descrição
Este projeto é um teste técnico para a vaga de Desenvolvedor PHP. O teste consiste em desenvolver uma API que recebe dados de voos de uma API disponibilizada pela empresa e agrupa esses voos de acordo com:
 - Tipo de taxa
 - Preço
 - Se o voo é ida
 - Se o voo é volta
 
E retorna os voos agrupados em formato JSON, e ordenados de forma crescente de acordo com o preço.


### Documentação também disponível no Postman
  https://documenter.getpostman.com/view/20420178/Uyr4JKSC

## Teste Local
*Requisitos:*
- Php
- Laravel
- Composer
- Git

Para realizar o teste localmente siga os seguintes passos:
#### 1- Clone o projeto em seu ambiente
  Para isso abra o diretório que deseja cloná-lo no cmd/git bash e rode a seguinte linha:
  
    `git clone https://github.com/AmandaJacomette/TesteTecnico-BackEndPHP.git`
  
#### 2- Entre na pasta
  >SeuDiretirio\TesteTecnico-BackEndPHP

#### 3- Atualize o composer

  `composer update`
  
#### 4- Inicie o artisan

  `php artisan serve`

#### 5- Teste no postman ou derivados
  Basta entrar no postman com sua conta.
  
  Criar ou entrar em um workspace.
  
  Clicar no +
  
  ![image](https://user-images.githubusercontent.com/56279759/163081713-62e1705b-59e4-4938-a83d-b4386849ca65.png)
  
  E fazer o request na seguinte rota:
    `localhost:8000/api/AgrupaVoos`

   ![image](https://user-images.githubusercontent.com/56279759/163081943-84f0f7fd-504a-452c-8eb2-10c19770480b.png)
   
   
   Ao clicar em send, será retornado o json com o resultado do agrupamento.

