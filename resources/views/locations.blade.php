<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blocked countries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-3 gap-4">
                        <script type="text/javascript">
                            function changeLocation(event, form) {
                                var checkbox = form.querySelector('input[type="checkbox"]');
                                checkbox.checked = checkbox.value === '0' ? false : true;

                                form.submit();

                                return false;
                            }
                        </script>
                        @foreach ($locations as $location)
                        <form class="py-2 px-4 font-semibold rounded-lg shadow-md cursor-pointer {!! empty($location->danger) ? 'bg-gray-200' : 'bg-red-500 text-white' !!}" action="{{ route('location.update', ['location' => $location->id]) }}" method="post" onclick="changeLocation(event, this);">
                            @csrf
                            <label>
                                <input class="mr-4" type="checkbox" name="danger" value="{!! empty($location->danger) ? '1' : '0' !!}" {!! empty($location->danger) ? '' : 'checked="checked"' !!} />
                                <span>{{ $location->name }}</span>
                            </label>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>