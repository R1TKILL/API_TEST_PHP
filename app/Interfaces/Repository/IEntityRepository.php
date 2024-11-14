<?php

declare(strict_types=1);
namespace App\IRepository;

use App\Models\Pessoa;
use App\DTO\PessoaDTO;

interface IEntityRepository {

    public function load(): array | bool | null;
    public function loadById(int $id): object | bool;
    public function save(object $entity);
    public function update(int $id, array $data): bool;
    public function delete($id): bool;

}
