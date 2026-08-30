<?php

namespace App\Entity;

namespace App\Entity;

use JsonSerializable;

class Member implements JsonSerializable
{
    // Constructor Promotion: maakt automatisch private variabelen aan
    public function __construct(
        private ?int $id = null,
        private string $firstName = '',
        private string $lastName = '',
        private string $email = ''
    ) {}

    // Getters
    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getFullName(): string 
    { 
        return trim("{$this->firstName} {$this->lastName}"); 
    }

    public function getEmail(): string 
    { 
        return $this->email; 
    }

    // Bepaalt hoe deze klasse als JSON naar je API/front-end wordt gestuurd
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getFullName(),
            'email' => $this->email,
        ];
    }
}