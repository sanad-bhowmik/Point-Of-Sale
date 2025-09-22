<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;
use App\Models\Lc;
use App\Models\Container;

class SearchProduct extends Component
{
    public $query;
    public $search_results;
    public $how_many;

    public $selected_lc = '';
    public $selected_container = '';

    // These will be loaded in mount()
    public $lcs;
    public $containers;

    public function mount()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();

        // Load all LCs
        $this->lcs = Lc::select('id', 'lc_name', 'lc_number')->orderBy('lc_name')->get();

        // Load containers based on selected LC (if any)
        $this->updateContainers();
    }

    public function updatedSelectedLc($value)
    {
        $this->selected_lc = $value;
        $this->selected_container = ''; // Reset container when LC changes
        $this->updateContainers();
    }

    public function updatedSelectedContainer($value)
    {
        $this->selected_container = $value;
    }

    // private function updateContainers()
    // {
    //     if ($this->selected_lc) {
    //         $this->containers = Container::where('lc_id', $this->selected_lc)
    //             ->select('id', 'lc_id', 'name', 'number')
    //             ->orderBy('name')
    //             ->get();
    //     } else {
    //         $this->containers = collect([]);
    //     }
    // }
    private function updateContainers()
    {
        if ($this->selected_lc) {
            $this->containers = Container::where('lc_id', $this->selected_lc)
                ->whereIn('status', [2, 3])
                ->select('id', 'lc_id', 'name', 'number', 'status')
                ->orderBy('name')
                ->get();
        } else {
            $this->containers = collect([]);
        }
    }

    public function updatedQuery()
    {
        $this->search_results = Product::where('product_name', 'like', '%' . $this->query . '%')
            ->orWhere('product_code', 'like', '%' . $this->query . '%')
            ->take($this->how_many)
            ->get();
    }

    public function loadMore()
    {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function selectProduct($product)
    {
        // Pass product along with LC and Container IDs
        $this->dispatch('productSelected', [
            'product' => $product,
            'lc_id' => $this->selected_lc,
            'container_id' => $this->selected_container
        ]);
    }


    public function render()
    {
        return view('livewire.search-product');
    }
}
