<template>
    <info-text class="mb-3"
        >Select or remove permissions from the dropdown below.</info-text
    >

    <Multiselect
        id="permissions"
        v-model="form.permissions"
        mode="tags"
        :create-tag="true"
        :options="permissions"
        placeholder="Assign permissions"
    />

    <jet-button
        :disabled="!form.isDirty"
        class="mt-3"
        @click="updatePermissions"
        >Save permissions</jet-button
    >
</template>

<script>
import { defineComponent } from "vue";
import Multiselect from "@vueform/multiselect";
import JetButton from "@/Jetstream/Button";
import InfoText from "@/Jetstream/InfoText";

export default defineComponent({
    components: { InfoText, Multiselect, JetButton },

    props: {
        userObject: Object,
        permissions: Array,
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                permissions: this.userObject.permissions_names,
            }),
        };
    },

    methods: {
        updatePermissions() {
            this.form.post(route("users.update", this.userObject.id), {
                errorBag: "updateUser",
            });
        },
    },
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
