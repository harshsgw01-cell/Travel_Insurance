<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Reports</h2>
    <p class="text-sm text-gray-500 mt-0.5">Business insights and performance metrics</p>
</div>

<div x-show="loading" class="flex justify-center py-16">
    <svg class="animate-spin w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
</div>

<div x-show="!loading" class="space-y-6">
    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Customers</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" x-text="metrics?.customers ?? 0"></p>
            <p class="text-xs text-green-600 mt-1">Registered users</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Policies</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" x-text="metrics?.policies ?? 0"></p>
            <p class="text-xs text-blue-600 mt-1"><span x-text="metrics?.active_policies ?? 0"></span> active</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Claims</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2" x-text="metrics?.pending_claims ?? 0"></p>
            <p class="text-xs text-gray-500 mt-1">Awaiting review</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Revenue</p>
            <p class="text-3xl font-bold text-emerald-600 mt-2" x-text="'₹' + Number(metrics?.revenue ?? 0).toLocaleString('en-IN')"></p>
            <p class="text-xs text-gray-500 mt-1">Successful payments</p>
        </div>
    </div>

    <!-- Report cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Policy Sales Report</p>
                <p class="text-xs text-gray-500 mt-0.5">Monthly and yearly policy issuance trends</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Claims Ratio Report</p>
                <p class="text-xs text-gray-500 mt-0.5">Claim approval rates and settlement amounts</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Revenue Report</p>
                <p class="text-xs text-gray-500 mt-0.5">Premium collections and payment gateway breakdown</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Country Report</p>
                <p class="text-xs text-gray-500 mt-0.5">Top destinations and coverage by country</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Agent Performance</p>
                <p class="text-xs text-gray-500 mt-0.5">Policies sold and commissions per agent</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4 opacity-60">
            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Expiry Report</p>
                <p class="text-xs text-gray-500 mt-0.5">Policies expiring in the next 30/60/90 days</p>
                <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Coming soon</span>
            </div>
        </div>
    </div>
</div>
