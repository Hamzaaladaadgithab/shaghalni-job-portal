<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Company') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
            <form method="POST" action="{{ route('company.store') }}">
                @csrf

                <!-- Company details -->
                <div class="mb-6 p-6 bg-gray-100 rounded-lg shadow-md">
                    <h3 class="text-2xl font-semibold mb-4">Company Details</h3>

                    <p class="text-gray-600">Enter the company  details.</p>

                    <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Company Name
                    </label>
                    <input
                        class="{{$errors->has('name') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="name" name="name" type="text" placeholder="Enter company name" required
                        value="{{ old('name') }}">

                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Address Name
                    </label>
                    <input
                        class="{{$errors->has('address') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3
                        text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="address" name="address" type="text" placeholder="Enter company address" required
                        value="{{ old('address') }}">

                    @error('address')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!--industry-->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="industry">Industry</label>

                    <select name="industry" id="industry" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500
                     focus:ring-indigo-500">
                        @foreach ($industries as $industry)
                        <option value="{{ $industry }}">{{ $industry }}</option>
                        @endforeach
                        </select>

                    @error('industry')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="website">
                    WebSite (optional)
                    </label>
                    <input
                        class="{{$errors->has('website') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3
                        text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="website" name="website" type="text" placeholder="Enter company website" required
                        value="{{ old('website') }}">

                    @error('website')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                </div>



                <!-- Company details -->
                <div>
                    <div class="mb-6 p-6 bg-gray-100 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Company Owner</h2>

                    <p class="text-gray-600">Enter the company Owner details.</p>

                    <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="owner_name">
                        Owner Name
                    </label>
                    <input
                        class="{{$errors->has('owner_name') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="owner_name" name="owner_name" type="text" placeholder="Enter owner name" required
                        value="{{ old('owner_name') }}">

                    @error('owner_name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="owner_email">
                        Owner Email
                    </label>
                    <input
                        class="{{$errors->has('owner_email') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="owner_email" name="owner_email" type="text" placeholder="Enter Owner Email" required
                        value="{{ old('owner_email') }}">

                    @error('owner_email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>


                <!--owner password-->
                <div class="mb-4">

                <label class="block text-gray-700 text-sm font-bold mb-2" for="owner_password">
                        Owner Password
                    </label>

        <div class="relative" x-data="{ showPassword: false }">

        <x-text-input id="owner_password" class="block mt-1 w-full pr-10 py-2 px-3"   placeholder="Enter Owner Password" name="owner_password"
                    required autocomplete="current-password"  x-bind:type="showPassword ? 'text':'password'"/>

        <button type="button" @click="showPassword = !showPassword"  class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">

            <svg x-show="!showPassword" id="icon-closed" class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <svg x-show="showPassword" id="icon-open" class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.0012 5C7.52354 5 3.73326 7.94288 2.45898 12C3.73324 16.0571 7.52354 19 12.0012 19C16.4788 19 20.2691 16.0571 21.5434 12C20.2691 7.94291 16.4788 5 12.0012 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    </div>
                </div>



                <div class="flex  justify-end space-x-4">

                <a href="{{ route('company.index') }}"
                class=" px-2 py-2 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                ❌ Cancel
                </a>
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Add Company
                </button>

                </div>
            </form>
        </div>

        </div>

</x-app-layout>
