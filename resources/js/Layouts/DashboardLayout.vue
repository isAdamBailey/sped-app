<template>
    <div>
        <Head :title="title" />

        <banner />

        <div class="min-h-screen bg-gray-100">
            <primary-nav />

            <div
                v-if="
                    permissions.includes(
                        'edit chapters',
                        'edit users',
                        'edit site settings'
                    )
                "
                class="grid grid-cols-1 md:min-h-screen md:grid-cols-[15rem,1fr]"
            >
                <div class="aside bg-gray-800 text-gray-100">
                    <dashboard-nav></dashboard-nav>
                </div>
                <main>
                    <slot></slot>
                </main>
            </div>

            <div v-else class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                <main>
                    <slot></slot>
                </main>
            </div>
        </div>
        <scroll-top />
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import PrimaryNav from "@/Layouts/Nav/PrimaryNav";
import DashboardNav from "@/Layouts/Nav/DashboardNav";
import Banner from "@/Jetstream/Banner";
import ScrollTop from "@/Jetstream/ScrollTop";

export default defineComponent({
    components: {
        Banner,
        PrimaryNav,
        DashboardNav,
        Head,
        ScrollTop,
    },
    props: {
        title: String,
    },
    computed: {
        permissions() {
            return this.$page.props.user.permissions;
        },
    },
});
</script>
