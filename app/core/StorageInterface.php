<?php

interface StorageInterface
{
    public function all(): array;
    public function findById(string $id): ?array;
    public function insert(array $data): void;
    public function update(string $id, array $data): void;
    public function delete(string $id): void;
}