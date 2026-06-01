<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'approved', 'connected', 'rejected'];

    public const SERVICE_CATEGORIES = [
        'new_connection',
        'reconnection',
        'meter_change',
        'billing_update',
        'disconnection',
        'other',
    ];

    protected $fillable = [
        'entry_no',
        'application_date',
        'applicant_name',
        'id_number',
        'contact_number',
        'address',
        'service_address',
        'billing_address',
        'service_category',
        'status',
        'supervised_by',
        'fenaka_id',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
        ];
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervised_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'success',
            'connected' => 'info',
            'rejected' => 'danger',
            default => 'warning',
        };
    }

    public function getWhatsappUrlAttribute(): string
    {
        $number = preg_replace('/\D/', '', config('services.whatsapp.number', '9600000000'));
        $message = urlencode("Regarding application {$this->entry_no} - {$this->applicant_name}");

        return "https://wa.me/{$number}?text={$message}";
    }
}
