<template>
    <Link :href="route('dashboard')" class="my-2 hidden justify-center md:flex">
        <application-mark size="text-7xl" color="text-gray-400" />
    </Link>
    <dashboard-nav-link
        :href="route('dashboard')"
        :active="route().current('dashboard')"
    >
        <h1 class="text-xl font-semibold">Dashboard</h1>
    </dashboard-nav-link>
    <dashboard-nav-link
        v-if="permissions.includes('edit site settings')"
        :href="route('site-settings.show')"
        :active="route().current('site-settings.*')"
    >
        Site Settings
    </dashboard-nav-link>
    <dashboard-nav-link
        v-if="permissions.includes('edit chapters')"
        :href="route('chapters.index')"
        :active="route().current('chapters.*')"
    >
        Active Chapters
    </dashboard-nav-link>
    <dashboard-nav-link
        v-if="permissions.includes('edit users')"
        :href="route('users.index')"
        :active="route().current('users.*')"
    >
        Users
    </dashboard-nav-link>
</template>

<script>
import { defineComponent } from "vue";
import DashboardNavLink from "@/Layouts/Nav/DashboardNavLink";
import ApplicationMark from "@/Jetstream/ApplicationMark";

export default defineComponent({
    components: { ApplicationMark, DashboardNavLink },
    computed: {
        permissions() {
            return this.$page.props.user.permissions;
        },
    },
});
</script>
