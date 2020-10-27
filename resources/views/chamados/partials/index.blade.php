<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Meus Chamados</span>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    </div>
</div>

<table class="table table-striped meus-chamados">
  <thead>
    <tr>
      <th>Nro</th>
      <th>Fila</th>
      <th>Chamado</th>
      <th>Status</th>
      <th>Aberto em</th>
    </tr>
  </thead>
  <tbody>

    @forelse ($chamados->sortByDesc('created_at') as $chamado)
    <tr>
      <td> {{ $chamado->nro }}/{{ Carbon\Carbon::parse($chamado->created_at)->format('Y') }} </td>
      <td> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}</td>
      <td> <a href="chamados/{{$chamado->id}}"> {!! $chamado->assunto !!} </a></td>
      <td> @include('chamados.partials.status') </td>
      <td> {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}</td>
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
      dom: 't',
      "paging": false,
      "sort": true,
      "order": [[4, "desc"]] 
    });

  })
</script>
@endsection
