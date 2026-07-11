@extends('layout.master')
@section('main')
    <main class="mx-auto w-full bg-gray-50 px-4 pt-24 sm:px-6 lg:px-20">
        <div class="mx-auto max-w-6xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:p-8">
            <div class="mb-8 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>
                    <p class="mt-1 text-sm text-gray-600">Consultez et gérez vos dernières alertes</p>
                </div>
                <form action="{{ route('client.notifications.all-lu') }}" method="POST">
                    @csrf
                    <button class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-black">
                        <i class="fas fa-check-double"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <div class="h-2.5 w-2.5 rounded-full bg-gray-900"></div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $notification->titre }}</h3>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-gray-600">{{ $notification->message }}</p>
                        </div>
                        <div class="flex flex-col items-start gap-2 sm:items-end">
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            <form action="{{ route('client.notifications.lu', $notification->id) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center gap-1 text-sm font-medium text-gray-700 transition hover:text-gray-900">
                                    <i class="fas fa-check"></i>
                                    Marquer comme lu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                    Aucune notification pour le moment
                </div>
                @endforelse
            </div>
        </div>
    </main>
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
@endsection

@section('script')
<script>
    setTimeout(() => {
        document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
    }, 3000);   
</script>
@endsection
