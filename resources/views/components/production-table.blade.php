<div class="card">
    <div class="card-header">
        <i class="fas fa-table"></i> Detalhamento por Linha de Produção
    </div>
    <div class="card-body">
        @if($productionData->isEmpty())
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> Nenhum dado encontrado para o período selecionado.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th class="text-end">Produzido</th>
                            <th class="text-end">Defeitos</th>
                            <th class="text-end">Eficiência</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productionData as $line)
                            <tr>
                                <td>
                                    <strong>{{ $line->line_name }}</strong>
                                </td>
                                <td class="text-end">
                                    {{ number_format($line->total_produced) }}
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-danger">
                                        {{ number_format($line->total_defective_parts) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge {{ $line->avg_efficiency >= 95 ? 'bg-success' : ($line->avg_efficiency >= 90 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ number_format($line->avg_efficiency, 2) }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>