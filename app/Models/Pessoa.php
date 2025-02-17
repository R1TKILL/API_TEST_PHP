<?php

declare(strict_types=1);
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: 'pessoa')]
#[ORM\HasLifecycleCallbacks] // * Enables lifecycle events.
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

    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $updated_at;


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


    // * Getter and Setter for Updated_at:
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }


    // * Lifecycle event for automatically updates the updatedAt field when creating a new record.
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->updated_at = new DateTime('now');
    }

    // * Lifecycle event for automatically updates the updatedAt field when modifying an existing record.
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTime('now');
    }

}
