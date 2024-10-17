<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Http\Controllers\Api\Map as ApiMap;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Link;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\On;

final class Map extends Component
{
    public string $lng = '19.4803';
    public string $lat = '52.0693';
    public string $accessToken;
    public string $link_view;
    public string $souece_data;

    //filters
    public $property = [];



    public function mount()
    {
        $this->accessToken = Config::get('app.map_api_key');
        $this->property = \App\Models\LinkProperty::select(['links_property.id', 'links_property.name', 'links_property_values.value'])->where('links_property.category', 1)
        ->leftJoin('links_property_values', function ($join) {
            $join->on('links_property_values.link_property_id', '=', 'links_property.id');
            $join->on('links_property_values.link_id', '=', DB::raw(0));
        })
    
            ->orderBy('links_property.name', 'ASC')
            ->get()->toArray();

        
        $this->property[] = ['id' => 'confirmed', 'name' => __('Confirmed'), 'value' => 0 ];

        $this->dispatch('browseMap');
    }

    // Method to toggle the switch state
    public function toggle($key)
    {
        $this->property[$key]['value'] = ($this->property[$key]['value'] == 0 ? 1 : 0);

        $api = new \App\Http\Controllers\Api\Map();

        $this->souece_data = $api->getLinks($this->property);// dd( $this->souece_data);
        $this->dispatch('filterMap');
    }



    #[On('show-link')]
    public function showLink($link_id): void
    {
        if ($link_id > 0) {
            $this->link_view =  view('api.modal_link', ['link_id' => $link_id])->render();
            // } else {
        }
        $this->dispatch('open-modal', 'link-show-modal');
    }



    /**
     * Render the component.
     */
    public function render(): View
    {


        return view('livewire.map', [
            'accessToken' => $this->accessToken,
        ]);
    }
}
