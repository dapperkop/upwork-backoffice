<div class="mb-5">
    <span class="block">
        <span class="text-3xl">{{ __('Total proposals') }}</span>
        <span class="text-2xl italic">{{ __('for selected date range') }}:</span>
        <span class="font-bold text-3xl">({{ $stats['totalProposals'] }})</span>
    </span>
    <span class="block">
        {{ __('Most common country') }}:
        <span class="font-bold text-2xl @if (!empty($stats['mostCommonCountry']) && !empty($stats['mostCommonCountry']->danger)) {{ 'text-red-500' }} @else {{ 'text-green-500' }} @endif">
            @if (empty($stats['mostCommonCountry']) || empty($stats['mostCommonCountry']->proposals_count))
            {{ '- (-)' }}
            @else
            {{ $stats['mostCommonCountry']->name . ' (' . $stats['mostCommonCountry']->proposals_count . ')' }}
            @endif
        </span>
    </span>
    <span class="block">
        {{ __('Most common blocked country') }}:
        <span class="font-bold text-1xl text-red-500">
            @if (empty($stats['mostCommonDangerousCountry']) || empty($stats['mostCommonDangerousCountry']->proposals_count))
            {{ '- (-)' }}
            @else
            {{ $stats['mostCommonDangerousCountry']->name . ' (' . $stats['mostCommonDangerousCountry']->proposals_count . ')' }}
            @endif
        </span>
    </span>
</div>