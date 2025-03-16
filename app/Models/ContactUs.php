<?php

namespace App\Models;

use App\Events\ContactUsCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory, HasUlids;
    use SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'company',
        'employees',
        'title',
        'message',
        'status',
        'reply_title',
        'reply_message',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [

    ];

    protected $dispatchesEvents = [
        'created' => ContactUsCreated::class,
    ];

    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

}
