@php
    $navClasses = 'fixed z-50 inset-0 h-16 flex md:justify-end md:px-4 ';
    $navClasses .= auth()->check() ? ' justify-center' : ' justify-end px-4';
@endphp

<nav>
    <div class="{{ $navClasses }} backdrop-blur-sm md:backdrop-blur-none">
        <div class="flex h-16 justify-between">
            <div
                class="flex items-center space-x-2.5"
                x-data
            >



                    <a
                        title="Home"
                        href="{{ route('home.feed') }}"
                        class=""
                        wire:navigate
                    >
                        <button
                            type="button"
                            class="{{ request()->routeIs('home.*') ? 'dark:text-slate-100 text-slate-900' : 'dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900' }} inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none"
                        >
                            <x-heroicon-o-home class="h-6 w-6"/>
                        </button>
                    </a>

                    <a
                        title="Mapa" 
                        href="{{ route('maps') }}"
                        class=""
                    >
                        <button
                            type="button"
                            class="dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900 inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none"
                        >
                            <x-heroicon-o-map class="h-6 w-6"/>
                        </button>
                    </a>
                @auth
                    

                    <a
                        title="Profile"
                        href="{{ route('profile.show', ['username' => auth()->user()->username]) }}"
                        class=""
                        wire:navigate
                    >
                        <button
                            type="button"
                            class="{{ request()->fullUrlIs(route('profile.show', ['username' => auth()->user()->username])) ? 'dark:text-slate-100 text-slate-900' : 'dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900' }} inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none"
                        >
                            <x-heroicon-o-user class="h-6 w-6"/>
                        </button>
                    </a>

                    <a
                        title="Bookmarks"
                        href="{{ route('bookmarks.index') }}"
                        class=""
                        wire:navigate
                    >
                        <button
                            type="button"
                            class="{{ request()->routeIs('bookmarks.*') ? 'dark:text-slate-100 text-slate-900' : 'dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900' }} inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none"
                        >
                            <x-heroicon-o-bookmark class="h-6 w-6"/>
                        </button>
                    </a>

                    <a
                        title="Notifications"
                        href="{{ route('notifications.index') }}"
                        class=""
                        wire:navigate
                    >
                        <button
                            type="button"
                            class="{{ request()->routeIs('notifications.index') ? 'dark:text-slate-100 text-slate-900' : 'dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900' }} inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none"
                        >
                            <x-heroicon-o-bell class="h-6 w-6"/>

                            <livewire:navigation.notifications-count.show/>
                        </button>
                    </a>
                @endauth

                <x-dropdown
                    align="right"
                    width="48"
                >
                    <x-slot name="trigger">
                        <button
                            title="Menu"
                            class="inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 dark:text-slate-500 text-slate-400 transition duration-150 ease-in-out dark:hover:text-slate-100 hover:text-slate-900 focus:outline-none"
                        >
                            <x-icons.bars class="size-6"/>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-button x-data="themeSwitch()" @click="toggleTheme" class="flex flex-row items-center justify-between">
                            <span x-text="toggleThemeButtonText"></span>
                            <span class="mr-2">
                                <x-heroicon-o-moon x-show="theme == 'dark'" class="h-4 w-4"/>
                                <x-heroicon-o-sun x-show="theme == 'light'" class="h-4 w-4"/>
                                <x-heroicon-o-computer-desktop x-show="theme == 'system'" class="h-4 w-4"/>
                            </span>
                        </x-dropdown-button>
                        <x-dropdown-link :href="route('about')">
                            {{ __('About') }}
                        </x-dropdown-link>
                        @auth
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                x-data
                            >
                                @csrf

                                <x-dropdown-button onclick="event.preventDefault();this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-button>
                            </form>
                        @else
                            <x-dropdown-link
                                :href="route('home.feed')"
                                :class="request()->routeIs('home.feed') ? 'dark:bg-slate-800 bg-slate-200' : ''"
                            >
                                {{ __('Feed') }}
                            </x-dropdown-link>

                            <x-dropdown-link
                                :href="route('login')"
                                :class="request()->routeIs('login') ? 'dark:bg-slate-800 bg-slate-200' : ''"
                            >
                                {{ __('Log in') }}
                            </x-dropdown-link>

                            <x-dropdown-link
                                :href="route('register')"
                                :class="request()->routeIs('register') ? 'dark:bg-slate-800 bg-slate-200' : ''"
                            >
                                {{ __('Register') }}
                            </x-dropdown-link>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
