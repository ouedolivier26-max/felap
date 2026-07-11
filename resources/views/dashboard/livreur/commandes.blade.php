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

        <div class="mb-6 grid gap-3 lg:grid-cols-[1.2fr_0.7fr_0.7fr]">
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

@foreach ($commandes as $commande)
<div id="detailsModal{{ $commande->id }}" class="fixed hidden inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50">
    <div class="bg-white rounded-md shadow-lg w-full max-w-2xl max-h-[80vh] overflow-y-auto">
      <div class="flex items-center justify-between border-b px-4 sm:px-6 py-3">
        <h2 class="text-base sm:text-md font-medium">Details de Commande N <span class="text-gray-600">{{ $commande->commande_number }}</span></h2>
        <button onclick="closeModalDetails({{ $commande->id }})" class="text-gray-500 hover:text-gray-700">
          <i class="ri-close-line text-2xl"></i>
        </button>
      </div>
      
      <div class="px-4 sm:px-6 py-2 my-1">
        <p class="text-gray-800 text-sm sm:text-base">Date de commande: {{ $commande->created_at->format('d M, Y') }}</p>
      </div>
      
      <div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
        <div class="col-span-1">
          <div class="mb-5">
            <div class="bg-[#f5f5f566] p-1 mb-3">
              <h5 class="text-gray-900 text-sm sm:text-base font-normal">INFORMATION DU PRODUIT</h5>
            </div>
            
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
              <div>
                <p class="mb-1 text-sm sm:text-base font-normal">{{ $commande->nom_produit }}</p>
                <p class="text-gray-600 text-sm sm:text-base mb-2">{{ $commande->details_produit }}</p>
              </div>
            </div>
            
            <div class="mt-5">
              <div class="grid grid-cols-1 gap-5">
                <div>
                  <h6 class="mb-2 text-sm sm:text-base font-normal">Details de paiement</h6>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-gray-600 text-sm sm:text-base">Prix unitaire:</span>
                      <span class="text-sm sm:text-base font-normal">{{ $commande->prix }} DH</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600 text-sm sm:text-base">Quantité :</span>
                      <span class="text-sm sm:text-base font-normal">{{ $commande->quantite }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600 text-sm sm:text-base">Total à payer</span>
                      <span class="text-sm sm:text-base font-normal">{{ $commande->total_a_payer }} DH</span>
                    </div>
                  </div>
                </div>
                <div>
                  <h6 class="mb-2 text-sm sm:text-base font-normal">Méthode de paiement</h6>
                  <p class="text-sm sm:text-base text-gray-600">
                    {{ $commande->paiement_type == 'a_la_livraison' ? 'Paiement à la livraison' : 'paiement en ligne' }}
                  </p>
                  @if ($commande->paiement_status == 1)
                    <p class="mt-2 text-sm sm:text-base text-green-700 font-normal">Payé</p>
                  @else
                    <p class="mt-2 text-sm sm:text-base text-red-700 font-normal">Pas encore payé</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-span-1">
          <div class="bg-[#f5f5f566] p-1 mb-3">
            <h5 class="text-gray-900 text-sm sm:text-base font-normal">INFORMATION DU CLIENT</h5>
          </div>
          
          <div class="space-y-3">
            <div>
              <p class="mb-1 text-sm sm:text-base font-normal">Nom Complet</p>
              <p class="text-gray-600 text-sm sm:text-base mb-2">{{ $commande->client->utilisateur->name }}</p>
            </div>
            
            <div>
              <p class="mb-1 text-sm sm:text-base font-normal">Téléphone</p>
              <p class="text-gray-600 text-sm sm:text-base mb-2">{{ $commande->client->utilisateur->phone }}</p>
            </div>
            
            <div>
              <p class="mb-1 text-sm sm:text-base font-normal">Email</p>
              <p class="text-gray-600 text-sm sm:text-base mb-2">{{ $commande->client->utilisateur->email }}</p>
            </div>
            
            <div>
              <p class="mb-1 text-sm sm:text-base font-normal">Adresse de livraison</p>
              <p class="text-gray-600 text-sm sm:text-base mb-2">{{ $commande->client->utilisateur->adresse }}</p>
            </div>
          </div>
        </div>
      </div>

      @if($commande->livraison_statut === "refuser")
      <div class="flex items-center justify-between px-4 py-4 lg:px-6 border-t mt-4 gap-2">
         <p class="text-sm text-red-700">Vous avez refuser la livraison de cette commande</p>
          <button class="px-4 py-1 bg-gradient-to-b from-red-800 to-red-900 text-white text-sm font-normal rounded hover:from-red-900 hover:to-red-950">Supprimer</button>
      </div>
      @elseif($commande->livraison_statut === 'en_attente')
      <div class="flex justify-end p-4 border-t mt-4 gap-2">
       <form method="POST" action="{{ route('livreur.commandes.accepter', $commande->id) }}">
          @csrf
          <button class="w-auto px-4 py-1.5 bg-gradient-to-b from-green-700 to-green-800 text-white rounded-md hover:from-green-800 hover:to-green-900 text-sm sm:text-base">
            Accepter
          </button>
      </form>
      <form method="POST" action="{{ route('livreur.commandes.refuser', $commande->id) }}">
         @csrf
          <button class="w-auto px-4 py-1.5 bg-gradient-to-b from-red-700 to-red-800 text-white rounded-md hover:from-red-800 hover:to-red-900 text-sm sm:text-base">
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

        setTimeout(() => {
          document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
      }, 3000);   

    </script>
@endsection
