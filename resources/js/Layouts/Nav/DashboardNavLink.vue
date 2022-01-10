<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: {
        Link,
    },
    props: {
        active: Boolean,
        href: String,
    },

    computed: {
        classes() {
            return this.active
                ? "inline-flex w-full p-3 border-b bg-blue-400 hover:bg-blue-800 transition"
                : "inline-flex w-full p-3 border-b hover:bg-blue-800 transition";
        },
    },
});
</script>
