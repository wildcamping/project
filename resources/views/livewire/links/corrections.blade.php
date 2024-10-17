<?php

use Livewire\Volt\Component;

use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use App\Rules\MaxUploads;
use Imagick as Imk;

new class extends Component {
    use WithFileUploads;

    /**
     * The component's link ID.
     */
    #[Locked]
    public $link;
    #[Locked]
    public $correction;

    use WithFileUploads;

    /**
     * Max number of images allowed.
     */
    #[Locked]
    public int $uploadLimit = 3;

    /**
     * Max file size allowed.
     */
    #[Locked]
    public int $maxFileSize = 1024 * 8;
    /**
     * Uploaded images.
     *
     * @var array<int, UploadedFile>
     */

    public array $images = [];
    public array $images_preview = [];

    public string $description = '';

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'description.required' => 'Tytuł jest wymagany.',
            'description.max' => 'Maksymalnie :max znaków.',
        ];
    }

    public function mount()
    {
    }

    #[On('link.corrections_form')]
    public function corrections_form(Link $link): void
    {
        $this->link = $link;
        $this->dispatch('open-modal', 'link-corrections-modal');
        //  $this->dispatch('close-modal', 'link-edit-modal');
    }

    public function cancelCorrecttion()
    {
        if (!empty($this->correction)) $this->correction->delete();
        foreach($this->images_preview as $item){
            $this->deleteImage($item['path'], $item['id'], 1);
        }
        
        $this->dispatch('close-modal', 'link-corrections-modal');
    }

    /**
     * The updated lifecycle hook.
     */
    public function updated(mixed $property): void
    {
        if (empty($this->correction)) {
            $this->correction = \App\Models\LinkCorrections::Create(['link_id' => $this->link->id, 'user_id' => auth()->id(), 'is_confirm' => false]);
        }

        if ($property === 'images') {
            $this->runImageValidation();
            $this->uploadImages();
        }
    }

    /**
     * Store a new link.
     *
     * @throws AuthorizationException
     */
    public function store(Request $request): void
    {
        $validated = $this->validate([
            'description' => 'required|max:255',
        ]);
        $this->dispatch('link.refresh.'.$this->link->id); 
        $this->correction->update($validated);
        $this->dispatch('close-modal', 'link-corrections-modal');
        $this->dispatch('notification.created', message: __('Corrections add.'));
    }

    /**
     * Run image validation rules.
     */
    public function runImageValidation(): void
    {
        $this->validate(
            rules: [
                'images' => ['bail', new MaxUploads($this->uploadLimit)],
                'images.*' => [
                    File::image()
                        ->types(['jpeg', 'png', 'gif', 'webp', 'jpg'])
                        ->max($this->maxFileSize)
                        ->dimensions(Rule::dimensions()->maxWidth(4000)->maxHeight(4000)),

                    static function (string $attribute, mixed $value, Closure $fail): void {
                        /** @var UploadedFile $value */
                        $dimensions = $value->dimensions();
                        if (is_array($dimensions)) {
                            [$width, $height] = $dimensions;
                            $aspectRatio = $width / $height;
                            $maxAspectRatio = 2 / 5;
                            if ($aspectRatio < $maxAspectRatio) {
                                $fail(__('The image aspect ratio must be less than 2/5.'));
                            }
                        } else {
                            $fail(__('The image aspect ratio could not be determined.'));
                        }
                    },
                ],
            ],
            messages: [
                'images.*.image' => 'Plik musi być obrazem.',
                'images.*.mimes' => 'Obraz musi być plikiem typu: :values.',
                'images.*.max' => 'Obraz nie może być większy niż :max kilobajtów.',
                'images.*.dimensions' => 'Obraz musi być mniejszy niż :max_width x :max_height pixeli.',
            ],
        );
    }

    /**
     * Handle the image uploads.
     */
    public function uploadImages(): void
    {
        collect($this->images)->each(function (UploadedFile $image): void {
            $today = now()->format('Y-m-d');

            /** @var string $path */
            $path = $image->store("images/links/{$today}", 'public');
            $this->optimizeImage($path);

            if ($path) {
                $this->dispatch('image.uploaded', path: Storage::url($path), originalName: $image->getClientOriginalName());

                $image_tmp = \App\Models\LinkImages::Create([
                    'link_id' => $this->link->id,
                    'link_corrections_id' => $this->correction->id,
                    'name' => $image->getClientOriginalName(),
                    'url' => Storage::url($path),
                    'is_confirm' => false,
                ]);

                $this->images_preview[] = ['path' => Storage::url($path), 'name' => $image->getClientOriginalName(), 'id' => $image_tmp->id];
            } else {
                // @codeCoverageIgnoreStart
                $this->addError('images', 'Nie można przesłać obrazu.');
                $this->dispatch('notification.created', message: 'Nie można przesłać obrazu.');
            } // @codeCoverageIgnoreEnd
        });

        $this->reset('images');
    }

    /**
     * Optimize the images.
     */
    public function optimizeImage(string $path): void
    {
        $imagePath = Storage::disk('public')->path($path);
        $imagick = new Imk($imagePath);

        if ($imagick->getNumberImages() > 1) {
            $imagick = $imagick->coalesceImages();

            foreach ($imagick as $frame) {
                $frame->resizeImage(1000, 1000, Imk::FILTER_LANCZOS, 1, true);
                $frame->stripImage();
                $frame->setImageCompressionQuality(80);
            }

            $imagick = $imagick->deconstructImages();
            $imagick->writeImages($imagePath, true);
        } else {
            $imagick->resizeImage(1000, 1000, Imk::FILTER_LANCZOS, 1, true);
            $imagick->stripImage();
            $imagick->setImageCompressionQuality(80);
            $imagick->writeImage($imagePath);
        }

        $imagick->clear();
        $imagick->destroy();
    }

    /**
     * Handle the image deletes.
     */
    public function deleteImage(string $path, int $id, int $index): void
    {
        $this->reset('images');
        if ($id > 0 && \App\Models\LinkImages::where('id', $id)->delete()) {
            Storage::disk('public')->delete($path);
            unset($this->images_preview[$index]);
        }
    }
}; ?>

<form wire:submit="store" x-data="imageUpload"
    x-init='() => {
            uploadLimit = {{ $this->uploadLimit }};
            maxFileSize = {{ $this->maxFileSize }}; 
            images_preview = {{ json_encode($this->images_preview) }}; 
        }'>
    <div class="p-10">
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-textarea id="description" row="6" class="mt-1 block w-full" wire:model="description" required />
            @error('description')
                <x-input-error :messages="$message" class="mt-2" />
            @enderror
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
        <div class="flex items-center justify-end gap-4 border-t dark:border-slate-900 border-slate-200 mt-3 pt-3">
            <x-primary-colorless-button class="text-teal-500 border-teal-500" type="submit">
                {{ __('Add') }}
            </x-primary-colorless-button>
            <button wire:click="cancelCorrecttion" type="button"
                class="dark:text-slate-400 text-slate-600 dark:hover:text-slate-600 hover:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>
</form>
