@extends('layout.master')
@section('main')
    <div class="w-full flex-1 overflow-x-hidden bg-gray-50 px-4 sm:px-5">
        <div class="mx-auto max-w-7xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
            <header class="mb-6 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 sm:text-3xl">Tableau de bord</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('D, d M, Y, h:i A') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button id="notificationButton" class="relative rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                            onclick="toggleNotificationModal()">
                            <i class="text-xl fas fa-bell"></i>
                            @if ($notifications && $notifications->count() > 0)
                                <span class="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $notifications->count() }}</span>
                            @endif
                        </button>
                        <div id="notificationModal" class="absolute right-0 z-50 mt-2 hidden w-[90vw] max-w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg" style="max-height: 400px; overflow-y: auto;">
                            <div class="border-b border-gray-200 bg-gray-50 px-3 py-2">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                    <a href="{{ route('admin.notifications') }}" class="text-xs font-medium text-gray-700 hover:text-black">Voir tout</a>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @if ($notifications && $notifications->count() > 0)
                                    @foreach ($notifications as $notification)
                                        <div class="px-4 py-3 transition duration-150 ease-in-out hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $notification->titre }}</p>
                                                    <p class="truncate text-xs text-gray-500">{{ $notification->message }}</p>
                                                    <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-sm text-gray-500">Aucune notification</p>
                                    </div>
                                @endif
                            </div>
                            @if ($notifications && $notifications->count() > 0)
                                <div class="border-t border-gray-100 bg-gray-50 p-2">
                                    <form action="{{ route('admin.notifications.all-lu') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-center text-xs font-medium text-gray-800 transition hover:bg-gray-100">
                                            Tout marquer comme lu
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">Administrateur</p>
                        </div>
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-200">
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="profile" class="h-full w-full object-cover" />
                            @else
                                <span class="flex h-full w-full items-center justify-center text-sm font-medium text-gray-500">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            {{--
                Badge de variation % réutilisable pour les 4 cartes KPI.
                $change est null, un float positif ou négatif (voir
                DashboardStatsService::percentChange) :
                - null   -> pas de période précédente comparable, badge neutre "—"
                - >= 0   -> flèche verte vers le haut
                - < 0    -> flèche rouge vers le bas
            --}}
            @php
                $renderChangeBadge = function ($change) {
                    if ($change === null) {
                        return '<span class="text-sm text-gray-400">—</span>';
                    }
                    $isUp = $change >= 0;
                    $colorClass = $isUp ? 'text-green-700' : 'text-red-700';
                    $icon = $isUp ? 'up' : 'down';
                    return '<span class="flex items-center text-sm ' . $colorClass . '"><i class="mr-1 fas fa-arrow-' . $icon . '"></i>' . number_format(abs($change), 1) . '%</span>';
                };
            @endphp

            <div class="mb-10 mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
                <div class="min-w-0 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-600">Commande livrée</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                    </div>
                    <div class="mb-4 h-px bg-gray-200"></div>
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-800">{{ $todayDeliveredColis }}</span>
                        {!! $renderChangeBadge($deliveredChange) !!}
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm text-gray-600">{{ $monthlyDeliveredColis }} commande</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                    </div>
                </div>

                <div class="min-w-0 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-600">Commande en Livraison</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                    </div>
                    <div class="mb-4 h-px bg-gray-200"></div>
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-800">{{ $todayColisInDelivery }}</span>
                        {!! $renderChangeBadge($inDeliveryChange) !!}
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm text-gray-600">{{ $monthlyColisInDelivery }} commande</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                    </div>
                </div>

                <div class="min-w-0 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Commande</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                    </div>
                    <div class="mb-4 h-px bg-gray-200"></div>
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-800">{{ $todayTotalOrders }}</span>
                        {!! $renderChangeBadge($ordersChange) !!}
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm text-gray-600">{{ $monthlyTotalOrders }} commande</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                    </div>
                </div>

                <div class="min-w-0 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Revenus</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Aujourd'hui</span>
                    </div>
                    <div class="mb-4 h-px bg-gray-200"></div>
                    <div class="mb-4 flex items-center justify-between">
                        <span class="truncate text-xl font-bold text-gray-800 sm:text-2xl">{{ number_format($todayRevenue, 2) }} DH</span>
                        {!! $renderChangeBadge($revenueChange) !!}
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm text-gray-600">{{ number_format($monthlyRevenue, 2) }} DH</span>
                        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">ce-mois-ci</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6 lg:flex-row">
                <div class="min-w-0 w-full rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:w-1/2">
                    <h2 class="mb-6 font-semibold text-gray-700">Répartition des revenus par ville</h2>
                    <div class="flex flex-col items-center justify-center">
                        <div class="relative h-40 w-40 sm:h-48 sm:w-48">
                            @if (count($revenusParVille ?? []) > 0)
                                <canvas id="doughnutChart" width="192" height="192"></canvas>
                                <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xs text-gray-500 sm:text-sm">Total Revenus</span>
                                    <span class="text-lg font-bold text-gray-800 sm:text-2xl">{{ number_format(collect($revenusParVille)->sum('total'), 0) }} DH</span>
                                </div>
                            @else
                                <div class="flex h-full w-full items-center justify-center rounded-full border-2 border-gray-100 text-center text-sm text-gray-400">Aucune donnée pour l'instant</div>
                            @endif
                        </div>

                        @if (count($revenusParVille ?? []) > 0)
                            <div class="mt-6 flex w-full flex-wrap items-center justify-center gap-x-6 gap-y-2">
                                @foreach ($revenusParVille as $index => $item)
                                    <div class="flex items-center">
                                        <span class="mr-2 inline-block h-3 w-3 flex-shrink-0 rounded-full" style="background-color: {{ ['#000000', '#4B5563', '#9CA3AF', '#D1D5DB', '#111827', '#6B7280'][$index % 6] }}"></span>
                                        <span class="mr-1 max-w-[120px] truncate text-sm text-gray-600">{{ $item->ville }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="min-w-0 w-full rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:w-1/2">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-semibold text-gray-700">Évolutions des commandes</h2>
                        {{--
                            Boutons de granularité fonctionnels : data-granularity
                            pilote l'appel AJAX en JS plus bas. "mois" est actif
                            par défaut car c'est ce que le contrôleur charge déjà
                            côté serveur (ordersByMonth), évitant un appel AJAX
                            inutile au chargement initial de la page.
                        --}}
                        <div id="chartGranularityButtons" class="flex gap-2">
                            <button type="button" data-granularity="day"
                                class="granularity-btn rounded-full px-4 py-1 text-sm text-gray-600 transition hover:bg-gray-100">
                                Jours
                            </button>
                            <button type="button" data-granularity="month"
                                class="granularity-btn rounded-full bg-black px-4 py-1 text-sm text-white transition">
                                Mois
                            </button>
                            <button type="button" data-granularity="year"
                                class="granularity-btn rounded-full px-4 py-1 text-sm text-gray-600 transition hover:bg-gray-100">
                                Années
                            </button>
                        </div>
                    </div>
                    <div class="relative h-56 sm:h-64">
                        <canvas id="lineChart"></canvas>
                        <div id="chartLoading" class="absolute inset-0 hidden items-center justify-center bg-white/60">
                            <span class="text-sm text-gray-500">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

    @section('toast')
        @if (session('success'))
            <div id="toast-success"
                class="flex fixed top-6 right-6 left-6 sm:left-auto z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                            fill="none" />
                        <path d="M9 12l2 2l4 -4" stroke="currentColor" stroke-width="2" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-900">
                    {{ session('success') }}
                </div>
                <button type="button"
                    class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="toast-error"
                class="flex fixed top-6 right-6 left-6 sm:left-auto z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                            fill="none" />
                        <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-900">
                    {{ session('error') }}
                </div>
                <button type="button"
                    class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div id="toast-validation"
                class="flex fixed top-6 right-6 left-6 sm:left-auto z-50 items-center p-4 max-w-xs bg-white rounded-lg border border-gray-200 shadow-lg animate-fade-in">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                            fill="none" />
                        <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-900">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" onclick="this.parentElement.remove()"
                    class="inline-flex p-1.5 -mx-1.5 -my-1.5 ml-auto w-8 h-8 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:ring-2 focus:ring-gray-100">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    @endsection

    @section('script')
        <script>
            function toggleNotificationModal() {
                const modal = document.getElementById('notificationModal');
                modal.classList.toggle('hidden');
            }

            // Bug corrigé : le toast de validation avait id="toast" (sans le
            // préfixe "toast-"), donc il n'était jamais masqué par ce
            // sélecteur alors que succès/erreur l'étaient. Renommé en
            // "toast-validation" ci-dessus pour rentrer dans le sélecteur.
            setTimeout(() => {
                document.querySelectorAll('[id^="toast-"]').forEach(el => el.style.display = 'none');
            }, 3000);

            @if (count($revenusParVille ?? []) > 0)
                const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
                const doughnutChart = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(collect($revenusParVille)->pluck('ville')) !!},
                        datasets: [{
                            data: {!! json_encode(collect($revenusParVille)->pluck('total')) !!},
                            backgroundColor: ['#000000', '#4B5563', '#9CA3AF', '#D1D5DB', '#111827', '#6B7280'],
                            borderWidth: 0,
                            cutout: '75%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
            @endif

            // Données initiales fournies par DashboardStatsService::getOrdersByMonth()
            // (12 derniers mois glissants) — évite un appel AJAX au chargement
            // puisque le contrôleur les calcule déjà côté serveur.
            const initialOrdersData = {!! json_encode($ordersByMonth ?? ['labels' => [], 'data' => []]) !!};

            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: initialOrdersData.labels,
                    datasets: [{
                        label: 'Commandes',
                        data: initialOrdersData.data,
                        borderColor: '#000000',
                        borderWidth: 1,
                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            // min/max fixes retirés : avec de vraies données,
                            // un max codé à 500 aurait tronqué le graphique
                            // dès qu'une période dépasse ce total.
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });

            // Bascule de granularité jours/mois/années. "mois" est déjà
            // rendu côté serveur (voir initialOrdersData) donc on ne
            // refait pas l'appel AJAX pour ce cas-là au premier clic.
            //
            // ATTENTION : nécessite côté backend une route + méthode encore
            // à créer, ex. :
            //   GET /admin/dashboard/orders-chart/{granularity}
            //   -> AdminController::ordersChartData($granularity)
            //   -> DashboardStatsService::getOrdersChart($granularity)
            // qui renvoie du JSON { labels: [...], data: [...] }.
            // Sans cette route, le fetch() ci-dessous renverra une 404.
            const granularityButtons = document.querySelectorAll('.granularity-btn');
            const chartLoading = document.getElementById('chartLoading');
            let currentGranularity = 'month';
            const chartCache = { month: initialOrdersData };

            function setActiveButton(granularity) {
                granularityButtons.forEach(btn => {
                    const isActive = btn.dataset.granularity === granularity;
                    btn.classList.toggle('bg-black', isActive);
                    btn.classList.toggle('text-white', isActive);
                    btn.classList.toggle('text-gray-600', !isActive);
                    btn.classList.toggle('hover:bg-gray-100', !isActive);
                });
            }

            async function loadGranularity(granularity) {
                if (granularity === currentGranularity) {
                    return;
                }

                setActiveButton(granularity);
                currentGranularity = granularity;

                if (chartCache[granularity]) {
                    lineChart.data.labels = chartCache[granularity].labels;
                    lineChart.data.datasets[0].data = chartCache[granularity].data;
                    lineChart.update();
                    return;
                }

                chartLoading.classList.remove('hidden');
                chartLoading.classList.add('flex');

                try {
                    const response = await fetch(`{{ url('/admin/dashboard/orders-chart') }}/${granularity}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error(`Réponse HTTP ${response.status}`);
                    }

                    const json = await response.json();
                    chartCache[granularity] = json;

                    lineChart.data.labels = json.labels;
                    lineChart.data.datasets[0].data = json.data;
                    lineChart.update();
                } catch (error) {
                    console.error('Erreur chargement du graphique :', error);
                    // On revient sur le bouton précédent plutôt que de
                    // laisser l'UI dans un état incohérent (bouton actif
                    // sur une granularité dont les données n'ont pas chargé).
                    setActiveButton(currentGranularity === granularity ? 'month' : currentGranularity);
                } finally {
                    chartLoading.classList.add('hidden');
                    chartLoading.classList.remove('flex');
                }
            }

            granularityButtons.forEach(btn => {
                btn.addEventListener('click', () => loadGranularity(btn.dataset.granularity));
            });
        </script>
    @endsection
