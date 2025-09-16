<div>
    <!-- LC & Container Dropdowns -->
    <div class="position-relative">
        <div class="card mb-0 border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-center">

                    <!-- LC Dropdown -->
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="bi bi-bank text-primary"></i>
                                </div>
                            </div>
                            <select wire:model.live="selected_lc" class="form-control">
                                <option value="">-- Select LC --</option>
                                @foreach($lcs as $lc)
                                    <option value="{{ $lc->id }}">
                                        {{ $lc->lc_name }} ({{ $lc->lc_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Container Dropdown -->
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="bi bi-box text-primary"></i>
                                </div>
                            </div>
                            <select wire:model="selected_container" class="form-control">
                                <option value="">-- Select Container --</option>
                                @foreach($containers as $container)
                                    <option value="{{ $container->id }}">
                                        {{ $container->name }} ({{ $container->number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Product Search -->
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="bi bi-search text-primary"></i>
                                    </div>
                                </div>
                                <input wire:keydown.escape="resetQuery" wire:model.live.debounce.500ms="query" type="text" class="form-control" placeholder="Type product name or code....">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Product Search Results -->
    <div class="position-relative">
        <div wire:loading class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
            <div class="card-body shadow">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($query))
            <div wire:click="resetQuery" class="position-fixed w-100 h-100" style="z-index: 1; left:0; top:0;"></div>
            @if($search_results->isNotEmpty())
                <div class="card position-absolute mt-1" style="z-index: 2; left:0; right:0; border:0;">
                    <div class="card-body shadow">
                        <ul class="list-group list-group-flush">
                            @foreach($search_results as $result)
                                <li class="list-group-item list-group-item-action">
                                    <a wire:click.prevent="selectProduct({{ $result }})" href="#">
                                        {{ $result->product_name }} | {{ $result->product_code }}
                                    </a>
                                </li>
                            @endforeach
                            @if($search_results->count() >= $how_many)
                                <li class="list-group-item text-center">
                                    <a wire:click.prevent="loadMore" class="btn btn-primary btn-sm" href="#">
                                        Load More <i class="bi bi-arrow-down-circle"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @else
                <div class="card position-absolute mt-1 border-0" style="z-index: 1; left:0; right:0;">
                    <div class="card-body shadow">
                        <div class="alert alert-warning mb-0">
                            No Product Found....
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
