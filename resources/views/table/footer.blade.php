<x-tables::row>
    <x-tables::cell>
        {{-- for the checkbox column --}}
    </x-tables::cell>

    @foreach ($columns as $column)
    <x-tables::cell wire:loading.remove.delay wire:target="{{ implode(',', \Filament\Tables\Table::LOADING_TARGETS) }}">
        @for ($i = 0; $i < count($calc_columns); $i++ ) @if ($column->getName() == ($calc_columns[$i]))
            <div class="filament-tables-column-wrapper">
                <div class="flex justify-start w-full px-4 py-2 filament-tables-text-column text-start">
                    <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                        <span class="font-medium text-violet-700">
                            {{ "Rp " . number_format($records->sum($calc_columns[$i]), 0, '.', ',') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
            @endfor
    </x-tables::cell>
    @endforeach
</x-tables::row>