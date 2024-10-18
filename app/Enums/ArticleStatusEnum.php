<?php

namespace App\Enums;

/**
 * Enum representing article status.
 */
enum ArticleStatusEnum: int
{
    /**
     * The article status is draft.
     */
    case DRAFT = 10;

    /**
     * The article status is published.
     */
    case PUBLISHED = 20;

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $enum) => [
                $enum->value => $enum->label(),
            ])
            ->toArray();
    }

    public function label(): string
    {
        return trans('article'.'.'.strtolower($this->name));
    }
}
