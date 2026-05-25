<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">
                Create a Gig
            </h2>
            <p class="mb-4">Post a Gig to find a developer</p>
        </header>

        <form method="POST" action="/" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="company" class="inline-block text-lg mb-2">Company Name</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="company"
                    value="{{ old('company') }}" />

                @error('company')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="title" class="inline-block text-lg mb-2">Gig Title</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="title"
                    value="{{ old('title') }}" placeholder="Example: Senior Laravel Developer" />

                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="location" class="inline-block text-lg mb-2">Gig Location</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="location"
                    value="{{ old('location') }}" placeholder="Example: Remote, Boston MA, etc" />

                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="inline-block text-lg mb-2">Contact Email</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="email"
                    value="{{ old('email') }}" />

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="website" class="inline-block text-lg mb-2">
                    Website/Application URL
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="website"
                    value="{{ old('website') }}" />

                @error('website')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tags" class="inline-block text-lg mb-2">
                    Tags (Comma Separated)
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="tags"
                    value="{{ old('tags') }}" placeholder="Example: Laravel, Backend, Postgres, etc" />

                @error('tags')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="logo" class="inline-block text-lg mb-2">Company Logo</label>

                <div x-data="{
                    previewUrl: '{{ asset('images/No_Image_Available.jpg') }}',
                    placeholder: '{{ asset('images/No_Image_Available.jpg') }}',
                    uploading: false,
                    async handleFile(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        this.uploading = true;
                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('upload_preset', 'devgigs_upload');
                        formData.append('folder', 'devgigs/logos');
                        const response = await fetch('https://api.cloudinary.com/v1_1/daufnw5dc/image/upload', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();
                        this.previewUrl = data.secure_url;
                        this.$refs.logoUrl.value = data.secure_url;
                        this.uploading = false;
                    },
                    removeLogo() {
                        this.previewUrl = this.placeholder;
                        this.$refs.logoUrl.value = '';
                        this.$refs.logoInput.value = '';
                    }
                }">
                    <img :src="previewUrl" alt="Logo preview"
                        class="mb-3 w-48 h-48 object-contain border border-gray-200 rounded" />

                    <div x-show="uploading" class="text-sm text-gray-500 mb-2">Uploading...</div>

                    <input type="file" class="border border-gray-200 rounded p-2 w-full" x-ref="logoInput"
                        @change="handleFile" accept="image/*" />

                    <input type="hidden" name="logo_url" x-ref="logoUrl" value="" />

                    <button type="button" class="mt-2 text-sm text-red-500 hover:text-red-700" @click="removeLogo">
                        Remove logo
                    </button>

                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="inline-block text-lg mb-2">
                    Gig Description
                </label>
                <textarea class="border border-gray-200 rounded p-2 w-full" name="description" rows="10"
                    placeholder="Include tasks, requirements, salary, etc">{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button class="bg-gigs text-white rounded py-2 px-4 hover:bg-black">
                    Create Gig
                </button>

                <a href="/" class="text-black ml-4"> Back </a>
            </div>
        </form>
    </x-card>
</x-layout>
