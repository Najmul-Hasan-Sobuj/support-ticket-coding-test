<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket') }}: {{ $ticket->subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Ticket Description -->
                    <h3 class="font-bold text-lg">{{ __('Description') }}</h3>
                    <p class="mb-4">{{ $ticket->description }}</p>

                    <!-- Responses Section -->
                    <h3 class="font-bold text-lg mt-6">{{ __('Responses') }}</h3>
                    @if ($ticket->responses->isEmpty())
                        <p class="text-gray-600">{{ __('No responses yet.') }}</p>
                    @else
                        @foreach ($ticket->responses as $response)
                            <div class="border rounded p-3 my-2 bg-gray-50">
                                <p class="text-sm">
                                    <strong>{{ $response->user->name }}:</strong> {{ $response->message }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $response->created_at->format('F j, Y, g:i a') }}
                                </p>

                                @if ($ticket->status === 'open')
                                    <!-- Delete Button -->
                                    <form action="{{ route('tickets.responses.destroy', [$ticket, $response]) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs ml-2"
                                            onclick="return confirm('Are you sure you want to delete this response?');">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <!-- Add Response Section -->
                    <h3 class="font-bold text-lg mt-6">{{ __('Add Response') }}</h3>
                    @if ($ticket->status === 'closed')
                        <p class="text-red-600">{{ __('This ticket is closed. You cannot add more responses.') }}</p>
                    @else
                        <form method="POST" action="{{ route('tickets.responses.store', $ticket) }}">
                            @csrf
                            <x-text-input name="message" required class="mt-1 block w-full"
                                placeholder="{{ __('Type your response...') }}" />
                            <x-input-error :messages="$errors->get('message')" />
                            <div class="mt-4">
                                <x-primary-button>
                                    {{ __('Submit Response') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif

                    <!-- Close Ticket Button -->
                    <div class="mt-6">
                        @if ($ticket->status !== 'closed')
                            <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-primary-button class="bg-red-600 hover:bg-red-500">
                                    {{ __('Close Ticket') }}
                                </x-primary-button>
                            </form>
                        @else
                            <p class="text-gray-500">{{ __('This ticket is already closed.') }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('tickets.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
