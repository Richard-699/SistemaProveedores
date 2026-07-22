<?php

namespace App\Aplication\Interface\Repository\Auth;

use App\Domain\Model\Auth\User;

interface IRegistroRepository {
    public function findByEmail(string $correo): ?int;
    public function save(User $user): bool;
    public function updatePassword(User $user): bool;
}
