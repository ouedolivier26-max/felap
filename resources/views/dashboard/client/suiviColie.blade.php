@extends('layout.master')
@section('main')
<div class="mx-auto w-full bg-gray-50 px-4 pt-24 sm:px-6 lg:px-24">
    <div class="mx-auto max-w-6xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:p-8">
        <div class="mb-8 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Suivi de colis</h1>
                <p class="mt-1 text-sm text-gray-600">Visualisez l’état de livraison de vos colis en temps réel</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                Retour au tableau de bord
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-900 text-white">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Aucun suivi détaillé disponible</h2>
                    <p class="text-sm text-gray-500">Utilisez le moteur de recherche du tableau de bord pour consulter un colis précis.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
