<template>
    <dashboard-layout title="Dashboard">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div>
                <application-mark class="block h-12 w-auto" />
            </div>

            <div class="mt-5 text-2xl">Hello, {{ $page.props.user.name }}</div>

            <div class="mt-5 text-2xl">
                Welcome to the unnamed special education helper!
            </div>

            <div class="mt-6">
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
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout";
import ApplicationMark from "@/Jetstream/ApplicationMark";

export default defineComponent({
    components: {
        ApplicationMark,
        DashboardLayout,
    },

    props: {
        chapters: Object,
    },
});
</script>
