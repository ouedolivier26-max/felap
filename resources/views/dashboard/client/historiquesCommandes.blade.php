@extends('layout.master')
@section('main')
<div class="mx-auto w-full bg-gray-50 px-4 pt-24 sm:px-6 lg:px-24">
    <div class="mx-auto max-w-6xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:p-8">
        <div class="mb-8 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Historique des commandes</h1>
                <p class="mt-1 text-sm text-gray-600">Consultez toutes vos commandes passées et leur statut</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                Revenir au suivi
            </a>
        </div>

        @if(!empty($commandes) && $commandes->count())
            <div class="space-y-4">
                @foreach($commandes as $commande)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $commande->commande_number }}</p>
                                <p class="text-sm text-gray-500">Commande passée le {{ $commande->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">{{ ucfirst($commande->commande_statut ?? 'en attente') }}</span>
                                <span class="text-sm font-semibold text-gray-800">{{ number_format($commande->total_a_payer ?? 0, 2) }} DH</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center text-gray-500">
                Aucun historique de commande disponible pour le moment.
            </div>
        @endif
    </div>
</div>
@endsection
