<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Member</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #312e81);
        }

        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-5">

<div class="w-full max-w-3xl">

    <div class="glass rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-cyan-500 to-blue-700 p-8 text-white">

            <div class="flex justify-between items-center">

                <div>
                    <h1 class="text-4xl font-extrabold">
                        ➕ Create Member
                    </h1>

                    <p class="mt-2 text-cyan-100">
                        Add new team member into Laravel Bridge
                    </p>
                </div>

                <a href="{{ route('members.index') }}"
                   class="bg-white/20 hover:bg-white/30 px-5 py-2 rounded-xl font-semibold duration-300">
                    ← Back
                </a>

            </div>

        </div>

        <!-- Form -->
        <div class="p-8 text-white">

            @if ($errors->any())

                <div class="bg-red-500/20 border border-red-400 text-red-200 p-4 rounded-2xl mb-6">

                    <ul class="list-disc ml-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <form action="{{ route('members.store') }}" method="POST">

                @csrf

                <!-- Name -->
                <div class="mb-6">

                    <label class="block mb-2 font-semibold text-lg">
                        Full Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter full name"
                           class="w-full bg-slate-800/80 border border-slate-600 rounded-2xl p-4 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400">

                </div>

                <!-- Email -->
                <div class="mb-6">

                    <label class="block mb-2 font-semibold text-lg">
                        Email Address
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter email address"
                           class="w-full bg-slate-800/80 border border-slate-600 rounded-2xl p-4 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400">

                </div>

                <!-- Role -->
                <div class="mb-8">

                    <label class="block mb-2 font-semibold text-lg">
                        Select Role
                    </label>

                    <select name="role"
                            class="w-full bg-slate-800/80 border border-slate-600 rounded-2xl p-4 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400">

                        <option value="">Choose Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Developer">Developer</option>
                        <option value="Tester">Tester</option>

                    </select>

                </div>

                <!-- Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-[1.02] duration-300 py-4 rounded-2xl text-lg font-bold shadow-xl">
                    🚀 Save Member
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>