@extends('layout.master')
@section('main')
<div class="flex-1 overflow-auto bg-gray-50 px-4 py-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Gestion de Livraison</h1>
                <p class="mt-1 text-sm text-gray-600">Gérer vos livreurs et leurs informations</p>
            </div>
        </div>

        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative w-full lg:max-w-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500">
                    <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                </svg>
                <input class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm outline-none transition focus:border-gray-400" placeholder="Rechercher" />
            </div>
            <button onclick="openModalLivreur()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                <i class="fas fa-user-plus"></i>
                Ajouter un livreur
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <th scope="col" class="px-6 py-3">Nom livreur</th>
                            <th scope="col" class="hidden px-6 py-3 sm:table-cell">Téléphone</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Email</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Ville</th>
                            <th scope="col" class="hidden px-6 py-3 lg:table-cell">Adresse</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($livreurs as $livreur)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $livreur->nom_livreur }}</div>
                                    <div class="text-xs text-gray-500">{{ $livreur->nom_entreprise }}</div>
                                </td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 sm:table-cell">{{ $livreur->utilisateur->phone }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ $livreur->utilisateur->email }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ $livreur->utilisateur->ville }}</td>
                                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 lg:table-cell">{{ $livreur->utilisateur->adresse }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        @if($livreur->statut == 'disponible')
                                            <span class="mr-2 h-2.5 w-2.5 rounded-full bg-green-700"></span>
                                            <span class="text-sm text-gray-700">Disponible</span>
                                        @else
                                            <span class="mr-2 h-2.5 w-2.5 rounded-full bg-red-700"></span>
                                            <span class="text-sm text-gray-700">Indisponible</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.livraison.delete', $livreur->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Supprimer</button>
                                    </form>
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
{{--
    Bug corrigé : le modal restait "hidden" par défaut même quand la
    soumission précédente avait échoué la validation. L'admin remplissait
    le formulaire, cliquait sur "Enregistrer", la page se rechargeait, le
    modal disparaissait et le formulaire était vide — donnant l'impression
    que "rien ne se passe". On rouvre maintenant automatiquement le modal
    dès que $errors contient une erreur liée à ce formulaire.
--}}
<div id="livreurModal" class="{{ $errors->any() ? 'flex' : 'hidden' }} fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
 <div class="bg-white rounded-md shadow-lg w-full max-w-2xl max-h-[100vh] overflow-y-auto">
  <div class="flex justify-between items-center p-4 border-b">
    <h2 class="text-xl font-bold text-gray-800">Ajouter un livreur</h2>
    <button onclick="closeModalLivreur()" class="text-gray-500 hover:text-gray-700">
      <i class="text-2xl ri-close-line"></i>
    </button>
  </div>
   
  <div class="px-6 py-4">
   <form method="POST" action="{{ route('admin.livraison.store') }}" id="livreurForm">
     @csrf
     <div class="mb-6">
       <h3 class="pb-2 mb-4 text-sm font-medium text-gray-700 uppercase border-b">INFORMATION DU LIVREUR</h3>
         
         <div class="space-y-4">
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Entreprise</label>
             <input type="text" name="nom_entreprise" value="{{ old('nom_entreprise') }}" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('nom_entreprise') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
               placeholder="Nom d'entreprise">
             @error('nom_entreprise')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>
           
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
             <input type="email" name="email" value="{{ old('email') }}" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
               placeholder="email@exemple.com">
             @error('email')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>
           
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Nom Livreur</label>
             <input type="text" name="nom_livreur" value="{{ old('nom_livreur') }}" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('nom_livreur') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
               placeholder="Nom et prénom">
             @error('nom_livreur')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>
           
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Téléphone</label>
             <input type="tel" name="phone" value="{{ old('phone') }}" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
               placeholder="Ex: 06 12 34 56 78">
             @error('phone')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>
           
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Ville</label>
             <select name="ville" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('ville') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow">
               <option value="">Sélectionnez une ville</option>
               <option value="casablanca" @selected(old('ville') === 'casablanca')>Casablanca</option>
               <option value="rabat" @selected(old('ville') === 'rabat')>Rabat</option>
               <option value="marrakech" @selected(old('ville') === 'marrakech')>Marrakech</option>
               <option value="fes" @selected(old('ville') === 'fes')>Fès</option>
               <option value="tanger" @selected(old('ville') === 'tanger')>Tanger</option>
               <option value="agadir" @selected(old('ville') === 'agadir')>Agadir</option>
             </select>
             @error('ville')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>
           
           <div>
             <label class="block mb-1 text-sm font-medium text-gray-700">Adresse</label>
             <input type="text" name="adresse" value="{{ old('adresse') }}" required
               class="px-4 py-2 w-full text-sm text-gray-600 bg-transparent rounded-md border {{ $errors->has('adresse') ? 'border-red-400' : 'border-gray-200' }} shadow-sm transition duration-300 placeholder:text-gray-400 ease focus:outline-none focus:border-gray-400 hover:border-gray-300 focus:shadow"
               placeholder="Adresse complète">
             @error('adresse')
               <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
             @enderror
           </div>

         </div>
       </div>

       <div class="flex justify-end mt-6 space-x-3">
         <button type="button" onclick="closeModalLivreur()" class="px-4 py-2 text-gray-700 rounded-md hover:bg-gray-100">
           Annuler
         </button>
         <button type="submit" id="livreurSubmitBtn" class="px-4 py-2 text-white bg-gradient-to-b from-gray-900 rounded-md to-gray-950 hover:from-gray-950 hover:to-black disabled:cursor-not-allowed disabled:opacity-60">
           <span id="livreurSubmitLabel">Enregistrer</span>
         </button>
       </div>
     </form>
   </div>
 </div>
</div>
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
    <button type="button" onclick="this.closest('#toast-success').remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
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
    <button type="button" onclick="this.closest('#toast-error').remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
        <span class="sr-only">Fermer</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
                </button>
            </div>
@endif

{{--
    id renommé de "toast" à "toast-validation" pour rentrer dans le
    sélecteur [id^="toast-"] du setTimeout ci-dessous — sinon ce toast
    ne se masquait jamais automatiquement, contrairement aux deux autres.
--}}
@if($errors->any())
<div id="toast-validation" class="flex fixed top-6 right-6 z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
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
    <button type="button" onclick="this.closest('#toast-validation').remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
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
 const livreurModal = document.getElementById('livreurModal');
 function openModalLivreur(){
  livreurModal.classList.remove('hidden');
  livreurModal.classList.add('flex');
 }
 function closeModalLivreur(){
  livreurModal.classList.remove('flex');
  livreurModal.classList.add('hidden');
 }

 // Empêche les double-soumissions : un admin qui clique plusieurs fois
 // pendant que la requête est en cours (email lent à envoyer, DB lente)
 // ne déclenche plus qu'un seul POST.
 const livreurForm = document.getElementById('livreurForm');
 const livreurSubmitBtn = document.getElementById('livreurSubmitBtn');
 const livreurSubmitLabel = document.getElementById('livreurSubmitLabel');

 livreurForm.addEventListener('submit', () => {
   livreurSubmitBtn.disabled = true;
   livreurSubmitLabel.textContent = 'Enregistrement...';
 });

 setTimeout(() => {
          document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
      }, 3000);    
 </script>
@endsection
