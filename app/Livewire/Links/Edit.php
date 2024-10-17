<?php

declare(strict_types=1);

namespace App\Livewire\Links;

use App\Models\User;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use App\Rules\MaxUploads;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Imagick;
use Closure;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\DB;

final class Edit extends Component
{
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
    /**
     * The component's link ID.
     */
    #[Locked]
    public ?int $linkId = null;

    public string $description = '';
    public string $title = '';
    public string $lat = '';
    public string $lng = '';
    public string $accessToken = 'pk.eyJ1Ijoic2d3ZWIiLCJhIjoiY2pvdWx3NzBkMWI2ZDNwbnYybXBuYzRpbyJ9.6btRcFZ8esWvP9OUrTSzAA';
    public $property = [];

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
            'title.required' => 'Opis jest wymagany.',
            'title.max' => 'Maksymalnie :max znaków.',
        ];
    }

    /**
     * Store a new link.
     *
     * @throws AuthorizationException
     */
    public function update(Request $request): void
    {
        $validated = $this->validate([
            'description' => 'required|max:255',
            'title' => ['required', 'max:100',],
            'property' => [],
            'lat' => ['required'],
            'lng' => ['required'],
        ]);

        $link = Link::findOrFail($this->linkId);

        $this->authorize('update', $link);

        if ($link->lat !== $validated['lat']) {
            $validated['click_count'] = 0;
        }

        $link->updateModel($validated);

        $this->dispatch('link.updated');
        $this->dispatch('close-modal', 'link-edit-modal');
        $this->dispatch('notification.created', message: 'Link updated.');
    }

    /**
     * Initialize the edit link form component.
     */
    #[On('link.edit')]
    public function edit(Link $link): void
    {
        //abort_unless($link->is_visible, 404);
        $this->authorize('edit', $link);

        $this->linkId = $link->id;
        $this->description = $link->description;
        $this->title = $link->title;
        $this->lat = $link->lat;
        $this->lng = $link->lng;

        $this->property = \App\Models\LinkProperty::select(['links_property.id', 'links_property.name', 'links_property_values.value'])->where('links_property.category', 1)
            ->leftJoin('links_property_values', function ($join) {
                $join->on('links_property_values.link_property_id', '=', 'links_property.id');
                $join->on('links_property_values.link_id', '=', DB::raw(($this->linkId > 0 ? $this->linkId : 0)));
            })
            ->orderBy('links_property.name', 'ASC')
            ->get()->toArray();

        if ($this->linkId > 0) {
            $this->images_preview = $link->images()->get(['url AS path', 'name AS originalName', 'id'])->toArray();
        }

        $this->dispatch('linkEditMap', []);
        $this->dispatch('open-modal', 'link-edit-modal');
    }

    
    /**
     * Render the component.
     */
    public function render(Request $request): View
    {
        return view('livewire.links.edit', [
            'user' => $request->user(),
        ]);
    }

    // Method to toggle the switch state
    public function toggle($key)
    {
        $this->property[$key]['value'] = ($this->property[$key]['value'] == 0 ? 1 : 0);
    }

    /**
     * The updated lifecycle hook.
     */
    public function updated(mixed $property): void
    {
        if ($property === 'images') {
            $this->runImageValidation();
            $this->uploadImages();
        }
    }

    /**
     * Run image validation rules.
     */
    public function runImageValidation(): void
    {
        $this->validate(
            rules: [
                'images' => [
                    'bail',
                    new MaxUploads($this->uploadLimit),
                ],
                'images.*' => [
                    File::image()
                        ->types(['jpeg', 'png', 'gif', 'webp', 'jpg'])
                        ->max($this->maxFileSize)
                        ->dimensions(
                            Rule::dimensions()->maxWidth(4000)->maxHeight(4000)
                        ),

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
            ]
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

                $this->dispatch(
                    'image.uploaded',
                    path: Storage::url($path),
                    originalName: $image->getClientOriginalName()
                );

                $image_tmp = \App\Models\LinkImages::Create(
                    ['link_id' => $this->linkId, 'name' => $image->getClientOriginalName(), 'url' => Storage::url($path)]
                );

                $this->images_preview[] = ['path' => Storage::url($path), 'name' => $image->getClientOriginalName(), 'id' => $image_tmp->id];
            } else { // @codeCoverageIgnoreStart
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
        $imagick = new Imagick($imagePath);

        if ($imagick->getNumberImages() > 1) {
            $imagick = $imagick->coalesceImages();

            foreach ($imagick as $frame) {
                $frame->resizeImage(1000, 1000, Imagick::FILTER_LANCZOS, 1, true);
                $frame->stripImage();
                $frame->setImageCompressionQuality(80);
            }

            $imagick = $imagick->deconstructImages();
            $imagick->writeImages($imagePath, true);
        } else {
            $imagick->resizeImage(1000, 1000, Imagick::FILTER_LANCZOS, 1, true);
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
}
