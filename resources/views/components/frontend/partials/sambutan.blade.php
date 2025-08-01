@php
    use Illuminate\Support\Str;
@endphp

<div class="mx-auto px-4">
    <div class="text-center mb-8" data-aos="fade-down">
        <h2 class="text-3xl font-bold">
            <span class="text-gradient">Sambutan Kepala Sekolah</span>
        </h2>
    </div>
    <div data-aos="fade-right"
        class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-6 w-full max-w-screen-xl mx-auto">
        <img src="{{ asset('storage/images/settings/' . get_setting('headmaster_photo')) }}" loading="lazy"
            class="max-w-sm rounded-lg border-4 border-white shadow-xl mx-auto" />
        <div class="text-center md:text-left w-full" data-aos="fade-left">
            <p>
                {!! Str::limit($sambutan->content, 800, '...') !!}
            </p>
            <h1 class="text-2xs font-bold">{{ get_setting('headmaster') }}</h1>
        </div>
    </div>

</div>
