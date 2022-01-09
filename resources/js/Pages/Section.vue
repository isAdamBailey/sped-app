<template>
    <app-layout :title="`${section.state.code_title} ${section.code}`">
        <template #header>
            <Link
                class="underline text-blue-600 hover:text-blue-400"
                :href="route('sections.index')"
                >State Laws</Link
            >
            / Section {{ section.state.code_title }}
            {{ section.code }}
        </template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">
                    {{ section.state.name }} Chapter
                    {{ section.state.code_title }}
                    {{ section.chapter.code }}, Section
                    {{ section.code }}
                </div>
            </div>
            <div :id="section.code" class="font-semibold border-b">
                <span class="text-lg">Section {{ section.code }}</span>
                - {{ section.description }}
            </div>
            <div class="ml-5 mt-3 text-sm">
                <span class="font-semibold">Source: </span>
                <a
                    :href="section.url"
                    class="text-blue-800 underline hover:text-blue-500"
                    target="_blank"
                    rel="nofollow"
                    >{{ section.url }}</a
                >
            </div>
            <div class="ml-5 my-3 prose max-w-full">
                {{ section.content }}
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Dropdown from "@/Jetstream/Dropdown";
import DropdownLink from "@/Jetstream/DropdownLink";

export default defineComponent({
    components: {
        DropdownLink,
        Dropdown,
        Link,
        AppLayout,
    },
    props: {
        section: Object,
    },
});
</script>
