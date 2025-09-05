<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'bio',
        'profile_photo_path',
        'languages',
        'experiences',
        'social_links',
        'teaching_methods',
        'phone',
        'website',
        'location',
    ];

    protected $casts = [
        'languages' => 'array',
        'experiences' => 'array',
        'social_links' => 'array',
        'teaching_methods' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,       // مدل مقصد
            User::class,        // مدل میانی
            'id',               // کلید خارجی مدل میانی (User)
            'created_by',       // کلید خارجی مدل مقصد (Course)
            'user_id',          // کلید محلی مدل جاری (Teacher)
            'id'                // کلید محلی مدل میانی (User)
        );
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'teacher_student', 'teacher_id', 'student_id')
            ->withPivot([
                'level',
                'notes',
                'status',
                'start_date',
                'end_date',
                'total_sessions',
            ])
            ->withTimestamps();
    }

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->profile_photo_path) {
                    return asset('storage/'.$this->profile_photo_path);
                }

                return asset('images/default-profile.jpg');
            }
        );
    }
}
