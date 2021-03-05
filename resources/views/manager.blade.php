<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistics by ') . $manager->fullname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                        <div class="mb-5">
                            <span class="block">
                                <span class="text-3xl">{{ __('Total') }}</span>
                                <span class="text-2xl italic">{{ __('for selected period') }}:</span>
                                <span class="font-bold text-3xl">({{ $count }})</span>
                            </span>
                        </div>
                        <?php

                        $monthsThs  = '';
                        $daysThs    = '';
                        $countsTds  = '';

                        foreach ($dateStats as $year => $months) {
                            foreach ($months as $month => $days) {
                                $monthsThs .= '
                                        <th class="border py-2 px-4 text-center" colspan="' . count($days['data']) . '">' . __($days['month']) . ' (' . $year . ')</th>';

                                foreach ($days['data'] as $day => $count) {
                                    $daysThs .= '
                                        <th class="border py-2 px-4 text-center">' . $day . '</th>';

                                    $countsTds .= '
                                        <td class="border py-2 px-4 text-center">
                                            <a class="underline" href="' . route('manager.show', ['manager' => $manager->id, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), 'show' => $year . '-' . $month . '-' . $day]) . '">' . $count . '</a>
                                        </td>';
                                }
                            }
                        }

                        echo '
                        <div class="overflow-x-auto mb-5">
                            <table>
                                <thead>
                                    <tr>' . $monthsThs . '
                                    </tr>
                                    <tr>' . $daysThs . '
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>' . $countsTds . '
                                    </tr>
                                </tbody>
                            </table>
                        </div>';

                        ?>
                        @isset($proposals)
                        <table class="w-full table-auto mb-5">
                            <thead>
                                <tr>
                                    <th class="w-2/12 border py-2 px-4 text-center">
                                        @if ($show->format('Y') !== \Carbon\Carbon::now()->format('Y'))
                                        <span>{{ $show->format('Y') }}</span>
                                        @endif
                                        <span>{{ $show->format('F, d') }}</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="w-2/12 border py-2 px-4 text-left">{{ __('Title') }} / {{ __('Country') }} / {{ __('Created') }}</th>
                                    <th class="w-1/12 border py-2 px-4 text-left">{{ __('Total spent') }}</th>
                                    <th class="w-1/12 border py-2 px-4 text-left">{{ __('Client\'s budget') }}</th>
                                    <th class="w-4/12 border py-2 px-4 text-left">{{ __('Job description') }}</th>
                                    <th class="w-4/12 border py-2 px-4 text-left">{{ __('Manager\'s reply') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proposals as $proposal)
                                <tr class="{!! empty($proposal->location->danger) ? '' : 'bg-red-500 text-white' !!}">
                                    <td class="border py-2 px-4 align-top">
                                        <span class="block break-words">
                                            <a class="underline" href="{{ $proposal->url }}" target="_blank">{{ $proposal->title }}</a>
                                        </span>
                                        <span class="block font-semibold italic break-words">{{ $proposal->location->name }}</span>
                                        <span class="block">{{ $proposal->created_at->format('d.m.Y') }}</span>
                                    </td>
                                    <td class="border py-2 px-4 align-top">
                                        {{ number_format($proposal->total_charges_amount, 2) }} {{ $proposal->total_charges_currency }}
                                    </td>
                                    <td class="border py-2 px-4 align-top">
                                        {{ number_format($proposal->budget_amount, 2) }} {{ $proposal->budget_currency }}
                                    </td>
                                    <td class="border py-2 px-4 align-top">
                                        <div class="overflow-y-auto h-32 break-all">{!! nl2br($proposal->description) !!}</div>
                                    </td>
                                    <td class="border py-2 px-4 align-top">
                                        <div class="overflow-y-auto h-32 break-all">{!! nl2br($proposal->reply) !!}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="border py-2 px-4 text-center" colspan="5">{{ __('No proposals') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $proposals->links() }}
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>