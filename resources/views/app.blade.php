<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Insurance — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors; }
        .sidebar-link.active { @apply bg-blue-700 text-white; }
        .sidebar-link:not(.active) { @apply text-blue-100 hover:bg-blue-700/50; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900" x-data="app()" x-init="init()">

<!-- Auth guard -->
<template x-if="!token">
    <div class="min-h-screen flex items-center justify-center">
        <p class="text-gray-500">Redirecting to login…</p>
    </div>
</template>

<template x-if="token">
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 flex flex-col flex-shrink-0">
        <!-- Brand -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-blue-800">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Travel Insurance</p>
                <p class="text-blue-300 text-xs">Admin Portal</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            <!-- MAIN -->
            <p class="px-3 pt-1 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Main</p>

            <button @click="navigate('dashboard')" :class="page==='dashboard' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </button>

            <!-- CUSTOMERS -->
            <p class="px-3 pt-4 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Customers</p>

            <button @click="navigate('customers')" :class="page==='customers' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Customers
            </button>

            <button @click="navigate('family-members')" :class="page==='family-members' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Family Members
            </button>

            <button @click="navigate('travelers')" :class="page==='travelers' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Travelers
            </button>

            <button @click="navigate('nominees')" :class="page==='nominees' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Nominees
            </button>

            <!-- POLICIES -->
            <p class="px-3 pt-4 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Policies</p>

            <button @click="navigate('plans')" :class="page==='plans' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Plans
            </button>

            <button @click="navigate('policies')" :class="page==='policies' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Policies
            </button>

            <button @click="navigate('coverage-types')" :class="page==='coverage-types' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Coverage Types
            </button>

            <!-- FINANCE -->
            <p class="px-3 pt-4 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Finance</p>

            <button @click="navigate('payments')" :class="page==='payments' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Payments
            </button>

            <button @click="navigate('claims')" :class="page==='claims' ? 'active' : ''" class="sidebar-link w-full text-left justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Claims
                </span>
                <span x-show="metrics && metrics.pending_claims > 0"
                    class="ml-auto text-xs font-bold bg-yellow-400 text-yellow-900 rounded-full px-1.5 py-0.5 leading-none"
                    x-text="metrics?.pending_claims"></span>
            </button>

            <!-- OPERATIONS -->
            <p class="px-3 pt-4 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Operations</p>

            <button @click="navigate('documents')" :class="page==='documents' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Documents
            </button>

            <button @click="navigate('countries')" :class="page==='countries' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Countries
            </button>

            <button @click="navigate('audit-logs')" :class="page==='audit-logs' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Audit Logs
            </button>

            <!-- REPORTS -->
            <p class="px-3 pt-4 pb-2 text-xs font-semibold text-blue-400 uppercase tracking-wider">Reports</p>

            <button @click="navigate('reports')" :class="page==='reports' ? 'active' : ''" class="sidebar-link w-full text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </button>

        </nav>

        <!-- User -->
        <div class="px-4 py-4 border-t border-blue-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                    x-text="user ? user.name.charAt(0).toUpperCase() : 'A'"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate" x-text="user ? user.name : 'Admin'"></p>
                    <p class="text-blue-300 text-xs truncate" x-text="user && user.roles ? user.roles[0]?.name : ''"></p>
                </div>
                <button @click="logout" title="Logout" class="text-blue-300 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 overflow-y-auto">

        <!-- ── Top Navbar ─────────────────────────────────────────────── -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
            <div class="flex items-center justify-between px-6 h-16 gap-4">

                <!-- Left: breadcrumb -->
                <div class="flex items-center gap-2 text-sm min-w-0">
                    <span class="text-gray-400">Portal</span>
                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-semibold text-gray-800 capitalize truncate" x-text="page"></span>
                </div>

                <!-- Center: search bar -->
                <div class="hidden md:flex flex-1 max-w-sm mx-4">
                    <div class="relative w-full">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Search policies, customers…"
                            class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    </div>
                </div>

                <!-- Right: actions -->
                <div class="flex items-center gap-1 flex-shrink-0">

                    <!-- Date -->
                    <div class="hidden lg:flex items-center gap-1.5 text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 mr-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span x-text="new Date().toLocaleDateString('en-IN', {weekday:'short', day:'numeric', month:'short', year:'numeric'})"></span>
                    </div>

                    <!-- Notification bell -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <!-- Badge -->
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="open" @click.outside="open = false" x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-50">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                <p class="font-semibold text-sm text-gray-800">Notifications</p>
                                <span class="text-xs text-blue-600 cursor-pointer hover:underline">Mark all read</span>
                            </div>
                            <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                                <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800">Policy Issued</p>
                                        <p class="text-xs text-gray-500 truncate">TI-20260525-XXXXXXXX is now active</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Just now</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800">New Claim Submitted</p>
                                        <p class="text-xs text-gray-500 truncate">CLM-20260525-XXXXXXXX awaiting review</p>
                                        <p class="text-xs text-gray-400 mt-0.5">5 min ago</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800">Payment Received</p>
                                        <p class="text-xs text-gray-500 truncate">₹2,950 via Razorpay</p>
                                        <p class="text-xs text-gray-400 mt-0.5">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-2.5 border-t border-gray-100 text-center">
                                <span class="text-xs text-blue-600 cursor-pointer hover:underline">View all notifications</span>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <button class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>

                    <!-- Divider -->
                    <div class="w-px h-6 bg-gray-200 mx-1"></div>

                    <!-- User dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2.5 pl-1 pr-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                x-text="user ? user.name.charAt(0).toUpperCase() : 'A'"></div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-800 leading-tight" x-text="user ? user.name : 'Admin'"></p>
                                <p class="text-xs text-gray-500 leading-tight" x-text="user?.roles?.[0]?.name ?? 'Super Admin'"></p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" @click.outside="open = false" x-cloak
                            class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="user ? user.name : 'Admin'"></p>
                                <p class="text-xs text-gray-500 truncate" x-text="user ? user.email : ''"></p>
                            </div>
                            <div class="py-1">
                                <button class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition text-left">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </button>
                                <button class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition text-left">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </button>
                            </div>
                            <div class="border-t border-gray-100 py-1">
                                <button @click="logout(); open = false"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ── Page tab bar ──────────────────────────────────────── -->
            <div class="flex items-center gap-1 px-6 overflow-x-auto border-t border-gray-100 bg-gray-50/60">
                <template x-for="tab in [
                    { key:'dashboard', label:'Dashboard',  icon:'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
                    { key:'customers', label:'Customers',  icon:'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
                    { key:'plans',     label:'Plans',      icon:'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
                    { key:'policies',  label:'Policies',   icon:'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
                    { key:'claims',    label:'Claims',     icon:'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
                    { key:'payments',  label:'Payments',   icon:'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
                    { key:'travelers', label:'Travelers',  icon:'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' }
                ]" :key="tab.key">
                    <button @click="navigate(tab.key)"
                        :class="page === tab.key
                            ? 'border-b-2 border-blue-700 text-blue-700 bg-white'
                            : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-white'"
                        class="flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium whitespace-nowrap transition-all">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                        </svg>
                        <span x-text="tab.label"></span>
                    </button>
                </template>
            </div>
        </header>
        <!-- ── End Navbar ──────────────────────────────────────────────── -->

        <div class="p-6">
            <!-- Pages -->
            <div x-show="page === 'dashboard'"><div x-data="dashboardPage()" x-init="load()">@include('pages.dashboard')</div></div>
            <div x-show="page === 'customers'"><div x-data="customersPage()" x-init="load()">@include('pages.customers')</div></div>
            <div x-show="page === 'family-members'"><div x-data="familyMembersPage()" x-init="load()">@include('pages.family-members')</div></div>
            <div x-show="page === 'travelers'"><div x-data="travelersPage()" x-init="load()">@include('pages.travelers')</div></div>
            <div x-show="page === 'nominees'"><div x-data="nomineesPage()" x-init="load()">@include('pages.nominees')</div></div>
            <div x-show="page === 'plans'"><div x-data="plansPage()" x-init="load()">@include('pages.plans')</div></div>
            <div x-show="page === 'policies'"><div x-data="policiesPage()" x-init="load()">@include('pages.policies')</div></div>
            <div x-show="page === 'coverage-types'"><div x-data="coverageTypesPage()" x-init="load()">@include('pages.coverage-types')</div></div>
            <div x-show="page === 'payments'"><div x-data="paymentsPage()" x-init="load()">@include('pages.payments')</div></div>
            <div x-show="page === 'claims'"><div x-data="claimsPage()" x-init="load()">@include('pages.claims')</div></div>
            <div x-show="page === 'documents'"><div x-data="documentsPage()" x-init="load()">@include('pages.documents')</div></div>
            <div x-show="page === 'countries'"><div x-data="countriesPage()" x-init="load()">@include('pages.countries')</div></div>
            <div x-show="page === 'audit-logs'"><div x-data="auditLogsPage()" x-init="load()">@include('pages.audit-logs')</div></div>
            <div x-show="page === 'reports'"><div x-data="reportsPage()" x-init="load()">@include('pages.reports')</div></div>
        </div>
    </main>
</div>
</template>

<script>
// ─── Global API helper ────────────────────────────────────────────────────────
const API_BASE = '/api';
function getToken() { return localStorage.getItem('ti_token'); }
async function api(path, options = {}) {
    const res = await fetch(API_BASE + path, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getToken(),
            ...(options.headers || {})
        },
        body: options.body ? JSON.stringify(options.body) : undefined
    });
    const json = await res.json();
    if (res.status === 401) { localStorage.clear(); window.location.href = '/login'; }
    return { ok: res.ok, status: res.status, data: json };
}

// ─── Root app ─────────────────────────────────────────────────────────────────
function app() {
    return {
        token: null,
        user: null,
        page: 'dashboard',
        init() {
            this.token = getToken();
            if (!this.token) { window.location.href = '/login'; return; }
            const u = localStorage.getItem('ti_user');
            if (u) this.user = JSON.parse(u);
            // Listen for navigation events dispatched by child components
            window.addEventListener('navigate-to', (e) => { this.page = e.detail; });
        },
        navigate(p) { this.page = p; },
        async logout() {
            await api('/logout', { method: 'POST' });
            localStorage.clear();
            window.location.href = '/login';
        }
    }
}

// ─── Dashboard ────────────────────────────────────────────────────────────────
function dashboardPage() {
    return {
        metrics: null, loading: true, error: '',
        async load() {
            const r = await api('/dashboard/admin');
            this.loading = false;
            if (r.ok) this.metrics = r.data.data;
            else this.error = r.data.message;
        }
    }
}

// ─── Customers ────────────────────────────────────────────────────────────────
function customersPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false,
        form: { user_id:'', first_name:'', last_name:'', email:'', mobile:'', dob:'', gender:'', passport_no:'', nationality:'', address:'' },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/customers?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const r = await api('/customers', { method: 'POST', body: this.form });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.resetForm(); this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        },
        resetForm() { this.form = { user_id:'', first_name:'', last_name:'', email:'', mobile:'', dob:'', gender:'', passport_no:'', nationality:'', address:'' }; }
    }
}

// ─── Plans ────────────────────────────────────────────────────────────────────
function plansPage() {
    return {
        items: [], loading: true, error: '',
        showForm: false,
        form: { name:'', code:'', policy_type:'', base_premium:'', coverage_amount:'', min_age:0, max_age:70, max_family_members:6, covered_countries:'', benefits:'', add_ons:'', is_active:true },
        formError: '', formLoading: false,
        async load() {
            this.loading = true;
            const r = await api('/plans');
            this.loading = false;
            if (r.ok) this.items = r.data.data.data || r.data.data;
            else this.error = r.data.message;
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const payload = {
                ...this.form,
                covered_countries: this.form.covered_countries ? this.form.covered_countries.split(',').map(s=>s.trim()) : [],
                benefits: this.form.benefits ? this.form.benefits.split(',').map(s=>s.trim()) : [],
                add_ons: this.form.add_ons ? this.form.add_ons.split(',').map(s=>s.trim()) : [],
                base_premium: parseFloat(this.form.base_premium),
                coverage_amount: parseFloat(this.form.coverage_amount),
            };
            const r = await api('/plans', { method: 'POST', body: payload });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Policies ─────────────────────────────────────────────────────────────────
function policiesPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false,
        customers: [], plans: [], travelers: [],
        form: { customer_id:'', plan_id:'', policy_type:'', start_date:'', end_date:'', destination_country:'', trip_type:'single', premium_amount:'', traveler_ids:[] },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/policies?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async openForm() {
            this.showForm = true;
            const [rc, rp, rt] = await Promise.all([api('/customers'), api('/plans'), api('/travelers')]);
            this.customers = rc.ok ? (rc.data.data.data || rc.data.data) : [];
            this.plans = rp.ok ? (rp.data.data.data || rp.data.data) : [];
            this.travelers = rt.ok ? (rt.data.data.data || rt.data.data) : [];
        },
        toggleTraveler(id) {
            const idx = this.form.traveler_ids.indexOf(id);
            if (idx === -1) this.form.traveler_ids.push(id);
            else this.form.traveler_ids.splice(idx, 1);
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const payload = { ...this.form, premium_amount: parseFloat(this.form.premium_amount) };
            const r = await api('/policies', { method: 'POST', body: payload });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Claims ───────────────────────────────────────────────────────────────────
function claimsPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false,
        policies: [], travelers: [],
        form: { policy_id:'', traveler_id:'', claim_type:'', incident_date:'', amount_claimed:'', remarks:'' },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/claims?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async openForm() {
            this.showForm = true;
            const [rp, rt] = await Promise.all([api('/policies'), api('/travelers')]);
            this.policies = rp.ok ? (rp.data.data.data || rp.data.data) : [];
            this.travelers = rt.ok ? (rt.data.data.data || rt.data.data) : [];
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const payload = { ...this.form, amount_claimed: parseFloat(this.form.amount_claimed) };
            const r = await api('/claims', { method: 'POST', body: payload });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Payments ─────────────────────────────────────────────────────────────────
function paymentsPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false,
        policies: [],
        form: { policy_id:'', transaction_id:'', gateway:'stripe', amount:'', currency:'INR', status:'success' },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/payments?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async openForm() {
            this.showForm = true;
            const rp = await api('/policies');
            this.policies = rp.ok ? (rp.data.data.data || rp.data.data) : [];
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const payload = { ...this.form, amount: parseFloat(this.form.amount) };
            const r = await api('/payments', { method: 'POST', body: payload });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Travelers ────────────────────────────────────────────────────────────────
function travelersPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false, customers: [],
        form: { customer_id:'', first_name:'', last_name:'', dob:'', gender:'', passport_no:'', nationality:'', visa_type:'', emergency_contact:'' },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/travelers?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async openForm() {
            this.showForm = true;
            const rc = await api('/customers');
            this.customers = rc.ok ? (rc.data.data.data || rc.data.data) : [];
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const r = await api('/travelers', { method: 'POST', body: this.form });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Family Members ───────────────────────────────────────────────────────────
function familyMembersPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        showForm: false, customers: [],
        form: { customer_id:'', relationship:'', first_name:'', last_name:'', dob:'', gender:'', passport_no:'', dependent: false },
        formError: '', formLoading: false,
        async load(page = 1) {
            this.loading = true;
            const r = await api('/family-members?page=' + page);
            this.loading = false;
            if (r.ok) { this.items = r.data.data.data || r.data.data; this.meta = r.data.data.meta; }
            else this.error = r.data.message;
        },
        async openForm() {
            this.showForm = true;
            const rc = await api('/customers');
            this.customers = rc.ok ? (rc.data.data.data || rc.data.data) : [];
        },
        async submit() {
            this.formLoading = true; this.formError = '';
            const r = await api('/family-members', { method: 'POST', body: this.form });
            this.formLoading = false;
            if (r.ok) { this.showForm = false; this.load(); }
            else this.formError = r.data.message || JSON.stringify(r.data.errors);
        }
    }
}

// ─── Nominees ─────────────────────────────────────────────────────────────────
function nomineesPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        async load(page = 1) {
            this.loading = true;
            // Nominees don't have a dedicated API yet — show placeholder
            this.loading = false;
            this.items = [];
        }
    }
}

// ─── Coverage Types ───────────────────────────────────────────────────────────
function coverageTypesPage() {
    return {
        items: [], loading: true, error: '',
        async load() {
            this.loading = true;
            // No dedicated API yet — show placeholder
            this.loading = false;
            this.items = [];
        }
    }
}

// ─── Documents ────────────────────────────────────────────────────────────────
function documentsPage() {
    return {
        items: [], loading: true, error: '',
        async load() {
            this.loading = false;
            this.items = [];
        }
    }
}

// ─── Countries ────────────────────────────────────────────────────────────────
function countriesPage() {
    return {
        items: [], loading: true, error: '',
        async load() {
            this.loading = false;
            this.items = [];
        }
    }
}

// ─── Audit Logs ───────────────────────────────────────────────────────────────
function auditLogsPage() {
    return {
        items: [], meta: null, loading: true, error: '',
        async load(page = 1) {
            this.loading = false;
            this.items = [];
        }
    }
}

// ─── Reports ──────────────────────────────────────────────────────────────────
function reportsPage() {
    return {
        metrics: null, loading: true, error: '',
        async load() {
            this.loading = true;
            const r = await api('/dashboard/admin');
            this.loading = false;
            if (r.ok) this.metrics = r.data.data;
            else this.error = r.data.message;
        }
    }
}
</script>
</body>
</html>
