<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Travelers</h2>
        <p class="text-sm text-gray-500 mt-0.5">All registered travelers</p>
    </div>
    <button @click="openForm()" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Traveler
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
                <th class="text-left px-4 py-3 font-medium text-gray-600">Name</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Passport</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Nationality</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">DOB</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Gender</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Visa Type</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Emergency Contact</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <template x-if="items.length === 0">
                <tr><td colspan="7" class="text-center py-10 text-gray-400">No travelers found</td></tr>
            </template>
            <template x-for="t in items" :key="t.id">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-900" x-text="t.name"></td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500" x-text="t.passport_no || '—'"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="t.nationality || '—'"></td>
                    <td class="px-4 py-3 text-gray-500" x-text="t.dob || '—'"></td>
                    <td class="px-4 py-3 text-gray-600 capitalize" x-text="t.gender || '—'"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="t.visa_type || '—'"></td>
                    <td class="px-4 py-3 text-gray-600" x-text="t.emergency_contact || '—'"></td>
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

<!-- Add Traveler Modal -->
<div x-show="showForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Add Traveler</h3>
            <button @click="showForm = false; formError = ''" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="submit" class="px-6 py-5 space-y-4">
            <div x-show="formError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-text="formError"></div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Customer *</label>
                <select x-model="form.customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select customer</option>
                    <template x-for="c in customers" :key="c.id">
                        <option :value="c.id" x-text="c.name + ' (' + c.customer_code + ')'"></option>
                    </template>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">First Name *</label>
                    <input x-model="form.first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Last Name *</label>
                    <input x-model="form.last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Date of Birth</label>
                    <input x-model="form.dob" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Gender</label>
                    <select x-model="form.gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Passport No</label>
                    <input x-model="form.passport_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nationality</label>
                    <input x-model="form.nationality" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Visa Type</label>
                    <input x-model="form.visa_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. Tourist">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Emergency Contact</label>
                    <input x-model="form.emergency_contact" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="+91 98765 43210">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="showForm = false; formError = ''" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" :disabled="formLoading" class="flex-1 px-4 py-2 bg-blue-700 hover:bg-blue-800 disabled:opacity-60 text-white rounded-lg text-sm font-medium transition">
                    <span x-text="formLoading ? 'Saving…' : 'Save Traveler'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
