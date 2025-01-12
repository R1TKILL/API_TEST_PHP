<?php

declare(strict_types=1);
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pessoa')]
class Pessoa {

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private $age;

    #[ORM\Column(type: 'string', length: 100, nullable: false, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 13, nullable: false)]
    private $cell;


    // * Getter for ID:
    public function getId(): int
    {
        return $this->id;
    }


    // * Getter and Setter for Name:
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    // * Getter and Setter for Age:
    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }


    // * Getter and Setter for Email:
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    // * Getter and Setter for Cell:
    public function getCell(): string
    {
        return $this->cell;
    }

    public function setCell(string $cell): void
    {
        $this->cell = $cell;
    }
}
