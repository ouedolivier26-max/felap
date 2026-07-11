@extends('layout.master')
@section('main')
<div class="flex-1 overflow-auto bg-gray-50 px-4 py-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Gestion de Clients</h1>
                <p class="mt-1 text-sm text-gray-600">Gérer vos clients et leurs informations</p>
            </div>
        </div>

        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative w-full lg:max-w-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500">
                    <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                </svg>
                <input class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm outline-none transition focus:border-gray-400" placeholder="Rechercher" />
            </div>
            <button onclick="openModalClient()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                <i class="fas fa-user-plus"></i>
                Ajouter un client
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <th scope="col" class="px-6 py-3">Nom client</th>
                            <th scope="col" class="hidden px-6 py-3 sm:table-cell">Téléphone</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Email</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Ville</th>
                            <th scope="col" class="hidden px-6 py-3 lg:table-cell">Adresse</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($clients as $client)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $client->utilisateur->name }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 sm:table-cell">{{ $client->utilisateur->phone }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ $client->utilisateur->email }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ $client->utilisateur->ville }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 lg:table-cell">{{ $client->utilisateur->adresse }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Supprimer</button>
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
<div id="clientModal" class="fixed inset-0 bg-black bg-opacity-50  items-center justify-center p-4 z-50 hidden">
 <div class="bg-white rounded-md shadow-lg w-full max-w-2xl max-h-[100vh] overflow-y-auto">
  <div class="flex justify-between items-center p-4 border-b">
    <h2 class="text-xl font-bold text-gray-800">Nouveau Client</h2>
    <button onclick="closeModalClient()" class="text-gray-500 hover:text-gray-700">
      <i class="ri-close-line text-2xl"></i>
    </button>
  </div>
   
  <div class="px-6 py-4">
   <form>
     <div class="mb-6">
       <h3 class="text-sm font-medium text-gray-700 uppercase mb-4 border-b pb-2">INFORMATION DU CLIENT</h3>
         
         <div class="space-y-4">
           <div>
             <label class="block text-sm font-medium text-gray-700 mb-1">Nom Complet</label>
             <input type="text" class="w-full bg-transparent placeholder:text-gray-400 text-gray-600 text-sm border border-gray-200 rounded-md px-4 py-2 transition duration-300 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 shadow-sm focus:shadow" placeholder="Nom et prénom">
           </div>
           
           <div>
             <label class="block text-sm font-medium text-gray-700 mb-1">Email (optionnel)</label>
             <input type="email" class="w-full bg-transparent placeholder:text-gray-400 text-gray-600 text-sm border border-gray-200 rounded-md px-4 py-2 transition duration-300 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 shadow-sm focus:shadow" placeholder="email@exemple.com">
           </div>       
           
           <div>
             <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
             <input type="tel" class="w-full bg-transparent placeholder:text-gray-400 text-gray-600 text-sm border border-gray-200 rounded-md px-4 py-2 transition duration-300 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 shadow-sm focus:shadow" placeholder="Ex: 06 12 34 56 78">
           </div>
           
           <div>
             <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
             <select class="w-full bg-transparent placeholder:text-gray-400 text-gray-600 text-sm border border-gray-200 rounded-md px-4 py-2 transition duration-300 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 shadow-sm focus:shadow">
               <option value="">Sélectionnez une ville</option>
               <option value="casablanca">Casablanca</option>
               <option value="rabat">Rabat</option>
               <option value="marrakech">Marrakech</option>
               <option value="fes">Fès</option>
               <option value="tanger">Tanger</option>
               <option value="agadir">Agadir</option>
             </select>
           </div>
           
           <div>
             <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
             <input type="text" class="w-full bg-transparent placeholder:text-gray-400 text-gray-600 text-sm border border-gray-200 rounded-md px-4 py-2 transition duration-300 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 shadow-sm focus:shadow" placeholder="Adresse complète">
           </div>
         </div>
       </div>
       
       <div class="flex justify-end space-x-3 mt-6">
         <button type="button" onclick="closeModalClient()" class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100">
           Annuler
         </button>
         <button type="submit" class="px-4 py-2 bg-gradient-to-b from-gray-900 to-gray-950 text-white rounded-md hover:from-gray-950 hover:to-black">
           Enregistrer
         </button>
       </div>
     </form>
   </div>
 </div>
</div>
@endsection

@section('script')
<script>
    const clientModal = document.getElementById('clientModal');
    function openModalClient(){
     clientModal.classList.remove('hidden');
     clientModal.classList.add('flex');
    }
    function closeModalClient(){
     clientModal.classList.remove('flex');
     clientModal.classList.add('hidden');
    }
</script>

@endsection