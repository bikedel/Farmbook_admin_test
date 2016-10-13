<?php

namespace App;

use App\Farmbook;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $casts = [
        'is_Admin'   => 'boolean',
        'is_Manager' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin', 'active', 'farmbook',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function farmbooks()
    {
        return $this->belongsToMany('App\Farmbook');
    }

    public function getDatabase()
    {

        $data = Farmbook::where('id', '=', $this->farmbook)->first();

        if (is_null($data)) {

            $data = "No database";
            dd("No database");
        } else {

            $data = $data->database;
        }

        return $data;
    }

    public function getDatabaseName()
    {

        $data = Farmbook::where('id', '=', $this->farmbook)->first();

        if (is_null($data)) {

            $data = "No database";
        } else {

            $data = $data->name;
        }

        return $data;
    }

    public function getDatabaseType()
    {

        $data = Farmbook::where('id', '=', $this->farmbook)->first();

        if (is_null($data)) {

            $data = "No database";
        } else {

            $data = $data->name;
            if (strpos($data, 'FH') !== false) {
                $data = "FH";
            }

        }

        return $data;
    }

    public function isAdmin()
    {

        return $this->admin; // this looks for an admin column in your users table
    }

    public function isManager()
    {

        return $this->manager; // this looks for an manager column in your users table
    }
}
