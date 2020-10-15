<?php

namespace App\Utils;

use Form;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class JSONForms
{
    public static function generateForm($fila, $chamado = null)
    {
        Form::macro('help', function($text)
        {
            $help = new HtmlString('<small class="form-text text-muted">'.$text.'</small>');
            return $help;
        });

        $template = json_decode($fila->template);
        $form = [];
        $data = null;
        if ($template) {
            if ($chamado) {
                $data = json_decode($chamado->extras);
            }
            foreach ($template as $key => $json) {
                $input = [];
                $type = $json->type;
                # se o template tem autorização
                if (isset($json->can)) {
                    if (!Gate::allows($json->can)) {
                        continue;
                    }
                }
                $value = null;
                $input[] = Form::label("extras[$key]", $template->$key->label, ['class' => 'control-label']);
                if (isset($data->$key)) {
                    $value = $data->$key;
                }
                $input[] = Form::$type("extras[$key]", $value, ['class' => 'form-control', 'rows' => '3']);
                if (isset($json->help)) {
                    $input[] = Form::help($json->help);
                }
                $form[] = $input;
            }
        }
        return $form;
    }
}
