<template>
    <!-- Hamburger -->
    <div class="-mr-2 flex items-center sm:hidden">
        <button
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
            @click="$emit('toggle-navigation-dropdown', !show)"
        >
            <svg
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
            >
                <path
                    :class="{
                        hidden: show,
                        'inline-flex': !show,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="{
                        hidden: !show,
                        'inline-flex': show,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        show: Boolean,
    },
    emits: ["toggle-navigation-dropdown"],
});
</script>
