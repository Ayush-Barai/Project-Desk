<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ProjectDesk</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-white font-sans antialiased">

    <!-- Navbar -->
    <nav class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
        <h1 class="text-xl font-bold text-blue-500">ProjectDesk</h1>

        <div class="space-x-4">
            @if(auth()->check())
                <a href="{{ route('dashboard') }}"
                   class="text-sm hover:text-blue-400">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-sm hover:text-blue-400">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-blue-600 px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Get Started
                </a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="text-center py-20 px-6">
        <h2 class="text-4xl md:text-5xl font-bold leading-tight">
            Manage Projects <span class="text-blue-500">Smarter</span>
        </h2>

        <p class="mt-6 text-gray-400 max-w-xl mx-auto">
            ProjectDesk helps teams collaborate, manage tasks, and track progress —
            all in one simple and powerful workspace.
        </p>

        <div class="mt-8 flex justify-center gap-4">
            @if(auth()->check())
                <a href="{{ route('dashboard') }}"
                   class="bg-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="bg-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                    Start for Free
                </a>

                <a href="{{ route('login') }}"
                   class="border border-gray-700 px-6 py-3 rounded-lg hover:bg-gray-800">
                    Login
                </a>
            @endif
        </div>
    </section>

    <!-- Features -->
    <section class="grid md:grid-cols-3 gap-6 px-6 pb-20 max-w-6xl mx-auto">

        <div class="bg-gray-900 p-6 rounded-xl border border-gray-800">
            <h3 class="text-lg font-semibold mb-2">📁 Workspaces</h3>
            <p class="text-gray-400 text-sm">
                Organize teams and projects into structured workspaces.
            </p>
        </div>

        <div class="bg-gray-900 p-6 rounded-xl border border-gray-800">
            <h3 class="text-lg font-semibold mb-2">👥 Team Collaboration</h3>
            <p class="text-gray-400 text-sm">
                Add members, assign roles, and work together seamlessly.
            </p>
        </div>

        <div class="bg-gray-900 p-6 rounded-xl border border-gray-800">
            <h3 class="text-lg font-semibold mb-2">✅ Task Management</h3>
            <p class="text-gray-400 text-sm">
                Track tasks, progress, and deadlines efficiently.
            </p>
        </div>

    </section>

    <!-- CTA -->
    <section class="text-center pb-20">
        <h3 class="text-2xl font-semibold">
            Ready to boost your productivity?
        </h3>

        @if(auth()->check())
            <a href="{{ route('dashboard') }}"
               class="inline-block mt-6 bg-blue-600 px-6 py-3 rounded-lg hover:bg-blue-700">
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('register') }}"
               class="inline-block mt-6 bg-blue-600 px-6 py-3 rounded-lg hover:bg-blue-700">
                Create Your Workspace
            </a>
        @endif
    </section>

    <!-- Footer -->
    <footer class="text-center text-gray-500 text-sm py-6 border-t border-gray-800">
        © {{ date('Y') }} ProjectDesk. All rights reserved.
    </footer>

</body>
</html>