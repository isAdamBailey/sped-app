<template>
    <dashboard-nav-link
        :href="route('dashboard')"
        :active="route().current('dashboard')"
    >
        <h1 class="text-2xl font-semibold">Dashboard</h1>
    </dashboard-nav-link>
    <dashboard-nav-link
        :href="route('chapters.index')"
        :active="route().current('chapters.*')"
    >
        Active Chapters
    </dashboard-nav-link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import DashboardNavLink from "@/Layouts/Nav/DashboardNavLink";

export default defineComponent({
    components: { Link, DashboardNavLink },
});
</script>
