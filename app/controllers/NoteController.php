<?php
require_once __DIR__ . '/../services/NoteServices.php';

class NoteController
{
    private NoteService $service;

    public function __construct()
    {
        $this->service = new NoteService();
    }

    public function store(array $data): void
    {
        $this->service->add($data);
    }

    public function update(string $id, array $data): void
    {
        $this->service->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->service->delete($id);
    }
}