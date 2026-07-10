<ul class="space-y-2">
    <li>
        <a href="{{ route('client.dashboard') }}" class="flex items-center p-2 text-gray-700 hover:bg-indigo-100 rounded">
            <i class="ri-home-line mr-2"></i> Tableau de bord
        </a>
    </li>
    <li>
        <a href="{{ route('client.notifications') }}" class="flex items-center p-2 text-gray-700 hover:bg-indigo-100 rounded">
            <i class="ri-notification-3-line mr-2"></i> Notifications
        </a>
    </li>
    <li>
        <a href="{{ route('client.profile') }}" class="flex items-center p-2 text-gray-700 hover:bg-indigo-100 rounded">
            <i class="ri-user-line mr-2"></i> Profil
        </a>
    </li>
    <li>
        <a href="{{ route('client.colis.recherche') }}" class="flex items-center p-2 text-gray-700 hover:bg-indigo-100 rounded">
            <i class="ri-search-line mr-2"></i> Recherche colis
        </a>
    </li>
</ul>
