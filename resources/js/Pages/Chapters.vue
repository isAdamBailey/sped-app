<template>
    <app-layout title="State Laws">
        <template #header>State Laws</template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">Chapters</div>

                <search-input route-name="chapters.index" />

                <filter-dropdown>
                    <dropdown-link
                        :href="
                            route('chapters.index', {
                                filter: 'Washington',
                                search: $page.props.search,
                            })
                        "
                    >
                        Washington
                    </dropdown-link>
                    <dropdown-link
                        :href="
                            route('chapters.index', {
                                filter: 'Oregon',
                                search: $page.props.search,
                            })
                        "
                    >
                        Oregon
                    </dropdown-link>
                    <dropdown-link :href="route('chapters.index')">
                        <i class="ri-filter-off-fill"></i> Clear
                    </dropdown-link>
                </filter-dropdown>
            </div>
            <div v-if="chaptersData.length">
                <ul>
                    <Link
                        v-for="(chapter, index) in chaptersData"
                        :key="index"
                        :href="route('chapters.show', chapter.slug)"
                    >
                        <li
                            class="p-3 hover:bg-gray-100 hover:text-blue-800 transition"
                        >
                            <span class="font-bold"
                                >{{ chapter.state.code_title }}
                                {{ chapter.code }}</span
                            >
                            -
                            {{ chapter.description }}
                        </li>
                    </Link>
                </ul>
                <div
                    v-if="chapters.next_page_url"
                    class="flex justify-center mt-7"
                >
                    <jet-button @click="loadMore">
                        Load More Chapters
                    </jet-button>
                </div>
            </div>
            <div v-else class="my-12 text-gray-500">No chapters found</div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import SearchInput from "@/Jetstream/SearchInput";
import FilterDropdown from "@/Jetstream/FilterDropdown";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetButton from "@/Jetstream/Button";

export default defineComponent({
    components: {
        JetButton,
        DropdownLink,
        FilterDropdown,
        SearchInput,
        Link,
        AppLayout,
    },
    props: {
        chapters: Object,
    },
    data() {
        return {
            chaptersData: this.chapters.data,
        };
    },
    watch: {
        chapters() {
            this.chaptersData.push(...this.chapters.data);
        },
    },
    methods: {
        loadMore() {
            const links = this.chapters.links;
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
