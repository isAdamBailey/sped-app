<template>
    <Link :href="'/'">
        <application-mark />
    </Link>
</template>

<script>
import { defineComponent } from "vue";
import ApplicationMark from "@/Jetstream//ApplicationMark";

export default defineComponent({
    components: {
        ApplicationMark,
    },
});
</script>
