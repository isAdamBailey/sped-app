<template>
    <dashboard-layout title="Dashboard">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div class="mt-5 text-2xl">Hello, {{ $page.props.user.name }}</div>

            <div class="mt-5 text-2xl">Welcome to {{ $page.props.name }}!</div>

            <div v-if="$page.props.user.roles.length" class="mt-6">
                <span class="text-gray-500">You are logged in as </span>
                <span class="text-xl font-semibold">{{
                    $page.props.user.roles[0]
                }}</span>
                <span v-if="$page.props.user.permissions.length">
                    which allows you to
                    <span
                        v-for="(permission, index) in $page.props.user
                            .permissions"
                        :key="index"
                        class="inline-flex"
                        >{{ permission }}&nbsp;
                    </span>
                </span>
            </div>

            <div class="flex mt-10">
                <Link :href="route('sections.index')">
                    <jet-button>View State Laws</jet-button>
                </Link>
                <Link class="ml-3" :href="route('documents.index')">
                    <jet-button>View Your Documentation</jet-button>
                </Link>
            </div>
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout";
import JetButton from "@/Jetstream/Button";

export default defineComponent({
    components: {
        DashboardLayout,
        JetButton,
    },

    props: {
        chapters: Object,
    },
});
</script>
