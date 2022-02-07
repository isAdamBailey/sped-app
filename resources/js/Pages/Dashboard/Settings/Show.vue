<template>
    <dashboard-layout title="Site Settings">
        <div class="m-5 rounded-lg bg-white p-5 shadow-xl">
            <div
                class="mt-5 grid gap-1 rounded-lg bg-gray-200 shadow-xl md:grid-cols-2"
            >
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="border-b text-lg font-semibold">Registration</h3>
                    <div class="flex flex-col">
                        <info-text class="mb-3"
                            >change site-wide registration settings
                            below.</info-text
                        >

                        <label class="flex items-center">
                            <jet-checkbox
                                v-model:checked="form.registration_active"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Registration Active</span
                            >
                        </label>

                        <jet-button
                            :disabled="!form.isDirty"
                            class="mt-5"
                            @click="updateSettings"
                            >Save site settings</jet-button
                        >
                    </div>
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
            </div>
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout";
import JetButton from "@/Jetstream/Button";
import InfoText from "@/Jetstream/InfoText";
import JetCheckbox from "@/Jetstream/Checkbox";

export default defineComponent({
    components: { JetCheckbox, InfoText, DashboardLayout, JetButton },

    props: {
        settings: Object,
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                registration_active: Boolean(this.settings.registration_active),
            }),
        };
    },

    methods: {
        updateSettings() {
            this.form.post(route("site-settings.update", this.settings.id), {
                errorBag: "updateSettings",
            });
        },
    },
});
</script>
