@extends('layouts.app')

@section('title', 'Invoice Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8" x-data="invoiceDemoData()"
        x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-purple-100 mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 px-8 py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Invoice Management</h1>
                            <p class="text-purple-100">Manage and track invoices for clients</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button @click="openCreateModal()"
                                class="inline-flex items-center px-6 py-3 bg-white text-purple-600 font-semibold rounded-xl shadow-lg hover:bg-purple-50 hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" x-model="search" @input="searchInvoices()"
                            placeholder="Search by invoice number, client name..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Status Filter -->
                    <select x-model="statusFilter" @change="filterByStatus()"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        <option value="">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <!-- Date Range Filter -->
                    <select x-model="dateFilter" @change="filterByStatus()"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>

                    <!-- Show Deleted Toggle -->
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" x-model="showDeleted" @change="loadInvoices()" class="sr-only">
                            <div class="relative">
                                <div class="w-10 h-6 bg-gray-200 rounded-full shadow-inner transition-colors duration-200"
                                    :class="showDeleted ? 'bg-purple-500' : 'bg-gray-200'"></div>
                                <div class="absolute w-4 h-4 bg-white rounded-full shadow top-1 left-1 transition-transform duration-200"
                                    :class="showDeleted ? 'transform translate-x-4' : ''"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Show Deleted</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Loading State -->
                <div x-show="loading" class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                    <span class="ml-3 text-gray-600">Loading invoices...</span>
                </div>

                <!-- Table -->
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-purple-50 to-indigo-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nro
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-purple-100 transition-colors duration-200"
                                    @click="sortInvoices('invoice_number')">
                                    Invoice Number
                                    <svg x-show="sortBy === 'invoice_number'" class="inline w-4 h-4 ml-1"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-purple-100 transition-colors duration-200"
                                    @click="sortInvoices('bill_to_name')">
                                    Bill To
                                    <svg x-show="sortBy === 'bill_to_name'" class="inline w-4 h-4 ml-1"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-purple-100 transition-colors duration-200"
                                    @click="sortInvoices('balance_due')">
                                    Balance Due
                                    <svg x-show="sortBy === 'balance_due'" class="inline w-4 h-4 ml-1"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-purple-100 transition-colors duration-200"
                                    @click="sortInvoices('invoice_date')">
                                    Invoice Date
                                    <svg x-show="sortBy === 'invoice_date'" class="inline w-4 h-4 ml-1"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-purple-100 transition-colors duration-200"
                                    @click="sortInvoices('date_of_loss')">
                                    Date of Loss
                                    <svg x-show="sortBy === 'date_of_loss'" class="inline w-4 h-4 ml-1"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(invoice, index) in invoices" :key="invoice.uuid">
                                <tr class="hover:bg-purple-25 transition-colors duration-200"
                                    :class="invoice.deleted_at ? 'bg-red-50 opacity-75' : ''">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900"
                                            x-text="((currentPage - 1) * perPage) + index + 1">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="text-sm font-medium text-gray-900"
                                                x-text="invoice.invoice_number">
                                            </div>
                                            <div x-show="invoice.pdf_url" class="ml-2 text-green-500"
                                                title="PDF Available">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900" x-text="invoice.bill_to_name">
                                        </div>
                                        <div class="text-sm text-gray-500"
                                            x-text="invoice.bill_to_phone_formatted || invoice.bill_to_phone"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900"
                                            x-text="formatCurrency(invoice.balance_due)"></div>
                                        <div class="text-xs text-gray-500">Subtotal: <span
                                                x-text="formatCurrency(invoice.subtotal)"></span></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full"
                                            :class="getStatusBadgeClass(invoice.status)"
                                            x-text="invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                                        x-text="formatDate(invoice.invoice_date)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                                        x-text="formatDate(invoice.date_of_loss)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <template x-if="!invoice.deleted_at">
                                                <div class="flex space-x-2">
                                                    <!-- PDF Actions -->
                                                    <div class="relative" x-data="{ showPdfMenu: false }">
                                                        <button @click="showPdfMenu = !showPdfMenu"
                                                            class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <div x-show="showPdfMenu" @click.away="showPdfMenu = false"
                                                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                            <div class="py-1">
                                                                <a :href="window.invoiceDemoManager.getPdfViewUrl(invoice.uuid)"
                                                                    target="_blank"
                                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-900">
                                                                    <svg class="w-4 h-4 mr-2" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                        </path>
                                                                    </svg>
                                                                    View PDF
                                                                </a>
                                                                <a :href="window.invoiceDemoManager.getPdfDownloadUrl(invoice
                                                                    .uuid)"
                                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-900">
                                                                    <svg class="w-4 h-4 mr-2" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                        </path>
                                                                    </svg>
                                                                    Download PDF
                                                                </a>
                                                                <button
                                                                    @click="generatePdf(invoice.uuid); showPdfMenu = false"
                                                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-900"
                                                                    :class="{ 'opacity-50 cursor-not-allowed': pdfGenerating }"
                                                                    :disabled="pdfGenerating">
                                                                    <svg class="w-4 h-4 mr-2" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                        </path>
                                                                    </svg>
                                                                    <span
                                                                        x-text="pdfGenerating ? 'Generating...' : 'Regenerate PDF'"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button @click="openEditModal(invoice)"
                                                        class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="deleteInvoice(invoice)"
                                                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <template x-if="invoice.deleted_at">
                                                <button @click="restoreInvoice(invoice)"
                                                    class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div x-show="invoices.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new invoice.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span x-text="((currentPage - 1) * perPage) + 1"></span> to
                            <span x-text="Math.min(currentPage * perPage, total)"></span> of
                            <span x-text="total"></span> results
                        </div>
                        <div class="flex space-x-2">
                            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                Previous
                            </button>
                            <template
                                x-for="page in Array.from({length: Math.min(5, totalPages)}, (_, i) => Math.max(1, currentPage - 2) + i).filter(p => p <= totalPages)"
                                :key="page">
                                <button @click="goToPage(page)"
                                    :class="page === currentPage ? 'bg-purple-600 text-white' :
                                        'bg-white text-gray-500 hover:bg-gray-50'"
                                    class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors duration-200"
                                    x-text="page"></button>
                            </template>
                            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Modal -->
        @include('invoice-demos.partials.invoice-modal')

        <!-- Success/Error Messages -->
        <div x-show="message" x-transition class="fixed top-4 right-4 z-50">
            <div class="bg-white border-l-4 p-4 rounded-lg shadow-lg"
                :class="messageType === 'success' ? 'border-green-500' : 'border-red-500'">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg x-show="messageType === 'success'" class="h-5 w-5 text-green-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg x-show="messageType === 'error'" class="h-5 w-5 text-red-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" :class="messageType === 'success' ? 'text-green-700' : 'text-red-700'"
                            x-text="message"></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="message = ''" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/invoice-demos.js') }}"></script>
    @endpush
@endsection
