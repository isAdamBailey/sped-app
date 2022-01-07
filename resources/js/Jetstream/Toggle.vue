<template>
    <div class="flex">
        <label for="toggle" class="flex items-center cursor-pointer">
            <span class="relative">
                <input
                    id="toggle"
                    v-model="proxyChecked"
                    type="checkbox"
                    class="sr-only"
                    :value="value"
                />
                <span class="block bg-gray-300 w-14 h-8 rounded-full"></span>
                <span
                    class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"
                ></span>
            </span>
            <span v-if="label">{{ label }}</span>
        </label>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        checked: {
            type: [Array, Boolean, Number],
            default: false,
        },
        value: {
            type: [String, Number],
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
    },
    emits: ["update:checked"],

    computed: {
        proxyChecked: {
            get() {
                return this.checked.length
                    ? this.checked
                    : Boolean(this.checked);
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
});
</script>
