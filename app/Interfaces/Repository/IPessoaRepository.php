<?php

declare(strict_types=1);
namespace App\IRepository;

use App\Models\Pessoa;
use App\DTO\PessoaDTO;

interface IPessoaRepository {

    public function load(): array | bool | null;
    public function loadById(int $id): Pessoa | bool;
    public function save(PessoaDTO $pessoaDTO);
    public function update(int $id, array $data): bool;
    public function delete($id): bool;

}
