<template>
    <jet-dropdown width="48" :arrow-trigger-title="dropdownText">
        <template #content>
            <div class="block px-4 py-2 text-xs text-gray-400">Filter By:</div>
            <slot></slot>
        </template>
    </jet-dropdown>
</template>

<script>
import { defineComponent } from "vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";

export default defineComponent({
    components: { JetDropdown },

    computed: {
        dropdownText() {
            let text = "Filter By State";
            const state = this.$inertia.page.props.chapters.data[0].state;
            if (this.$inertia.page.props.filter) {
                text = `${this.$inertia.page.props.filter} ${state.code_title}`;
            }
            return text;
        },
    },
});
</script>
