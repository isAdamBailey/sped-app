<template>
    <toggle v-model:checked="form.active" @update:checked="onUpdateChecked" />
</template>

<script>
import { defineComponent } from "vue";
import Toggle from "@/Jetstream/Toggle";

export default defineComponent({
    components: { Toggle },

    props: {
        chapter: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            form: this.$inertia.form(
                {
                    _method: "PUT",
                    active: this.chapter.active,
                },
                {
                    bag: "updateChapter",
                    resetOnSuccess: true,
                }
            ),
        };
    },

    methods: {
        onUpdateChecked() {
            console.log(this.chapter);
            this.form.post(route("chapters.update", this.chapter), {
                preserveScroll: true,
            });
        },
    },
});
</script>
