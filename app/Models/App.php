<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;
    use Notifiable;
    use EncryptableDbAttribute;    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'secret',
        'name',
        'description',
        'domain'
    ];

    /**
     * The attributes that should be encrypted/decrypted to/from db.
     */
    protected $encryptable = [
        'secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    /**
     * Get the apps this user has.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }    
}
