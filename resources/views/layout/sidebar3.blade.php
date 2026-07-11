<div class="space-y-2">
    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('client.dashboard') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
        <i class="ri-home-line text-lg"></i>
        <span>Tableau de bord</span>
    </a>
    <a href="{{ route('client.notifications') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('client.notifications') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
        <i class="ri-notification-3-line text-lg"></i>
        <span>Notifications</span>
    </a>
    <a href="{{ route('client.profile') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('client.profile') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
        <i class="ri-user-line text-lg"></i>
        <span>Profil</span>
    </a>
    <a href="{{ route('client.colis.recherche') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('client.colis.recherche') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
        <i class="ri-search-line text-lg"></i>
        <span>Recherche colis</span>
    </a>
</div>
