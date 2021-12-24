<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="px-4 py-3 text-green-900 bg-green-100 border-t-4 border-green-500 rounded-b shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="w-6 h-6 mr-4 text-green-500 fill-current"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">The verification was successful.</p>
                            <p class="text-sm">An account has been created for
                                {{ auth()->user()->company->name }},
                                {{ auth()->user()->company->address }}, {{ auth()->user()->company->district }} -
                                {{ auth()->user()->company->postalcod }}
                                <em>(from the REGON database)</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
