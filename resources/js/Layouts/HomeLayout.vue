<template>
    <div>
        <Head :title="title" />

        <div
            class="items-top relative flex min-h-screen justify-center bg-gray-100 sm:items-center sm:pt-0"
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
                                class="mx-auto mb-6 w-1/2 lg:mb-0"
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
