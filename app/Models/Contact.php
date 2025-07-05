<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Set the contact's name attribute.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim(strip_tags($value));
    }

    /**
     * Set the contact's email attribute.
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the contact's phone attribute.
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Get the contact's formatted phone attribute.
     */
    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->phone;

        if (strlen($phone) === 11) {
            return sprintf('(%s) %s-%s',
                substr($phone, 0, 2),
                substr($phone, 2, 5),
                substr($phone, 7)
            );
        }

        if (strlen($phone) === 10) {
            return sprintf('(%s) %s-%s',
                substr($phone, 0, 2),
                substr($phone, 2, 4),
                substr($phone, 6)
            );
        }

        return $phone;
    }
}
