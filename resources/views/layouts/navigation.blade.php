<nav x-data="{ open: false }"
    class="border-b"
    style="background-color: #6B21A8 !important; border-color: #5b1896 !important;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        style="background-color: #6B21A8 !important;">
        <div class="flex justify-between h-16">
            <!-- Left side -->
            <div class="flex" style="background-color: transparent !important;">
                <!-- Logo -->
                <div class="shrink-0 flex items-center" style="background-color: transparent !important;">
                    <a href="{{ route('home') }}" style="display:inline-block; background-color: transparent !important;">
                        <!-- place your logo SVG/img here -->
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                    style="background-color: transparent !important;">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')"
                        class="text-gray-100 hover:text-white"
                        style="background-color: transparent !important;">
                        {{ __('Home') }}
                    </x-nav-link>

                    <x-nav-link :href="route('public.scores')" :active="request()->routeIs('public.scores')"
                        class="text-gray-100 hover:text-white"
                        style="background-color: transparent !important;">
                        {{ __('Wedstrijdresultaten') }}
                    </x-nav-link>

                    <x-nav-link :href="route('historie')" :active="request()->routeIs('historie')"
                        class="text-gray-100 hover:text-white"
                        style="background-color: transparent !important;">
                        {{ __('Vorige Winnaars') }}
                    </x-nav-link>

                    @auth
                        @if(auth()->user()->is_admin)
                            <x-nav-link :href="route('admin.panel')" :active="request()->routeIs('admin.panel*')"
                                class="text-gray-100 hover:text-white"
                                style="background-color: transparent !important;">
                                {{ __('🛠️ Admin Panel') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    @auth
                    @if(Auth::user()->is_admin)
                    <x-nav-link :href="route('admin.scores.index')" :active="request()->routeIs('admin.scores.index', 'admin.scores.create', 'admin.scores.edit')"
                        class="text-gray-100 hover:text-white"
                        style="background-color: transparent !important;">
                        {{ __('Scores Beheren') }}
                    </x-nav-link>
                    @endif
                    @endauth

                    <x-nav-link :href="route('admin.schools.register')" :active="request()->routeIs('admin.schools.register')"
                class="text-gray-100 hover:text-white"
                style="background-color: transparent !important;">
                {{ __('Registreer School') }}
                </x-nav-link>
                </div>


            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6" style="background-color: transparent !important;">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-100 hover:text-white focus:outline-none transition ease-in-out duration-150"
                            style="background-color: transparent !important;">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" style="background-color: transparent !important;">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- wrap content in a div that forces transparent background to avoid white dropdown background -->
                        <div style="background-color: transparent !important; border: none !important;">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
                @endauth

                @guest
                <div class="flex space-x-2" style="background-color: transparent !important;">
                    <a href="{{ route('login') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-medium"
                        style="background-color: transparent !important;">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-medium"
                        style="background-color: transparent !important;">
                        Registreren
                    </a>
                </div>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden" style="background-color: transparent !important;">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-white hover:bg-opacity-25 focus:outline-none transition duration-150 ease-in-out"
                    style="background-color: transparent !important;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"
                        style="background-color: transparent !important;">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden"
        style="background-color: #6B21A8 !important;">
        <div class="pt-2 pb-3 space-y-1" style="background-color: transparent !important;">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')"
                style="background-color: transparent !important; color: #fff !important;">
                Home
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('results')" :active="request()->routeIs('results')"
            <x-responsive-nav-link :href="route('public.scores')" :active="request()->routeIs('public.scores')"
                style="background-color: transparent !important; color: #fff !important;">
                Wedstrijdresultaten
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('historie')" :active="request()->routeIs('historie')"
                style="background-color: transparent !important; color: #fff !important;">
                Vorige Winnaars
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.panel')" :active="request()->routeIs('admin.panel*')"
                        style="background-color: transparent !important; color: #fff !important;">
                        🛠️ Admin Panel
                    </x-responsive-nav-link>
                @endif
            @endauth

            <x-responsive-nav-link :href="route('admin.schools.register')" :active="request()->routeIs('admin.schools.register')"
                class="text-gray-100 hover:text-white"
                style="background-color: transparent !important; color: #fff !important;">
                {{ __('Registreer School') }}
            </x-responsive-nav-link>

        </div>

        @auth
        <div class="pt-4 pb-1" style="border-top: 1px solid #5b1896 !important;">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    style="background-color: transparent !important; color: #fff !important;">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        style="background-color: transparent !important; color: #fff !important;">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>