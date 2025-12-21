<?php
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../storage/json/JsonStorage.php';

class NoteService
{
    private JsonStorage $notes;

    public function __construct()
    {
        $this->notes = new JsonStorage('notes.json');
        Session::start();
    }

    public function add(array $data): void
    {
        $this->notes->insert($data);
    }

    public function update(string $id, array $data): void
    {
        $this->notes->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->notes->delete($id);
    }

    public function allByUser(): array
    {
        $userId = Session::user()['id'] ?? '';
        return array_values(array_filter($this->notes->all(), fn($n) => $n['user_id'] === $userId));
    }

    public function summary(): array
    {
        $income = $expense = 0;
        foreach ($this->allByUser() as $note) {
            if ($note['type'] === 'income') $income += $note['amount_idr'];
            else $expense += $note['amount_idr'];
        }
        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense
        ];
    }
}