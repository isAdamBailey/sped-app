<template>
    <dashboard-layout title="User">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div class="mt-5 text-2xl">{{ user.name }}</div>
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout";

export default defineComponent({
    components: {
        DashboardLayout,
    },

    props: {
        user: Object,
    },
});
</script>
