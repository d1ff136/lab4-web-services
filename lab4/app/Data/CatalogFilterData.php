<?php

namespace App\Data;

final readonly class CatalogFilterData
{
    public const DEFAULT_SORT = 'newest';
    public const DEFAULT_PER_PAGE = 9;

    public function __construct(
        public ?string $q = null,
        public ?string $category = null,
        public ?string $operation = null,
        public string $sort = self::DEFAULT_SORT,
        public int $page = 1,
        public int $perPage = self::DEFAULT_PER_PAGE,
    ) {
    }

    /**створення DTO з перевірених даних запиту ($request->validated()). */
    public static function fromArray(array $data): self
    {
        $q = isset($data['q']) ? trim((string) $data['q']) : '';

        return new self(
            q: $q !== '' ? $q : null,
            category: isset($data['category']) ? (string) $data['category'] : null,
            operation: isset($data['operation']) ? (string) $data['operation'] : null,
            sort: (string) ($data['sort'] ?? self::DEFAULT_SORT),
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? self::DEFAULT_PER_PAGE),
        );
    }

    /**параметри для посилань пагінації (лише перевірені фільтри, без page). */
    public function toQuery(): array
    {
        return array_filter([
            'q' => $this->q,
            'category' => $this->category,
            'operation' => $this->operation,
            'sort' => $this->sort !== self::DEFAULT_SORT ? $this->sort : null,
            'per_page' => $this->perPage !== self::DEFAULT_PER_PAGE ? $this->perPage : null,
        ], fn ($value) => $value !== null);
    }
}