<template>
    <div>
        <Head :title="title" />

        <banner />

        <div class="min-h-screen bg-gray-100">
            <primary-nav/>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <slot name="header"></slot>
                  </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
              <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <slot></slot>
                  </div>
                </div>
              </div>
            </main>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
    import PrimaryNav from "@/Layouts/Nav/PrimaryNav";
    import Banner from "@/Jetstream/Banner";

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Banner,
            PrimaryNav,
            Head,
        }
    })
</script>
