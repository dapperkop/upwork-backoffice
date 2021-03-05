<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistics by managers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('components.stats-global')
                    <div class="search">
                        <form class="filters mb-5">
                            <div class="inline-block mr-5 mb-5">
                                <label for="from" class="inline-block">{{ __('From') }}</label>
                                <input type="date" id="from" name="from" value="{{ $from->format('Y-m-d') }}" />
                            </div>
                            <div class="inline-block mr-5 mb-5">
                                <label for="to" class="inline-block">{{ __('To') }}</label>
                                <input type="date" id="to" name="to" value="{{ $to->format('Y-m-d') }}" />
                            </div>
                            <button class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit">{{ __('Apply') }}</button>
                        </form>
                        @include('components.stats-filter')
                        <table class="w-full table-auto mb-5">
                            <thead>
                                <tr>
                                    <th class="w-2/3 border py-2 px-4 text-left">{{ __('Manager') }}</th>
                                    <th class="w-1/3 border py-2 px-4 text-left">{{ __('Sent proposals') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($managers as $manager)
                                <tr>
                                    <td class="border py-2 px-4">
                                        <a class="hover:underline" href="{{ route('manager.show', [ 'manager' => $manager->id, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d') ]) }}">{{ $manager->fullname }}</a>
                                    </td>
                                    <td class="border py-2 px-4">{{ $manager->proposals_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="border py-2 px-4 text-center" colspan="2">{{ __('No managers') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $managers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>