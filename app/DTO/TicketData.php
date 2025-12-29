<?php

namespace App\DTO;

class TicketData
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $email,
        public string $subject,
        public string $message,
        public array $attachments = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            email: $data['email'] ?? null,
            subject: $data['subject'],
            message: $data['message'],
            attachments: $data['attachments'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
