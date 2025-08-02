<!-- Navigation Header Component -->
<nav class="bg-blue-600 text-white p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-bold">SATTENAPALLI Layout Plan</h2>
            
            <!-- Project Role Indicator -->
            <div class="hidden md:flex items-center space-x-2 bg-blue-700 px-3 py-1 rounded-lg text-sm">
                <span class="text-blue-200">Current Role:</span>
                <span class="font-semibold text-white" id="currentRole">Sales Agent</span>
                <span class="text-blue-200">in</span>
                <span class="font-semibold text-yellow-300" id="currentProject">Sattenapalli Project</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Role Switcher -->
            <x-role-switcher />

            <!-- Options Menu -->
            <x-options-menu />

            <!-- Auth Links -->
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-700">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-700">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-green-500 px-4 py-2 rounded hover:bg-green-700">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-purple-500 px-4 py-2 rounded hover:bg-purple-700">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
