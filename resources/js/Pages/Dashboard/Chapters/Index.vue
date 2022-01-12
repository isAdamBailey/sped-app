<template>
    <dashboard-layout title="Chapters">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div class="p-10">
                <div class="flex justify-between">
                    <div class="text-2xl font-bold mb-5">Chapters</div>

                    <search-input
                        route-name="chapters.index"
                        :result-count="chapters.total"
                    />

                    <jet-dropdown
                        width="48"
                        :arrow-trigger-title="dropdownText"
                    >
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

                <info-text class="mb-5">
                    Choose which chapters users can see sections from. This
                    helps reduce the amount of data users need to sift through,
                    and narrow down results. Note: this also limits the content
                    we read from each state's website. Only active chapters are
                    checked daily for changes.
                </info-text>
                <div v-if="chaptersData.length">
                    <span class="text-sm font-semibold underline">Active</span>
                    <div
                        v-for="(chapter, index) in chaptersData"
                        :key="index"
                        class="flex items-center"
                    >
                        <chapter-active-form class="mr-5" :chapter="chapter" />

                        <div class="p-2">
                            <span class="font-bold"
                                >{{ chapter.state.code_title }}
                                {{ chapter.code }}</span
                            >
                            -
                            {{ chapter.description }}
                        </div>
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
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import SearchInput from "@/Jetstream/SearchInput";
import JetDropdown from "@/Jetstream/Dropdown";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetButton from "@/Jetstream/Button";
import ChapterActiveForm from "@/Pages/Dashboard/Chapters/Partials/ChapterActiveForm";
import DashboardLayout from "@/Layouts/DashboardLayout";
import InfoText from "@/Jetstream/InfoText";

export default defineComponent({
    components: {
        InfoText,
        DashboardLayout,
        ChapterActiveForm,
        JetButton,
        DropdownLink,
        JetDropdown,
        SearchInput,
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
