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
                                        filter: 'team_admin',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                Team Admin
                            </dropdown-link>
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
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div
                                class="py-2 inline-block min-w-full sm:px-6 lg:px-8"
                            >
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="border-b">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="text-sm font-semibold text-gray-900 px-6 py-4 text-left"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="text-sm font-semibold text-gray-900 px-6 py-4 text-left"
                                                >
                                                    Email
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="text-sm font-semibold text-gray-900 px-6 py-4 text-left"
                                                >
                                                    Role
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    user, index
                                                ) in usersData"
                                                :key="index"
                                                class="bg-white border-b transition hover:bg-blue-100 cursor-pointer"
                                                @click="
                                                    $inertia.get(
                                                        route(
                                                            'users.show',
                                                            user.id
                                                        )
                                                    )
                                                "
                                            >
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                    {{ user.name }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                                >
                                                    {{ user.email }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                                >
                                                    {{
                                                        user.roles[0]?.name ||
                                                        "user"
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
