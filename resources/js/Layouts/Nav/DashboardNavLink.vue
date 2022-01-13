<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        active: Boolean,
        href: String,
    },

    computed: {
        classes() {
            return this.active
                ? "inline-flex w-full p-2 text-sm border-b border-gray-400 bg-blue-900"
                : "inline-flex w-full p-2 text-sm border-b border-gray-400 hover:bg-blue-900 transition";
        },
    },
});
</script>
