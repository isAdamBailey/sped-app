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
                                filter: 'washington',
                                search: $page.props.search,
                            })
                        "
                    >
                        Washington
                    </dropdown-link>
                    <dropdown-link
                        :href="
                            route('chapters.index', {
                                filter: 'oregon',
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
            <ul>
                <li v-for="(chapter, index) in chapters.data" :key="index">
                    <Link
                        class="font-semibold text-blue-600 hover:underline"
                        :href="route('chapters.show', chapter.id)"
                    >
                        {{ chapter.state.code_title }} {{ chapter.code }}
                    </Link>
                    - {{ chapter.description }}
                </li>
            </ul>
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

export default defineComponent({
    components: {
        DropdownLink,
        FilterDropdown,
        SearchInput,
        Link,
        AppLayout,
    },
    props: {
        chapters: Object,
    },
});
</script>
