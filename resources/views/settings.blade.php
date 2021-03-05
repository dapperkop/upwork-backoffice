<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('setting.update') }}" method="POST">
                        @csrf
                        @foreach ($settings as $setting)
                        <div class="mb-5">
                            <label class="inline-block w-1/3" for="setting_{{ $setting->key }}">{{ __($setting->label) }}</label>
                            <input class="w-1/3" type="text" id="setting_{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" />
                            @if ($errors->has($setting->key))
                            <span class="block text-xl text-red-500">{{ $errors->first($setting->key) }}</span>
                            @endif
                        </div>
                        @endforeach
                        <button class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit">{{ __('Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>