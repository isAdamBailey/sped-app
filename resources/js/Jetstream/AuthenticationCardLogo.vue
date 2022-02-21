<template>
    <Link :href="'/'">
        <application-mark
            color="text-orange-300"
            class="rounded-full bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 p-3 text-center lg:mb-0"
        />
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
