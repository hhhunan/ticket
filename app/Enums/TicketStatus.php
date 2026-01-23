<?php

namespace App\Enums;

enum TicketStatus: string
{

    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case PROCESSED = 'processed';


    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Ticket is new',
            self::IN_PROGRESS => 'Ticket is progressing',
            self::PROCESSED => 'Ticket is processed',
        };
    }

    public static function toArray(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'bg-blue-100 text-blue-800',
            self::IN_PROGRESS => 'bg-yellow-100 text-yellow-800',
            self::PROCESSED => 'bg-green-100 text-green-800',
        };
    }
}
