<template>
    <home-layout title="Welcome">
        <div>
            <div class="text-5xl font-bold text-blue-900">
                {{ $page.props.name }}
            </div>
            <div class="mt-6 text-lg text-gray-500 md:text-2xl">
                We hope this application can be a great resource for navigating
                special education.
            </div>

            <div class="-mx-2 -mx-4 mt-10 md:flex">
                <div
                    class="mx-3 px-2"
                    :class="registrationEnabled ? 'md:w-1/3' : ''"
                >
                    <i class="ri-book-open-fill text-7xl text-blue-900"></i>

                    <h3 class="mb-0 text-2xl text-blue-900">States Laws</h3>

                    <p class="text-gray-600">
                        This application includes the following state's special
                        education laws:
                    </p>
                    <ul>
                        <li
                            v-for="(state, index) in states"
                            :key="index"
                            class="text-xl text-gray-800"
                        >
                            {{ state }}
                        </li>
                    </ul>
                    <p class="mt-5 text-gray-600">
                        And the following federal laws:
                    </p>
                    <ul>
                        <li
                            v-for="(fed, index) in federal"
                            :key="index"
                            class="text-xl text-gray-800"
                        >
                            {{ fed }}
                        </li>
                    </ul>
                </div>

                <div
                    v-if="registrationEnabled"
                    class="mx-3 mt-8 px-2 md:mt-0 md:w-1/3"
                >
                    <i class="ri-draft-fill text-7xl text-blue-900"></i>

                    <h3 class="mb-0 text-2xl text-blue-900">Documentation</h3>

                    <p class="text-gray-600">
                        We have a way for you to upload and securely keep track
                        of important documents. Set a reminder date for the
                        document to be acted upon. We'll email your team when
                        it's time!
                    </p>
                </div>

                <div
                    v-if="registrationEnabled"
                    class="mx-3 mt-8 px-2 md:mt-0 md:w-1/3"
                >
                    <i class="ri-team-fill text-7xl text-blue-900"></i>

                    <h3 class="mb-0 text-2xl text-blue-900">
                        Contribute as a team.
                    </h3>

                    <p class="text-gray-600">
                        You're not in this alone! You can invite others to your
                        team to review documents and help you in your journey.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-12">
                <Link :href="route('laws.index')" class="mr-4">
                    <jet-button
                        class="bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 py-3 px-10 text-3xl text-orange-300 transition hover:text-gray-900 hover:shadow-lg"
                        >Search Laws</jet-button
                    >
                </Link>

                <Link v-if="$page.props.user.name" :href="route('dashboard')">
                    <secondary-button>Dashboard</secondary-button>
                </Link>

                <div class="mt-12">
                    <Link :href="route('login')">
                        <secondary-button>Log In</secondary-button>
                    </Link>

                    <Link
                        v-if="registrationEnabled"
                        :href="route('register')"
                        class="ml-4"
                    >
                        <jet-button> Register </jet-button>
                    </Link>
                </div>
            </div>
        </div>
    </home-layout>
</template>

<script>
import { defineComponent } from "vue";
import HomeLayout from "@/Layouts/HomeLayout";
import JetButton from "@/Jetstream/Button";
import SecondaryButton from "../Jetstream/SecondaryButton";

export default defineComponent({
    components: {
        SecondaryButton,
        HomeLayout,
        JetButton,
    },

    props: {
        states: Array,
        federal: Array,
    },

    computed: {
        registrationEnabled() {
            return this.$page.props.siteSettings.registration_active;
        },
    },
});
</script>
