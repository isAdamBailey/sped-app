<template>
    <app-layout title="State Laws">
        <template #header>State Laws</template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">Chapters</div>
                <search-input route-name="chapters.index" />
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
import SearchInput from "../Jetstream/SearchInput";

export default defineComponent({
    components: {
        SearchInput,
        Link,
        AppLayout,
    },
    props: {
        chapters: Object,
    },
});
</script>
