<x-app-layout>
    <x-card>
        <div class="row">
            <div class="col-7">
                <div class="search mb-2">
                    <div class="search__inner tree-search">
                        <input id="treesearch" type="search" class="form-control search__text"
                            placeholder="@localize('Tree Search')" autocomplete="off">
                        <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                    </div>
                </div>
                {{-- @include('accounts::coa.subblade.coatree') --}}
            </div>
            <div class="col-5">
                {{-- @include('accounts::coa.subblade.coafrom') --}}
            </div>
        </div>
    </x-card>
</x-app-layout>
