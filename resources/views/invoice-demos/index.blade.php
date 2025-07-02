@extends('layouts.app')

@section('title', 'Invoice Management')

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr Theme - Puedes elegir otro tema si prefieres -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <style>
        /* Estilos personalizados para Flatpickr */
        .flatpickr-calendar {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, 
        .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, 
        .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover, 
        .flatpickr-day.selected.prevMonthDay, 
        .flatpickr-day.startRange.prevMonthDay, 
        .flatpickr-day.endRange.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay, 
        .flatpickr-day.startRange.nextMonthDay, 
        .flatpickr-day.endRange.nextMonthDay {
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            border-color: #8b5cf6;
        }
        
        .flatpickr-day.inRange, 
        .flatpickr-day.prevMonthDay.inRange, 
        .flatpickr-day.nextMonthDay.inRange, 
        .flatpickr-day.today.inRange, 
        .flatpickr-day.prevMonthDay.today.inRange, 
        .flatpickr-day.nextMonthDay.today.inRange, 
        .flatpickr-day:hover, 
        .flatpickr-day.prevMonthDay:hover, 
        .flatpickr-day.nextMonthDay:hover, 
        .flatpickr-day:focus, 
        .flatpickr-day.prevMonthDay:focus, 
        .flatpickr-day.nextMonthDay:focus {
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.3);
        }
        
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            font-weight: 600;
            color: #4b5563;
        }
        
        .flatpickr-time input:hover, 
        .flatpickr-time .flatpickr-am-pm:hover, 
        .flatpickr-time input:focus, 
        .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(139, 92, 246, 0.1);
        }
        
        .flatpickr-months .flatpickr-prev-month:hover svg, 
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #8b5cf6;
        }
        
        /* Estilo para el input alternativo que muestra Flatpickr */
        .flatpickr-input[readonly] {
            background-color: transparent;
        }
    </style>
@endpush

