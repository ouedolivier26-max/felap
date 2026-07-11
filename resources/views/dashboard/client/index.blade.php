@extends('layout.master')
@section('main')
    <main class="mx-auto w-full bg-gray-50 px-4 pt-24 sm:px-6 lg:px-28">
        <div class="mb-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 border-b border-gray-100 bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-white/20 bg-white/10 shadow-inner">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="h-full w-full object-cover" />
                        @else
                            <img src="{{ asset('assets/images/avatar-placeholder.png') }}" alt="Photo de profil" class="h-full w-full object-cover" />
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-white">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-gray-300">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-gray-100 backdrop-blur">
                    <p class="font-medium">{{ $today->translatedFormat('l, d F Y') }}</p>
                    <p class="mt-1 text-xs text-gray-300">Dernière connexion à {{ now()->format('H:i') }}</p>
                </div>
            </div>

            <div class="px-6 py-6">
                <form action="{{ route('client.colis.recherche') }}" method="GET" class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm">
                    <div class="mx-auto max-w-3xl">
                        <div class="mb-4 flex items-center gap-2">
                            <i class="ri-search-line text-lg text-gray-700"></i>
                            <h2 class="text-lg font-semibold text-gray-800">Rechercher un colis</h2>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <div class="flex-1">
                                <input
                                    type="text"
                                    name="colieNumber"
                                    value="{{ $colieNumber }}"
                                    placeholder="Entrez le numéro du colis (ex: CLS-12345678)"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                                    required
                                >
                            </div>
                            <button
                                type="submit"
                                class="rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-black"
                            >
                                Rechercher
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-5 grid gap-3 md:grid-cols-3">
                    <a href="{{ route('client.notifications') }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Notifications</p>
                                <p class="text-xs text-gray-500">Consulter vos alertes</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('client.profile') }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Profil</p>
                                <p class="text-xs text-gray-500">Mettre à jour vos infos</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('client.dashboard') }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Suivi colis</p>
                                <p class="text-xs text-gray-500">Retour rapide au suivi</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @if ($colis != null)
            <div class="mx-auto mb-8 max-w-5xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-6 flex flex-col justify-between gap-4 border-b border-gray-200 pb-5 md:flex-row md:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Colis ID : <span class="font-bold text-gray-900">{{ $colis->colie_number }}</span></h2>
                        <p class="mt-1 text-sm text-gray-600">Date de création : {{ \Carbon\Carbon::parse($colis->date_creation)->locale('fr_FR')->translatedFormat('d F, Y') }}</p>
                    </div>
                    <a href="{{ route('client.colis.pdf', $colis->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900" title="Télécharger PDF">
                        <i class="ri-download-line"></i>
                        Télécharger PDF
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Information du colis</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Poids</p>
                                    <p class="text-sm text-gray-600">{{ $colis->poids }} kg</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Dimensions</p>
                                    <p class="text-sm text-gray-600">{{ $colis->longueur }} × {{ $colis->largeur }} × {{ $colis->hauteur }} cm</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Commande associée</p>
                                <p class="text-sm text-gray-600 break-words">{{ $colis->commande->commande_number }} - {{ $colis->commande->client->utilisateur->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Adresse de livraison</p>
                                <p class="text-sm text-gray-600 break-words">{{ $colis->commande->client->utilisateur->adresse }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Méthode de paiement</p>
                                <p class="text-sm text-gray-600">{{ $colis->commande->paiement_type == 'a_la_livraison' ? 'Paiement à la livraison' : 'Paiement en ligne' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Information du livreur</h3>
                        @if ($colis->commande->livreur)
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Entreprise de Livraison</p>
                                    <p class="text-sm text-gray-600 break-words">{{ $colis->commande->livreur->nom_entreprise }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Nom Livreur</p>
                                    <p class="text-sm text-gray-600 break-words">{{ $colis->commande->livreur->utilisateur->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Téléphone</p>
                                    <p class="text-sm text-gray-600">{{ $colis->commande->livreur->utilisateur->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Email</p>
                                    <p class="text-sm text-gray-600 break-words">{{ $colis->commande->livreur->utilisateur->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Aucun livreur assigné pour l'instant.</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Status</h3>
                        @php
                            $statutActuel = $colis->statut;
                            $etapeEnPreparationFaite = true;
                            $etapeEnRouteFaite = in_array($statutActuel, ['en_route', 'livree']);
                            $etapeLivreeFaite = $statutActuel === 'livree';
                        @endphp

                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="mt-1 flex-shrink-0">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-800">
                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-700">Commande confirmée</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($colis->commande->date_commande)->locale('fr_FR')->translatedFormat('d M Y | l') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="mt-1 flex-shrink-0">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-800">
                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-700">En préparation</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($colis->date_creation)->locale('fr_FR')->translatedFormat('d M Y | l') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="mt-1 flex-shrink-0">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $etapeEnRouteFaite ? 'bg-gray-800' : 'bg-gray-200' }}">
                                        @if ($etapeEnRouteFaite)
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                        @endif
                                    </span>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <p class="text-sm font-medium {{ $etapeEnRouteFaite ? 'text-gray-700' : 'text-gray-400' }}">En livraison</p>
                                    <p class="text-xs {{ $etapeEnRouteFaite ? 'text-gray-500' : 'text-gray-400' }}">
                                        {{ $colis->date_sortie_reelle ? \Carbon\Carbon::parse($colis->date_sortie_reelle)->locale('fr_FR')->translatedFormat('d M Y | l') : 'En attente' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="mt-1 flex-shrink-0">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $etapeLivreeFaite ? 'bg-gray-800' : 'bg-gray-200' }}">
                                        @if ($etapeLivreeFaite)
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                        @endif
                                    </span>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <p class="text-sm font-medium {{ $etapeLivreeFaite ? 'text-gray-700' : 'text-gray-400' }}">Livré</p>
                                    <p class="text-xs {{ $etapeLivreeFaite ? 'text-gray-500' : 'text-gray-400' }}">
                                        {{ $colis->date_arrivee ? \Carbon\Carbon::parse($colis->date_arrivee)->locale('fr_FR')->translatedFormat('d M Y | l') : 'En attente' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(!empty($colieNumber))
            <div class="mx-auto flex max-w-3xl justify-center rounded-2xl border border-gray-200 bg-white px-6 py-10 text-center text-gray-700 shadow-sm">
                <p>
                    Aucun colis trouvé pour <span class="font-semibold">{{ $colieNumber }}</span>.
                </p>
            </div>
        @endif
    </main>
@endsection