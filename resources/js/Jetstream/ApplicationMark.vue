<template>
    <i :class="combinedClasses" class="ri-user-heart-fill"></i>
</template>

<script>
export default {
    props: {
        size: {
            type: String,
            default: "text-9xl",
        },
        color: {
            type: String,
            default: "text-blue-900",
        },
    },

    computed: {
        combinedClasses() {
            return `${this.size} ${this.color}`;
        },
    },
};
</script>
