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

final class Create extends Component
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


    public string $description = '';
    public string $title = '';
    public string $lng = '19.4803'; 
    public string $lat = '52.0693';
    public string $accessToken = 'pk.eyJ1Ijoic2d3ZWIiLCJhIjoiY2pvdWx3NzBkMWI2ZDNwbnYybXBuYzRpbyJ9.6btRcFZ8esWvP9OUrTSzAA';
    public $property = [];

    // Method to toggle the switch state
    public function toggle($key)
    { 
        $this->property[$key]['value'] = ($this->property[$key]['value']==0?1:0);
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
                'images.*.image' => 'The file must be an image.',
                'images.*.mimes' => 'The image must be a file of type: :values.',
                'images.*.max' => 'The image may not be greater than :max kilobytes.',
                'images.*.dimensions' => 'The image must be less than :max_width x :max_height pixels.',
            ]
        );
    }

    public function mount()
    {
        $this->dispatch('linkCreateMap');
        $this->property = \App\Models\LinkProperty::select(['links_property.id','links_property.name','links_property_values.value'])->where('links_property.category', 1)
            ->leftJoin('links_property_values', function($join)
            {   
                $join->on('links_property_values.link_property_id', '=', 'links_property.id');
                $join->on('links_property_values.link_id', '=', DB::raw(0));
            })
            ->get()->toArray();
    }

    #[On('link.create')]
    public function create(): void
    {
        $this->dispatch('linkCreateMap', []); 
        $this->dispatch('open-modal', 'link-create-modal');
    }


    /**
     * Store a new link.
     */
    public function store(Request $request): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $user = type($request->user())->as(User::class);

        $validated = $this->validate([
            'description' => 'required|max:255',
            'title' => ['required', 'max:100'],
            'property' => [],
            'images_preview' => [],
            'lat' => ['required'],
            'lng' => ['required'],
        ]);

        Link::createModel($validated, $user->id);

        $this->deleteUnusedImages();
        $this->images_preview = [];
        $this->images = [];

        $this->dispatch('link.created');
        $this->dispatch('close-modal', 'link-create-modal');
        $this->dispatch('notification.created', message: 'Link created.');
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
                session()->push('images_link', $path);

                $this->dispatch(
                    'image.uploaded',
                    path: Storage::url($path),
                    originalName: $image->getClientOriginalName()
                );
                
                $this->images_preview[] = ['path' => Storage::url($path), 'name' => $image->getClientOriginalName()];
            } else { // @codeCoverageIgnoreStart
                $this->addError('images', 'The image could not be uploaded.');
                $this->dispatch('notification.created', message: 'The image could not be uploaded.');
            } // @codeCoverageIgnoreEnd
        });
//dd($this->images);
     //   $this->reset('images');
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
     * Delete any unused images.
     */
    private function deleteUnusedImages(): void
    {
        /** @var array<int, string> $images */
        $images = session()->get('images_link', []);
        
        $collect =  collect($this->images_preview);
      //  dd($collect->where('path', '/storage/images/links/2024-09-30/XAuvUaSuFRvyDuJJJzOCMFqBDE7shPUovcvWDYji.png')->isEmpty());

        collect($images)
            ->reject(fn (string $path): bool => $collect->where('path', $path)->isEmpty())
            ->each(fn (string $path): ?bool => $this->deleteImage($path));

        session()->forget('images_link');
    }

    /**
     * Handle the image deletes.
     */
    public function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
        $this->cleanSession($path);
    }

    /**
     * Clean the session of the given image path.
     */
    private function cleanSession(string $path): void
    {
        /** @var array<int, string> $images */
        $images = session()->get('images_link', []);

        $remainingImages = collect($images)
            ->reject(fn (string $imagePath): bool => $imagePath === $path);

        session()->put('images_link', $remainingImages->toArray());
    }

    /**
     * Render the component.
     */
    public function render(Request $request): View
    {
        return view('livewire.links.create', [
            'user' => $request->user(),
        ]);
    }
}
