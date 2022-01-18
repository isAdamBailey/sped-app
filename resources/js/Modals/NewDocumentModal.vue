<template>
    <jet-dialog-modal :show="open" @close="closeModal()">
        <template #title>New Document</template>

        <template #content>
            <div class="mt-3 col-span-6 sm:col-span-4">
                <jet-label for="name" value="Document Title" />
                <jet-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 w-full"
                    autocomplete="name"
                />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-3 col-span-6">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <jet-label
                            for="next_action_date"
                            value="Date of next expected action"
                        />
                        <date-picker v-model="form.next_action_date" />
                        <jet-input-error
                            :message="form.errors.next_action_date"
                            class="mt-2"
                        />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <jet-label for="document" :value="`${fileText} file`" />
                        <jet-input
                            id="fileInput"
                            name="document"
                            type="file"
                            class="hidden"
                            @input="form.document = $event.target.files[0]"
                        />
                        <jet-button @click="getFile">{{ fileText }}</jet-button>
                        <jet-input-error
                            :message="form.errors.document"
                            class="mt-2"
                        />
                    </div>
                </div>
            </div>

            <div class="mt-3 col-span-6 sm:col-span-4">
                <jet-label for="description" value="Description" />
                <wysiwyg v-model="form.description" />
                <jet-input-error
                    :message="form.errors.description"
                    class="mt-2"
                />
            </div>
            <progress
                v-if="form.progress && form.document"
                :value="form.progress.percentage"
                max="100"
            >
                {{ form.progress.percentage }}%
            </progress>
        </template>

        <template #footer>
            <jet-secondary-button @click="closeModal()">
                Nevermind
            </jet-secondary-button>

            <jet-button
                class="ml-2"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="storeDocument"
            >
                Add Document
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
import { defineComponent } from "vue";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import DatePicker from "@/Jetstream/DatePicker";
import Wysiwyg from "@/Jetstream/Wysiwyg";
import JetButton from "@/Jetstream/Button";

export default defineComponent({
    components: {
        JetButton,
        Wysiwyg,
        JetDialogModal,
        JetSecondaryButton,
        JetInput,
        JetInputError,
        JetLabel,
        DatePicker,
    },

    props: {
        open: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["close"],

    data() {
        return {
            form: this.$inertia.form({
                name: null,
                description: null,
                next_action_date: null,
                document: null,
            }),
        };
    },

    computed: {
        fileText() {
            return this.form.document ? "Replace" : "Upload";
        },
    },

    methods: {
        getFile() {
            let fileUpload = document.getElementById("fileInput");
            if (fileUpload !== null) {
                fileUpload.click();
            }
        },

        storeDocument() {
            this.form.post(route("documents.store"), {
                errorBag: "storeDocument",
                onSuccess: () => this.closeModal(),
            });
        },

        closeModal() {
            this.form.reset();
            this.form.clearErrors();
            this.$emit("close", true);
        },
    },
});
</script>
