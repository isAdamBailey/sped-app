<template>
    <app-layout title="Laws">
        <template #header>Laws</template>

        <div class="p-10">
            <div class="mb-5 text-2xl font-bold">{{ dropdownText }}</div>

            <div class="mb-5 flex flex-wrap">
                <button
                    v-for="(state, index) in stateChoices"
                    :key="index"
                    class="mb-3 mr-3 rounded-full py-2 px-5 hover:shadow-lg md:mr-5"
                    :class="
                        filter === state.filter
                            ? 'bg-blue-500 text-white'
                            : 'bg-gray-200 text-gray-900'
                    "
                >
                    <Link
                        :href="
                            route('laws.index', {
                                filter: state.filter,
                                search,
                            })
                        "
                    >
                        {{ state.name }}
                    </Link>
                </button>
            </div>
            <search-input
                class="mb-5"
                route-name="laws.index"
                :result-count="sections.total"
            />

            <div v-if="sectionsData.length">
                <Link
                    v-for="(section, index) in sectionsData"
                    :key="index"
                    :href="route('laws.show', section.slug)"
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

<script setup>
import { defineProps, ref, computed } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import SearchInput from "@/Jetstream/SearchInput";
import LoadMore from "@/Components/LoadMore";

const props = defineProps({
    sections: Object,
});

const stateChoices = [
    { name: "All", filter: null },
    { name: "Washington", filter: "Washington" },
    { name: "Oregon", filter: "Oregon" },
    { name: "Federal", filter: "Federal" },
];
const sectionsData = ref(props.sections.data);
const search = usePage().props.value.search;
const filter = usePage().props.value.filter;

function onAppendSections(event) {
    sectionsData.value.push(...event);
}

const dropdownText = computed(() => {
    let text = "All Laws";
    const state = props.sections.data[0]?.state;
    if (filter) {
        text = `${filter} ${state?.code_title || ""}`;
    }
    return text;
});
</script>
