<template>
    <dashboard-layout title="User">
        <div class="m-5 rounded-lg bg-white p-5 shadow-xl">
            <div class="mb-3 flex items-center">
                <img
                    class="mr-3 h-12 w-12 rounded-full object-cover"
                    :src="userObject.profile_photo_url"
                    :alt="userObject.name"
                />
                <div class="text-2xl">{{ userObject.name }}</div>
            </div>
            <div>{{ userObject.email }}</div>
            <div
                class="mt-5 grid gap-1 rounded-lg bg-gray-200 shadow-xl md:grid-cols-2"
            >
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="border-b text-lg font-semibold">Teams</h3>
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
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Users
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-1 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Role
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    team, index
                                                ) in userObject.teams"
                                                :key="index"
                                                class="border-b bg-white"
                                            >
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"
                                                >
                                                    {{ team.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900"
                                                >
                                                    {{ team.users_count }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm"
                                                    :class="
                                                        team.membership.role ===
                                                        'editor'
                                                            ? 'text-blue-600'
                                                            : 'text-purple-600'
                                                    "
                                                >
                                                    {{ team.membership.role }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">
                        Permissions
                    </h3>
                    <assign-permissions
                        :user-object="userObject"
                        :permissions="permissions"
                    />
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
                <div class="m-3 rounded-lg bg-white p-3">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
            </div>
        </div>
    </dashboard-layout>
</template>

<script>
import { defineComponent } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout";
import AssignPermissions from "@/Pages/Dashboard/Users/Partials/AssignPermissions";

export default defineComponent({
    components: {
        AssignPermissions,
        DashboardLayout,
    },

    props: {
        userObject: Object,
        permissions: Array,
    },
});
</script>
