<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="subject">{{ __('Subject') }}</x-input-label>
                            <x-text-input id="subject" name="subject" required autofocus />
                            <x-input-error :messages="$errors->get('subject')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description">{{ __('Description') }}</x-input-label>
                            <textarea id="description" name="description" class="mt-1 block w-full" required></textarea>
                            <x-input-error :messages="$errors->get('description')" />
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Submit Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
