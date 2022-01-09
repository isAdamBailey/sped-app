<template>
    <dashboard-layout title="State Laws">
        <template #header>State Laws</template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">Chapters</div>

                <search-input route-name="chapters.index" />

                <jet-dropdown width="48" :arrow-trigger-title="dropdownText">
                    <template #content>
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Filter By:
                        </div>
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
                    </template>
                </jet-dropdown>
            </div>
            <div v-if="chaptersData.length">
                <span class="text-sm font-semibold underline">Active</span>
                <div
                    v-for="(chapter, index) in chaptersData"
                    :key="index"
                    class="flex items-center"
                >
                    <chapter-active-form class="mr-5" :chapter="chapter" />

                    <Link :href="route('chapters.show', chapter.slug)">
                        <div
                            class="p-3 hover:bg-gray-100 hover:text-blue-800 transition"
                        >
                            <span class="font-bold"
                                >{{ chapter.state.code_title }}
                                {{ chapter.code }}</span
                            >
                            -
                            {{ chapter.description }}
                        </div>
                    </Link>
                </div>
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
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import SearchInput from "@/Jetstream/SearchInput";
import JetDropdown from "@/Jetstream/Dropdown";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetButton from "@/Jetstream/Button";
import ChapterActiveForm from "@/Pages/Dashboard/Chapters/Partials/ChapterActiveForm";
import DashboardLayout from "@/Layouts/DashboardLayout";

export default defineComponent({
    components: {
        DashboardLayout,
        ChapterActiveForm,
        JetButton,
        DropdownLink,
        JetDropdown,
        SearchInput,
        Link,
    },
    props: {
        chapters: Object,
    },
    data() {
        return {
            chaptersData: this.chapters.data,
            loadingMore: false,
        };
    },
    computed: {
        dropdownText() {
            let text = "Filter By State";
            const state = this.chapters.data[0]?.state;
            if (this.$inertia.page.props.filter) {
                text = `${this.$page.props.filter} ${state?.code_title || ""}`;
            }
            return text;
        },
    },
    watch: {
        chapters() {
            if (this.loadingMore) {
                this.chaptersData.push(...this.chapters.data);
                this.loadingMore = false;
            }
        },
    },
    methods: {
        loadMore() {
            this.loadingMore = true;

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
