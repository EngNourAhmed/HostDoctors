@extends('layouts.admin')

@section('title', 'All Users')
@section('header', 'All Users')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-2 px-2">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Total Users</h2>
                <p class="text-sm text-gray-400 mt-1">Manage roles, cases, and filter active members across the platform.</p>
            </div>
        </div>

        <div class="mb-4"></div>

        <div class="bh-table-transparent rounded-xl border border-white/10 bg-[#0c0c0c]">
            <!-- Desktop Table (Hidden on mobile) -->
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300 table-users overflow-visible border-collapse">
                    <thead class="sticky top-0 z-20 shadow-md">
                        <tr class="border-b border-white/10">
                            <th class="px-4 py-3 font-bold text-gray-400 uppercase tracking-widest text-[10px]">Name</th>
                            <th class="px-4 py-3 font-bold text-gray-400 uppercase tracking-widest text-[10px]">Email</th>
                            <th class="px-4 py-3 font-bold text-gray-400 uppercase tracking-widest text-[10px]">Address</th>
                            <th class="px-4 py-3 font-bold text-gray-400 uppercase tracking-widest text-[10px]">Phone</th>
                        </tr>
                    </thead>
                    <tbody class="divide-none">
                        @foreach ($users as $user)
                            <tr class="transition-colors group hover:bg-white/[0.03] user-row">
                                <td class="px-4 py-2.5 text-[#FACC15] font-bold search-target text-[13px] border-b border-white/10">
                                    <a href="{{ route('admin.users.reports', $user) }}" class="hover:underline">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5 text-gray-300 search-target text-[13px] border-b border-white/10">{{ $user->email }}</td>
                                <td class="px-4 py-2.5 text-gray-400 text-[13px] border-b border-white/10">{{ $user->address ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-gray-400 search-target text-[13px] border-b border-white/10">{{ $user->phone ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Cards) -->
            <div class="md:hidden divide-y divide-white/5 users-cards">
                @foreach ($users as $user)
                    <div class="p-4 space-y-3 user-row">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col">
                                <a href="{{ route('admin.users.reports', $user) }}" class="text-[#FACC15] font-bold text-sm tracking-tight search-target">
                                    {{ $user->name }}
                                </a>
                                <p class="text-gray-400 text-xs mt-0.5 search-target">{{ $user->email }}</p>
                            </div>
                            <div class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-[#FACC15]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex items-center gap-3">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <span class="text-[11px] text-gray-400 search-target">{{ $user->phone ?? 'No phone' }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-3.5 h-3.5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[11px] text-gray-500 leading-tight">{{ $user->address ?? 'No address' }}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.users.reports', $user) }}" class="flex items-center justify-center w-full py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-white hover:bg-[#FACC15] hover:text-black transition-all">
                                View User Cases
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
               
            <div class="pt-3 px-6 pb-6">
                {{ $users->links('vendor.pagination.custom') }}
             </div>
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('usersSearch');
            const table = document.querySelector('.table-users');
            if (!input || !table) return;

            const rows = Array.from(table.querySelectorAll('tbody tr'));

            function normalize(value) {
                return (value || '').toLowerCase().replace(/\s+/g, ' ').trim();
            }

            function applyFilter() {
                const q = normalize(input.value);
                const allRows = document.querySelectorAll('.user-row');
                
                allRows.forEach((row) => {
                    const targets = row.querySelectorAll('.search-target');
                    let text = '';
                    targets.forEach(t => { text += ' ' + t.innerText; });
                    text = normalize(text);
                    
                    row.style.display = !q || text.includes(q) ? '' : 'none';
                });
            }

            input.addEventListener('input', applyFilter);
        })();

    </script>
@endsection
