<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Claims</h2>
        <p class="text-sm text-gray-500 mt-0.5">All submitted insurance claims</p>
    </div>
    <button @click="openForm()" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        File Claim
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
                <th class="text-left px-4 py-3 font-medium text-gray-600">Claim #</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Policy</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Type</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Incident Date</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Claimed</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Approved</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <template x-if="items.length === 0">
                <tr><td colspan="7" class="text-center py-10 text-gray-400">No claims found</td></tr>
            </template>
            <template x-for="c in items" :key="c.id">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs text-blue-700 font-medium" x-text="c.claim_number"></td>
                    <td class="px-4 py-3 text-gray-600 text-xs font-mono" x-text="c.policy?.policy_number || '#' + c.policy_id"></td>
                    <td class="px-4 py-3 text-gray-700 capitalize" x-text="c.claim_type"></td>
                    <td class="px-4 py-3 text-gray-500" x-text="c.incident_date"></td>
                    <td class="px-4 py-3 font-medium text-gray-900" x-text="'₹' + Number(c.amount_claimed).toLocaleString('en-IN')"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="c.amount_approved ? '₹' + Number(c.amount_approved).toLocaleString('en-IN') : '—'"></td>
                    <td class="px-4 py-3">
                        <span :class="{
                            'bg-yellow-100 text-yellow-700': c.status === 'submitted',
                            'bg-blue-100 text-blue-700': c.status === 'under_review',
                            'bg-green-100 text-green-700': c.status === 'approved',
                            'bg-red-100 text-red-700': c.status === 'rejected',
                            'bg-gray-100 text-gray-600': !['submitted','under_review','approved','rejected'].includes(c.status)
                        }" class="px-2 py-0.5 rounded-full text-xs font-medium capitalize" x-text="c.status?.replace('_',' ')"></span>
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

<!-- File Claim Modal -->
<div x-show="showForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">File a Claim</h3>
            <button @click="showForm = false; formError = ''" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">
            <div x-show="formError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="formError"></div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Policy *</label>
                <select x-model="form.policy_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select policy</option>
                    <template x-for="p in policies" :key="p.id">
                        <option :value="p.id" x-text="p.policy_number + ' — ' + (p.customer?.name || '')"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Traveler</label>
                <select x-model="form.traveler_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select traveler (optional)</option>
                    <template x-for="t in travelers" :key="t.id">
                        <option :value="t.id" x-text="t.name"></option>
                    </template>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Claim Type *</label>
                    <select x-model="form.claim_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Select type</option>
                        <option value="medical">Medical</option>
                        <option value="baggage">Lost Baggage</option>
                        <option value="flight_delay">Flight Delay</option>
                        <option value="trip_cancellation">Trip Cancellation</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Incident Date *</label>
                    <input x-model="form.incident_date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Amount Claimed (₹) *</label>
                <input x-model="form.amount_claimed" type="number" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Remarks</label>
                <textarea x-model="form.remarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none" placeholder="Describe the incident…"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="showForm = false; formError = ''" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" :disabled="formLoading" class="flex-1 px-4 py-2 bg-blue-700 hover:bg-blue-800 disabled:opacity-60 text-white rounded-lg text-sm font-medium transition">
                    <span x-text="formLoading ? 'Submitting…' : 'Submit Claim'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
