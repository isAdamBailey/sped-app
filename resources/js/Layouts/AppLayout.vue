<template>
    <div>
        <Head :title="title" />

        <banner />

        <div class="min-h-screen bg-gray-100">
            <primary-nav />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-gray-800 leading-tight">
                        <slot name="header"></slot>
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div
                            class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
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
