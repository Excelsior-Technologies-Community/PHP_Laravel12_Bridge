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
            border: 1px solid rgba(255,255,255,0.1);
        }

        .table-row:hover {
            background: rgba(255,255,255,0.05);
            transition: 0.3s;
        }
    </style>
</head>

<body class="min-h-screen text-white">

<div class="max-w-7xl mx-auto py-10 px-4">

    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 shadow-2xl mb-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            <div>
                <h1 class="text-4xl font-extrabold tracking-wide">
                    🚀 Laravel Bridge Members
                </h1>

                <p class="text-gray-300 mt-2">
                    Manage your team members with modern AWS Lambda powered Laravel UI
                </p>
            </div>

            <a href="{{ route('members.create') }}"
               class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-105 duration-300 px-6 py-3 rounded-xl font-semibold shadow-lg">
                + Add Member
            </a>

        </div>

    </div>

    <!-- Success Message -->
    @if(session('success'))

        <div class="bg-green-500/20 border border-green-400 text-green-300 p-4 rounded-2xl mb-6 shadow">
            ✅ {{ session('success') }}
        </div>

    @endif

    <!-- Search -->
    <div class="glass-card rounded-2xl p-5 mb-6 shadow-xl">

        <form method="GET" class="flex flex-col md:flex-row gap-4">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search members..."
                   class="flex-1 bg-slate-800 border border-slate-600 text-white p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">

            <button class="bg-gradient-to-r from-purple-500 to-pink-500 hover:opacity-90 px-6 py-3 rounded-xl font-semibold shadow-lg">
                Search
            </button>

        </form>

    </div>

    <!-- Table -->
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

                <tbody>

                    @forelse($members as $member)

                    <tr class="border-t border-white/10 table-row">

                        <td class="p-5 font-semibold text-cyan-200">
                            #{{ $member->id }}
                        </td>

                        <td class="p-5">
                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($member->name,0,1)) }}
                                </div>

                                <div>
                                    <h2 class="font-semibold">
                                        {{ $member->name }}
                                    </h2>
                                </div>

                            </div>
                        </td>

                        <td class="p-5 text-gray-300">
                            {{ $member->email }}
                        </td>

                        <td class="p-5">

                            <span class="px-4 py-1 rounded-full text-sm font-semibold
                                @if($member->role == 'Admin')
                                    bg-red-500/20 text-red-300
                                @elseif($member->role == 'Manager')
                                    bg-yellow-500/20 text-yellow-300
                                @elseif($member->role == 'Developer')
                                    bg-blue-500/20 text-blue-300
                                @else
                                    bg-green-500/20 text-green-300
                                @endif
                            ">
                                {{ $member->role }}
                            </span>

                        </td>

                        <td class="p-5">

                            <div class="flex gap-3">

                                <a href="{{ route('members.edit', $member->id) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-xl text-sm font-semibold shadow">
                                    Edit
                                </a>

                                <form action="{{ route('members.destroy', $member->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Delete Member?')"
                                            class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl text-sm font-semibold shadow">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center p-10 text-gray-300">

                            <div class="flex flex-col items-center">

                                <div class="text-6xl mb-4">
                                    😢
                                </div>

                                <h2 class="text-2xl font-bold">
                                    No Members Found
                                </h2>

                                <p class="mt-2 text-gray-400">
                                    Try searching with another keyword
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-8">

        {{ $members->links() }}

    </div>

</div>

</body>
</html>