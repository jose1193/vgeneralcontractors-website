<x-app-layout>
    <div x-data="{ dark: $persist(false) }" :class="{ 'dark': dark }" class="min-h-screen transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Bienvenido, aquí tienes un resumen de tu actividad.
                    </p>
                </div>
                <button @click="dark = !dark"
                    class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg x-show="!dark" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 4.95l-.71-.71M4.05 4.93l-.71-.71" />
                    </svg>
                    <svg x-show="dark" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                    </svg>
                    <span x-text="dark ? 'Light' : 'Dark'"></span>
                </button>
            </div>

            <!-- Cards de acceso rápido -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 mb-10">
                <a href="{{ route('appointments.index') }}" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg></span>
                    <span class="quick-card-label">Citas</span>
                </a>
                <a href="" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10l4.553-2.276A2 2 0 0021 6.382V5a2 2 0 00-2-2H5a2 2 0 00-2 2v1.382a2 2 0 00 1.447 1.342L9 10m6 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4m10 0l-4 2-4-2" />
                        </svg></span>
                    <span class="quick-card-label">Llamadas</span>
                </a>
                <a href="{{ route('users') }}" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg></span>
                    <span class="quick-card-label">Usuarios</span>
                </a>
                <a href="{{ route('admin.posts') }}" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z" />
                        </svg></span>
                    <span class="quick-card-label">Blog</span>
                </a>
                <a href="{{ route('portfolios') }}" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg></span>
                    <span class="quick-card-label">Portafolio</span>
                </a>
                <a href="{{ route('company-data') }}" class="quick-card">
                    <span class="quick-card-icon bg-yellow-100 text-yellow-600"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0-4v-4" />
                        </svg></span>
                    <span class="quick-card-label">Administración</span>
                </a>
            </div>

            <!-- Widgets principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Widget: Últimas grabaciones de llamadas (dummy) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col">
                    <span class="text-gray-500 dark:text-gray-400 mb-2 font-semibold">Últimas grabaciones de
                        llamadas</span>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10l4.553-2.276A2 2 0 0021 6.382V5a2 2 0 00-2-2H5a2 2 0 00-2 2v1.382a2 2 0 00 1.447 1.342L9 10m6 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4m10 0l-4 2-4-2" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">+1 555-123-4567</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Duración: 02:15 min</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Escuchar</a>
                        </li>
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10l4.553-2.276A2 2 0 0021 6.382V5a2 2 0 00-2-2H5a2 2 0 00-2 2v1.382a2 2 0 00 1.447 1.342L9 10m6 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4m10 0l-4 2-4-2" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">+1 555-987-6543</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Duración: 01:42 min</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Escuchar</a>
                        </li>
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10l4.553-2.276A2 2 0 0021 6.382V5a2 2 0 00-2-2H5a2 2 0 00-2 2v1.382a2 2 0 00 1.447 1.342L9 10m6 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4m10 0l-4 2-4-2" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">+1 555-222-3333</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Duración: 03:05 min</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Escuchar</a>
                        </li>
                    </ul>
                </div>
                <!-- Widget: Appointments confirmados (dummy) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col">
                    <span class="text-gray-500 dark:text-gray-400 mb-2 font-semibold">Citas confirmadas</span>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">Juan Pérez</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Mañana, 10:00 AM</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Ver</a>
                        </li>
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">Ana Gómez</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Hoy, 3:00 PM</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Ver</a>
                        </li>
                        <li class="py-3 flex items-center gap-3">
                            <span
                                class="inline-block w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg></span>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">Carlos Ruiz</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Viernes, 11:30 AM</div>
                            </div>
                            <a href="#" class="text-yellow-600 hover:underline text-sm">Ver</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Widgets de resumen y gráfica -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Widget: Gráfica de actividad -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 min-h-[260px] flex flex-col items-center justify-center">
                    <span class="text-gray-500 dark:text-gray-400 mb-2 font-semibold">Actividad mensual</span>
                    <canvas id="activityChart" width="400" height="180"></canvas>
                </div>
                <!-- Widget: Últimas actividades -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 min-h-[260px] flex flex-col">
                    <span class="text-gray-500 dark:text-gray-400 mb-2 font-semibold">Últimas actividades</span>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-2 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-gray-700 dark:text-gray-200">Nuevo usuario registrado</span>
                            <span class="ml-auto text-xs text-gray-400">hace 2 min</span>
                        </li>
                        <li class="py-2 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-gray-700 dark:text-gray-200">Cita agendada para mañana</span>
                            <span class="ml-auto text-xs text-gray-400">hace 1 hora</span>
                        </li>
                        <li class="py-2 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                            <span class="text-gray-700 dark:text-gray-200">Post publicado en el blog</span>
                            <span class="ml-auto text-xs text-gray-400">ayer</span>
                        </li>
                        <li class="py-2 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                            <span class="text-gray-700 dark:text-gray-200">Proyecto agregado al portafolio</span>
                            <span class="ml-auto text-xs text-gray-400">hace 2 días</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Widgets de KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Widget: Total Usuarios -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-start">
                    <span class="text-gray-500 dark:text-gray-400 text-sm mb-2">Usuarios</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">123</span>
                    <span class="text-xs text-green-500 mt-1">+5 este mes</span>
                </div>
                <!-- Widget: Citas próximas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-start">
                    <span class="text-gray-500 dark:text-gray-400 text-sm mb-2">Citas próximas</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">8</span>
                    <span class="text-xs text-blue-500 mt-1">2 hoy</span>
                </div>
                <!-- Widget: Posts publicados -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-start">
                    <span class="text-gray-500 dark:text-gray-400 text-sm mb-2">Posts publicados</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">45</span>
                    <span class="text-xs text-purple-500 mt-1">+3 esta semana</span>
                </div>
                <!-- Widget: Portafolio -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-start">
                    <span class="text-gray-500 dark:text-gray-400 text-sm mb-2">Proyectos en portafolio</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">12</span>
                    <span class="text-xs text-yellow-500 mt-1">1 nuevo</span>
                </div>
            </div>
        </div>
        <style>
            .quick-card {
                @apply flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow p-4 hover:shadow-lg transition cursor-pointer border border-transparent hover:border-yellow-400;
                text-decoration: none;
            }

            .quick-card-icon {
                @apply mb-2 p-2 rounded-full text-xl;
            }

            .quick-card-label {
                @apply text-sm font-semibold text-gray-700 dark:text-gray-200;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('activityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                            'Dic'
                        ],
                        datasets: [{
                            label: 'Actividades',
                            data: [12, 19, 8, 15, 22, 13, 17, 14, 20, 18, 10, 16],
                            backgroundColor: 'rgba(234, 179, 8, 0.8)', // yellow-500
                            borderRadius: 8,
                            maxBarThickness: 32,
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#a3a3a3'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    color: '#a3a3a3'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>
</x-app-layout>
