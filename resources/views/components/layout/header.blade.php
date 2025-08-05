<!-- Navigation Header Component -->
<nav class="bg-blue-600 text-white p-1">
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
             <x-role-switcher />
                         <x-options-menu />

        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Role Switcher -->
           

            <!-- Options Menu -->

            <!-- Auth Links -->
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-700 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-700 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-green-500 px-4 py-2 rounded hover:bg-green-700 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        <span class="sr-only">Login</span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-purple-500 px-4 py-2 rounded hover:bg-purple-700 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="sr-only">Register</span>
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
