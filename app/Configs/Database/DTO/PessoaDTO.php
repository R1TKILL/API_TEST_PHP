<?php

declare(strict_types=1);
namespace App\DTO;

class PessoaDTO {
    
    public int $id;
    public string $name;
    public int $age;
    public string $email;
    public string $cell;

    public function __construct(int $id = 0, string $name = '', int $age = 0, string $email = '', string $cell = '') {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
        $this->cell = $cell;
    }   

}
