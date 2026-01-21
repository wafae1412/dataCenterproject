<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resources as $resource)
            <tr>
                <td>{{ $resource->id }}</td>
                <td>{{ $resource->name }}</td>
                <td>{{ $resource->type }}</td>
                <td>{{ $resource->status }}</td>
                <td>
                    <a href="{{ route('resources.show', $resource->id) }}">Voir</a> |
                    <a href="{{ route('resources.edit', $resource->id) }}">Modifier</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucune ressource trouvée</td>
            </tr>
        @endforelse
    </tbody>
</table>
