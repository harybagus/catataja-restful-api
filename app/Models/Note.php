<?php

namespace App\Models;

use App\Enums\Pinned;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    protected $table = "notes";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        "title",
        "description",
        "pinned"
    ];

    protected $attributes = [
        'pinned' => Pinned::FALSE->value,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Note::class, "user_id", "id");
    }
}
