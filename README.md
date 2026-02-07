# Product Price Calculator

## 1. Introdução

Este repositório representa a solução para um desafio técnico voltado à construção de um **componente de cálculo de preços para e-commerce B2B**, com foco em regras de negócio complexas e arquitetura orientada a boas práticas de desenvolvimento em PHP.

O desafio propõe a criação de uma biblioteca capaz de calcular o preço final de produtos considerando múltiplas variáveis, como:

- Margem de lucro
- Descontos progressivos por quantidade
- Descontos por tipo de cliente
- Acréscimos por peso
- Impostos configuráveis por estado

Embora o enunciado original incentive o uso de PHP puro, a solução foi concebida utilizando **Laravel 11** como base estrutural, com o objetivo de:

- Organizar melhor as camadas do sistema
- Facilitar a exposição de uma **API REST**
- Garantir maior testabilidade e padronização
- Demonstrar domínio de arquitetura e boas práticas

O sistema disponibiliza um endpoint REST para consumo externo, onde a lógica central de cálculo é executada pela classe de negócio:


Essa classe é responsável por orquestrar o fluxo de cálculo utilizando um conjunto de estratégias desacopladas, mantendo a lógica de negócio extensível e de fácil manutenção.

---

## 1.2 Resolução do problema

Para resolver o problema proposto, foi adotado o conceito de **Pipeline de estratégias de desconto**, utilizando o padrão **Strategy** como base arquitetural.

O processo de cálculo de preço em cenários de e-commerce normalmente segue uma **progressão sequencial de transformações** sobre o valor base. Esse fluxo inclui:

- Aplicação de margem
- Aplicação de descontos progressivos
- Descontos por tipo de cliente
- Acréscimos por peso
- Incidência de impostos

Essa sequência de operações caracteriza um **processo procedural encadeado**, onde cada regra modifica o valor anterior e entrega o resultado para a próxima etapa do cálculo.

Para representar esse comportamento de forma flexível e extensível, foi implementado um **pipeline de estratégias**, onde cada regra de negócio é encapsulada em uma estratégia independente.

### Motivo da escolha do pipeline

O pipeline foi escolhido porque:

- O cálculo de preço é naturalmente sequencial
- As regras podem ser ativadas, removidas ou reordenadas
- Novas regras podem ser adicionadas sem alterar o código existente
- Cada regra possui responsabilidade única

Essa abordagem permite evoluir o sistema sem criar uma classe monolítica com múltiplas condições e cálculos acoplados.

### SOLID aplicado à solução

A classe `ProductCalculator` foi projetada para ser a **orquestradora do processo de cálculo**, e não a responsável por executar todos os cálculos matemáticos diretamente.

Com isso, foram aplicados os seguintes princípios do SOLID:

#### Single Responsibility Principle (SRP)
A responsabilidade da classe `ProductCalculator` é apenas:

- Receber o contexto de cálculo
- Executar o pipeline de estratégias
- Retornar o valor final

Cada regra de negócio possui sua própria classe de estratégia, isolando responsabilidades.

#### Open/Closed Principle (OCP)
O sistema foi projetado para:

- Estar **aberto para extensão** (novas estratégias)
- Estar **fechado para modificação** (sem alterar o núcleo do cálculo)

Novas regras podem ser adicionadas apenas incluindo novas estratégias no pipeline.

#### Dependency Inversion Principle (DIP)
A classe principal não depende de implementações concretas de regras.

Ela depende apenas de abstrações:

```php
interface IProductCalculate
{
    public function calculate(ICalculateContext $productContext): float;
}

class ProductCalculator implements IProductCalculate
{
    protected Collection $strategiesPipeline;

    public function __construct(Collection $strategiesPipeline)
    {
        if ($strategiesPipeline->isEmpty()) {
            throw new InvalidArgumentException('Construct params needs a strategy police...');
        }
        $this->strategiesPipeline = $strategiesPipeline;
    }

    public function calculate(ICalculateContext $calculateContex): float
    {
        $priceCalculate = $calculateContex->getTotal();

        return $this->strategiesPipeline->reduce(function (float $actualPrice, $strategy) use (&$calculateContex) {
            return $strategy->apply($actualPrice, $calculateContex);
        }, $priceCalculate);
    }
}
```

# 2. Strategy Pattern para Cálculos Matemáticos

## 2.1 Conceito de Strategy aplicado ao domínio

O sistema de cálculo de preços foi projetado utilizando o **Strategy Pattern** para representar cada regra de negócio como uma unidade de cálculo independente.

No domínio de e-commerce B2B, o preço final de um produto não é obtido por uma única fórmula fixa. Ele resulta de uma **sequência de operações matemáticas**, como:

