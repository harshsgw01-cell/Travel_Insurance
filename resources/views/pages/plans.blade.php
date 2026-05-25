<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Insurance Plans</h2>
        <p class="text-sm text-gray-500 mt-0.5">Active plans available for purchase</p>
    </div>
    <button @click="showForm = true" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Plan
    </button>
</div>

<!-- Loading -->
<div x-show="loading" class="flex justify-center py-16">
    <svg class="animate-spin w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
</div>

<!-- Error -->
<div x-show="error && !loading" class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="error"></div>

<!-- Plan Cards -->
<div x-show="!loading && !error" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    <template x-if="items.length === 0">
        <div class="col-span-3 text-center py-16 text-gray-400">No plans found</div>
    </template>
    <template x-for="p in items" :key="p.id">
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-semibold text-gray-900" x-text="p.name"></h3>
                    <p class="text-xs text-gray-400 font-mono mt-0.5" x-text="p.code"></p>
                </div>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full" x-text="p.policy_type"></span>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Base Premium</p>
                    <p class="font-bold text-gray-900 mt-0.5" x-text="'₹' + Number(p.base_premium).toLocaleString('en-IN')"></p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Coverage</p>
                    <p class="font-bold text-gray-900 mt-0.5" x-text="'₹' + Number(p.coverage_amount).toLocaleString('en-IN')"></p>
                </div>
            </div>
            <div class="text-xs text-gray-500 space-y-1">
                <p>Age: <span class="text-gray-700" x-text="p.age_limits?.min + ' – ' + p.age_limits?.max + ' yrs'"></span></p>
                <p>Max members: <span class="text-gray-700" x-text="p.max_family_members || '—'"></span></p>
                <p>Countries: <span class="text-gray-700" x-text="(p.covered_countries || []).join(', ') || '—'"></span></p>
            </div>
            <div x-show="p.benefits && p.benefits.length" class="mt-3 flex flex-wrap gap-1">
                <template x-for="b in (p.benefits || [])" :key="b">
                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-full" x-text="b"></span>
                </template>
            </div>
        </div>
    </template>
</div>

<!-- Add Plan Modal -->
<div x-show="showForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">New Insurance Plan</h3>
            <button @click="showForm = false; formError = ''" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">
            <div x-show="formError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="formError"></div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Plan Name *</label>
                    <input x-model="form.name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Code *</label>
                    <input x-model="form.code" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. INTL-BASIC">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Policy Type *</label>
                <input x-model="form.policy_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. Family Travel">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Base Premium (₹) *</label>
                    <input x-model="form.base_premium" type="number" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Coverage Amount (₹) *</label>
                    <input x-model="form.coverage_amount" type="number" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Min Age</label>
                    <input x-model="form.min_age" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Max Age</label>
                    <input x-model="form.max_age" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Max Members</label>
                    <input x-model="form.max_family_members" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Covered Countries <span class="text-gray-400">(comma-separated)</span></label>
                <input x-model="form.covered_countries" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="USA, GBR, IND">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Benefits <span class="text-gray-400">(comma-separated)</span></label>
                <input x-model="form.benefits" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Medical emergency, Lost baggage">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Add-ons <span class="text-gray-400">(comma-separated)</span></label>
                <input x-model="form.add_ons" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Adventure sports, COVID coverage">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showForm = false; formError = ''" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" :disabled="formLoading" class="flex-1 px-4 py-2 bg-blue-700 hover:bg-blue-800 disabled:opacity-60 text-white rounded-lg text-sm font-medium transition">
                    <span x-text="formLoading ? 'Saving…' : 'Create Plan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
