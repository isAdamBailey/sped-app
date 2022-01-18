<template>
    <dashboard-layout title="Users">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div class="p-10">
                <div class="flex justify-between">
                    <div class="text-2xl font-bold mb-5">Users</div>

                    <search-input
                        route-name="users.index"
                        :result-count="users.total"
                    />

                    <jet-dropdown
                        width="48"
                        :arrow-trigger-title="dropdownText"
                    >
                        <template #content>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Filter By:
                            </div>
                            <dropdown-link
                                :href="
                                    route('users.index', {
                                        filter: 'super_admin',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                Super Admin
                            </dropdown-link>
                            <dropdown-link :href="route('users.index')">
                                <i class="ri-filter-off-fill"></i> Clear
                            </dropdown-link>
                        </template>
                    </jet-dropdown>
                </div>

                <info-text class="mb-5">
                    Administer users from this page
                </info-text>
                <div v-if="usersData.length">
                    <div
                        v-for="(user, index) in usersData"
                        :key="index"
                        class="flex items-center"
                    >
                        <div class="p-2">
                            <span class="font-bold">
                                {{ user.name }}
                            </span>
                            -
                            {{ user.email }}
                        </div>
                    </div>
                    <div
                        v-if="users.next_page_url"
                        class="flex justify-center mt-7"
                    >
                        <jet-button @click="loadMore">
                            Load More Users
                        </jet-button>
                    </div>
                </div>
                <div v-else class="my-12 text-gray-500">No users found</div>
            </div>
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import SearchInput from "@/Jetstream/SearchInput";
import JetDropdown from "@/Jetstream/Dropdown";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetButton from "@/Jetstream/Button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import InfoText from "@/Jetstream/InfoText";

export default defineComponent({
    components: {
        InfoText,
        DashboardLayout,
        JetButton,
        DropdownLink,
        JetDropdown,
        SearchInput,
    },
    props: {
        users: Object,
    },
    data() {
        return {
            usersData: this.users.data,
            loadingMore: false,
        };
    },
    computed: {
        dropdownText() {
            let text = "Filter By Role";

            if (this.$inertia.page.props.filter) {
                text = this.$page.props.filter;
            }
            return text;
        },
    },
    watch: {
        users() {
            if (this.loadingMore) {
                this.usersData.push(...this.users.data);
                this.loadingMore = false;
            }
        },
    },
    methods: {
        loadMore() {
            this.loadingMore = true;

            const links = this.users.links;
            const next = links[links.length - 1];
            if (next.url) {
                this.$inertia.get(
                    next.url,
                    {
                        search: this.$inertia.page.props.search,
                        filter: this.$inertia.page.props.filter,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                    }
                );
            }
        },
    },
});
</script>
