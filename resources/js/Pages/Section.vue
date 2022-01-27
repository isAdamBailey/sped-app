<template>
    <app-layout :title="`${section.state.code_title} ${section.code}`">
        <template #header>
            <Link
                class="text-blue-600 underline hover:text-blue-400"
                :href="route('sections.index')"
                >Laws</Link
            >
            / Section {{ section.state.code_title }}
            {{ section.code }}
        </template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="mb-5 text-2xl font-bold">
                    {{ section.state.name }} Chapter
                    {{ section.state.code_title }}
                    {{ section.chapter.code }}, Section
                    {{ section.code }}
                </div>
            </div>
            <div :id="section.code" class="border-b font-semibold">
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
            <div class="prose my-3 ml-5 max-w-full">
                {{ section.content }}
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
    components: {
        AppLayout,
    },
    props: {
        section: Object,
    },
});
</script>
