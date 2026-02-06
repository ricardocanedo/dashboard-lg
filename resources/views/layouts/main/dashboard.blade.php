<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard de Produção - Plant A</h2>
        <p class="text-muted">Período: {{ $period['month'] ?? 'N/A' }}</p>
    </div>
</div>

<!-- Filtro -->
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
            <div class="flex-grow-1">
                <select name="production_line_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas as Linhas</option>
                    @foreach($productionLines as $line)
                        <option value="{{ $line->id }}" {{ $selectedLine == $line->id ? 'selected' : '' }}>
                            {{ $line->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($selectedLine)
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Limpar
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Cards Consolidados -->
<x-consolidated-cards :consolidated="$consolidated" />

<!-- Tabela -->
<div class="row">
    <div class="offset-md-2 col-md-8">
        <x-production-table :productionData="$productionData" />
    </div>
</div>

<!-- Gráfico -->
<div class="row mt-4">
    <div class="offset-md-2 col-md-8 mb-4">
        <x-production-chart :productionData="$productionData" />
    </div>
</div>