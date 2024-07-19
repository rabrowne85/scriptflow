<template>
    <div class="flex space-y-8 flex-col m-0">
        <div class="w-full p-6 m-0 min-h-52 sm:rounded-b max-h-80 overflow-y-auto border-x-4 border-rose-500 bg-rose-500/5 select-none cursor-pointer tracking-wide" ref="display" @wheel="adjustSpeed" @wheel.ctrl.exact="adjustSize" @click="() => isScrolling ? stopScrolling(false) : startScrolling()">
            <div v-html="compiledMarkdown" :class="['prose prose-slate prose-invert max-w-none text-center w-full pb-62', sizes[currentSize]]"></div>
        </div>

        <div class="flex items-center px-4 space-x-4 text-sm font-medium select-none">
            <LightBulbIcon class="size-5 text-rose-600 shrink-0" aria-hidden="true" />
            <p class="text-slate-500">Click the prompter to start/pause scrolling. Use your scroll wheel to jump back and forth. Hold <kbd class="rounded border bg-rose-500/10 border-rose-500/30 px-1.5 py-0.5 text-sm leading-5 text-rose-500 font-sans font-normal">Ctrl</kbd> whilst you scroll vertically to change the font size. Scroll horizontally to adjust the speed. </p>
        </div>

        <div class="relative">
            <textarea v-model="markdownText" @keyup="debouncedSave" placeholder="Write some markdown" class="block sm:rounded border-0 w-full bg-slate-900 py-1.5 text-slate-100 ring-1 sm:ring-offset-2 ring-offset-slate-900 ring-rose-500 placeholder:text-slate-500 focus:ring-2 focus:ring-offset-0 focus:ring-rose-600 sm:leading-6" rows="9"></textarea>
            <div>
                <transition
                    leave-active-class="transition ease-in duration-1000"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div class="absolute bottom-0 right-0 p-1 m-2 bg-rose-500/5 rounded border border-rose-500/25 text-rose-500/75 select-none inline-flex gap-x-1.5 items-center text-sm uppercase" v-show="saved">
                        <SparklesIcon class="size-5" />
                        Saved
                    </div>
                </transition>
            </div>
        </div>

        <div class="flex space-x-4 items-center justify-center">
            <button type="button" class="inline-flex items-center gap-x-2 rounded bg-rose-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                    v-if="!isScrolling"
            @click="startScrolling">
                <PlayIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                Start Scrolling
            </button>
            <template v-else>
                <button type="button" class="inline-flex items-center gap-x-2 rounded bg-rose-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                        @click.prevent="stopScrolling(false)">
                    <PauseIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                    Pause
                </button>
                <button type="button" class="inline-flex items-center gap-x-2 rounded bg-rose-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                        @click.prevent="stopScrolling(true)">
                    <StopIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                    Stop & Reset
                </button>
            </template>
        </div>

        <div class="flex items-center justify-evenly space-x-4">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-slate-400">Scroll Speed:</label>
                <div class="mt-2">
                    <input v-model="scrollSpeed" type="number" step="0.25" min="1" max="20" class="block w-40 text-right bg-slate-800 rounded border-0 py-1.5 text-slate-100 shadow-sm ring-1 ring-inset ring-rose-500 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm sm:leading-6"/>
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-slate-400">Text Size:</label>
                <div class="mt-2">
                    <input v-model="currentSize" type="number" step="1" min="0" max="100" class="block w-40 text-right bg-slate-800 rounded border-0 py-1.5 text-slate-100 shadow-sm ring-1 ring-inset ring-rose-500 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm sm:leading-6"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {marked} from 'marked'; // Assuming 'marked' is installed for markdown parsing
import { PlayIcon, StopIcon, PauseIcon, LightBulbIcon, SparklesIcon } from '@heroicons/vue/24/solid'

const markdownText = ref('');
const display = ref(null);
const scrollSpeed = ref(1.5); // Initial scroll speed
const isScrolling = ref(false);
const currentSize = ref(4);
const prevX = ref(400);
const saved = ref(false);
let scrollInterval = null;

const compiledMarkdown = computed(() => marked.parse(markdownText.value));

const sizes = [
    'prose-sm',
    'prose-base',
    'prose-lg',
    'prose-xl',
    'prose-2xl',
];

const startScrolling = () => {
    if (scrollInterval) clearTimeout(scrollInterval);
    const displayElement = display.value;
    scrollInterval = setTimeout(() => {
        if (displayElement.scrollTop < displayElement.scrollHeight - displayElement.clientHeight) {
            displayElement.scrollTop += 1;
            isScrolling.value = true;
            startScrolling();
        } else {
            clearTimeout(scrollInterval); // Stop scrolling when end is reached
            isScrolling.value = false;
        }
    }, Math.floor(100/scrollSpeed.value) ); // Adjust the interval for smoother or faster scrolling
};

const stopScrolling = (reset = false) => {
    if (scrollInterval) clearTimeout(scrollInterval);
    const displayElement = display.value;

    if (reset) {
        displayElement.scrollTop = 0;
    }
    isScrolling.value = false;
}

const adjustSpeed = (event) => {
    if (!isScrolling.value) return; // Only adjust speed if scrolling is active
    const deltaX = event.deltaX;
    const deltaY = event.deltaY;
    if (deltaY !== 0 && deltaX === 0) {
        return
    }
    event.preventDefault();

    // scrollSpeed.value += (deltaY > 0) ? 50 : -50; // Adjust the multiplier for sensitivity
    if (deltaX > 0) {
        scrollSpeed.value -= 0.25;
    } else if (deltaX < 0) {
        scrollSpeed.value += 0.25;
    }
    scrollSpeed.value = Math.max(1, scrollSpeed.value); // Prevent negative or too slow speed
    scrollSpeed.value = Math.min(20, scrollSpeed.value); // Prevent negative or too slow speed
};

const adjustSize = (event) => {
    if (!isScrolling.value) return; // Only adjust speed if scrolling is active
    event.preventDefault();
    const deltaY = event.deltaY;
    if(deltaY !== 0) {
        prevX.value -= deltaY;
        currentSize.value = Math.floor(prevX.value / 100);
        currentSize.value = Math.max(0, currentSize.value);
        currentSize.value = Math.min(sizes.length - 1, currentSize.value);
    }
    prevX.value = Math.max(0, prevX.value);
    prevX.value = Math.min(400, prevX.value);
}

const debounce = (func, wait) => {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    }
}

const saveMarkdown = () => {
    localStorage.setItem('promptly.markdown', markdownText.value);
    saved.value = true;
    let timeout;

    clearTimeout(timeout);
    timeout = setTimeout(() => { saved.value = false;}, 500);
}

const debouncedSave = debounce(saveMarkdown, 500);

onMounted(() => {
    const savedMarkdown = localStorage.getItem('promptly.markdown');
    const savedSpeed = localStorage.getItem('promptly.scrollSpeed');
    const savedSize = localStorage.getItem('promptly.currentSize');
    if (!markdownText.value && savedMarkdown) {
        markdownText.value = savedMarkdown;
    }
    if(savedSpeed) {
        scrollSpeed.value = parseFloat(savedSpeed);
    }
    if (savedSize) {
        currentSize.value = parseInt(savedSize);
    }
})

watch(markdownText, () => {
    if (scrollInterval) {
        clearTimeout(scrollInterval); // Reset scrolling on text change
        startScrolling();
    }
});

watch([scrollSpeed, currentSize], () => {
    localStorage.setItem('promptly.scrollSpeed', scrollSpeed.value.toString());
    localStorage.setItem('promptly.currentSize', currentSize.value.toString());
})
</script>
