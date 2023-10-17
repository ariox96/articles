<?php

namespace App\Enums;

use InvalidArgumentException;

/**
 * Enum representing article status.
 */
enum ArticleStatusEnum: int
{
    /**
     * The article status is draft.
     */
    case STATUS_DRAFT = 10;

    /**
     * The article status is published.
     */
    case STATUS_PUBLISHED = 20;

    /**
     * Get the string representation of the enum value.
     *
     * @param int $value
     * @return string
     */
    public static function getString(int $value): string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return strtolower($case->name);
            }
        }
        throw new InvalidArgumentException('Invalid value: ' . $value);
    }

    /**
     * Get the name representation of the enum value.
     *
     * @param int $value
     * @return string
     */
    public static function getName(int $value): string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return strtolower(str_replace('_', ' ', $case->name));
            }
        }
        throw new InvalidArgumentException('Invalid value: ' . $value);
    }
}
