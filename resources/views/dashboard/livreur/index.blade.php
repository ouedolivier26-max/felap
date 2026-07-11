@extends('layout.master')
@section('main')
    <div class="main-content flex-1 overflow-auto px-5">
        <header class="mt-2 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
                <p class="px-1 py-2 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('D, d M, Y, h:i A') }}</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <button id="notificationButton" class="relative rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                        onclick="toggleNotificationModal()">
                        <i class="text-xl fas fa-bell"></i>
                        @if ($notifications && $notifications->count() > 0)
                            <span class="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $notifications->count() }}</span>
                        @endif
                    </button>
                    <div id="notificationModal" class="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg" style="max-height: 400px; overflow-y: auto;">
                        <div class="border-b border-gray-200 bg-gray-50 px-3 py-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                <a href="{{ route('livreur.notifications') }}" class="text-xs font-medium text-gray-700 hover:text-black">Voir tout</a>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @if ($notifications && $notifications->count() > 0)
                                @foreach ($notifications as $notification)
                                    <div class="px-4 py-3 transition duration-150 ease-in-out hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->titre }}</p>
                                                <p class="truncate text-xs text-gray-500">{{ $notification->message }}</p>
                                                <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-4 py-6 text-center">
                                    <p class="text-sm text-gray-500">Aucune notification</p>
                                </div>
                            @endif
                        </div>
                        @if ($notifications && $notifications->count() > 0)
                            <div class="border-t border-gray-100 bg-gray-50 p-2">
                                <form action="{{ route('livreur.notifications.all-lu') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-center text-xs font-medium text-gray-800 transition hover:bg-gray-100">
                                        Tout marquer comme lu
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Livreur</p>
                    </div>
                    <div class="h-10 w-10 overflow-hidden rounded-full bg-gray-200">
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="profile" class="h-full w-full object-cover" />
                    </div>
                </div>
            </div>
        </header>

        <div class="mb-10 mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Revenus</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                </div>
                <div class="mb-4 h-px bg-gray-200"></div>
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">{{ number_format($todayRevenue, 2) }} DH</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ number_format($monthlyRevenue, 2) }} DH</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Colie livré</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                </div>
                <div class="mb-4 h-px bg-gray-200"></div>
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">{{ $todayDeliveredColis }} colie</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $monthlyDeliveredColis }} colie</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Colie en livraison</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                </div>
                <div class="mb-4 h-px bg-gray-200"></div>
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">{{ $todayColisInDelivery }} colie</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $monthlyColisInDelivery }} colie</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Commande</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                </div>
                <div class="mb-4 h-px bg-gray-200"></div>
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800">{{ $todayTotalOrders }} commande</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $monthlyTotalOrders }} commande</span>
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                </div>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm lg:col-span-1">
                <div class="p-4 md:p-6">
                    <div class="mb-4 flex items-center justify-between md:mb-6">
                        <h3 class="text-base font-semibold text-gray-700 md:text-lg">Paiements Récents</h3>
                        <a href="{{ route('livreur.paiements') }}" class="text-xs text-gray-500 transition hover:text-gray-700 md:text-sm">Voir tout</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Command ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($paiements as $paiement)
                                    <tr>
                                        <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-gray-900"><span class="rounded bg-gray-100 px-2 py-1">{{ $paiement->colis->colie_number }}</span></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('M d, Y \a\t H:i') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-xs font-medium text-green-700">+ {{ $paiement->montant }} dh</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm lg:col-span-1">
                <div class="p-4 md:p-6">
                    <div class="mb-4 flex items-center justify-between md:mb-6">
                        <h3 class="text-base font-semibold text-gray-700 md:text-lg">Commandes Récents</h3>
                        <a href="{{ route('livreur.commandes') }}" class="text-xs text-gray-500 transition hover:text-gray-700 md:text-sm">Voir tout</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Command ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th scope="col" class="hidden px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 sm:table-cell">Client</th>
                                    <th scope="col" class="hidden px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 md:table-cell">Status</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($commandes as $commande)
                                    <tr>
                                        <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-gray-900"><span class="rounded bg-gray-100 px-2 py-1">{{ $commande->commande_number }}</span></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">{{ $commande->created_at->format('d/m/Y') }}</td>
                                        <td class="hidden whitespace-nowrap px-3 py-4 text-xs text-gray-500 sm:table-cell">{{ $commande->client->utilisateur->name }}</td>
                                        <td class="hidden whitespace-nowrap px-3 py-4 md:table-cell">
                                            <div class="flex items-center">
                                                @if ($commande->commande_statut == 'livree')
                                                    <span class="mr-2 h-2.5 w-2.5 rounded-full bg-green-700"></span>
                                                    <span class="text-xs">Livrée</span>
                                                @elseif($commande->commande_statut == 'en_livraison')
                                                    <span class="mr-2 h-2.5 w-2.5 rounded-full bg-yellow-700"></span>
                                                    <span class="text-xs">En livraison</span>
                                                @else
                                                    <span class="mr-2 h-2.5 w-2.5 rounded-full bg-blue-900"></span>
                                                    <span class="text-xs">En attente</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-right text-xs font-medium">
                                            <button onclick="openModalDetails({{ $commande->id }})" class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-black">Details</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @foreach ($commandes as $commande)
        <div id="detailsModal{{ $commande->id }}"
            class="hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
            <div class="bg-white rounded-md shadow-lg w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center px-4 py-3 border-b sm:px-6">
                    <h2 class="text-base font-medium sm:text-md">Details de Commande N <span
                            class="text-gray-600">{{ $commande->commande_number }}</span></h2>
                    <button onclick="closeModalDetails({{ $commande->id }})" class="text-gray-500 hover:text-gray-700">
                        <i class="text-2xl ri-close-line"></i>
                    </button>
                </div>

                <div class="px-4 py-2 my-1 sm:px-6">
                    <p class="text-sm text-gray-800 sm:text-base">Date de commande:
                        {{ $commande->created_at->format('d M, Y') }}</p>
                </div>

                <div class="grid grid-cols-1 gap-4 px-4 sm:px-6 md:grid-cols-2 sm:gap-8">
                    <div class="col-span-1">
                        <div class="mb-5">
                            <div class="bg-[#f5f5f566] p-1 mb-3">
                                <h5 class="text-sm font-normal text-gray-900 sm:text-base">INFORMATION DU PRODUIT</h5>
                            </div>

                            <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="mb-1 text-sm font-normal sm:text-base">{{ $commande->nom_produit }}</p>
                                    <p class="mb-2 text-sm text-gray-600 sm:text-base">{{ $commande->details_produit }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <h6 class="mb-2 text-sm font-normal sm:text-base">Details de paiement</h6>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600 sm:text-base">Prix unitaire:</span>
                                                <span class="text-sm font-normal sm:text-base">{{ $commande->prix }}
                                                    DH</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600 sm:text-base">Quantité :</span>
                                                <span
                                                    class="text-sm font-normal sm:text-base">{{ $commande->quantite }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600 sm:text-base">Total à payer</span>
                                                <span
                                                    class="text-sm font-normal sm:text-base">{{ $commande->total_a_payer }}
                                                    DH</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-2 text-sm font-normal sm:text-base">Méthode de paiement</h6>
                                        <p class="text-sm text-gray-600 sm:text-base">
                                            {{ $commande->paiement_type == 'a_la_livraison' ? 'Paiement à la livraison' : 'paiement en ligne' }}
                                        </p>
                                        @if ($commande->paiement_status == 1)
                                            <p class="mt-2 text-sm font-normal text-green-700 sm:text-base">Payé</p>
                                        @else
                                            <p class="mt-2 text-sm font-normal text-red-700 sm:text-base">Pas encore payé
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="bg-[#f5f5f566] p-1 mb-3">
                            <h5 class="text-sm font-normal text-gray-900 sm:text-base">INFORMATION DU CLIENT</h5>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="mb-1 text-sm font-normal sm:text-base">Nom Complet</p>
                                <p class="mb-2 text-sm text-gray-600 sm:text-base">
                                    {{ $commande->client->utilisateur->name }}</p>
                            </div>

                            <div>
                                <p class="mb-1 text-sm font-normal sm:text-base">Téléphone</p>
                                <p class="mb-2 text-sm text-gray-600 sm:text-base">
                                    {{ $commande->client->utilisateur->phone }}</p>
                            </div>

                            <div>
                                <p class="mb-1 text-sm font-normal sm:text-base">Email</p>
                                <p class="mb-2 text-sm text-gray-600 sm:text-base">
                                    {{ $commande->client->utilisateur->email }}</p>
                            </div>

                            <div>
                                <p class="mb-1 text-sm font-normal sm:text-base">Adresse de livraison</p>
                                <p class="mb-2 text-sm text-gray-600 sm:text-base">
                                    {{ $commande->client->utilisateur->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($commande->livraison_statut === 'refuser')
                    <div class="flex gap-2 justify-between items-center px-4 py-4 mt-4 border-t lg:px-6">
                        <p class="text-sm text-red-700">Vous avez refuser la livraison de cette commande</p>
                        <button
                            class="px-4 py-1 text-sm font-normal text-white bg-gradient-to-b from-red-800 to-red-900 rounded hover:from-red-900 hover:to-red-950">Supprimer</button>
                    </div>
                @elseif($commande->livraison_statut === 'en_attente')
                    <div class="flex gap-2 justify-end p-4 mt-4 border-t">
                        <form method="POST" action="{{ route('livreur.commandes.accepter', $commande->id) }}">
                            @csrf
                            <button
                                class="px-4 py-1.5 w-auto text-sm text-white bg-gradient-to-b from-green-700 to-green-800 rounded-md hover:from-green-800 hover:to-green-900 sm:text-base">
                                Accepter
                            </button>
                        </form>
                        <form method="POST" action="{{ route('livreur.commandes.refuser', $commande->id) }}">
                            @csrf
                            <button
                                class="px-4 py-1.5 w-auto text-sm text-white bg-gradient-to-b from-red-700 to-red-800 rounded-md hover:from-red-800 hover:to-red-900 sm:text-base">
                                Refuser
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endsection

@section('toast')
    @if (session('success'))
        <div id="toast-success"
            class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                        fill="none" />
                    <path d="M9 12l2 2l4 -4" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="ml-3 text-sm font-medium text-gray-900">
                {{ session('success') }}
            </div>
            <button type="button"
                class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                <span class="sr-only">Fermer</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="toast-error"
            class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                        fill="none" />
                    <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="ml-3 text-sm font-medium text-gray-900">
                {{ session('error') }}
            </div>
            <button type="button"
                class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                <span class="sr-only">Fermer</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif
    @if ($errors->any())
        <div id="toast"
            class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                        fill="none" />
                    <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="ml-3 text-sm font-medium text-gray-900">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button type="button" onclick="this.parentElement.remove()"
                class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                <span class="sr-only">Fermer</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif
@endsection

@section('script')
    <script>
        function toggleNotificationModal() {
            const modal = document.getElementById('notificationModal');
            modal.classList.toggle('hidden');
        }

        function openModalDetails(commandeId) {
            const modal = document.getElementById(`detailsModal${commandeId}`);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModalDetails(commandeId) {
            const modal = document.getElementById(`detailsModal${commandeId}`);
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        setTimeout(() => {
            document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
        }, 3000);
    </script>
@endsection
