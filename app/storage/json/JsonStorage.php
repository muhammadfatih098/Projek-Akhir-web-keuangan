<?php
class JsonStorage
{
    private string $file;

    public function __construct(string $filename)
    {
        $this->file = __DIR__ . '/' . $filename;
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    public function all(): array
    {
        return json_decode(file_get_contents($this->file), true) ?: [];
    }

    public function findById(string $id): ?array
    {
        foreach ($this->all() as $item) {
            if ($item['id'] === $id) return $item;
        }
        return null;
    }

    public function insert(array $data): void
    {
        $items = $this->all();
        $items[] = $data;
        file_put_contents($this->file, json_encode($items, JSON_PRETTY_PRINT));
    }

    public function update(string $id, array $data): void
    {
        $items = $this->all();
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $data);
                break;
            }
        }
        file_put_contents($this->file, json_encode($items, JSON_PRETTY_PRINT));
    }

    public function delete(string $id): void
    {
        $items = array_filter($this->all(), fn($i) => $i['id'] !== $id);
        file_put_contents($this->file, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }
}