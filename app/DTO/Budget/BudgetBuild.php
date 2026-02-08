<?php

declare(strict_types=1);

namespace App\DTO\Budget;

use App\Models\Product;
use App\Models\User;
use App\Services\IBudget;
use InvalidArgumentException;

class BudgetBuild implements IBudget
{
    public function __construct(
        private readonly string $id,
        private readonly int $productId,
        private readonly int $userId,
        private readonly int $quantity
    ) {}

    public static function fromJson(array $data): self
    {

        if (! isset($data['id'], $data['user_id'], $data['product']['id'])) {
            throw new InvalidArgumentException('Expected a object type Budget from data');
        }

        return new self(
            (string) $data['id'],
            (int) $data['product']['id'],
            (int) $data['user_id'],
            (int) $data['product']['quantity']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return Product::find($this->productId);
    }

    public function getUser(): User
    {
        return User::find($this->userId);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
