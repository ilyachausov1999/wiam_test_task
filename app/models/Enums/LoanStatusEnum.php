<?php

declare(strict_types=1);

namespace app\models\Enums;

enum LoanStatusEnum: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case DECLINED = 2;

    public static function getStatusCodes(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function tryFromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'В обработке',
            self::APPROVED => 'Одобрена',
            self::DECLINED => 'Отклонена',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this === self::DECLINED;
    }

    public static function getValues(): array
    {
        return [
            self::PENDING->value,
            self::APPROVED->value,
            self::DECLINED->value,
        ];
    }
}