@extends('layout.master')
@section('main')
<div class="flex-1 overflow-auto bg-gray-50 px-4 py-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Gestion des Colis</h1>
                <p class="mt-1 text-sm text-gray-600">Gérer vos colis et leurs informations</p>
            </div>
            <button onclick="openModalColie()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                <i class="fas fa-plus"></i>
                Ajouter un Colis
            </button>
        </div>

        <div class="mb-6 grid gap-3 md:grid-cols-2">
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
                <option value="en_preparation">En préparation</option>
                <option value="en_route">En route</option>
                <option value="livree">Livrée</option>
            </select>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <th scope="col" class="px-6 py-3">Colis ID</th>
                            <th scope="col" class="hidden px-6 py-3 sm:table-cell">Date</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Destination</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Client</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($colis as $colie)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">{{ $colie->colie_number }}</span>
                            </td>
                            <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 sm:table-cell">{{ $colie->created_at->format('d/m/Y') }}</td>
                            <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ $colie->commande->client->utilisateur->adresse }}</td>
                            <td class="hidden whitespace-nowrap px-6 py-4 md:table-cell">
                                <div class="text-sm text-gray-900">{{ $colie->commande->client->utilisateur->name }}</div>
                                <div class="text-xs text-gray-500">{{ $colie->commande->client->utilisateur->phone }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    @if($colie->statut == 'en_preparation')
                                        <span class="mr-2 h-2.5 w-2.5 rounded-full bg-blue-900"></span>
                                        <span class="text-xs">En préparation</span>
                                    @elseif($colie->statut == 'en_route')
                                        <span class="mr-2 h-2.5 w-2.5 rounded-full bg-yellow-700"></span>
                                        <span class="text-xs">En route</span>
                                    @else
                                        <span class="mr-2 h-2.5 w-2.5 rounded-full bg-green-700"></span>
                                        <span class="text-xs">Livrée</span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <button onclick="openModalDetails({{ $colie->id }})" class="rounded-lg border border-gray-200 bg-gray-100 px-4 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">Détails</button>
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
<div id="colisModal" class="hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
 <div class="bg-white rounded-md shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
   <div class="flex sticky top-0 z-10 justify-between items-center px-4 py-3 bg-white border-b sm:px-6">
     <h2 class="text-base font-medium sm:text-lg">Ajouter un Colis</h2>
     <button onclick="closeModalColie()" class="text-gray-500 hover:text-gray-700">
       <i class="text-2xl ri-close-line"></i>
     </button>
   </div>
   
   <div class="p-4 sm:p-6">
     <form action="{{ route('livreur.colis.store') }}" method="POST">
       @csrf
       <div class="mb-6">
         <div class="pb-2 mb-4 border-b border-gray-200">
           <h3 class="text-sm font-medium text-gray-700">INFORMATION DU COLIS</h3>
         </div>
         
         <div class="space-y-4">
           <div>
             <label class="block mb-1 text-sm text-gray-700">Commande associée</label>
             <select name="commande_id" required class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow">
               <option value="">Sélectionner une commande</option>
               @foreach($commandes as $commande)
                 <option value="{{ $commande->id }}">{{ $commande->commande_number }} - {{ $commande->client->utilisateur->name }}</option>
               @endforeach
             </select>
           </div>
           
           <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <div>
               <label class="block mb-1 text-sm text-gray-700">Poids (kg)</label>
               <input
                 type="number"
                 name="poids"
                 step="0.01"
                 class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                 placeholder="Poids"
                 required
               >
             </div>
             
             <div>
               <label class="block mb-1 text-sm text-gray-700">Hauteur (cm)</label>
               <input
                 type="number"
                 name="hauteur"
                 step="0.01"
                 class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                 placeholder="Hauteur"
                 required
               >
             </div>
           </div>
           
           <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
             <div>
               <label class="block mb-1 text-sm text-gray-700">Longueur (cm)</label>
               <input
                 type="number"
                 name="longueur"
                 step="0.01"
                 class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                 placeholder="Longueur"
                 required
               >
             </div>
             
             <div>
               <label class="block mb-1 text-sm text-gray-700">Largeur (cm)</label>
               <input
                 type="number"
                 name="largeur"
                 step="0.01"
                 class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
                 placeholder="Largeur"
                 required
               >
             </div>
           </div>
         </div>
       </div>
       
       <div class="flex gap-2 justify-end mt-6 sm:gap-3">
         <button
           type="button"
           onclick="closeModalColie()"
           class="px-4 py-2 w-auto text-sm text-gray-700 rounded-md hover:bg-gray-100"
         >
           Annuler
         </button>
         <button
           type="submit"
           class="px-4 py-2 w-auto text-sm text-white bg-gradient-to-b from-gray-900 rounded-md to-gray-950 hover:from-gray-950 hover:to-black"
         >
           Enregistrer
         </button>
       </div>
     </form>
   </div>
 </div>
</div>
@foreach($colis as $colie)
<div id="detailsModal{{$colie->id}}" class="hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
 <div class="bg-white rounded-md shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
   <div class="flex sticky top-0 z-10 justify-between items-center px-4 py-3 bg-white border-b sm:px-6">
     <h2 class="text-base font-medium sm:text-md">
        Details de Colis N <span class="text-gray-600" id="colisNumber">{{$colie->colie_number}}</span>
     </h2>
     <button onclick="closeModalDetails({{$colie->id}})" class="text-gray-500 hover:text-gray-700">
       <i class="text-2xl ri-close-line"></i>
     </button>
   </div>
   
   <div class="px-4 py-2 my-1 sm:px-6">
     <p class="text-sm text-gray-800 sm:text-base">
        Date de création: <span>{{ $colie->created_at->format('d/m/Y H:i') }}</span>
     </p>
   </div>
   
   <div class="grid grid-cols-1 gap-4 px-4 sm:px-6 md:grid-cols-2 sm:gap-8">
     <div>
       <div class="bg-[#f5f5f566] p-1 mb-3">
         <h6 class="text-sm font-normal text-gray-900 sm:text-base">INFORMATION DU COLIS</h6>
       </div>
       
       <div class="space-y-4">
         <div class="flex flex-col sm:flex-row sm:items-center">
           <div class="mb-3 w-full sm:w-1/2 sm:mb-0">
             <p class="mb-1 text-sm font-normal sm:text-base">Poids</p>
             <p class="mb-1 text-sm text-gray-600 sm:text-base">{{ $colie->poids }} kg</p>
           </div>
           <div class="w-full text-left sm:w-1/2 sm:text-right">
             <p class="mb-1 text-sm font-normal sm:text-base">Dimensions</p>
             <p class="mb-1 text-sm text-gray-600 sm:text-base">
                {{ $colie->longueur }} × {{ $colie->largeur }} × {{ $colie->hauteur }} cm
             </p>
           </div>
         </div>
         
         <div>
           <p class="mb-1 text-sm font-normal sm:text-base">Commande associée</p>
           <p class="mb-2 text-sm text-gray-600 sm:text-base">
                {{ $colie->commande->commande_number }} - {{ $colie->commande->client->utilisateur->name }}
           </p>
         </div>
         
         <div>
           <p class="mb-1 text-sm font-normal sm:text-base">Statut actuel</p>
           <div class="flex items-center">
                @if($colie->statut == 'en_preparation')
                    <span class="mr-2 w-2.5 h-2.5 bg-blue-900 rounded-full"></span>
                    <span class="text-xs">En préparation</span>
                @elseif($colie->statut == 'en_route')
                    <span class="mr-2 w-2.5 h-2.5 bg-yellow-700 rounded-full"></span>
                    <span class="text-xs">En route</span>
                @else
                    <span class="mr-2 w-2.5 h-2.5 bg-green-700 rounded-full"></span>
                    <span class="text-xs">Livrée</span>
                @endif
           </div>
          </div>
          <form action="{{ route('livreur.colis.update-status', $colie->id) }}" method="POST">
           @csrf
           @method('PUT')  
            <div>
              <p class="mb-2 text-sm font-normal sm:text-base">Mettre à jour status</p>
              <select name="colisStatut" class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400">
                <option value="">Selectionner une status</option>
                <option value="en_preparation" @if($colie->statut == 'en_preparation') selected @endif>En préparation</option>
                <option value="en_route" @if($colie->statut == 'en_route') selected @endif>En route</option>
                <option value="livree" @if($colie->statut == 'livree') selected @endif>Livrée</option>
              </select>
            </div>
       </div>
     </div>
     
     <div>
       <div class="bg-[#f5f5f566] p-1 mb-3">
         <h6 class="text-sm font-normal text-gray-900 sm:text-base">INFORMATION DU CLIENT</h6>
       </div>
       
       <div class="space-y-4">
         <div>
           <p class="mb-1 text-sm font-normal sm:text-base">Nom Complet</p>
           <p class="mb-2 text-sm text-gray-600 sm:text-base">{{ $colie->commande->client->utilisateur->name }}</p>
         </div>
         
         <div>
           <p class="mb-1 text-sm font-normal sm:text-base">Téléphone</p>
           <p class="mb-2 text-sm text-gray-600 sm:text-base">{{ $colie->commande->client->utilisateur->phone }}</p>
         </div>
         
         <div>
           <p class="mb-1 text-sm font-normal sm:text-base">Email</p>
           <p class="mb-2 text-sm text-gray-600 sm:text-base">{{ $colie->commande->client->utilisateur->email }}</p>
         </div>
         
         <div>
           <p class="mb-2 text-sm font-normal sm:text-base">Adresse de livraison</p>
           <p class="mb-2 text-sm text-gray-600 sm:text-base">{{ $colie->commande->client->utilisateur->adresse }}</p>
         </div>
       </div>
     </div>
   </div>

   {{-- <div class="px-4 py-2 sm:px-6">
    <p class="mb-2 text-sm font-normal sm:text-base">Dernière adresse</p>
    <input 
      type="text" 
      id="derniereAdresse" 
      placeholder="Dernière position arriver" 
      class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border border-gray-200 shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
    >
  </div> --}}
   
   <div class="flex gap-2 justify-end p-4 mt-4 border-t">
       <button onclick="closeModalDetails({{$colie->id}})" class="px-4 py-1.5 w-auto text-sm text-gray-700 rounded-md hover:bg-gray-100 sm:text-base">
        Annuler
      </button>

        <button type="submit" class="px-4 py-1.5 w-auto text-sm text-white bg-gradient-to-b from-gray-900 rounded-md to-gray-950 hover:from-gray-950 hover:to-black sm:text-base">
         Enregistrer
        </button>
   </form>  
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
  
    const colisModal = document.getElementById('colisModal');
    function openModalColie(){
     colisModal.classList.remove('hidden');
     colisModal.classList.add('flex');
    }
    function closeModalColie(){
     colisModal.classList.remove('flex');
     colisModal.classList.add('hidden');
    }


    function openModalDetails(colisId){
     const detailsModal = document.getElementById(`detailsModal${colisId}`);
     detailsModal.classList.remove('hidden');
     detailsModal.classList.add('flex');
    }
    function closeModalDetails(colisId) {
           const detailsModal = document.getElementById(`detailsModal${colisId}`);
     detailsModal.classList.remove('flex');
     detailsModal.classList.add('hidden');
    }

      setTimeout(() => {
          document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
      }, 3000);   

</script>
@endsection