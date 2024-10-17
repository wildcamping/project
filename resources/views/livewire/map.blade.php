<div class="relatve" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->


    <x-popover text="{{ __('Filters') }}" class="absolute z-10 top-20 left-4">
        <button type="button" @click="sidebarOpen = !sidebarOpen"
            class="dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900 inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none">
            <x-heroicon-o-adjustments-horizontal class="h-4 w-4" />
        </button>
    </x-popover>
    <div class="absolute top-0 left-0">
        <aside :class="sidebarOpen ? 'w-72' : 'w-0 '"
            class="relative z-10 dark:bg-slate-950 bg-slate-50 h-screen py-5 transition-all duration-300 flex flex-col text-slate-400 hover:text-slate-900 pt-18">
            <button type="button" @click="sidebarOpen = !sidebarOpen"
                class="absolute z-20 top-20 right-4 dark:text-slate-500 text-slate-400 dark:hover:text-slate-100 hover:text-slate-900 inline-flex items-center rounded-md border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out focus:outline-none">
                <x-heroicon-o-x-mark class="h-4 w-4" />
            </button>
            <div class="z-10 flex flex-col space-y-1 overflow-y-auto overflow-x-hidden scrollbar mt-12">
                <x-h2 class="m-3">{{ __('Filters') }}</x-h2>
                <ul>
                    <li class="group">
                        @if (isset($property))
                            <div class="p-3">
                                <div class="grid grid-cols-1 gap-4">
                                    <x-h3 class="">
                                        {{ __('Amenities') }}
                                    </x-h3>
                                    @foreach ($property as $key => $item)
                                        @if ($item['id'] == 'confirmed')
                                            <x-h3 class="mt-6">
                                                {{ __('Confirmed') }}
                                            </x-h3>
                                            <x-paragraf>{{ __('Users places of residence.') }}</x-paragraf>
                                            <div class="flex items-center space-x-4">
                                                <button type="button" wire:click="toggle({{ $key }})"
                                                    wire:model='property.{{ $key }}.value'
                                                    class="relative w-10 h-5 rounded-full transition-colors duration-300 focus:outline-none
                        {{ $item['value'] == 1 ? 'bg-teal-500' : 'bg-gray-300' }}">
                                                    <span
                                                        class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform duration-300 transform 
                            {{ $item['value'] == 1 ? 'translate-x-5' : 'translate-x-0' }}">
                                                    </span>
                                                </button>
                                                <span class="block text-sm text-slate-500">{{ $item['name'] }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-4">
                                                <button type="button" wire:click="toggle({{ $key }})"
                                                    wire:model='property.{{ $key }}.value'
                                                    class="relative w-10 h-5 rounded-full transition-colors duration-300 focus:outline-none
                        {{ $item['value'] == 1 ? 'bg-teal-500' : 'bg-gray-300' }}">
                                                    <span
                                                        class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform duration-300 transform 
                            {{ $item['value'] == 1 ? 'translate-x-5' : 'translate-x-0' }}">
                                                    </span>
                                                </button>
                                                <span class="block text-sm text-slate-500">{{ $item['name'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                </ul>
            </div>
        </aside>
        <!-- Sidebar -->
    </div>
    <div wire:ignore id="browseMap" class="w-full h-screen" @click="sidebarOpen = false"></div>
    <x-modal name="link-show-modal" maxWidth="xl">
        <div class="p-10">
            {!! $link_view !!}
        </div>
    </x-modal>
        <div class="absolute w-full z-10 bottom-4 flex flex-col items-center justify-center">
        <x-cta-button as="a" href="{{ route('profile.links.index')}}"
            class=" gap-3">
            <x-heroicon-o-plus class="h-4 w-4" />{{ __('Add place') }}
        </x-cta-button>
    </div>
</div>
@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet" />
    <style>
        .mapboxgl-popup {
            max-width: none !important;
        }

        .mapboxgl-popup-content {
            border-radius: 12px !important;
        }

        .mapboxgl-popup-close-button {
            font-size: 24px;
            right: 8px !important;
            top: 6px !important;
        }
    </style>
@endpush
@script
    <script>
        window.addEventListener('browseMap', e => {
            mapboxgl.accessToken = '{{ $accessToken }}';
            const map = new mapboxgl.Map({
                container: 'browseMap',
                style: 'mapbox://styles/mapbox/standard',
                center: [{{ $lng }}, {{ $lat }}],
                zoom: 5,
                attributionControl: false
            });

            var popup = new mapboxgl.Popup();

            map.on('load', function() {
                map.resize();

                map.addSource('links', {
                    type: 'geojson',
                    data: "{{ route('api-getlinks') }}"
                });

                map.loadImage(
                    '/img/camping-location.png?t=666i6',
                    (error, image) => {
                        if (error) throw error;
                        map.addImage('custom-marker', image);
                    });

                // Create layer from source
                map.addLayer({
                    'id': 'Link',
                    'type': 'symbol',
                    'source': 'links',
                    'layout': {
                        'icon-image': 'custom-marker',
                       // "icon-size": 0.6,
                        'icon-offset': [0, -20],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        // get the title name from the source's "title" property
                        'text-field': ['get', 'title'],
                        'text-font': [
                            'Open Sans Semibold',
                            'Arial Unicode MS Bold'
                        ],
                        'text-offset': [0, 1.25],
                        'text-anchor': 'top'
                    }
                });

            });
            window.addEventListener('filterMap', e => {
                var souece_data = JSON.parse(@this.souece_data)
                map.getSource('links').setData(souece_data)
            })

            map.on('click', 'Link', function(e) {
                $wire.dispatch('show-link', {
                    'link_id': e.features[0].properties.id
                });
                return false;
            });
        });
    </script>
@endscript
@push('scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>
    <script></script>
@endpush
