<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-bar"></i> Eficiência por Linha
    </div>
    <div class="card-body">
        <canvas id="{{ $chartId }}" height="280"></canvas>
    </div>
</div>

<!-- Dados do gráfico (hidden) -->
<div id="chartData" 
    data-labels='@json($productionData->pluck("line_name"))' 
    data-values='@json($productionData->pluck("avg_efficiency"))'
    data-empty="{{ $productionData->isEmpty() ? '1' : '0' }}"
    style="display:none;">
</div>