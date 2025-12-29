<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;

    protected $keyType = 'string';

    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject',
        'message',
        'customer_id',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'reply_date' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
            ])->onlyKeepLatest(5);
    }

    public function getAttachmentUrl(Media $media): string
    {
        return $media->getUrl();
    }

    public function scopeNew($query)
    {
        return $query->where('status', TicketStatus::NEW->value);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', TicketStatus::IN_PROGRESS->value);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', TicketStatus::PROCESSED->value);
    }

    public function scopeCreatedBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function changeStatus(string $status): bool
    {
        if (!in_array($status, TicketStatus::toArray())) {
            return false;
        }
        $this->status = $status;
        $this->manager_id = Auth::id();

        return $this->save();
    }


    /**
     * Обработка загрузки файлов
     */
    public function addAttachments(array $files): void
    {
        foreach ($files as $file) {
            $this->addMedia($file)
                ->toMediaCollection('attachments');
        }
    }

}
