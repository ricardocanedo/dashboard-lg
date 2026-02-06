<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard de Produção - Plant A</h2>
        <p class="text-muted">Período: {{ $period['month'] ?? 'N/A' }}</p>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Filtro de Linha de Produção -->
                        <div class="col-md-3">
                            <label for="production_line_id" class="form-label">Linha de Produção</label>
                            <select name="filter[production_line_id]" id="production_line_id" class="form-select">
                                <option value="">Todas as Linhas</option>
                                @foreach($productionLines as $line)
                                    <option value="{{ $line->id }}" 
                                        {{ ($filters['production_line_id'] ?? null) == $line->id ? 'selected' : '' }}>
                                        {{ $line->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro de Data Inicial -->
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Data Inicial</label>
                            <input type="date" 
                                   name="filter[start_date]" 
                                   id="start_date" 
                                   class="form-control"
                                   value="{{ $filters['start_date'] ?? $period['start_raw'] }}">
                        </div>

                        <!-- Filtro de Data Final -->
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Data Final</label>
                            <input type="date" 
                                   name="filter[end_date]" 
                                   id="end_date" 
                                   class="form-control"
                                   value="{{ $filters['end_date'] ?? $period['end_raw'] }}">
                        </div>

                        <!-- Filtro de Eficiência Mínima -->
                        <div class="col-md-3">
                            <label for="min_efficiency" class="form-label">Eficiência Mínima (%)</label>
                            <input type="number" 
                                   name="filter[min_efficiency]" 
                                   id="min_efficiency" 
                                   class="form-control"
                                   min="0" 
                                   max="100" 
                                   step="0.1"
                                   placeholder="Ex: 85"
                                   value="{{ $filters['min_efficiency'] ?? '' }}">
                        </div>

                        <!-- Botões de Ação -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Aplicar Filtros
                            </button>
                            
                            @if(count($activeFilters) > 0)
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpar Filtros
                            </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Filtros Ativos -->
                @if(count($activeFilters) > 0)
                <div class="mt-3">
                    <strong>Filtros Ativos:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($activeFilters as $key => $value)
                        <span class="badge bg-info text-dark">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
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