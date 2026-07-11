@extends('layout.master')
@section('main')
<div class="flex-1 overflow-auto bg-gray-50 px-4 py-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Historique des paiements</h1>
                <p class="mt-1 text-sm text-gray-600">Consulter votre historique des paiements en détails</p>
            </div>
            <button onclick="openModalAjoutPaiement()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                <i class="fas fa-plus"></i>
                Ajouter un paiement
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <th scope="col" class="px-6 py-3">Commande ID</th>
                            <th scope="col" class="hidden px-6 py-3 sm:table-cell">Détails</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Date de paiement</th>
                            <th scope="col" class="hidden px-6 py-3 md:table-cell">Client</th>
                            <th scope="col" class="px-6 py-3 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($paiements as $paiement)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">{{ $paiement->colis->colie_number }}</span>
                            </td>
                            <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 sm:table-cell">{{ $paiement->details }}</td>
                            <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-700 md:table-cell">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                            <td class="hidden whitespace-nowrap px-6 py-4 md:table-cell">
                                <div class="text-sm text-gray-900">{{ $paiement->colis->commande->client->utilisateur->name }}</div>
                                <div class="text-xs text-gray-500">{{ $paiement->colis->commande->client->utilisateur->phone }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-green-600">+ {{ $paiement->montant }} dh</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 p-6 md:flex-row">
                <p class="text-sm text-gray-600">Affichage de 1 à 5 sur 45 entrées</p>
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

<div id="ajoutPaiementModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-lg">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b bg-white px-4 py-3 sm:px-6">
            <h2 class="text-base font-semibold sm:text-lg">Ajouter un paiement</h2>
            <button onclick="closeModalAjoutPaiement()" class="text-gray-500 hover:text-gray-700">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>

        <div class="p-4 sm:p-6">
            <form action="{{ route('livreur.paiements.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <div class="mb-4 border-b border-gray-200 pb-2">
                        <h3 class="text-sm font-semibold text-gray-700">Information du paiement</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm text-gray-700">Référence</label>
                            <select name="id_colie" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 shadow-sm outline-none transition focus:border-gray-400 focus:bg-white">
                                <option value="">Sélectionner une référence</option>
                                @foreach($colis as $colie)
                                    <option value="{{ $colie->id }}" @if(old('id_colie') == $colie->id) selected @endif>
                                        {{ $colie->colie_number }} - {{ $colie->commande->client->utilisateur->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm text-gray-700">Date de paiement</label>
                            <input type="date" name="date_paiement" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 shadow-sm outline-none transition focus:border-gray-400 focus:bg-white">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm text-gray-700">Notes (optionnel)</label>
                            <textarea name="details" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 shadow-sm outline-none transition focus:border-gray-400 focus:bg-white" rows="3" placeholder="Ajouter des notes pour ce paiement"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col justify-end gap-2 sm:flex-row sm:gap-3">
                    <button type="button" onclick="closeModalAjoutPaiement()" class="w-full rounded-xl px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-100 sm:w-auto">Annuler</button>
                    <button type="submit" class="w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-black sm:w-auto">Enregistrer</button>
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
 <button type="button" onclick="this.parentElement.remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
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
 <button type="button" onclick="this.parentElement.remove()" class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
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

        const paiementModal = document.getElementById('ajoutPaiementModal');

        function openModalAjoutPaiement() {
            paiementModal.classList.remove('hidden');
            paiementModal.classList.add('flex');
        }

        function closeModalAjoutPaiement() {
            paiementModal.classList.remove('flex');
            paiementModal.classList.add('hidden');
        }

        setTimeout(() => {
            document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
        }, 3000); 
         
    </script>
@endsection
