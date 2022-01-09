<template>
    <div>
        <Head :title="title" />

        <banner />

        <div class="bg-gray-100">
            <primary-nav />

            <div class="min-h-screen grid grid-cols-1 md:grid-cols-[15rem,1fr]">
                <div class="aside bg-gray-800 text-gray-100">
                    <dashboard-nav></dashboard-nav>
                </div>
                <div class="main">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import PrimaryNav from "@/Layouts/Nav/PrimaryNav";
import DashboardNav from "@/Layouts/Nav/DashboardNav";
import Banner from "@/Jetstream/Banner";

export default defineComponent({
    components: {
        Banner,
        PrimaryNav,
        DashboardNav,
        Head,
    },
    props: {
        title: String,
    },
});
</script>
