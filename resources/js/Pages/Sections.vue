<template>
    <app-layout title="State Laws">
        <template #header>Laws</template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="mb-5 text-2xl font-bold">Sections</div>

                <search-input
                    route-name="sections.index"
                    :result-count="sections.total"
                />

                <jet-dropdown width="48" :arrow-trigger-title="dropdownText">
                    <template #content>
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Filter By:
                        </div>
                        <dropdown-link
                            :href="
                                route('sections.index', {
                                    filter: 'Washington',
                                    search: $page.props.search,
                                })
                            "
                        >
                            Washington
                        </dropdown-link>
                        <dropdown-link
                            :href="
                                route('sections.index', {
                                    filter: 'Oregon',
                                    search: $page.props.search,
                                })
                            "
                        >
                            Oregon
                        </dropdown-link>
                        <dropdown-link
                            :href="
                                route('sections.index', {
                                    filter: 'Federal',
                                    search: $page.props.search,
                                })
                            "
                        >
                            IDEA
                        </dropdown-link>
                        <dropdown-link :href="route('sections.index')">
                            <i class="ri-filter-off-fill"></i> Clear
                        </dropdown-link>
                    </template>
                </jet-dropdown>
            </div>
            <div v-if="sectionsData.length">
                <Link
                    v-for="(section, index) in sectionsData"
                    :key="index"
                    :href="route('sections.show', section.slug)"
                    class="flex items-center transition hover:bg-blue-100 hover:text-blue-800"
                >
                    <div class="p-3">
                        <span class="font-bold"
                            >{{ section.state.code_title }}
                            {{ section.code }}</span
                        >
                        -
                        {{ section.description }}
                    </div>
                </Link>
                <div
                    v-if="sections.next_page_url"
                    class="mt-7 flex justify-center"
                >
                    <jet-button @click="loadMore">
                        Load More Sections
                    </jet-button>
                </div>
            </div>
            <div v-else class="my-12 text-gray-500">No sections found</div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SearchInput from "@/Jetstream/SearchInput";
import JetDropdown from "@/Jetstream/Dropdown";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetButton from "@/Jetstream/Button";

export default defineComponent({
    components: {
        JetButton,
        DropdownLink,
        JetDropdown,
        SearchInput,
        AppLayout,
    },
    props: {
        sections: Object,
    },
    data() {
        return {
            sectionsData: this.sections.data,
            loadingMore: false,
        };
    },
    computed: {
        dropdownText() {
            let text = "Filter Laws";
            const state = this.sections.data[0]?.state;
            if (this.$inertia.page.props.filter) {
                text = `${this.$page.props.filter} ${state?.code_title || ""}`;
            }
            return text;
        },
    },
    watch: {
        sections() {
            if (this.loadingMore) {
                this.sectionsData.push(...this.sections.data);
                this.loadingMore = false;
            }
        },
    },
    methods: {
        loadMore() {
            this.loadingMore = true;

            const links = this.sections.links;
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
