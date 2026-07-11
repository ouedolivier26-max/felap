@extends('layout.master')
@section('main')
    <div class="flex-1 overflow-auto bg-gray-50 px-4 py-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
            <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Gestion des Commandes</h1>
                    <p class="mt-1 text-sm text-gray-600">Gérer vos commandes et leurs informations</p>
                </div>
            </div>

            <div class="mb-6 grid gap-3 lg:grid-cols-[1.2fr_0.7fr_0.7fr_auto]">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>
                    <input class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm outline-none transition focus:border-gray-400" placeholder="Rechercher .." />
                </div>
                <select class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm outline-none">
                    <option value="">Filtrer par mois</option>
                    <option value="01">Janvier</option>
                    <option value="02">Février</option>
                    <option value="03">Mars</option>
                    <option value="04">Avril</option>
                    <option value="05">Mai</option>
                    <option value="06">Juin</option>
                    <option value="07">Juillet</option>
                    <option value="08">Août</option>
                    <option value="09">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Décembre</option>
                </select>
                <select class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm outline-none">
                    <option value="">Filtrer par statut</option>
                    <option value="en_cours">En cours</option>
                    <option value="termine">Terminé</option>
                    <option value="annule">Annulé</option>
                </select>
                <button onclick="openModalCommande()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                    <i class="fas fa-plus"></i>
                    Nouvelle Commande
                </button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                <th scope="col" class="px-6 py-3">Commande ID</th>
                                <th scope="col" class="hidden px-6 py-3 sm:table-cell">Date</th>
                                <th scope="col" class="hidden px-6 py-3 md:table-cell">Client</th>
                                <th scope="col" class="hidden px-6 py-3 md:table-cell">Produit</th>
                                <th scope="col" class="hidden px-6 py-3 md:table-cell">Quantité</th>
                                <th scope="col" class="hidden px-6 py-3 md:table-cell">Prix</th>
                                <th scope="col" class="hidden px-6 py-3 md:table-cell">Montant TTC</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($commandes as $commande)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="rounded bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">{{ $commande->commande_number }}</span>
                                    </td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 sm:table-cell">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 md:table-cell">
                                        <div class="text-sm text-gray-900">{{ $commande->client->utilisateur->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $commande->client->utilisateur->phone }}</div>
                                    </td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 md:table-cell">{{ $commande->nom_produit }}</td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 md:table-cell">{{ $commande->quantite }}</td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 md:table-cell">{{ $commande->prix }} DH</td>
                                    <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 md:table-cell">{{ $commande->total_a_payer }} DH</td>
                                    <td class="whitespace-nowrap px-6 py-4">
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
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <button onclick="openModalDetails({{ $commande->id }})" class="rounded-lg border border-gray-200 bg-gray-100 px-4 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">Détails</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 p-6 md:flex-row">
                    <p class="text-sm text-gray-600">Affichage de 1 à 10 sur 45 entrées</p>
                    <div class="flex items-center justify-center space-x-1 md:justify-end">
                        <button class="rounded-lg border border-gray-200 px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 sm:px-3 sm:text-sm">Précédent</button>
                        <button class="rounded-lg bg-gray-950 px-2 py-1 text-xs text-white sm:px-3 sm:text-sm">1</button>
                        <button class="rounded-lg border border-gray-200 px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 sm:px-3 sm:text-sm">2</button>
                        <button class="rounded-lg border border-gray-200 px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 sm:px-3 sm:text-sm">3</button>
                        <button class="rounded-lg border border-gray-200 px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 sm:px-3 sm:text-sm">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- ------------------------------------------  Modal d'Ajoute des Commandes -------------------------------------------- --}}
    <div id="commandeModal" class="hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
        <div class="bg-white rounded-md shadow-lg w-full max-w-4xl max-h-[100vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-medium text-gray-800">Nouvelle Commande</h2>
                <button onclick="closeModalCommande()" class="text-gray-500 hover:text-gray-900">
                    <i class="text-2xl ri-close-line"></i>
                </button>
            </div>

            <div class="px-6 py-4">
                <form method="POST" action="{{ route('admin.commandes.store') }}">
                    @csrf
                    <div class="mb-4">
                        <h3 class="flex justify-between items-center pb-2 mb-2 text-sm font-medium text-gray-900 uppercase border-b cursor-pointer"
                            onclick="toggleSection('clientInfo')">
                            INFORMATION DU CLIENT
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                id="clientInfoIcon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </h3>
                        <div id="clientInfo" class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Nom Complet</label>
                                <input type="text" name="nom_complet"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Nom et prénom">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Téléphone</label>
                                <input type="tel" name="telephone"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Ex: 06 12 34 56 78">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Email</label>
                                <input type="email" name="email"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="email@exemple.com">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Ville</label>
                                <select name="ville"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow">
                                    <option value="">Sélectionnez une ville</option>
                                    <option value="casablanca">Casablanca</option>
                                    <option value="rabat">Rabat</option>
                                    <option value="marrakech">Marrakech</option>
                                    <option value="fes">Fès</option>
                                    <option value="tanger">Tanger</option>
                                    <option value="agadir">Agadir</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-normal text-gray-900">Adresse</label>
                                <input type="text" name="adresse"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Adresse complète">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="flex justify-between items-center pb-2 mb-2 text-sm font-medium text-gray-900 uppercase border-b cursor-pointer"
                            onclick="toggleSection('produitInfo')">
                            INFORMATION DU PRODUIT
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform"
                                id="produitInfoIcon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </h3>

                        <div id="produitInfo" class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Produit</label>
                                <input type="text" name="nom_produit"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Nom du produit">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Description (optionnel)</label>
                                <input type="text" name="details_produit"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Details complémentaire">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Prix Unitaire (DH)</label>
                                <input type="number" name="prix"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Prix unitaire">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Quantité</label>
                                <input type="number" name="quantite"
                                    class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                                    placeholder="Quantité">
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-normal text-gray-900">Image</label>
                                <div class="p-4 text-center bg-gray-50 rounded-md cursor-pointer"
                                    onclick="document.getElementById('fileInput').click()">
                                    <input type="file" id="fileInput" accept="image/jpeg,image/png,image/svg+xml"
                                        class="hidden">
                                    <div class="flex flex-col justify-center items-center">
                                        <div class="mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"
                                                    ry="2" stroke="currentColor" />
                                                <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" />
                                                <polyline points="21 15 16 10 5 21" stroke="currentColor" />
                                                <line x1="14" y1="14" x2="19" y2="19"
                                                    stroke="currentColor" />
                                            </svg>
                                        </div>
                                        <p class="text-base text-gray-900"><span class="text-gray-600">Importer</span> une
                                            image pour commande</p>
                                        <p class="mt-1 text-sm text-gray-500">Les types d'images supporter sont: JPG, PNG,
                                            SVG</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-normal text-gray-900">Méthode de paiement</label>
                                    <div class="relative">
                                        <select name="paiement_type"
                                            class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 appearance-none placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow">
                                            <option value="a_la_livraison">Paiement à la livraison</option>
                                            <option value="en_ligne">Carte bancaire</option>
                                        </select>
                                        <div
                                            class="flex absolute inset-y-0 right-0 items-center px-2 text-gray-900 pointer-events-none">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-normal text-gray-900">Statut de paiement</label>
                                    <div class="relative">
                                        <select name="paiement_status"
                                            class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 appearance-none placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow">
                                            <option value="0">Pas encore payé</option>
                                            <option value="1">Payé</option>
                                        </select>
                                        <div
                                            class="flex absolute inset-y-0 right-0 items-center px-2 text-gray-900 pointer-events-none">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" onclick="closeModalCommande()"
                            class="px-4 py-2 text-gray-900 bg-gray-100 rounded-md hover:bg-gray-200">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-gradient-to-b from-gray-900 rounded-md to-gray-950 hover:from-gray-950 hover:to-black">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ------------------------------------- Modal Details des commandes ------------------------------------------- --}}
    @foreach ($commandes as $commande)
        <div id="detailsModal{{ $commande->id }}"
            class="hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
            <div class="bg-white rounded-md shadow-lg w-full max-w-3xl max-h-[100vh] overflow-y-auto">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <h2 class="text-lg font-medium">Details de Commande N <span
                            class="text-gray-600">{{ $commande->commande_number }}</span></h2>
                    <button onclick="closeModalDetails({{ $commande->id }})" class="text-gray-500 hover:text-gray-900">
                        <i class="text-2xl ri-close-line"></i>
                    </button>
                </div>

                <div class="px-6 py-2 my-1">
                    <p class="text-gray-800">Date de commande: {{ $commande->created_at->format('d M, Y') }}</p>
                </div>

                <div class="grid grid-cols-6 gap-5 px-6">
                    <div class="col-span-4">
                        <div class="mb-5">
                            <div class="bg-[#f5f5f566] p-1 mb-3">
                                <h5 class="text-gray-900">INFORMATION DU PRODUIT</h5>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <div class="text-left">
                                    <p class="mb-1">{{ $commande->nom_produit }}</p>
                                    <p class="mb-2 text-sm text-gray-600">{{ $commande->details_produit }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="mb-1">{{ $commande->prix }} DH</p>
                                    <p class="mb-2 text-sm text-gray-600">Quantité : {{ $commande->quantite }}</p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="grid grid-cols-4 gap-5 justify-between">
                                    <div class="col-span-2">
                                        <h6 class="mb-2">Details de paiement</h6>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Prix unitaire:</span>
                                                <span class="text-sm">{{ $commande->prix }} DH</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Total à payer</span>
                                                <span class="text-sm">{{ $commande->total_a_payer }} DH</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-2 justify-self-end">
                                        <h6 class="mb-2">Méthode de paiement</h6>

                                        <p class="text-sm text-gray-600">
                                            {{ $commande->paiement_type == 'a_la_livraison' ? 'Paiement à la livraison' : 'paiement en ligne' }}
                                        </p>
                                        @if ($commande->paiement_status == 1)
                                            <p class="mt-2 text-sm text-green-700">Payé</p>
                                        @else
                                            <p class="mt-2 text-sm text-red-700">Pas encore payé</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <div class="bg-[#f5f5f566] p-1 mb-3">
                            <h5 class="text-gray-900">INFORMATION DU CLIENT</h5>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="mb-1">Nom Complet</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->client->utilisateur->name }}</p>
                            </div>

                            <div>
                                <p class="mb-1">Téléphone</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->client->utilisateur->photo }}</p>
                            </div>

                            <div>
                                <p class="mb-1">Email</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->client->utilisateur->email }}</p>
                            </div>

                            <div>
                                <p class="mb-1">Adresse de livraison</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->client->utilisateur->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 mt-2 mb-4">
                    <div class="bg-[#f5f5f566] py-1 px-3 mb-3">
                        <h5 class="text-gray-900">INFORMATION DE LIVRAISON</h5>
                    </div>

                    @if ($commande->id_livreur && $commande->livraison_statut == 'accepter')
                        <div class="grid grid-cols-3 gap-5">
                            <div>
                                <p class="mb-1">Entreprise de Livraison</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->livreur->nom_entreprise }}</p>

                                <p class="mb-1">Nom du Livreur</p>
                                <p class="text-sm text-gray-600">{{ $commande->livreur->utilisateur->name }}</p>
                            </div>

                            <div>
                                <p class="mb-1">Email</p>
                                <p class="mb-2 text-sm text-gray-600">{{ $commande->livreur->utilisateur->email }}</p>

                                <p class="mb-1">Téléphone</p>
                                <p class="text-sm text-gray-600">{{ $commande->livreur->utilisateur->phone }}</p>
                            </div>

                            <div>
                                <p class="mb-1">Statut</p>
                                <div class="flex items-center">
                                 @if($commande->livreur->statut ==="disponible")
                                    <span class="mr-2 w-2.5 h-2.5 bg-green-700 rounded-full"></span>
                                    <span class="mb-2 text-sm text-gray-600">{{ $commande->livreur->statut }}</span>
                                 @else   
                                    <span class="mr-2 w-2.5 h-2.5 bg-red-700 rounded-full"></span>
                                    <span class="mb-2 text-sm text-gray-600">{{ $commande->livreur->statut }}</span>
                                 @endif
                                </div>

                                <p class="mb-1">Date de livraison estimée</p>
                                <p class="text-sm text-gray-600">{{ $commande->livreur->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('admin.commandes.assigner-livreur', $commande->id) }}" method="POST">
                            @csrf
                            <div>
                                <select name="livreur_id" class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400">
                                    <option value="">Sélectionnez un livreur</option>
                                    @foreach ($livreurs as $livreur)
                                        <option value="{{ $livreur->id }}" @if($commande->id_livreur === $livreur->id ) selected @endif>{{ $livreur->utilisateur->name }} - {{ $livreur->nom_entreprise }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($commande->livraison_statut === "refuser")
                            <p class="mt-1 text-sm text-red-700">"{{ $commande->livreur->utilisateur->name }}" n'accepte pas la livraison du commande</p>
                            @endif
                            <div class="flex justify-end mt-4 space-x-3">
                                <button type="button" onclick="closeModalDetails({{ $commande->id }})"
                                    class="px-4 py-2 text-gray-900 bg-gray-100 rounded-md hover:bg-gray-200">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-gradient-to-b from-gray-900 rounded-md to-gray-950 hover:from-gray-950 hover:to-black">
                                    Assigner
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('toast')   
@if(session('success'))
<div id="toast-success" class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
    <div class="flex-shrink-0">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            <path d="M9 12l2 2l4 -4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="ml-3 text-sm font-medium text-gray-900">
        {{ session('success') }}
    </div>
    <button type="button" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
        <span class="sr-only">Fermer</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
    <div class="flex-shrink-0">
        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="ml-3 text-sm font-medium text-gray-900">
        {{ session('error') }}
    </div>
    <button type="button" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
        <span class="sr-only">Fermer</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif
@if($errors->any())
<div id="toast" class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
    <div class="flex-shrink-0">
        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="ml-3 text-sm font-medium text-gray-900">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
        <span class="sr-only">Fermer</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif
@endsection

@section('script')
    <script>
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

        const commandeModal = document.getElementById('commandeModal');

        function openModalCommande() {
            commandeModal.classList.remove('hidden');
            commandeModal.classList.add('flex');
        }

        function closeModalCommande() {
            commandeModal.classList.remove('flex');
            commandeModal.classList.add('hidden');
        }

        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const icon = document.getElementById(sectionId + 'Icon');

            section.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        setTimeout(() => {
          document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
      }, 3000);   

    </script>
@endsection