<template>
    <div>
        <Head :title="title" />

        <div
            class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0"
        >
            <main>
                <div class="max-w-6xl mx-auto">
                    <div
                        class="p-10 bg-white overflow-hidden shadow-lg rounded-lg"
                    >
                        <div
                            class="flex flex-col-reverse mb-5 md:mb-7 lg:flex-row"
                        >
                            <slot></slot>
                            <application-mark
                                class="w-1/2 mx-auto mb-6 lg:mb-0"
                            />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import ApplicationMark from "@/Jetstream/ApplicationMark";

export default defineComponent({
    components: {
        Head,
        ApplicationMark,
    },
    props: {
        title: String,
    },
});
</script>
