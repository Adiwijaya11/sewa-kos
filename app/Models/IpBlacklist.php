<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpBlacklist extends Model
{
    use HasFactory;

    protected $table = 'ip_blacklists';

    protected $fillable = [
        'ip_address',
        'reason',
        'banned_by',
    ];

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
