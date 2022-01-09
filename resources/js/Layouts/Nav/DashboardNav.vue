<template>
    <h1 class="text-2xl font-semibold p-3 border-b">Dashboard</h1>
    <ul>
        <li class="p-5 border-b">
            <Link class="w-full"> Active Chapters </Link>
        </li>
    </ul>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: { Link },
});
</script>