@section('content')
    <style>
        /* Modern Animated Gradient Header with Particles */
        .animated-gradient-header {
            background: linear-gradient(-45deg, #8b5cf6, #6366f1, #3b82f6, #1d4ed8, #7c3aed, #f59e0b, #eab308);
            background-size: 500% 500%;
            animation: gradientShift 10s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating White Particles */
        .animated-gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: floatPattern 20s linear infinite;
            pointer-events: none;
        }

        .animated-gradient-header::after {
            content: '';
            position: absolute;
            top: 20%;
            right: 10%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 50%;
            animation: floatBubble 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floatPattern {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(-50px, -50px) rotate(360deg);
            }
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-20px) scale(1.1);
                opacity: 0.9;
            }
        }

        /* Modern Glass Container */
        .glass-container {
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.1) 0%,
                    rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow:
                0 8px 32px rgba(139, 92, 246, 0.1),
                0 4px 16px rgba(99, 102, 241, 0.05);
        }

        /* Modern Glass Input */
        .glass-input {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgb(243, 244, 246);
            transition: all 0.3s ease;
        }

        .glass-input::placeholder {
            color: rgb(209, 213, 219);
        }

        .glass-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(139, 92, 246, 0.4);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            color: rgb(255, 255, 255);
        }

        .glass-input:hover {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Modern Toggle */
        .glass-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .glass-toggle.active {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
        }

        .glass-toggle-thumb {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        /* Text Styling */
        .glass-text {
            color: rgb(243, 244, 246);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .glass-label {
            color: rgb(229, 231, 235);
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        /* Enhanced Modern Button with Circle Effect */
        .modern-button {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow:
                0 8px 32px rgba(139, 92, 246, 0.2),
                0 4px 16px rgba(99, 102, 241, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .modern-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.2), rgba(139, 92, 246, 0.1));
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.6s ease;
            z-index: 0;
        }

        .modern-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .modern-button:hover {
            transform: translateY(-2px);
            box-shadow:
                0 12px 40px rgba(139, 92, 246, 0.3),
                0 6px 20px rgba(245, 158, 11, 0.2),
                0 4px 12px rgba(99, 102, 241, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.98);
        }

        .modern-button>* {
            position: relative;
            z-index: 1;
        }

        /* Option Styling for Dark Theme */
        .glass-input option {
            background: rgb(31, 41, 55);
            color: rgb(243, 244, 246);
        }
    </style>
    <div class="min-h-screen py-8" x-data="invoiceDemoData()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-purple-100 mb-8 overflow-hidden">
                <div class="animated-gradient-header px-8 py-6 relative">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);">
                                Invoice Management</h1>
                            <p class="text-purple-100 opacity-90 glass-text">Manage and track invoices for clients</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button @click="openCreateModal()"
                                class="modern-button inline-flex items-center px-6 py-3 text-purple-600 font-semibold rounded-xl relative z-10">
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
            <div class="glass-container rounded-2xl mb-8 p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Search Input -->
                    <div class="relative group lg:col-span-2">
                        <label class="glass-label text-xs mb-2 block">Search</label>
                        <input type="text" x-model="search" @input="searchInvoices()"
                            placeholder="Search by invoice number, client name..."
                            class="glass-input w-full pl-12 pr-4 py-4 rounded-xl">
                        <svg class="absolute left-4 top-10 h-5 w-5 text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <label class="glass-label text-xs mb-2 block">Status</label>
                        <select x-model="statusFilter" @change="filterByStatus()"
                            class="glass-input w-full px-4 py-4 rounded-xl appearance-none cursor-pointer">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <svg class="absolute right-3 top-10 h-5 w-5 text-purple-400 pointer-events-none" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <!-- Show Deleted Toggle -->
                    <div class="flex flex-col justify-center items-center space-y-2">
                        <label class="glass-label text-xs text-center">Show Deleted</label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" x-model="showDeleted" @change="loadInvoices()" class="sr-only">
                            <div class="relative">
                                <div class="glass-toggle w-12 h-6 rounded-full transition-all duration-300"
                                    :class="showDeleted ? 'active' : ''">
                                </div>
                                <div class="glass-toggle-thumb absolute w-5 h-5 rounded-full top-0.5 left-0.5 transition-all duration-300"
                                    :class="showDeleted ? 'transform translate-x-6' : ''"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date Range Filters - Second Row -->
                <div class="grid grid-cols-1 gap-6 mt-6">
                    <!-- Flatpickr Date Range Filter -->
                    <div class="relative">
                        <div class="flex justify-between items-center mb-2">
                            <label class="glass-label text-xs block">Rango de Fechas</label>
                            <button @click="clearDateFilter()" 
                                class="text-xs text-purple-500 hover:text-purple-700 transition-colors duration-200 flex items-center">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Limpiar
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" id="date-range-picker" placeholder="Seleccionar rango de fechas..."
                                class="glass-input w-full px-4 py-4 rounded-xl pl-12">
                            <svg class="absolute left-4 top-4 h-5 w-5 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-900 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">
                <!-- Loading State -->
                <div x-show="loading" class="flex items-center justify-center py-12 bg-gray-900">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-400"></div>
                    <span class="ml-3 text-gray-300">Loading invoices...</span>
                </div>

                <!-- Table -->
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Nro
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer hover:bg-gray-700 transition-colors duration-200"
                                    @click="sortInvoices('invoice_number')"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Invoice Number
                                    <svg x-show="sortBy === 'invoice_number'" class="inline w-4 h-4 ml-1 text-gray-300"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer hover:bg-gray-700 transition-colors duration-200"
                                    @click="sortInvoices('bill_to_name')"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Bill To
                                    <svg x-show="sortBy === 'bill_to_name'" class="inline w-4 h-4 ml-1 text-gray-300"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer hover:bg-gray-700 transition-colors duration-200"
                                    @click="sortInvoices('balance_due')"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Balance Due
                                    <svg x-show="sortBy === 'balance_due'" class="inline w-4 h-4 ml-1 text-gray-300"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer hover:bg-gray-700 transition-colors duration-200"
                                    @click="sortInvoices('invoice_date')"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Invoice Date
                                    <svg x-show="sortBy === 'invoice_date'" class="inline w-4 h-4 ml-1 text-gray-300"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer hover:bg-gray-700 transition-colors duration-200"
                                    @click="sortInvoices('date_of_loss')"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Date of Loss
                                    <svg x-show="sortBy === 'date_of_loss'" class="inline w-4 h-4 ml-1 text-gray-300"
                                        :class="sortOrder === 'asc' ? 'transform rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider"
                                    style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <template x-for="(invoice, index) in invoices" :key="invoice.uuid">
                                <tr class="transition-colors duration-200 hover:bg-slate-700"
                                    :class="[
                                        invoice.deleted_at ? 'bg-red-900 bg-opacity-30' : '',
                                        index % 2 === 0 ? 'bg-gray-800 bg-opacity-30' : 'bg-transparent'
                                    ]">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-100"
                                            x-text="((currentPage - 1) * perPage) + index + 1">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="text-sm font-medium text-gray-100"
                                                x-text="invoice.invoice_number">
                                            </div>
                                            <div x-show="invoice.pdf_url" class="ml-2 text-green-400"
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
                                        <div class="text-sm font-medium text-gray-100" x-text="invoice.bill_to_name">
                                        </div>
                                        <div class="text-sm text-gray-400"
                                            x-text="invoice.bill_to_phone_formatted || invoice.bill_to_phone"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-100"
                                            x-text="formatCurrency(invoice.balance_due)"></div>
                                        <div class="text-xs text-gray-400">Subtotal: <span
                                                x-text="formatCurrency(invoice.subtotal)"></span></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full"
                                            :class="getStatusBadgeClass(invoice.status)"
                                            x-text="invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-100"
                                        x-text="formatDate(invoice.invoice_date)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-100"
                                        x-text="formatDate(invoice.date_of_loss)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <template x-if="!invoice.deleted_at">
                                                <div class="flex space-x-2">
                                                    <!-- PDF Actions -->
                                                    <div class="relative" x-data="{ showPdfMenu: false }">
                                                        <button @click="showPdfMenu = !showPdfMenu"
                                                            class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                                            title="PDF Actions">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <div x-show="showPdfMenu" @click.away="showPdfMenu = false"
                                                            class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-10 border border-gray-600">
                                                            <div class="py-1">
                                                                <a :href="window.invoiceDemoManager.getPdfViewUrl(invoice.uuid)"
                                                                    target="_blank"
                                                                    class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white">
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
                                                                    class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white">
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
                                                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white"
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
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                                        title="Edit Invoice">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="deleteInvoice(invoice)"
                                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                                        title="Delete Invoice">
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
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                                    title="Restore Invoice">
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
                    <div x-show="invoices.length === 0" class="text-center py-12 bg-gray-900">
                        <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-200">No invoices found</h3>
                        <p class="mt-1 text-sm text-gray-400">Get started by creating a new invoice.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="bg-gray-800 px-6 py-4 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-300">
                            Showing <span x-text="((currentPage - 1) * perPage) + 1"></span> to
                            <span x-text="Math.min(currentPage * perPage, total)"></span> of
                            <span x-text="total"></span> results
                        </div>
                        <div class="flex space-x-2">
                            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                                class="px-3 py-2 text-sm font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                Previous
                            </button>
                            <template
                                x-for="page in Array.from({length: Math.min(5, totalPages)}, (_, i) => Math.max(1, currentPage - 2) + i).filter(p => p <= totalPages)"
                                :key="page">
                                <button @click="goToPage(page)"
                                    :class="page === currentPage ? 'bg-purple-600 text-white border-purple-600' :
                                        'bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600'"
                                    class="px-3 py-2 text-sm font-medium border rounded-lg transition-colors duration-200"
                                    x-text="page"></button>
                            </template>
                            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                                class="px-3 py-2 text-sm font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
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
        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- Flatpickr Español -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
        <script src="{{ asset('js/invoice-demos.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('invoiceDemoData', () => ({
                    // Mantener el resto de la definición original
                    init() {
                        // Inicializar Flatpickr después de que Alpine.js haya cargado
                        this.initFlatpickr();
                        this.loadFormData();
                        this.loadInvoices();
                    },
                    
                    // Inicializar Flatpickr
                    initFlatpickr() {
                        const self = this;
                        
                        // Crear el contenedor para los rangos predefinidos
                        const rangeButtonsContainer = document.createElement('div');
                        rangeButtonsContainer.className = 'flatpickr-ranges flex flex-wrap gap-2 p-3 border-t border-gray-200';
                        
                        // Función para crear un botón de rango predefinido
                        const createRangeButton = (label, days) => {
                            const btn = document.createElement('button');
                            btn.className = 'px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors';
                            btn.textContent = label;
                            btn.addEventListener('click', (e) => {
                                e.preventDefault();
                                const today = new Date();
                                let startDate = new Date();
                                
                                if (days === 0) { // Hoy
                                    self.flatpickrInstance.setDate([today, today]);
                                } else if (days === 'month') { // Este mes
                                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                                    const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                                    self.flatpickrInstance.setDate([startDate, endDate]);
                                } else if (days === 'year') { // Este año
                                    startDate = new Date(today.getFullYear(), 0, 1);
                                    const endDate = new Date(today.getFullYear(), 11, 31);
                                    self.flatpickrInstance.setDate([startDate, endDate]);
                                } else { // Días específicos hacia atrás
                                    startDate.setDate(today.getDate() - days);
                                    self.flatpickrInstance.setDate([startDate, today]);
                                }
                            });
                            return btn;
                        };
                        
                        // Añadir botones de rangos predefinidos
                        rangeButtonsContainer.appendChild(createRangeButton('Hoy', 0));
                        rangeButtonsContainer.appendChild(createRangeButton('Últimos 7 días', 7));
                        rangeButtonsContainer.appendChild(createRangeButton('Últimos 30 días', 30));
                        rangeButtonsContainer.appendChild(createRangeButton('Este mes', 'month'));
                        rangeButtonsContainer.appendChild(createRangeButton('Este año', 'year'));
                        
                        // Inicializar Flatpickr
                        this.flatpickrInstance = flatpickr('#date-range-picker', {
                            mode: 'range',
                            dateFormat: 'Y-m-d',
                            locale: 'es',
                            altInput: true,
                            altFormat: 'j F, Y',
                            showMonths: 2,
                            static: true,
                            disableMobile: true,
                            animate: true,
                            position: 'below',
                            onChange: function(selectedDates, dateStr, instance) {
                                if (selectedDates.length === 2) {
                                    self.startDate = self.formatFlatpickrDate(selectedDates[0]);
                                    self.endDate = self.formatFlatpickrDate(selectedDates[1]);
                                    self.filterByDateRange();
                                } else if (selectedDates.length === 0) {
                                    self.startDate = '';
                                    self.endDate = '';
                                    self.filterByDateRange();
                                }
                            },
                            onReady: function(selectedDates, dateStr, instance) {
                                // Añadir los rangos predefinidos al calendario
                                const calendar = instance.calendarContainer;
                                calendar.appendChild(rangeButtonsContainer);
                                
                                // Si ya hay fechas seleccionadas, establecerlas en el picker
                                if (self.startDate && self.endDate) {
                                    this.setDate([self.startDate, self.endDate]);
                                }
                            }
                        });
                    },
                    
                    // Formatear fecha para Flatpickr
                    formatFlatpickrDate(date) {
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    },
                    
                    // Limpiar filtro de fechas
                    clearDateFilter() {
                        this.startDate = '';
                        this.endDate = '';
                        this.flatpickrInstance.clear();
                        this.filterByDateRange();
                    }
                }))
            });
        </script>
    @endpush
@endsection
