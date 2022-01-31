<template>
    <dashboard-layout title="Users">
        <div class="m-5 rounded-lg bg-white p-5 shadow-xl">
            <div class="p-10">
                <div class="flex justify-between">
                    <div class="mb-5 text-2xl font-bold">Users</div>

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
                                        filter: 'team_editor',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                Team Editor
                            </dropdown-link>
                            <dropdown-link
                                :href="
                                    route('users.index', {
                                        filter: 'edit_users',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                Can Edit Users
                            </dropdown-link>
                            <dropdown-link
                                :href="
                                    route('users.index', {
                                        filter: 'edit_chapters',
                                        search: $page.props.search,
                                    })
                                "
                            >
                                Can Edit Chapters
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
                                class="inline-block min-w-full py-2 sm:px-6 lg:px-8"
                            >
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="border-b">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Email
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-900"
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
                                                class="cursor-pointer border-b bg-white transition hover:bg-blue-100"
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
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"
                                                >
                                                    {{ user.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900"
                                                >
                                                    {{ user.email }}
                                                </td>
                                                <td
                                                    :class="
                                                        userRole(user) ===
                                                        'site administrator'
                                                            ? 'text-blue-600'
                                                            : 'text-purple-600'
                                                    "
                                                    class="whitespace-nowrap px-6 py-4 text-sm"
                                                >
                                                    {{ userRole(user) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <load-more
                        title="Users"
                        :items="users"
                        @append-items="onAppendUsers"
                    />
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
import DashboardLayout from "@/Layouts/DashboardLayout";
import InfoText from "@/Jetstream/InfoText";
import LoadMore from "@/Components/LoadMore";

export default defineComponent({
    components: {
        LoadMore,
        InfoText,
        DashboardLayout,
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
        };
    },
    computed: {
        dropdownText() {
            let text = "Filter By Permissions";

            if (this.$inertia.page.props.filter) {
                text = this.$page.props.filter;
            }
            return text;
        },
    },
    methods: {
        userRole(user) {
            // does the user have ANY of the possible admin roles?
            const roles = ["edit chapters", "edit users"].some((perm) =>
                user.permissions.includes(perm)
            );
            const teamPermission = user.teams[0].membership.role;
            return roles ? "site administrator" : `team ${teamPermission}`;
        },
        onAppendUsers(event) {
            this.usersData.push(...event);
        },
    },
});
</script>
