<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">
                Edit a Gig
            </h2>
            <p class="mb-4">Edit: {{ $gig->title }}</p>
        </header>

        <form method="POST" action="/{{ $gig->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label for="company" class="inline-block text-lg mb-2">Company Name</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="company"
                    value="{{ $gig->company }}" />

                @error('company')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="title" class="inline-block text-lg mb-2">Gig Title</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="title"
                    value="{{ $gig->title }}" placeholder="Example: Senior Laravel Developer" />

                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="location" class="inline-block text-lg mb-2">Gig Location</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="location"
                    value="{{ $gig->location }}" placeholder="Example: Remote, Boston MA, etc" />

                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="inline-block text-lg mb-2">Contact Email</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="email"
                    value="{{ $gig->email }}" />

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="website" class="inline-block text-lg mb-2">
                    Website/Application URL
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="website"
                    value="{{ $gig->website }}" />

                @error('website')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tags" class="inline-block text-lg mb-2">
                    Tags (Comma Separated)
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="tags"
                    value="{{ $gig->tags }}" placeholder="Example: Laravel, Backend, Postgres, etc" />

                @error('tags')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6"
                x-data="{
                    previewUrl: '{{ $gig->logo ? asset('storage/' . $gig->logo) : asset('images/No_Image_Available.jpg') }}',
                    placeholder: '{{ asset('images/No_Image_Available.jpg') }}',
                    removeLogo: false,
                    handleFile(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                            this.removeLogo = false;
                        }
                    },
                    handleRemove() {
                        this.previewUrl = this.placeholder;
                        this.removeLogo = true;
                        this.$refs.logoInput.value = '';
                    }
                }"
            >
                <label for="logo" class="inline-block text-lg mb-2">Company Logo</label>

                <img :src="previewUrl" alt="Logo preview" class="mb-3 w-48 h-48 object-contain border border-gray-200 rounded" />

                <input
                    type="file"
                    class="border border-gray-200 rounded p-2 w-full"
                    name="logo"
                    x-ref="logoInput"
                    @change="handleFile"
                />

                <input type="hidden" name="remove_logo" :value="removeLogo ? '1' : '0'" />

                <button
                    type="button"
                    class="mt-2 text-sm text-red-500 hover:text-red-700"
                    @click="handleRemove"
                >
                    Remove logo
                </button>

                @error('logo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="inline-block text-lg mb-2">
                    Gig Description
                </label>
                <textarea class="border border-gray-200 rounded p-2 w-full" name="description" rows="10"
                    placeholder="Include tasks, requirements, salary, etc">{{ $gig->description }}</textarea>

                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button class="bg-gigs text-white rounded py-2 px-4 hover:bg-black">
                    Edit Gig
                </button>

                <a href="/" class="text-black ml-4"> Back </a>
            </div>
        </form>
    </x-card>
    <x-footer bgColor='bg-gigs' buttonText="Post a gig" buttonHref="/create" :showButton="true" />
</x-layout>
