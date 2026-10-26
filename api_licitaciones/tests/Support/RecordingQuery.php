<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Doble de prueba mínimo que registra las llamadas a where().
 */
final class RecordingQuery
{
    /** @var list<array{0: string, 1: mixed, 2: mixed}> */
    public array $wheres = [];

    public function where(string $column, $operator = null, $value = null): self
    {
        $this->wheres[] = [$column, $operator, $value];

        return $this;
    }
}
