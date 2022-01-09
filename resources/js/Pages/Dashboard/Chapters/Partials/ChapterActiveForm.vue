<template>
    <checkbox v-model:checked="form.active" @update:checked="onUpdateChecked" />
</template>

<script>
import { defineComponent } from "vue";
import Checkbox from "@/Jetstream/Checkbox";

export default defineComponent({
    components: { Checkbox },

    props: {
        chapter: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                active: Boolean(this.chapter.active),
            }),
        };
    },

    methods: {
        onUpdateChecked() {
            this.form.post(route("chapters.update", this.chapter), {
                preserveScroll: true,
            });
        },
    },
});
</script>
