<template>
    <home-layout :title="title">
        <div>
            <div class="text-6xl font-bold text-gray-700 md:text-8xl">
                <h1>{{ title }}</h1>
            </div>
            <div class="text-l mt-6 text-gray-500 md:text-2xl">
                <div>{{ description }}</div>
            </div>
            <div class="mt-10 flex justify-center">
                <Link :href="route('dashboard')">
                    <jet-button>Back Home?</jet-button>
                </Link>
            </div>
        </div>
    </home-layout>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import HomeLayout from "@/Layouts/HomeLayout";
export default {
    components: { HomeLayout, JetButton },
    props: {
        status: Number,
    },
    computed: {
        title() {
            return {
                503: "503: Service Unavailable",
                500: "500: Server Error",
                404: "404: Page Not Found",
                403: "403: Forbidden",
            }[this.status];
        },
        description() {
            return {
                503: "Sorry, we are doing some maintenance. Please check back soon.",
                500: "Whoops, something went wrong on our servers.",
                404: "Sorry, the page you are looking for could not be found.",
                403: "Sorry, you are forbidden from accessing this page.",
            }[this.status];
        },
    },
};
</script>
