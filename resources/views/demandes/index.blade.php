@extends('layouts.app')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { corePlugins: { preflight: false } };
</script>
@endpush

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-xl rounded-2xl p-8 mt-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📄 Liste des demandes</h2>
        <a href="{{ route('demande.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ Nouvelle demande</a>
    </div>

    @if($demandes->count() === 0)
        <p class="text-gray-600">Aucune demande pour le moment.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Demandeur</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($demandes as $d)
                    <tr>
                        <td class="px-4 py-2">{{ $d->id_demande }}</td>
                        <td class="px-4 py-2">{{ optional($d->demandeur)->nom }} {{ optional($d->demandeur)->prenom }}</td>
                        <td class="px-4 py-2">{{ optional($d->demandeur)->telephone }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ $d->status }}</span>
                        </td>
                        <td class="px-4 py-2">{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-right">
                            <a class="text-indigo-600 hover:underline" href="{{ route('demande.show', $d->id_demande) }}">Voir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $demandes->links() }}
        </div>
    @endif
</div>
@endsection









