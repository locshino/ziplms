@php
    use Filament\Support\Facades\FilamentAsset;
    use Studio15\FilamentTree\FilamentTreeServiceProvider;
@endphp

<x-filament-panels::page>
    <div x-data="{}"
         x-load-css="[@js(FilamentAsset::getStyleHref('filament-tree', package: FilamentTreeServiceProvider::$name))]"
         x-load-js="[@js(FilamentAsset::getScriptSrc('sortable', package: FilamentTreeServiceProvider::$name)), @js(FilamentAsset::getScriptSrc('filament-tree', package: FilamentTreeServiceProvider::$name))]">

        <livewire:filament-tree::header :component="static::class"/>

        <div id="studio15-tree" class="bg-white dark:bg-white/5 rounded-xl shadow-sm ring-1 ring-gray-950/5">
            <nav class="text-base lg:text-sm pe-4 pt-4">
                <ul class="studio15-tree" data-id>
                    @forelse($tree as $row)
                        <livewire:filament-tree::row
                                :component="static::class"
                                :row="$row"
                                :row-id="$row->getKey()"
                                :key="Studio15\FilamentTree\Helpers::treeKey($row)"
                        />
                    @empty
                        <li class="p-4 text-center">
                            <div class="pb-4">
                                @lang('filament-tree::translations.empty_tree')
                            </div>
                        </li>
                    @endforelse
                </ul>
            </nav>
        </div>

        <livewire:filament-tree::footer :component="static::class"/>
        <livewire:filament-tree::move :component="static::class" />
    </div>
</x-filament-panels::page>

