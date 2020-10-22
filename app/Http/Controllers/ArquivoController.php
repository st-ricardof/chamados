<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Comentario;

class ArquivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'arquivo'    => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'chamado_id' => 'required|integer|exists:chamados,id'
        ]);

        $arquivo = new Arquivo;
        $arquivo->chamado_id = $request->chamado_id;
        $arquivo->user_id = \Auth::user()->id;
        $arquivo->nome_original = $request->file('arquivo')->getClientOriginalName();
        $arquivo->caminho = $request->file('arquivo')->store('./arquivos/'. date("Y"));
        $arquivo->mimeType = $request->file('arquivo')->getClientMimeType();
        $arquivo->save();

        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $arquivo->chamado_id,
            'comentario' => 'O arquivo '. $arquivo->nome_original .' foi adicionado.',
        ]);
        $request->session()->flash('alert-success', 'O arquivo '. $arquivo->nome_original .' foi adicionado com sucesso!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Arquivo $arquivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $request->validate([
            'nome_arquivo'    => 'required',
        ],
        [
            'nome_arquivo.required'    => 'O nome do arquivo é obrigatório!',
        ]);
        $nome_antigo = $arquivo->nome_original;
        $arquivo->nome_original = $request->nome_arquivo;
        if(substr($arquivo->nome_original, -4) != '.pdf'){
            $arquivo->nome_original .= '.pdf';
        }
        $arquivo->update();
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $arquivo->chamado_id,
            'comentario' => 'O arquivo '. $nome_antigo .' foi renomeado para '.$request->nome_arquivo.'.',
        ]);
        request()->session()->flash('alert-success','Arquivo renomeado com sucesso!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $arquivo->delete();
        Comentario::create([
            'user_id' => \Auth::user()->id,
            'chamado_id' => $arquivo->chamado_id,
            'comentario' => 'O arquivo '. $arquivo->nome_original .' foi excluído.',
        ]);
        $request->session()->flash('alert-success', 'O arquivo '. $arquivo->nome_original .' foi excluído com sucesso!');

        return back();
    }
}