- Aplicação de margens
- Descontos por quantidade
- Descontos por tipo de cliente
- Acréscimos por peso
- Incidência de impostos

Essas operações formam um **fluxo procedural de transformações matemáticas**, onde cada etapa recebe um valor e retorna um novo valor modificado.

O uso do **Strategy Pattern** permite representar cada uma dessas transformações como uma estratégia isolada, responsável por apenas um tipo de cálculo.

---

## 2.2 Por que utilizar Strategy para cálculos

O uso do Strategy neste contexto resolve problemas comuns em sistemas de cálculo:

### Sem Strategy (abordagem tradicional)
- Uma única classe com vários `if/else`
- Métodos extensos e difíceis de testar
- Alto acoplamento entre regras
- Dificuldade de adicionar novas fórmulas

### Com Strategy (abordagem adotada)
- Cada cálculo é uma classe independente
- Regras podem ser adicionadas ou removidas facilmente
- Testes unitários isolados por estratégia
- Baixo acoplamento entre regras matemáticas

Essa abordagem permite que o sistema execute um **pipeline de cálculos**, onde:

1. O valor base é obtido
2. Cada estratégia aplica sua regra matemática
3. O valor resultante é enviado para a próxima estratégia
4. O processo continua até o valor final

Isso transforma o cálculo em um **fluxo procedural controlado por estratégias**, mantendo o código orientado a objetos e aderente ao SOLID.

---

## 2.3 Estrutura de diretórios das strategies

Todas as estratégias de cálculo estão localizadas no diretório:

# Estrutura de Serviços e Estratégias

`App\Services\Strategies`

### Visualização de estrutura

```text
App
└── Services
    ├── ProductCalculator.php
    └── Strategies
        ├── IStrategy.php
        ├── DiscountPremiumClientStrategy.php
        ├── DiscountPriceByClientTypeStrategy.php
        ├── ProgressiveDiscountByQuantity.php
        ├── HeavyWeightFreightTaxStrategy.php
        └── IcmsTaxStrategy.php
```

## 2.5 Tabela de strategies e responsabilidades

| Strategy | Interface | Construtor | Descrição do cálculo |
|----------|-----------|-------------|----------------------|
| IStrategy | — | — | Contrato base para todas as strategies de cálculo matemático, garantindo um método comum de aplicação de regras. |
| DiscountPremiumClientStrategy | IStrategy | `__construct(float $discountPercent)` | Aplica desconto adicional para clientes do tipo premium. |
| DiscountPriceByClientTypeStrategy | IStrategy | Sem parâmetros de construção (valores definidos internamente) | Aplica desconto com base no tipo de cliente (varejo, atacado, revendedor). |
| ProgressiveDiscountByQuantity | IStrategy | Sem parâmetros de construção (níveis de desconto definidos internamente no construtor) | Aplica desconto progressivo conforme a quantidade de itens comprados. |
| HeavyWeightFreightTaxStrategy | IStrategy | `__construct(int $startHeavyWeightGrams = 50000, float $taxHeavyWeight = 15)` | Aplica acréscimo fixo quando o peso total ultrapassa o limite configurado (ex: acima de 50kg). |
| IcmsTaxStrategy | IStrategy | Sem construtor (taxa obtida via `CalculateContext->getIcmsTax()`) | Aplica imposto ICMS conforme o estado, utilizando a taxa fornecida pelo contexto, sem acoplamento com models de usuário ou produto. |


## 3. Execução do Pipeline e Interações com Strategies

A execução do cálculo de preço ocorre por meio de um **pipeline de strategies**, onde cada regra de negócio é aplicada de forma sequencial sobre o valor atual do produto.

A classe `ProductCalculator` atua como **orquestradora do processo**, recebendo:

- Um objeto de contexto (`ICalculateContext`) com os dados do produto e do cliente
- Uma coleção de strategies (`strategiesPipeline`) responsáveis pelas regras de cálculo

Cada strategy recebe o valor atual, aplica sua lógica matemática e retorna o novo valor para a próxima etapa do pipeline.

### Diagrama de execução

