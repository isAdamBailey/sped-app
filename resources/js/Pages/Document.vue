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
                <jet-button
                    class="flex items-center h-10"
                    @click="editDocumentModalOpen = true"
                >
                    <i class="text-xl ri-file-edit-fill"></i>
                    <span class="hidden md:block md:ml-3">Edit Document</span>
                </jet-button>
            </div>
            <div v-if="document.next_action_date">
                This document has been marked for
                <span class="font-semibold">{{
                    formatDate(document.next_action_date)
                }}</span>
            </div>

            <div
                class="ml-5 mt-3 mb-16 prose max-w-full"
                v-html="document.description"
            ></div>

            <a
                v-if="document.file_url"
                :href="document.file_url"
                title="View document"
                target="_blank"
            >
                <jet-button>View Uploaded File</jet-button>
            </a>
        </div>
    </app-layout>
    <edit-document-modal
        :document="document"
        :open="editDocumentModalOpen"
        @close="editDocumentModalOpen = false"
    />
</template>

<script>
import { defineComponent } from "vue";
import { format, parseISO } from "date-fns";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button";
import EditDocumentModal from "@/Modals/EditDocumentModal";

export default defineComponent({
    components: {
        EditDocumentModal,
        Link,
        JetButton,
        AppLayout,
    },
    props: {
        document: Object,
    },
    data() {
        return {
            editDocumentModalOpen: false,
        };
    },
    methods: {
        formatDate(date) {
            return format(parseISO(date), "PPP");
        },
    },
});
</script>
