@props([
    'title',
    'subtitle',
    'entityName',
    'entityNamePlural',
    'searchPlaceholder',
    'showDeletedLabel',
    'addNewLabel',
    'managerName',
    'tableColumns' => [],
    'tableId' => 'crud-table',
    'createButtonId' => 'createBtn',
    'searchId' => 'searchInput',
    'showDeletedId' => 'showDeleted',
    'perPageId' => 'perPage',
    'paginationId' => 'pagination',
    'alertId' => 'alertContainer',
])

{{-- Include glassmorphic styles --}}
@push('styles')
    <style>
        /* Enhanced dark background with subtle patterns */
        .glassmorphic-main-container {
            background: #0a0a0a;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background overlay */
        .glassmorphic-main-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            z-index: 1;
            pointer-events: none;
        }

        /* Content wrapper */
        .glassmorphic-content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Enhanced header styling */
        .glassmorphic-header {
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.95), rgba(25, 25, 25, 0.90));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .glassmorphic-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(139, 92, 246, 0.05),
                    transparent);
            animation: header-shimmer 8s ease-in-out infinite;
        }

        .glassmorphic-header-content {
            position: relative;
            z-index: 2;
        }

        .glassmorphic-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff, #e5e7eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .glassmorphic-subtitle {
            font-size: clamp(0.875rem, 2.5vw, 1rem);
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
            line-height: 1.6;
            max-width: 600px;
        }

        /* Enhanced container styling */
        .glassmorphic-main-content {
            background: rgba(15, 15, 15, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
            margin: 0 auto;
            max-width: 1400px;
            position: relative;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.4),
                0 8px 16px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .glassmorphic-section {
            margin-bottom: 2rem;
        }

        .glassmorphic-section:last-child {
            margin-bottom: 0;
        }

        /* Enhanced alert styling */
        .glassmorphic-alert-container {
            position: relative;
            z-index: 10;
        }

        /* Enhanced pagination styling */
        .glassmorphic-pagination {
            background: rgba(25, 25, 25, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .glassmorphic-main-content {
                padding: 1.5rem;
                border-radius: 20px;
            }

            .glassmorphic-header {
                padding: 1.5rem 0;
            }
        }

        @media (max-width: 768px) {
            .glassmorphic-main-content {
                padding: 1rem;
                border-radius: 16px;
                margin: 0 0.5rem;
            }

            .glassmorphic-header {
                padding: 1rem 0;
            }

            .glassmorphic-title {
                text-align: center;
            }

            .glassmorphic-subtitle {
                text-align: center;
            }
        }

        @media (max-width: 640px) {
            .glassmorphic-main-content {
                padding: 0.75rem;
                border-radius: 12px;
            }

            .glassmorphic-section {
                margin-bottom: 1.5rem;
            }
        }

        /* Animation keyframes */
        @keyframes header-shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced focus styles */
        *:focus {
            outline: 2px solid rgba(139, 92, 246, 0.6);
            outline-offset: 2px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.6), rgba(6, 182, 212, 0.6));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.8), rgba(6, 182, 212, 0.8));
        }
    </style>
@endpush

<x-app-layout>
    <div class="glassmorphic-main-container">
        <div class="glassmorphic-content-wrapper">
            {{-- Enhanced Header Section --}}
            <div class="glassmorphic-header">
                <div class="glassmorphic-header-content">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center lg:text-left">
                            <h1 class="glassmorphic-title">
                                {{ $title }}
                            </h1>
                            <p class="glassmorphic-subtitle">
                                {{ $subtitle }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Container --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                <div class="glassmorphic-main-content">
                    {{-- Alert Container --}}
                    <div class="glassmorphic-section glassmorphic-alert-container">
                        <div id="{{ $alertId }}"></div>
                        @if (session()->has('message'))
                            <x-crud.alert type="success" :message="session('message')" />
                        @endif
                        @if (session()->has('error'))
                            <x-crud.alert type="error" :message="session('error')" />
                        @endif
                    </div>

                    {{-- Filter and Action Bar --}}
                    <div class="glassmorphic-section">
                        <x-crud.filter-bar :search-id="$searchId" :search-placeholder="$searchPlaceholder" :show-deleted-id="$showDeletedId" :show-deleted-label="$showDeletedLabel"
                            :per-page-id="$perPageId" :create-button-id="$createButtonId" :add-new-label="$addNewLabel" :manager-name="$managerName" />
                    </div>

                    {{-- Enhanced Glassmorphic Table --}}
                    <div class="glassmorphic-section">
                        <x-crud.glassmorphic-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName"
                            :responsive="true" :sortable="true" :dark-mode="true" />
                    </div>

                    {{-- Enhanced Pagination --}}
                    <div class="glassmorphic-section">
                        <div id="{{ $paginationId }}" class="glassmorphic-pagination">
                            <div class="flex justify-between items-center text-white/80">
                                <span class="text-sm">Loading pagination...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slot for additional content --}}
            {{ $slot }}
        </div>
    </div>
</x-app-layout>

{{-- Additional JavaScript for enhanced interactions --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize enhanced UI interactions
            initializeEnhancedUI();

            // Initialize smooth scrolling
            initializeSmoothScrolling();

            // Initialize responsive behavior
            initializeResponsiveBehavior();
        });

        function initializeEnhancedUI() {
            // Add loading states to interactive elements
            const interactiveElements = document.querySelectorAll('button, .clickable');
            interactiveElements.forEach(element => {
                element.addEventListener('click', function() {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 1000);
                });
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.glassmorphic-main-content');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 24px 48px rgba(0, 0, 0, 0.5), 0 12px 24px rgba(0, 0, 0, 0.3)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.4), 0 8px 16px rgba(0, 0, 0, 0.2)';
                });
            });
        }

        function initializeSmoothScrolling() {
            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        function initializeResponsiveBehavior() {
            // Handle responsive layout changes
            function handleResize() {
                const container = document.querySelector('.glassmorphic-main-content');
                if (!container) return;

                const width = window.innerWidth;

                if (width < 768) {
                    container.classList.add('mobile-layout');
                } else {
                    container.classList.remove('mobile-layout');
                }
            }

            // Initial check
            handleResize();

            // Listen for resize events
            window.addEventListener('resize', debounce(handleResize, 250));
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
@endpush
