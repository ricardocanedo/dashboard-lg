<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Total Produzido</h6>
                <h3 class="mb-0">{{ number_format($consolidated['total_produced'] ?? 0) }}</h3>
                <small class="text-muted">peças</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Peças Boas</h6>
                <h3 class="mb-0 text-success">{{ number_format($consolidated['total_good_parts'] ?? 0) }}</h3>
                <small class="text-muted">peças</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Peças Defeituosas</h6>
                <h3 class="mb-0 text-danger">{{ number_format($consolidated['total_defective_parts'] ?? 0) }}</h3>
                <small class="text-muted">peças</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Eficiência Média</h6>
                <h3 class="mb-0 text-info">{{ number_format($consolidated['efficiency'] ?? 0, 2) }}%</h3>
                <small class="text-muted">do total</small>
            </div>
        </div>
    </div>
</div>