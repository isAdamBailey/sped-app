<template>
    <div class="w-full rounded-full bg-white">
        <label for="search" class="hidden">Search</label>
        <input
            id="search"
            ref="search"
            v-model="search"
            class="h-10 w-full cursor-pointer rounded-full border border-gray-500 bg-gray-100 px-4 pb-0 pt-px text-gray-700 outline-none transition focus:border-purple-400"
            :class="{ 'transition-border': search }"
            autocomplete="off"
            name="search"
            placeholder="Search"
            type="search"
            @keyup.esc="search = null"
            @blur="search = null"
        />
        <span v-if="resultCount >= 0" class="ml-5 text-sm text-indigo-700">
            {{ resultCount }} found
        </span>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        routeName: String,
        resultCount: Number,
    },

    data() {
        return {
            search: this.$inertia.page.props.search || null,
            filter: this.$inertia.page.props.filter || null,
        };
    },

    watch: {
        search() {
            if (this.search) {
                this.searchMethod();
            } else {
                this.$inertia.get(route(this.routeName));
            }
        },
    },

    mounted() {
        this.$refs.search.focus();
    },

    methods: {
        searchMethod: _.debounce(function () {
            this.$inertia.get(
                route(this.routeName),
                { search: this.search, filter: this.filter },
                { preserveState: false }
            );
        }, 500),
    },
});
</script>
