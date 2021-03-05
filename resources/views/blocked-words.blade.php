<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blocked words') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-5">
                        <form class="create-form" action="{{ route('blocked-word.create') }}" method="POST">
                            @csrf
                            <span class="block text-3xl text-green-500">{{ __('Create new blocked words') }}</span>
                            <div class="inline-block mr-5">
                                <label for="blocked-words" class="inline-block">{{ __('Blocked words') }}</label>
                                <input type="text" id="blocked-words" name="blocked-words" value="" />
                            </div>
                            <button class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit">{{ __('Create') }}</button>
                            @if ($errors->has('blocked-words'))
                            <span class="block text-xl text-red-500">{{ $errors->first('blocked-words') }}</span>
                            @endif
                        </form>
                    </div>
                    <hr class="mb-5" />
                    <div class="search">
                        <form class="filters mb-5">
                            <div class="inline-block mr-5">
                                <label for="query" class="inline-block">{{ __('Search query') }}</label>
                                <input type="text" id="query" name="query" value="{!! empty($query) ? '' : $query !!}" />
                            </div>
                            <button class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit">{{ __('Apply') }}</button>
                        </form>
                        @forelse ($blockedWords as $blockedWord)
                        <form class="inline-block py-2 px-4 bg-gray-100 font-semibold rounded-lg shadow-md mr-5 mb-5" action="{{ route('blocked-word.delete', ['blockedWord' => $blockedWord->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="delete" />
                            <span class="mr-2">{{ $blockedWord->content }}</span>
                            <button class="py-1 px-2 bg-red-500 text-white font-bold rounded-lg shadow-md focus:outline-none" type="submit">&Cross;</button>
                        </form>
                        @empty
                        <span>{{ __('No blocked words') }}</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>