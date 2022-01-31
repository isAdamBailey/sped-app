<template>
    <div v-if="items.links.next" class="mt-7 flex justify-center">
        <jet-button @click="loadMore"> Load More {{ title }} </jet-button>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetButton from "@/Jetstream/Button";

export default defineComponent({
    components: {
        JetButton,
    },
    props: {
        items: Object,
        title: String,
    },
    emits: ["append-items"],
    data() {
        return {
            loadingMore: false,
        };
    },
    watch: {
        items() {
            if (this.loadingMore) {
                this.$emit("append-items", this.items.data);
                this.loadingMore = false;
            }
        },
    },
    methods: {
        loadMore() {
            this.loadingMore = true;

            const links = this.items.meta.links;
            const next = links[links.length - 1];
            if (next.url) {
                this.$inertia.get(
                    next.url,
                    {
                        search: this.$inertia.page.props.search,
                        filter: this.$inertia.page.props.filter,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                    }
                );
            }
        },
    },
});
</script>
