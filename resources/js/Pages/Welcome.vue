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
