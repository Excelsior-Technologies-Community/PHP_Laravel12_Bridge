<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Bridge Members</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #0f172a, #1e293b);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .table-row:hover {
            background: rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }
    </style>
</head>
<body class="min-h-screen text-white">

<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="glass-card rounded-3xl p-8 shadow-2xl mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-extrabold tracking-wide text-cyan-400">
                🚀 Laravel Bridge Members
            </h1>
            <p class="text-gray-300 mt-2">
                Manage your team members with modern AWS Lambda powered Laravel UI
            </p>
        </div>
        <a href="{{ route('members.create') }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-105 duration-300 px-6 py-3 rounded-xl font-semibold shadow-lg text-white">
            + Add Member
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-400 text-green-300 p-4 rounded-2xl mb-6 shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="glass-card rounded-2xl p-5 mb-6 shadow-xl relative">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search members by name, email or role..." class="w-full bg-slate-800 border border-slate-600 text-white p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 shadow-inner">
                <div id="suggestionBox" class="hidden absolute left-0 right-0 top-full mt-2 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl z-50 p-2 space-y-1"></div>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-gray-500 uppercase">Recent Searches</span>
                <button onclick="clearHistory()" class="text-[10px] text-rose-500 font-bold hover:underline">Clear All</button>
            </div>
            <div id="historyBox" class="flex flex-wrap gap-2"></div>
        </div>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/10 text-cyan-300 uppercase text-sm tracking-wider">
                    <tr>
                        <th class="p-5 text-left">ID</th>
                        <th class="p-5 text-left">Name</th>
                        <th class="p-5 text-left">Email</th>
                        <th class="p-5 text-left">Role</th>
                        <th class="p-5 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="memberTableBody">
                    @forelse($members as $member)
                    <tr class="border-t border-white/10 table-row">
                        <td class="p-5 font-semibold text-cyan-200">#{{ $member->id }}</td>
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 flex items-center justify-center font-bold text-white shadow">
                                    {{ strtoupper(substr($member->name,0,1)) }}
                                </div>
                                <div>
                                    <h2 class="font-semibold">{{ $member->name }}</h2>
                                </div>
                            </div>
                        </td>
                        <td class="p-5 text-gray-300">{{ $member->email }}</td>
                        <td class="p-5">
                            <span class="px-4 py-1 rounded-full text-sm font-semibold 
                                @if($member->role == 'Admin') bg-red-500/20 text-red-300
                                @elseif($member->role == 'Manager') bg-yellow-500/20 text-yellow-300
                                @elseif($member->role == 'Developer') bg-blue-500/20 text-blue-300
                                @else bg-green-500/20 text-green-300
                                @endif
                            ">
                                {{ $member->role }}
                            </span>
                        </td>
                        <td class="p-5">
                            <div class="flex gap-3">
                                <a href="{{ route('members.edit', $member->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow transition-all">Edit</a>
                                <form action="{{ route('members.destroy', $member->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete Member?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow transition-all">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noMembersRow">
                        <td colspan="5" class="text-center p-10 text-gray-300">
                            <div class="flex flex-col items-center">
                                <div class="text-6xl mb-4">😢</div>
                                <h2 class="text-2xl font-bold">No Members Found</h2>
                                <p class="mt-2 text-slate-400">Try searching with another keyword</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="paginationBox" class="mt-8">
        {{ $members->links() }}
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchMeta('');
    });

    let searchTimeout = null;

    function handleSearch() {
        clearTimeout(searchTimeout);
        const search = document.getElementById('searchInput').value;
        const paginationBox = document.getElementById('paginationBox');

        if (search.trim() === '') {
            paginationBox.style.display = 'block';
        } else {
            paginationBox.style.display = 'none';
        }

        searchTimeout = setTimeout(() => {
            fetch(`/monitoring/search/live?search=${encodeURIComponent(search)}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('memberTableBody');
                    tbody.innerHTML = '';

                    if (data.members.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center p-10 text-gray-300">
                                    <div class="flex flex-col items-center">
                                        <div class="text-6xl mb-4">😢</div>
                                        <h2 class="text-2xl font-bold">No Members Found</h2>
                                        <p class="mt-2 text-slate-400">Try searching with another keyword</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    data.members.forEach(member => {
                        let badgeClass = '';
                        if (member.role === 'Admin') badgeClass = 'bg-red-500/20 text-red-300';
                        else if (member.role === 'Manager') badgeClass = 'bg-yellow-500/20 text-yellow-300';
                        else if (member.role === 'Developer') badgeClass = 'bg-blue-500/20 text-blue-300';
                        else badgeClass = 'bg-green-500/20 text-green-300';

                        tbody.innerHTML += `
                            <tr class="border-t border-white/10 table-row">
                                <td class="p-5 font-semibold text-cyan-200">#${member.id}</td>
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 flex items-center justify-center font-bold text-white shadow">
                                            ${member.name.charAt(0).toUpperCase()}
                                        </div>
                                        <div>
                                            <h2 class="font-semibold">${member.name}</h2>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-gray-300">${member.email}</td>
                                <td class="p-5">
                                    <span class="px-4 py-1 rounded-full text-sm font-semibold ${badgeClass}">
                                        ${member.role}
                                    </span>
                                </td>
                                <td class="p-5">
                                    <div class="flex gap-3">
                                        <a href="/members/${member.id}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow">Edit</a>
                                        <form action="/members/${member.id}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button onclick="return confirm('Delete Member?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                });
            fetchMeta(search);
        }, 300);
    }

    function fetchMeta(query) {
        fetch(`/monitoring/search/meta?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                const box = document.getElementById('suggestionBox');
                if (data.suggestions.length > 0) {
                    box.classList.remove('hidden');
                    box.innerHTML = '';
                    data.suggestions.forEach(s => {
                        box.innerHTML += `<div onclick="applySearch('${s}')" class="p-2 hover:bg-slate-800 rounded-lg cursor-pointer text-xs font-semibold text-cyan-400">💡 Suggestion: ${s}</div>`;
                    });
                } else {
                    box.classList.add('hidden');
                }

                const hist = document.getElementById('historyBox');
                hist.innerHTML = '';
                if (data.history.length === 0) {
                    hist.innerHTML = `<span class="text-xs text-gray-500">No recent searches.</span>`;
                }
                data.history.forEach(h => {
                    hist.innerHTML += `<span onclick="applySearch('${h}')" class="bg-slate-800 hover:bg-slate-700 text-gray-300 text-[11px] px-2.5 py-1 rounded-lg cursor-pointer font-medium">🕒 ${h}</span>`;
                });
            });
    }

    function applySearch(val) {
        document.getElementById('searchInput').value = val;
        document.getElementById('suggestionBox').classList.add('hidden');
        handleSearch();
    }

    function clearHistory() {
        fetch('/monitoring/search/clear', { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
        }).then(() => {
            fetchMeta(document.getElementById('searchInput').value);
        });
    }

    document.addEventListener('click', function(e) {
        const box = document.getElementById('suggestionBox');
        if (!document.getElementById('searchInput').contains(e.target) && !box.contains(e.target)) {
            box.classList.add('hidden');
        }
    });
</script>
</body>
</html>