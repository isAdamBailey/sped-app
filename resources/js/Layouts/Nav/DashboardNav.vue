<template>
    <Link :href="route('dashboard')" class="hidden md:flex justify-center my-2">
        <application-mark size="text-7xl" color="text-gray-400" />
    </Link>
    <dashboard-nav-link
        :href="route('dashboard')"
        :active="route().current('dashboard')"
    >
        <h1 class="text-xl font-semibold">Dashboard</h1>
    </dashboard-nav-link>
    <dashboard-nav-link
        v-if="roles.includes('super admin')"
        :href="route('chapters.index')"
        :active="route().current('chapters.*')"
    >
        Active Chapters
    </dashboard-nav-link>
</template>

<script>
import { defineComponent } from "vue";
import DashboardNavLink from "@/Layouts/Nav/DashboardNavLink";
import ApplicationMark from "@/Jetstream/ApplicationMark";

export default defineComponent({
    components: { ApplicationMark, DashboardNavLink },
    computed: {
        roles() {
            return this.$page.props.user.roles;
        },
    },
});
</script>
