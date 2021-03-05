<div class="mb-5 grid grid-cols-3 gap-4 text-xl text-indigo-900">
    <div class="py-2 px-4 rounded-lg shadow-md bg-green-200">
        <span class="block font-bold text-3xl">{{ @\Carbon\Carbon::now()->format('F') }}</span>
        <span class="block">{{ __('Proposals this month') }}: <span class="font-bold text-2xl">{{ $stats['proposalsThisMonth'] }}</span></span>
        <span class="block">{{ __('Proposals today') }}: <span class="font-bold">{{ $stats['proposalsToday'] }}</span></span>
    </div>
    <div class="py-2 px-4 rounded-lg shadow-md bg-yellow-200">
        <span class="block">
            {{ __('Most proposals this month') }}:
            <span class="font-bold text-3xl">
                @if (empty($stats['mostProposalsThisMonth']) || (!empty($stats['mostProposalsThisMonth']) && empty($stats['mostProposalsThisMonth']->proposals_count)))
                {{ '- (-)' }}
                @else
                {{ $stats['mostProposalsThisMonth']->fullname . ' (' . $stats['mostProposalsThisMonth']->proposals_count . ')' }}
                @endif
            </span>
        </span>
        <span class="block">
            {{ __('Most proposals today') }}:
            <span class="font-bold text-2xl">
                @if (empty($stats['mostProposalsToday']) || (!empty($stats['mostProposalsToday']) && empty($stats['mostProposalsToday']->proposals_count)))
                {{ '- (-)' }}
                @else
                {{ $stats['mostProposalsToday']->fullname . ' (' . $stats['mostProposalsToday']->proposals_count . ')' }}
                @endif
            </span>
        </span>
    </div>
</div>