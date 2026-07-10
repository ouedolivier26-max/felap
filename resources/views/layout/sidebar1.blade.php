{{-- Logo & titre --}}
<div class="p-6 mt-2 border-b border-gray-800">
  <div class="flex items-center mb-4 text-2xl font-bold text-white">
    {{-- Logo SVG conservé --}}
    <svg width="180" height="40" viewBox="0 0 369.66 82.53" fill="#ffffff">
      <!-- Ton logo SVG -->
    </svg>
  </div>
  <p class="text-xs text-gray-400">Gestion des Commandes & Livraison</p>
</div>

{{-- Section GENERAL --}}
<div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">GENERAL</div>
<nav class="mt-2 space-y-1">
  <a href="{{ route('admin.dashboard') }}"
     class="flex items-center gap-3 px-6 py-3 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
    <i class="fas fa-chart-pie"></i>
    <span>Tableau de Bord</span>
  </a>

  <a href="{{ route('admin.commandes') }}"
     class="flex items-center gap-3 px-6 py-3 rounded-md {{ request()->routeIs('admin.commandes*') ? 'bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
    <i class="fas fa-shopping-cart"></i>
    <span>Commandes</span>
  </a>

  <a href="{{ route('admin.livraison') }}"
     class="flex items-center gap-3 px-6 py-3 rounded-md {{ request()->routeIs('admin.livraison*') ? 'bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
    <i class="fas fa-shipping-fast"></i>
    <span>Livraison</span>
  </a>

  <a href="{{ route('admin.clients') }}"
     class="flex items-center gap-3 px-6 py-3 rounded-md {{ request()->routeIs('admin.clients*') ? 'bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
    <i class="fas fa-user-friends"></i>
    <span>Clients</span>
  </a>

  <a href="{{ route('admin.notifications') }}"
     class="flex items-center gap-3 px-6 py-3 rounded-md {{ request()->routeIs('admin.notifications*') ? 'bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
    <i class="fas fa-bell"></i>
    <span>Notifications</span>
  </a>
</nav>

{{-- Section SYSTÈME --}}
<div class="px-4 py-2 mt-6 text-xs font-semibold text-gray-400 uppercase">SYSTÈME</div>
<nav class="mt-2 space-y-1">
  <a href="#" class="flex items-center gap-3 px-6 py-3 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition">
    <i class="fas fa-cog"></i>
    <span>Paramètres</span>
  </a>

  <form action="{{ route('logout') }}" method="POST" class="px-6 py-3">
    @csrf
    <button type="submit" class="flex items-center gap-3 text-gray-300 hover:text-red-500 transition">
      <i class="fas fa-sign-out-alt"></i>
      <span>Déconnexion</span>
    </button>
  </form>
</nav>