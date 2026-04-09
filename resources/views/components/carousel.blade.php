<div x-data="{ current: 0, slides: [
    { image: '/images/events/banner1.jpg', title: 'Spring Concert 2026' },
    { image: '/images/events/banner2.jpg', title: 'Tech Expo 2026' },
    { image: '/images/events/banner3.jpg', title: 'Charity Run 2026' }
] }" class="relative w-full h-[500px] mb-8 overflow-hidden rounded-lg shadow-lg">

    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="current === index" class="absolute inset-0 transition-opacity duration-500">
            <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
            <div class="absolute bottom-0 bg-black bg-opacity-50 text-white p-4 w-full text-center">
                <h2 x-text="slide.title" class="text-lg font-bold"></h2>
            </div>
        </div>
    </template>

    <!-- Controls -->
    <button @click="current = (current === 0) ? slides.length - 1 : current - 1"
        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-3 py-1 rounded">
        ‹
    </button>
    <button @click="current = (current === slides.length - 1) ? 0 : current + 1"
        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-3 py-1 rounded">
        ›
    </button>
</div>