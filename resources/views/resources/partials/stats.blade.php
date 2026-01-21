<div class="stats-container" style="margin-bottom:20px;">
    <div style="display:flex; gap:15px; flex-wrap:wrap;">
        <div class="stat-box">
            <strong>Total</strong><br>
            {{ $resources->count() }}
        </div>

        <div class="stat-box">
            <strong>Disponibles</strong><br>
            {{ $resources->where('status','available')->count() }}
        </div>

        <div class="stat-box">
            <strong>Occupées</strong><br>
            {{ $resources->where('status','occupied')->count() }}
        </div>

        <div class="stat-box">
            <strong>Maintenance</strong><br>
            {{ $resources->where('status','maintenance')->count() }}
        </div>
    </div>
</div>
