<template>
    <dashboard-layout title="Dashboard">
        <div class="m-5 rounded-lg bg-white p-5 shadow-xl">
            <div class="mt-5 text-2xl">Hello, {{ $page.props.user.name }}</div>

            <div class="mt-5 text-2xl">Welcome to {{ $page.props.name }}!</div>

            <div>You are on {{ $page.props.currentTeamPermissions.name }}.</div>

            <div class="mt-10 flex">
                <Link :href="route('laws.index')">
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
