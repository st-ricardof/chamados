<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Meus Chamados</span>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
        @include('chamados.partials.mostra-ano')

    </div>
</div>

<table class="table table-striped meus-chamados">
    <thead>
        <tr>
            <th>Nro</th>
            <th>Assunto</th>
            <th>Fila</th>
            <th class="text-right">Aberto em</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($chamados as $chamado)
        <tr>
            <td> {{ $chamado->nro }}</td>
            <td>
                @include('chamados.partials.status-small')
                <a href="chamados/{{$chamado->id}}"> {!! $chamado->assunto !!} </a>
                @include('chamados.partials.status-muted')
            </td>
            <td> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}</td>
            <td class="text-right">
                @if($chamado->created_at->format('d/m/Y') == date('d/m/Y'))
                {{ Carbon\Carbon::parse($chamado->created_at)->format('H:i') }}
                @else
                {{ Carbon\Carbon::parse($chamado->created_at)->format('d \d\e M.') }}
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Não há chamados</td>
        </tr>
        @endforelse

    </tbody>
</table>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.meus-chamados').DataTable({
            dom: 't'
            , "paging": false
            , "sort": true
            , "order": [
                [0, "desc"]
            ]
        });

    })

</script>
@endsection
