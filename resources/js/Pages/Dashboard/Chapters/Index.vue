<template>
    <dashboard-layout title="Chapters">
        <div class="m-5 rounded-lg bg-white p-5 shadow-xl">
            <div class="p-10">
                <div class="flex justify-between">
                    <div class="mb-5 text-2xl font-bold">Chapters</div>

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
                            <dropdown-link
                                :href="
                                    route('chapters.index', {
                                        filter: 'Federal',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                IDEA
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
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div
                                class="inline-block min-w-full py-2 sm:px-6 lg:px-8"
                            >
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="border-b">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Active
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Code
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Description
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    chapter, index
                                                ) in chaptersData"
                                                :key="index"
                                                class="border-b bg-white"
                                            >
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"
                                                >
                                                    <chapter-active-form
                                                        :chapter="chapter"
                                                    />
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900"
                                                >
                                                    {{
                                                        chapter.state.code_title
                                                    }}
                                                    {{ chapter.code }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm"
                                                >
                                                    {{ chapter.description }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <load-more
                        title="Chapters"
                        :items="chapters"
                        @append-items="onAppendChapters"
                    />
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
import ChapterActiveForm from "@/Pages/Dashboard/Chapters/Partials/ChapterActiveForm";
import DashboardLayout from "@/Layouts/DashboardLayout";
import InfoText from "@/Jetstream/InfoText";
import LoadMore from "@/Components/LoadMore";

export default defineComponent({
    components: {
        LoadMore,
        InfoText,
        DashboardLayout,
        ChapterActiveForm,
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
        };
    },
    computed: {
        dropdownText() {
            let text = "Filter Chapters";
            const state = this.chapters.data[0]?.state;
            if (this.$inertia.page.props.filter) {
                text = `${this.$page.props.filter} ${state?.code_title || ""}`;
            }
            return text;
        },
    },
    methods: {
        onAppendChapters(event) {
            this.chaptersData.push(...event);
        },
    },
});
</script>
