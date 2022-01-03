<template>
    <app-layout :title="`${chapter.state.code_title} ${chapter.code}`">
        <template #header>
            <Link :href="route('chapters.index')">State Laws</Link> / Chapter
            {{ chapter.state.code_title }} {{ chapter.code }}
        </template>

        <div class="p-10">
            <div class="text-2xl font-bold mb-5">Sections</div>
            <div v-for="(section, index) in chapter.sections" :key="index">
                <div class="font-semibold">
                    <span class="text-lg">Section {{ section.code }}</span>
                    - {{ section.description }}
                </div>
                <div class="ml-5 prose max-w-full">
                    <div class="text-blue-600 text-sm">{{ section.url }}</div>
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

export default defineComponent({
    components: {
        Link,
        AppLayout,
    },
    props: {
        chapter: Object,
    },
});
</script>
