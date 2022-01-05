<template>
    <app-layout :title="`${chapter.state.code_title} ${chapter.code}`">
        <template #header>
            <Link
                class="underline text-blue-600 hover:text-blue-400"
                :href="route('chapters.index')"
                >State Laws</Link
            >
            / Chapter {{ chapter.state.code_title }} {{ chapter.code }}
        </template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">
                    {{ chapter.state.name }} Chapter
                    {{ chapter.state.code_title }}
                    {{ chapter.code }}
                </div>
                <dropdown width="60" arrow-trigger-title="Go to section">
                    <template #content>
                        <dropdown-link
                            v-for="(section, index) in chapter.sections"
                            :key="index"
                            :href="`#${section.code}`"
                        >
                            <p class="truncate">
                                <span class="font-semibold">{{
                                    section.code
                                }}</span>
                                {{ section.description }}
                            </p>
                        </dropdown-link>
                    </template>
                </dropdown>
            </div>
            <div v-for="(section, index) in chapter.sections" :key="index">
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
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Dropdown from "../Jetstream/Dropdown";
import DropdownLink from "../Jetstream/DropdownLink";

export default defineComponent({
    components: {
        DropdownLink,
        Dropdown,
        Link,
        AppLayout,
    },
    props: {
        chapter: Object,
    },
});
</script>
