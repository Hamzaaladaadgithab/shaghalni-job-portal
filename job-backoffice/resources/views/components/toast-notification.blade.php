<!-- Success Message -->
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
        class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"
        role="alert">
        {{ session('success') }}
    </div>
@endif

<!-- Error Message -->
@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
        class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"
        role="alert">
        {{ session('error') }}
    </div>
@endif

<!-- Info Message -->
@if (session('info'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
        class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded"
        role="alert">
        {{ session('info') }}
    </div>
@endif