![Diagrama de execução do pipeline](https://storage.zeloezen.com.br/public/pessoal/diagram-1.jpg)
> **Observação:** A imagem acima é meramente ilustrativa e tem como objetivo facilitar o entendimento do fluxo de execução. Ela não representa necessariamente todos os strategies existentes nem todas as declarações e relações exatamente como estão implementadas no código.


### Fluxo de execução

O fluxo de execução segue as etapas abaixo:

1. O `ProductCalculator` recebe o `ICalculateContext`
2. O valor base é obtido através de:
3. O valor inicial entra no pipeline de strategies
4. Cada strategy executa o método:
5. O valor retornado por uma strategy é enviado para a próxima
6. Após a última strategy, o valor final é retornado pelo `ProductCalculator`

### Exemplo conceitual do pipeline

Preço base: 100,00
↓
Desconto por tipo de cliente → 90,00
↓
Desconto progressivo por quantidade → 87,30
↓
Acréscimo por peso → 102,30
↓
ICMS → 120,71
↓
Preço final


### Benefícios da abordagem

- Regras independentes e desacopladas
- Facilidade para adicionar novas strategies
- Possibilidade de reordenar o pipeline
- Testes unitários isolados por regra
- Código aderente aos princípios do SOLID


## 4. Instalação do projeto

Este projeto foi desenvolvido utilizando **Laravel 11** e pode ser executado localmente seguindo os passos abaixo.

### 4.1 Pré-requisitos

Certifique-se de ter instalado em sua máquina:

- PHP 8.2+
- Composer
- Docker e Docker Compose

---

### 4.3 Instalar dependências do Laravel

Execute o comando abaixo para instalar as dependências do projeto:
```
composer install
```
Depois, gere uma nova chave de aplicação do Laravel:
```
php artisan key:generate
```

### 4.5 Subir banco de dados com Docker

O projeto já possui um arquivo docker-compose.yml na raiz, com serviços de:

MySQL

phpMyAdmin

Para iniciar os containers, execute:
```
docker compose up -d
```

### 4.6 Configurar variáveis de ambiente

Copie o arquivo de exemplo:

```
cp .env.example .env
```
> **Observação:** Aponte o projeto para o MySQL levantado pelo docker com usuário, senha e banco de dados. 

Edite o arquivo .env com as credenciais do MySQL definidas no docker-compose.yml.

Exemplo:
```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=santridb
DB_USERNAME=root
DB_PASSWORD=iZd4m3rxi553gfMICh
```


### 4.7 Teste do banco (opcional)

No seu terminal execute o comando:
```
php artisan tinker
```
entrado no comando tinker, execute:
```
DB::connection()->getPdo();
```
em caso de sucesso de conexão com banco, você terá a resposta:
```
= PDO {#5355
    inTransaction: false,
    attributes: {
      CASE: NATURAL,
      ERRMODE: EXCEPTION,
```


### 4.8 Rodar migrations e seeders

Execute os comandos:
```
php artisan migrate
php artisan db:seed
```
Isso irá:

Criar as tabelas necessárias

Popular o banco com dados iniciais de teste

### 4.8 Iniciar o servidor local

```
php artisan serve
```

O projeto ficará disponível em:

```
http://127.0.0.1:8000
```
## 5. Endpoints / Rotas

A API disponibiliza dois recursos principais:

- **Products**: responsável pelo cadastro de produtos
- **Calculate**: responsável pelo cálculo do preço final com base nas regras de negócio

Todas as rotas seguem o padrão REST utilizando `apiResource`.

---

## 5.1 Products

Rota base:

```
/api/products
```

Responsável por cadastrar um novo produto no sistema.

#### Parâmetros da requisição

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| name | string | Sim | Nome do produto |
| price | numeric | Sim | Preço base do produto |
| stock_quantity | integer | Sim | Quantidade em estoque |
| weight_grams | integer | Não | Peso do produto em gramas |
| stock_uf | string(2) | Sim | Estado de origem do estoque |

#### Exemplo de request

```json
{
  "name": "Produto Teste",
  "price": 100,
  "stock_quantity": 50,
  "weight_grams": 2000,
  "stock_uf": "SP"
}
```
#### Response de sucesso

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | integer | ID do produto criado |

**Status:** `201 Created`

Exemplo:

```json
1
```

## 5.2 Calcular preço

### Endpoint

```
/api/calculate
```

Responsável por calcular o preço final de um ou mais produtos com base nas regras de negócio, utilizando o pipeline de strategies do `ProductCalculator`.

---

### Parâmetros da requisição

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| id | string | Sim | Identificador da requisição de cálculo |
| product | array | Sim | Lista de produtos para cálculo |
| product.id | integer | Sim | ID do produto cadastrado |
| product.quantity | integer | Sim | Quantidade do produto |
| user_id | integer | Sim | ID do usuário para cálculo de regras |

---

### Exemplo de request

```json
{
  "id": "da89d2a281061da08424fb51b3f0255f",
  "product":{
      "id": 1,
      "quantity": 2
    },
  "user_id": 1
}
```

### Response de sucesso

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | string | Identificador da requisição |
| total | float | Valor total calculado |
| performance.duration_ms | float | Tempo de execução do cálculo em milissegundos |

**Status:** `200 OK`

Exemplo:

```json
{
  "id": "teste",
  "total": 123.45,
  "performance": {
    "duration_ms": 4.12
  }
}
```



