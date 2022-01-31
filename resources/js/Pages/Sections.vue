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
                <load-more
                    title="Sections"
                    :items="sections"
                    @append-items="onAppendSections"
                />
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
import LoadMore from "@/Components/LoadMore";

export default defineComponent({
    components: {
        LoadMore,
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
    methods: {
        onAppendSections(event) {
            this.sectionsData.push(...event);
        },
    },
});
</script>
