<form method="GET" action="{{ route('resources.index') }}" class="mb-3">
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input 
            type="text" 
            name="search" 
            placeholder="Rechercher une ressource..."
            value="{{ request('search') }}"
            class="form-control"
        >

        <select name="status" class="form-control">
            <option value="">-- Statut --</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                Disponible
            </option>
            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>
                Occupée
            </option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                Maintenance
            </option>
        </select>

        <button type="submit" class="btn btn-primary">
            Filtrer
        </button>
    </div>
</form>
