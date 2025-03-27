@props(['showcaseId' => null])

<div class="company-cam-container my-8">
    <h1 class="text-3xl font-bold text-center mb-6">Our Latest Projects</h1>

    <div data-showcase-id="{{ $showcaseId ?? config('services.companycam.showcase_id', '01057770-8ca0-47a5-a1dc-40128a20f85b') }}"
        id="companycam-showcase-root"></div>
</div>
