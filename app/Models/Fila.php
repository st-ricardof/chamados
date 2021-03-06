<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    protected $attributes = [
        'estado' => 'Em elaboração',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'template',
        'setor_id',
        'estado',
    ];

    protected const fields = [
        [
            'name' => 'setor_id',
            'label' => 'Setor',
            'type' => 'select',
            'model' => 'Setor',
            'data' => [],
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'descricao',
            'label' => 'Descrição',
        ],
    ];

    public static function getFields()
    {
        $fields = SELF::fields;
        foreach ($fields as &$field) {
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    public function getDefaultColumn()
    {
        return 'nome';
    }

    public static function getPessoaModel()
    {
        return [
            [
                'name' => 'nome',
                'label' => 'Nome',
            ],
            [
                'name' => 'funcao',
                'label' => 'Função',
                'type' => 'select',
                'options' => ['Gerente', 'Atendente'],
                'data' => [],
            ],
        ];
    }

    public static function getTemplateFields()
    {
        return ['label', 'type', 'can', 'help', 'value', 'validate'];
    }

    public static function estados()
    {
        return ['Em elaboração', 'Em produção', 'Desativada'];
    }

    public function getConfigAttribute($value)
    {
        $value = $value ? json_decode($value) : new \StdClass;
        $value->triagem = isset($value->triagem) ? $value->triagem : true;
        return $value;
    }

    /**
     * Relacionamento: fila pertence a setor
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    /**
     * Relacionamento n:n com user, atributo funcao: Gerente, Atendente
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_fila')
            ->withPivot('funcao')->orderBy('user_fila.funcao')->orderBy('users.name')
            ->withTimestamps();
    }

    /**
     * Relacionamento: chamado pertence a fila
     */
    public function chamados()
    {
        return $this->hasMany(Chamado::class);
    }
}
