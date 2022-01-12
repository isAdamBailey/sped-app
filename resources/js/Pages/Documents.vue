<template>
    <app-layout title="Documentation">
        <template #header>Documentation</template>

        <div class="p-10">
            <div class="flex justify-between items-start">
                <div class="text-2xl font-bold mb-5">Documentation</div>

                <search-input
                    route-name="documents.index"
                    :result-count="documents.total"
                />

                <jet-button
                    class="flex items-center h-10"
                    @click="newDocumentModalOpen = true"
                >
                    <i class="text-3xl mr-2 ri-file-add-fill"></i>
                    New Document
                </jet-button>
            </div>
            <info-text
                >This is your teams documentation. Everyone on your team can
                view these items.</info-text
            >
            <div v-if="documentsData.length">
                <div
                    class="grid grid-cols-1 md:grid-cols-[repeat(auto-fit,minmax(20rem,1fr))] gap-4 mx-auto md:p-4"
                >
                    <div
                        v-for="(doc, index) in documentsData"
                        :key="index"
                        class="flex items-center rounded border shadow hover:shadow-lg transition"
                    >
                        <Link>
                            <div class="p-3">
                                <h3 class="font-bold">{{ doc.name }}</h3>
                                <p v-html="doc.description" />
                            </div>
                        </Link>
                    </div>
                </div>
                <div
                    v-if="documents.next_page_url"
                    class="flex justify-center mt-7"
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
import { Link } from "@inertiajs/inertia-vue3";
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
        Link,
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
