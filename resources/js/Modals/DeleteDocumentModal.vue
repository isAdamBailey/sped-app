<template>
    <jet-confirmation-modal :show="open" @close="closeModal()">
        <template #title> Delete {{ document.name }} </template>

        <template #content>
            Are you sure you want to delete {{ document.name }}? All of its data
            will be permanently deleted.
        </template>

        <template #footer>
            <jet-secondary-button @click="closeModal()">
                Nevermind
            </jet-secondary-button>

            <jet-danger-button
                class="ml-2"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="deleteDocument"
            >
                Delete Document
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetDangerButton from "@/Jetstream/DangerButton";

export default defineComponent({
    components: { JetConfirmationModal, JetSecondaryButton, JetDangerButton },
    props: {
        open: {
            type: Boolean,
            default: false,
        },
        document: {
            type: Object,
            required: true,
        },
    },
    emits: ["close"],
    data() {
        return {
            form: this.$inertia.form({
                _method: "DELETE",
            }),
        };
    },
    methods: {
        deleteDocument() {
            this.form.delete(route("documents.destroy", this.document.id), {
                errorBag: "destroyDocument",
                onSuccess: () => this.closeModal(),
            });
        },
        closeModal() {
            this.form.clearErrors();
            this.form.reset();
            this.$emit("close", true);
        },
    },
});
</script>
