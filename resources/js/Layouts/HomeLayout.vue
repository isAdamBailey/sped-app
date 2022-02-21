<template>
    <div>
        <Head :title="title" />

        <div
            class="items-top relative flex min-h-screen justify-center bg-gradient-to-r from-gray-100 to-gray-300 sm:items-center sm:pt-0"
        >
            <main>
                <div class="mx-auto max-w-6xl">
                    <div
                        class="overflow-hidden rounded-lg bg-white p-10 shadow-lg"
                    >
                        <div
                            class="mb-5 flex flex-col-reverse md:mb-7 lg:flex-row"
                        >
                            <slot></slot>
                            <application-mark
                                color="text-orange-300"
                                class="min-w-1/4 mx-auto mb-6 h-1/4 rounded-full bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 p-3 text-center lg:mb-0"
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
