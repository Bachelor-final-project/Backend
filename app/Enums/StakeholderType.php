<?php

namespace App\Enums;

enum StakeholderType: int
{
    case INBOUND = 1;
    case OUTBOUND = 2;

    public function label(): string
    {
        return match($this) {
            self::INBOUND => __('merchant'),
            self::OUTBOUND => __('beneficiary'),
        };
    }

    public static function options(): array
    {
        return [
            ['id' => self::INBOUND->value, 'name' => self::INBOUND->label()],
            ['id' => self::OUTBOUND->value, 'name' => self::OUTBOUND->label()],
        ];
    }
}