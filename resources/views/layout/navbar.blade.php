<nav class="fixed inset-x-0 top-0 z-50 h-20 border-b border-gray-200 bg-white/95 shadow-sm backdrop-blur">
 <div class="flex h-full items-center justify-between px-4 mx-auto sm:px-6 lg:px-28">
     <div class="flex items-center text-2xl font-bold text-gray-800">
        <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 rounded-lg px-2 py-1 transition hover:bg-gray-100">
         <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-900 text-white shadow-sm">
             <i class="ri-truck-line text-lg"></i>
         </div>
         <span class="hidden sm:inline">FEL Dashboard</span>
        </a>
     </div>

     <div class="flex items-center gap-3">
         <div class="hidden sm:flex sm:items-center sm:gap-2 mr-2">
             <a href="{{ route('client.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('client.dashboard') ? 'bg-gray-100 text-gray-900' : '' }}">
                 Accueil
             </a>
             <a href="{{ route('client.notifications') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('client.notifications') ? 'bg-gray-100 text-gray-900' : '' }}">
                 Notifications
             </a>
             <a href="{{ route('client.profile') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('client.profile') ? 'bg-gray-100 text-gray-900' : '' }}">
                 Profil
             </a>
         </div>
         <form action="{{ route('logout') }}" method="POST">
           @csrf
           <button class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-black">
               Déconnexion
           </button>
         </form>
     </div>
 </div>
</nav>