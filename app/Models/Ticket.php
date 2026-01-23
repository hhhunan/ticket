<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\Carbon;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
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
            'reply_at' => 'datetime',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->useDisk('ticket')
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

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);
    }

    public function changeStatus(string $status): bool
    {
        if (!in_array($status, TicketStatus::toArray())) {
            return false;
        }
        if($this->status == TicketStatus::PROCESSED){
            $this->reply_at = Carbon::now();
        }
        $this->status = $status;
        $this->manager_id = Auth::id();
        return $this->save();
    }

    public function addAttachments(array $files): void
    {
        foreach ($files as $file) {
            $this->addMedia($file)
                ->toMediaCollection('attachments');
        }
    }

}
