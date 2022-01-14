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
                <div class="flex items-center">
                    <jet-button
                        v-if="$page.props.teamPermissions.canUpdate"
                        class="mr-3 flex items-center"
                        @click="editDocumentModalOpen = true"
                    >
                        <i class="ri-file-edit-fill"></i>
                        <span class="hidden md:block md:ml-3"
                            >Edit Document</span
                        >
                    </jet-button>
                    <danger-button
                        v-if="$page.props.teamPermissions.canDelete"
                        class="flex items-center"
                        @click="deleteDocumentModalOpen = true"
                    >
                        <i class="ri-delete-bin-fill"></i>
                    </danger-button>
                </div>
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
    <delete-document-modal
        :document="document"
        :open="deleteDocumentModalOpen"
        @close="deleteDocumentModalOpen = false"
    />
</template>

<script>
import { defineComponent } from "vue";
import { format, parseISO } from "date-fns";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button";
import EditDocumentModal from "@/Modals/EditDocumentModal";
import DeleteDocumentModal from "@/Modals/DeleteDocumentModal";
import DangerButton from "@/Jetstream/DangerButton";

export default defineComponent({
    components: {
        DangerButton,
        DeleteDocumentModal,
        EditDocumentModal,
        JetButton,
        AppLayout,
    },
    props: {
        document: Object,
    },
    data() {
        return {
            editDocumentModalOpen: false,
            deleteDocumentModalOpen: false,
        };
    },
    methods: {
        formatDate(date) {
            return format(parseISO(date), "PPP");
        },
    },
});
</script>
