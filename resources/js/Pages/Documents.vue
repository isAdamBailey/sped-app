<template>
    <app-layout title="Documentation">
        <template #header>Documentation</template>

        <div class="p-10">
            <div class="flex flex-wrap items-start justify-between">
                <div class="mb-5 text-2xl font-bold">Documentation</div>

                <search-input
                    route-name="documents.index"
                    :result-count="documents.total"
                />

                <jet-button
                    class="flex h-10 items-center"
                    @click="newDocumentModalOpen = true"
                >
                    <i class="ri-file-add-fill text-xl"></i>
                    <span class="hidden md:ml-3 md:block">New Document</span>
                </jet-button>
            </div>
            <info-text
                >This is your teams documentation. Everyone on your team can
                view these items.</info-text
            >
            <div v-if="documentsData.length">
                <div
                    class="mx-auto grid grid-cols-1 gap-4 md:grid-cols-[repeat(auto-fit,minmax(20rem,1fr))] md:p-4"
                >
                    <Link
                        v-for="(doc, index) in documentsData"
                        :key="index"
                        :href="route('documents.show', doc.id)"
                        class="rounded border bg-gradient-to-tr from-blue-200 to-blue-800 shadow shadow-blue-500/50 transition hover:shadow-lg hover:shadow-blue-500/50"
                    >
                        <div
                            class="flex justify-between rounded-t bg-white p-3"
                        >
                            <h3 class="font-bold">{{ doc.name }}</h3>
                            <i
                                v-if="doc.file_url"
                                class="ri-folder-upload-fill text-2xl text-blue-800"
                            ></i>
                        </div>

                        <p
                            class="prose m-3 max-h-60 max-w-full overflow-hidden rounded bg-white p-3"
                            v-html="doc.description"
                        />
                    </Link>
                </div>
                <div
                    v-if="documents.next_page_url"
                    class="mt-7 flex justify-center"
                >
                    <jet-button @click="loadMore">
                        Load More Documentation
                    </jet-button>
                </div>
            </div>
            <div v-else class="my-12 text-gray-500">No documents found</div>
        </div>
    </app-layout>

    <new-document-modal
        :open="newDocumentModalOpen"
        @close="newDocumentModalOpen = false"
    />
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SearchInput from "@/Jetstream/SearchInput";
import JetButton from "@/Jetstream/Button";
import InfoText from "../Jetstream/InfoText";
import NewDocumentModal from "../Modals/NewDocumentModal";

export default defineComponent({
    components: {
        NewDocumentModal,
        InfoText,
        JetButton,
        SearchInput,
        AppLayout,
    },
    props: {
        documents: Object,
    },
    data() {
        return {
            documentsData: this.documents.data,
            loadingMore: false,
            newDocumentModalOpen: false,
        };
    },
    watch: {
        documents() {
            if (this.loadingMore) {
                this.documentsData.push(...this.documents.data);
                this.loadingMore = false;
            } else {
                this.documentsData = this.documents.data;
            }
        },
    },
    methods: {
        loadMore() {
            this.loadingMore = true;

            const links = this.documents.links;
            const next = links[links.length - 1];
            if (next.url) {
                this.$inertia.get(
                    next.url,
                    {
                        search: this.$inertia.page.props.search,
                        filter: this.$inertia.page.props.filter,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                    }
                );
            }
        },
    },
});
</script>
