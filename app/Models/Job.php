<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
   use HasFactory;

   protected $table = 'job_listing';

   protected $fillable = [
       'title',
       'description',
       'salary',
       'tags',
       'job_type',
       'remote_work',
       'requirements',
       'benefits',
       'address',
       'city',
       'state',
       'zipcode',
       'contact_email',
       'contact_phone',
       'company_name',
       'company_description',
       'company_logo',
       'company_website',
       'user_id'
   ];

   public function user():BelongsTo
   {
    return $this->belongsTo(User::class);
   }


   // Relation to bookmarks

   public function bookmarkedByUser() : BelongsToMany
   {
        return $this->belongsToMany(User::class, 'job_user_bookmarks')->withTimestamps();
   }

   //relationto applicants
   public function applicants():HasMany
   {
    return $this->HasMany(Applicant::class);
   }
}
