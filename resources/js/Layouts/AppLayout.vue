<template>
    <div>
        <Head :title="title" />

        <banner />

        <div class="min-h-screen bg-gradient-to-r from-gray-100 to-gray-300">
            <primary-nav />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold leading-tight text-gray-800">
                        <slot name="header"></slot>
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div
                            class="overflow-hidden bg-white shadow-xl sm:rounded-lg"
                        >
                            <slot></slot>
                        </div>
                    </div>
                </div>

                <scroll-top />
            </main>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import PrimaryNav from "@/Layouts/Nav/PrimaryNav";
import Banner from "@/Jetstream/Banner";
import ScrollTop from "@/Jetstream/ScrollTop";

export default defineComponent({
    components: {
        ScrollTop,
        Banner,
        PrimaryNav,
        Head,
    },
    props: {
        title: String,
    },
});
</script>
