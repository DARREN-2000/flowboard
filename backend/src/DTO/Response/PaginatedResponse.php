<?php
declare(strict_types=1);

namespace App\DTO\Response;

class PaginatedResponse
{
    public readonly array $data;
    public readonly int $total;
    public readonly int $page;
    public readonly int $limit;

    public function __construct(array $data, int $total, int $page, int $limit)
    {
        $this->data = $data;
        $this->total = $total;
        $this->page = $page;
        $this->limit = $limit;
    }

    public function toArray(): array
    {
        $serializedData = [];
        foreach ($this->data as $item) {
            $serializedData[] = method_exists($item, 'toArray') ? $item->toArray() : $item;
        }

        return [
            'data' => $serializedData,
            'meta' => [
                'total' => $this->total,
                'page' => $this->page,
                'limit' => $this->limit,
            ]
        ];
    }
}
