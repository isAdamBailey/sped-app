<template>
    <app-layout :title="document.name">
        <template #header>
            <Link
                class="underline text-blue-600 hover:text-blue-400"
                :href="route('documents.index')"
                >Documentation</Link
            >
            / {{ document.name }}
        </template>

        <div class="p-10">
            <div class="flex justify-between">
                <div class="text-2xl font-bold mb-5">
                    {{ document.name }}
                </div>
            </div>
            <div
                class="ml-5 my-3 prose max-w-full"
                v-html="document.description"
            ></div>
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
        document: Object,
    },
});
</script>
