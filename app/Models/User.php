<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'codpes', 'telefone', 'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const rules = [
        'codpes' => 'required',
        'name' => 'required',
        'email' => 'email:rfc',
        'telefone' => '',
    ];

    protected const fields = [
        [
            'name' => 'codpes',
            'label' => 'Número USP',
        ],
        [
            'name' => 'name',
            'label' => 'Nome',
        ],
        [
            'name' => 'email',
            'label' => 'Email',
        ],
        [
            'name' => 'telefone',
            'label' => 'Telefone',
        ],
        [
            'name' => 'last_login_at',
            'label' => 'Ultimo login',
            'format' => 'timestamp',
        ],
    ];

    public static function getFields()
    {
        $fields = SELF::fields;
        return $fields;
        // foreach ($fields as &$field) {
        //     if (substr($field['name'], -3) == '_id') {
        //         $class = '\\App\\Models\\' . $field['model'];
        //         $field['data'] = $class::allToSelect();
        //     }
        // }
        // return $fields;
    }


    /**
     * Relacionamento n:n com fila, atributo funcao: Gerente, Atendente
     */
    public function fila()
    {
        return $this->belongsToMany('App\Models\Fila')
            ->withPivot('funcao')
            ->withTimestamps();
    }
}
