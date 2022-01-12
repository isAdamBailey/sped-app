<template>
    <home-layout title="Welcome">
        <div>
            <div class="font-bold text-gray-700 text-6xl md:text-8xl">
                Welcome
            </div>
            <div class="text-gray-500 md:text-2xl">
                to {{ $page.props.name }}!
            </div>
            <div class="mt-6 text-gray-500 text-l md:text-2xl">
                We hope this application can be a great resource for navigating
                through the nightmare of special education.
            </div>
            <div class="mt-5 text-gray-500 md:text-lg">
                This application has all of your state's special education laws
                populated in a way that you can easily search through and find
                the text you need for your meetings.
            </div>

            <div class="mt-5 text-gray-500 md:text-lg">
                We have a way for you to upload and securely keep track of
                important documents. You can invite others to your "team" to
                review and help you in your journey.
            </div>

            <div class="mt-5 text-gray-500 md:text-lg">
                Creating a team requires a small monthly subscription, but you
                can add 3 people to your team for free.
            </div>

            <div class="mt-5 md:mt-12">
                <Link v-if="$page.props.user.name" :href="route('dashboard')">
                    <jet-button>Dashboard</jet-button>
                </Link>

                <template v-else>
                    <Link :href="route('login')">
                        <secondary-button>Log In</secondary-button>
                    </Link>

                    <Link :href="route('register')" class="ml-4">
                        <jet-button> Register </jet-button>
                    </Link>
                </template>
            </div>
        </div>
    </home-layout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import HomeLayout from "@/Layouts/HomeLayout";
import JetButton from "@/Jetstream/Button";
import SecondaryButton from "../Jetstream/SecondaryButton";

export default defineComponent({
    components: {
        SecondaryButton,
        HomeLayout,
        Link,
        JetButton,
    },
});
</script>
