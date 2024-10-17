<form wire:submit="update" x-data="imageUpload"
    x-init='() => {
            uploadLimit = {{ $this->uploadLimit }};
            maxFileSize = {{ $this->maxFileSize }}; 
            images_preview = {{ json_encode($this->images_preview) }}; 
        }'>
    <div class="space-y-3">
        <div>
            <x-input-label for="title" :value="__('Name')" />
            <x-text-input id="title" type="text" class="mt-1 block w-full" wire:model="title" />
            @error('title')
                <x-input-error :messages="$message" class="mt-2" />
            @enderror
        </div>
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-textarea id="description" row="6" wire:model="description" class="mt-1 block w-full" required
                autofocus />
            @error('description')
                <x-input-error :messages="$message" class="mt-2" />
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="lat" :value="__('Location')" />
                <x-paragraf class="text-xs">{{ __('Place the pointer at your parking spot.') }}</x-paragraf>
                <input id="lat" type="hidden" class="mt-1 block w-full" wire:model="lat" required />
                <input id="lng" type="hidden" class="mt-1 block w-full" wire:model="lng" required />
                <div wire:ignore id="linkEditMap" class="w-full h-[25vh] rounded-lg mt-1"></div>
                @error('lng')
                    <x-input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            @if (isset($property))
                <div>
                    <x-input-label :value="__('Amenities')" />
                    <div class="grid grid-cols-1 gap-4">
                    <x-paragraf class="text-xs">{{ __('Mark amenities at the staging area.') }}</x-paragraf>
                        @foreach ($property as $key => $item)
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
                                <span class="block font-medium text-sm text-slate-500">{{ $item['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div>
            <div class="flex justify-between gap-2 mt-10">
                <div class="flex w-full basis-3/5">
                    <div>
                        <x-input-label :value="__('Gallery')" />
                        <span
                            class="text-xs text-slate-600">{{ __('Maximum :number photos', ['number' => $uploadLimit]) }}</span>
                    </div>
                </div>

                <button title="{{ __('Add a photo') }}" x-ref="imageButton"
                    :disabled="uploading || images.length >= uploadLimit"
                    class="flex w-full basis-2/5 items-center justify-center p-1.5 rounded-lg border dark:border-transparent border-slate-200 dark:bg-slate-800 bg-slate-50 text-sm dark:text-slate-400 text-slate-600 hover:text-teal-500 dark:hover:bg-slate-700 hover:bg-slate-100"
                    :class="{ 'cursor-not-allowed text-teal-500': uploading || images.length >= uploadLimit }">
                    <template x-if="uploading">
                        <div class="cursor-wait inline-flex items-center gap-2 whitespace-nowrap"
                            :disabled="uploading || images.length >= uploadLimit">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                class="size-5 animate-spin motion-reduce:animate-none fill-neutral-100 dark:fill-black">
                                <path opacity="0.25"
                                    d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" />
                                <path
                                    d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
                            </svg>
                            {{ __('Uploading') }}
                        </div>
                    </template>
                    <template x-if="!uploading">
                        <div class="inline-flex items-center gap-2 whitespace-nowrap"
                            :disabled="uploading || images.length >= uploadLimit">
                            <x-heroicon-o-photo class="size-4" />
                            {{ __('Add a photo') }}
                        </div>
                    </template>
                </button>
            </div>
            <input class="hidden" type="file" x-ref="imageInput" multiple accept="image/*" />
            <input class="hidden" type="file" x-ref="imageUpload" multiple accept="image/*" wire:model="images" />

            <ul>
                <template x-for="(error, index) in errors_upload" :key="index">
                    <li class="py-2 text-sm text-red-600 w-full"><span x-text="error"></span></li>
                </template>
            </ul>

            <div x-show="images_preview.length > 0" class="relative mt-2 flex h-20 flex-wrap gap-2">
                <template x-for="(image, index) in images_preview" :key="index">
                    <div class="relative h-20 w-20">
                        <img :src="image.path" :alt="image.originalName" x-on:click="createMarkdownImage(index)"
                            title="Reinsert the image" class="h-full w-full rounded-lg object-cover cursor-pointer" />
                        <button @click="removeImage($event, index)"
                            class="absolute top-0.5 right-0.5 p-1 rounded-md dark:bg-slate-800 bg-slate-200 bg-opacity-75 dark:text-slate-400 text-slate-600 hover:text-teal-500">
                            <x-icons.close class="size-4" />
                        </button>
                    </div>
                </template>
            </div>
        </div>



        <div class="flex items-center gap-4 border-t border-t-slate-600 pt-3">
            <x-primary-colorless-button class="text-teal-500 border-teal-500" type="submit">
                {{ __($linkId > 0 ? __('Save') : __('Add')) }}
            </x-primary-colorless-button>
            <button x-on:click="showLinksForm = false" type="button"
                class="dark:text-slate-400 text-slate-600 dark:hover:text-slate-600 hover:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>
</form>
@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet" />
@endpush
@push('scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>
    <script>
        window.addEventListener('linkEditMap', e => {

           // var images = @this.images;
            mapboxgl.accessToken = '{{ $accessToken }}';
            const map = new mapboxgl.Map({
                container: 'linkEditMap',
                style: 'mapbox://styles/mapbox/standard',
                center: [{{ $lng }}, {{ $lat }}],
                zoom: 5,
                attributionControl: false
            });
            map.on('load', function() {
                map.resize();
            });

            map.doubleClickZoom.disable();

            const el = document.createElement('div');
            el.className = 'marker';
            el.style.backgroundImage = `url(/img/camping-location.png?t=666i66)`;
            el.style.width = `27px`;
            el.style.height = `40px`;
            el.style.backgroundSize = '100%';

            var marker = new mapboxgl.Marker({
                    element: el,
                    draggable: true,
                    anchor: 'bottom',
                }).setLngLat([{{ $lng }}, {{ $lat }}])
                .addTo(map);

            function onDragEnd() {
                var coord = marker.getLngLat();
                @this.set('lat', coord.lat);
                @this.set('lng', coord.lng);
            }

            marker.on('dragend', onDragEnd);

        });
    </script>
@endpush
