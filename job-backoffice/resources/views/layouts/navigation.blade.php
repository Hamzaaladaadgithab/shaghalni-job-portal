
<nav class="w-[250px] h-screen bg-white border-r border-gray-200">
    <!-- Logo Section -->
    <div class="flex items-center px-6 border-b border-gray-200 py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <x-application-logo class="h-6 w-auto fill-current text-gray-800"/>
            <span  class="text-lg font-semibold text-gray-800">Shaghalni</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <ul class="flex flex-col px-4 py-6 apace-y-2">

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-nav-link>

        @if(auth()->user()->role=='admin')
            <x-nav-link :href="route('company.index')" :active="request()->routeIs('company.index')">
            Companies
        </x-nav-link>
        @endif





        @if(auth()->user()->role=='company-owner')
            <x-nav-link :href="route('my-company.show')" :active="request()->routeIs('my-company.show')">
            My Company
        </x-nav-link>
        @endif






        <x-nav-link :href="route('job-application.index')" :active="request()->routeIs('job-application.index')">
            Job Applications
        </x-nav-link>

        @if(auth()->user()->role=='admin')
            <x-nav-link :href="route('job-category.index')" :active="request()->routeIs('job-category.index')">
            Job Categories
        </x-nav-link>
        @endif





        <x-nav-link :href="route('job-vacancy.index')" :active="request()->routeIs('job-vacancy.index')">
            Job Vacancies
        </x-nav-link>


    @if(auth()->user()->role=='admin')
        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
            Users
        </x-nav-link>
    @endif



        <hr/>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-nav-link  class="text-red-600" :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-nav-link>
        </form>


    </ul>
</nav>

