<template>
    <home-layout title="Welcome">
        <div>
            <div class="font-bold text-blue-900 text-6xl md:text-8xl">
                Welcome
            </div>
            <div class="text-gray-500 md:text-2xl">
                to
                <span class="font-semibold text-gray-900"
                    >{{ $page.props.name }}!</span
                >
            </div>
            <div class="mt-6 text-gray-500 text-l md:text-2xl">
                We hope this application can be a great resource for navigating
                through the atrocities and confusions of special education.
            </div>

            <div class="mt-10 md:flex -mx-2 -mx-4">
                <div class="mx-3 px-2 md:w-1/3">
                    <i class="text-7xl text-blue-900 ri-book-open-fill"></i>

                    <h3 class="text-2xl text-blue-900 mb-0">
                        Your states laws
                    </h3>

                    <p class="text-gray-600">
                        This application has all of your state's special
                        education laws populated in a way that you can easily
                        search through and find the text you need for your
                        meetings.
                    </p>
                </div>

                <div class="mt-8 md:mt-0 mx-3 px-2 md:w-1/3">
                    <i class="text-7xl text-blue-900 ri-draft-fill"></i>

                    <h3 class="text-2xl text-blue-900 mb-0">Documentation</h3>

                    <p class="text-gray-600">
                        We have a way for you to upload and securely keep track
                        of important documents. Set a reminder date for the
                        document to be acted upon. We'll email your team when
                        it's time!
                    </p>
                </div>

                <div class="mt-8 md:mt-0 mx-3 px-2 md:w-1/3">
                    <i class="text-7xl text-blue-900 ri-team-fill"></i>

                    <h3 class="text-2xl text-blue-900 mb-0">
                        Contribute as a team.
                    </h3>

                    <p class="text-gray-600">
                        You're not in this alone! You can invite others to your
                        team to review documents and help you in your journey.
                    </p>
                </div>
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
import HomeLayout from "@/Layouts/HomeLayout";
import JetButton from "@/Jetstream/Button";
import SecondaryButton from "../Jetstream/SecondaryButton";

export default defineComponent({
    components: {
        SecondaryButton,
        HomeLayout,
        JetButton,
    },
});
</script>
