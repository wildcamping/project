const imageUpload = () => ({
    uploading: false,
    uploadLimit: null,
    maxFileSize: null,
    images: [],
    images_preview: [],
    errors_upload: [],

    init() {
        if (this.$refs.imageButton !== undefined) {
            this.setupListeners();
        }
    },

    setupListeners() {
        this.$refs.imageButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.$refs.imageInput.click();
        });

        this.$refs.imageInput.addEventListener('change', (event) => {
            this.checkFileSize(event.target.files);
            event.target.value = '';
        });

        Livewire.on('image.uploaded', (event) => {
            this.createMarkdownImage(event);
        });

        Livewire.on('question.created', () => {
            this.images = [];
            this.errors_upload = [];
        });

        Livewire.hook('morph.updated', ({el, component}) => {
            if (this.$el === el) {
                const errors_upload = component.snapshot.memo.errors;
                this.addErrors(errors_upload);
            }
        });
    },

    handleImagePaste(event) {
        // if no files, handle paste event as normal
        if (event.clipboardData.files.length === 0) {
            return;
        }

        // don't allow multiple uploads at once
        if(this.uploading) {
            return;
        }

        // build out the file list from the clipboard, filtering only for images.
        const dataTransfer = new DataTransfer();
        for (const item of event.clipboardData.files) {
            if (!item.type.startsWith('image/')) {
                this.addErrors(["@lang('Załącznik musi być obrazem.')"]);
                return;
            }

            dataTransfer.items.add(item);
        }

        this.checkFileSize(dataTransfer.files);
    },

    addErrors(errors_upload) {
        this.$nextTick(() => {
            const incomingErrors = Object.values(errors_upload).flat()
            const uniqueErrors = new Set([...this.errors_upload, ...incomingErrors]);
            this.errors_upload = Array.from(uniqueErrors);
            this.uploading = false;
        });
    },

    checkFileSize(files) {
        if (files.length) {
            this.errors_upload = [];
            Array.from(files).forEach((file) => {
                if ((file.size / 1024) > this.maxFileSize) {
                    this.addErrors([`Zdjecie nie może być większe niż ${this.maxFileSize} kilobajtów.`]);
                }
            });
            if (this.errors_upload.length === 0) {
                this.handleUploading(files);
            }
        }
    },

    handleUploading(files) {
        if ((files.length + this.images_preview.length) > this.uploadLimit) {
            this.addErrors([`Możesz dodać tylko ${this.uploadLimit} zdjęcia.`]);
        } else {
            this.uploading = true;
            this.$refs.imageUpload.files = files;
            this.$refs.imageUpload.dispatchEvent(new Event('change'));
        }
    },

    removeImage(event, index) {
        event.preventDefault();
        this.$wire.deleteImage(
            this.normalizePath(this.images_preview[index].path), this.images_preview[index].id, index
        );
        //this.removeMarkdownImage(index);
        this.images.splice(index, 1);
        this.images_preview.splice(index, 1);
    },

    createMarkdownImage(item) {
        let path, originalName;
        if (item instanceof Object) {
            ({path, originalName} = item);
        } else if (typeof item === 'number') {
            ({path, originalName} = this.images[item]);
        }
        this.uploading = false;
    },

    normalizePath(path) {
        return path.replace(/\/storage\//, '');
    }
})

export { imageUpload }
