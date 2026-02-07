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
    public function calculate(ICalculateContext $baseValue): float;
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
