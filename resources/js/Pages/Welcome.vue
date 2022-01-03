<template>
    <Head title="Welcome" />

    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
    >
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link
                v-if="$page.props.user"
                :href="route('dashboard')"
                class="text-sm text-gray-700 underline"
            >
                Dashboard
            </Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-700 underline"
                >
                    Log in
                </Link>

                <Link
                    :href="route('register')"
                    class="ml-4 text-sm text-gray-700 underline"
                >
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto mt-20 md:mt-0">
            <div
                class="p-10 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg"
            >
                <div class="flex flex-col-reverse mb-5 md:mb-7 lg:flex-row">
                    <div>
                        <div
                            class="font-bold text-gray-700 dark:text-indigo-400 text-6xl md:text-8xl"
                        >
                            Welcome
                        </div>
                        <div
                            class="mt-6 text-gray-500 dark:text-indigo-300 text-l md:text-2xl"
                        >
                            etc etc
                        </div>
                    </div>
                    <application-mark
                        class="w-full md:w-1/2 mx-auto mb-6 lg:mb-0"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import ApplicationMark from "../Jetstream/ApplicationMark";

export default defineComponent({
    components: {
        ApplicationMark,
        Head,
        Link,
    },
});
</script>
