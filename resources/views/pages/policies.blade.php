<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Policies</h2>
        <p class="text-sm text-gray-500 mt-0.5">All issued and pending policies</p>
    </div>
    <button @click="openForm()" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Policy
    </button>
</div>

<!-- Loading -->
<div x-show="loading" class="flex justify-center py-16">
    <svg class="animate-spin w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
</div>

<!-- Error -->
<div x-show="error && !loading" class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="error"></div>

<!-- Table -->
<div x-show="!loading && !error" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Policy #</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Customer</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Plan</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Destination</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Period</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Total</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Payment</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <template x-if="items.length === 0">
                <tr><td colspan="8" class="text-center py-10 text-gray-400">No policies found</td></tr>
            </template>
            <template x-for="p in items" :key="p.id">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs text-blue-700 font-medium" x-text="p.policy_number"></td>
                    <td class="px-4 py-3 text-gray-700" x-text="p.customer?.name || '—'"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="p.plan?.name || '—'"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="p.destination_country"></td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="p.start_date + ' → ' + p.end_date"></td>
                    <td class="px-4 py-3 font-medium text-gray-900" x-text="'₹' + Number(p.total_amount).toLocaleString('en-IN')"></td>
                    <td class="px-4 py-3">
                        <span :class="{
                            'bg-green-100 text-green-700': p.status === 'active',
                            'bg-yellow-100 text-yellow-700': p.status === 'pending_payment',
                            'bg-red-100 text-red-700': p.status === 'cancelled',
                            'bg-gray-100 text-gray-600': !['active','pending_payment','cancelled'].includes(p.status)
                        }" class="px-2 py-0.5 rounded-full text-xs font-medium capitalize" x-text="p.status?.replace('_',' ')"></span>
                    </td>
                    <td class="px-4 py-3">
                        <span :class="p.payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'"
                            class="px-2 py-0.5 rounded-full text-xs font-medium capitalize" x-text="p.payment_status"></span>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <!-- Pagination -->
    <div x-show="meta && meta.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
        <span x-text="'Page ' + meta?.current_page + ' of ' + meta?.last_page"></span>
        <div class="flex gap-2">
            <button @click="load(meta.current_page - 1)" :disabled="meta?.current_page <= 1" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Prev</button>
            <button @click="load(meta.current_page + 1)" :disabled="meta?.current_page >= meta?.last_page" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Next</button>
        </div>
    </div>
</div>

<!-- New Policy Modal -->
<div x-show="showForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Create New Policy</h3>
            <button @click="showForm = false; formError = ''" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">
            <div x-show="formError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="formError"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Customer *</label>
                    <select x-model="form.customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Select customer</option>
                        <template x-for="c in customers" :key="c.id">
                            <option :value="c.id" x-text="c.name + ' (' + c.customer_code + ')'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Plan *</label>
                    <select x-model="form.plan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Select plan</option>
                        <template x-for="p in plans" :key="p.id">
                            <option :value="p.id" x-text="p.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Policy Type *</label>
                    <input x-model="form.policy_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. Family Travel">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Trip Type</label>
                    <select x-model="form.trip_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="single">Single Trip</option>
                        <option value="multi">Multi Trip</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Start Date *</label>
                    <input x-model="form.start_date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">End Date *</label>
                    <input x-model="form.end_date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Destination Country *</label>
                    <input x-model="form.destination_country" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. United States">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Premium Amount (₹) *</label>
                    <input x-model="form.premium_amount" type="number" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <!-- Travelers -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2">Select Travelers * <span class="text-gray-400">(at least one)</span></label>
                <div class="border border-gray-200 rounded-lg max-h-40 overflow-y-auto divide-y divide-gray-100">
                    <template x-if="travelers.length === 0">
                        <p class="text-center py-4 text-gray-400 text-xs">No travelers found. Add travelers first.</p>
                    </template>
                    <template x-for="t in travelers" :key="t.id">
                        <label class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" :value="t.id" @change="toggleTraveler(t.id)"
                                :checked="form.traveler_ids.includes(t.id)"
                                class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm text-gray-700" x-text="t.name + (t.passport_no ? ' — ' + t.passport_no : '')"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="showForm = false; formError = ''" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" :disabled="formLoading" class="flex-1 px-4 py-2 bg-blue-700 hover:bg-blue-800 disabled:opacity-60 text-white rounded-lg text-sm font-medium transition">
                    <span x-text="formLoading ? 'Creating…' : 'Create Policy'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
