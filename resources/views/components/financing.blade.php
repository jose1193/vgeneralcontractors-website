<!-- Parallax Financing Section -->
<section class="relative py-24 bg-fixed bg-center bg-cover fade-in-section"
    style="background-image: url('{{ asset('assets/img/bg-financial-1024x690.webp') }}');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Content -->
    <div class="relative container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-4xl font-bold text-white mb-6">{{ __('financing') }}</h2>
            <p class="text-xl text-gray-100 mb-2">{{ __('finance_roofing_project') }}</p>
            <p class="text-xl text-gray-100 mb-8">{{ __('getting_affordable_monthly_payment') }}</p>

        </div>
    </div>
</section>
@php
    use App\Helpers\PhoneHelper;
@endphp
<!-- Call Us Now Section -->
<section class="bg-yellow-500 py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8">
            <h3 class="text-2xl sm:text-3xl font-bold text-black">{{ __('call_us') }}</h3>
            <a href="tel:+13466920757"
                class="inline-flex items-center bg-black text-white text-xl sm:text-2xl font-bold px-6 sm:px-8 py-3 sm:py-4 rounded-lg hover:bg-gray-900 transition-colors duration-300">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                {{ PhoneHelper::format($companyData->phone) }}
            </a>
        </div>
    </div>
</section>

<!-- Financing Section -->
<section class="py-16 bg-white" x-data="{ openApplication: false }">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('flexible_financing') }}</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">{{ __('finance_roofing_project') }}</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- EnerBank Financing -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/img/enerbank-logo.webp') }}" alt="EnerBank USA financing options"
                        class="h-16 mx-auto mb-4" width="200" height="64" loading="lazy">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">EnerBank USA</h3>
                    <div class="text-yellow-500 text-xl font-semibold">{{ __('flexible_financing') }}</div>
                </div>
                <ul class="space-y-3 text-gray-600 mb-6">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Fast approval process</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Competitive interest rates</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Multiple payment options</span>
                    </li>
                </ul>
                <button @click="openApplication = true"
                    class="w-full bg-yellow-500 text-white py-3 px-6 rounded-lg hover:bg-yellow-600 transition-colors duration-300 font-semibold">
                    Apply Now
                </button>
            </div>

            <!-- Synchrony Bank -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/img/synchrony-logo.webp') }}" alt="Synchrony Bank financing solutions"
                        class="h-16 mx-auto mb-4" width="200" height="64" loading="lazy">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Synchrony Bank</h3>
                    <div class="text-yellow-500 text-xl font-semibold">Special Promotions</div>
                </div>
                <ul class="space-y-3 text-gray-600 mb-6">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>0% APR for 12 months*</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>No prepayment penalties</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Easy online management</span>
                    </li>
                </ul>
                <button @click="openApplication = true"
                    class="w-full bg-yellow-500 text-white py-3 px-6 rounded-lg hover:bg-yellow-600 transition-colors duration-300 font-semibold">
                    Learn More
                </button>
            </div>

            <!-- Wells Fargo -->
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/img/wells-fargo-logo.webp') }}" alt="Wells Fargo home improvement loans"
                        class="h-16 mx-auto mb-4" width="200" height="64" loading="lazy">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Wells Fargo</h3>
                    <div class="text-yellow-500 text-xl font-semibold">Home Improvement</div>
                </div>
                <ul class="space-y-3 text-gray-600 mb-6">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Large project funding</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Fixed interest rates</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        <span>Flexible terms</span>
                    </li>
                </ul>
                <button @click="openApplication = true"
                    class="w-full bg-yellow-500 text-white py-3 px-6 rounded-lg hover:bg-yellow-600 transition-colors duration-300 font-semibold">
                    Get Started
                </button>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-12">
            <p class="text-lg text-gray-600 mb-6">{{ __('ready_to_get_started') }}</p>
            <a href="tel:{{ $companyData->phone }}"
                class="inline-flex items-center bg-yellow-500 text-white px-8 py-4 rounded-lg hover:bg-yellow-600 transition-all duration-300 text-lg font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                {{ __('call_us') }} {{ $companyData->phone }}
            </a>
        </div>
    </div>

    <!-- Financing Application Modal -->
    <div x-show="openApplication" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="openApplication = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="openApplication = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('financing') }} Application
                            </h3>
                            <div class="space-y-4">
                                <p class="text-gray-600">{{ __('contact_support_schedule') }}</p>
                                <div class="flex flex-col space-y-2">
                                    <a href="tel:{{ $companyData->phone }}"
                                        class="inline-flex items-center justify-center bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $companyData->phone }}
                                    </a>
                                    <a href="mailto:{{ $companyData->email }}"
                                        class="inline-flex items-center justify-center bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $companyData->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="openApplication = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
