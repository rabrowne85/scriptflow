<script setup>
import {defineComponent, h} from 'vue';

const navigation = {
    social: [
        {
            name: 'Sponsor me',
            href: 'https://github.com/sponsors/rabrowne85',
            icon: defineComponent({
                render: () => h('svg', {
                    fill: 'currentColor',
                    viewBox: '0 0 24 24',
                }, [
                    h('path', {
                        d: 'm11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z',
                    }),
                ]),
            }),
        },
        {
            name: 'GitHub',
            href: 'https://github.com/rabrowne85/scriptflow',
            icon: defineComponent({
                render: () => h('svg', {
                    fill: 'currentColor',
                    viewBox: '0 0 24 24',
                }, [
                    h('path', {
                        'fill-rule': 'evenodd',
                        d: 'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
                        'clip-rule': 'evenodd',
                    }),
                ]),
            }),
        },
    ],
};
</script>

<template>
    <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
    <div class="mx-auto max-w-lg">
        <slot/>
    </div>
    <footer class="absolute bottom-8 w-full left-0 ">
        <div class="border-t border-white/10 px-6 pt-8 md:flex md:items-center md:justify-between">
            <div class="flex space-x-6 md:order-2">
                <a v-for="item in navigation.social" :key="item.name" :href="item.href"
                   class="text-slate-500 hover:text-rose-400">
                        <span class="sr-only">{{ item.name }}</span>
                        <component :is="item.icon" class="size-6" aria-hidden="true"/>
                    </a>
                </div>
                <p class="mt-8 text-xs leading-5 text-slate-400 md:order-1 md:mt-0">&copy; {{ new Date().getFullYear() }} Richard Browne. All rights reserved.</p>
            </div>
        </footer>
</template>
