<template>
    <dashboard-layout title="User">
        <div class="m-5 p-5 bg-white shadow-xl rounded-lg">
            <div class="flex items-center mb-3">
                <img
                    class="w-12 h-12 mr-3 rounded-full object-cover"
                    :src="userObject.profile_photo_url"
                    :alt="userObject.name"
                />
                <div class="text-2xl">{{ userObject.name }}</div>
            </div>
            <div>{{ userObject.email }}</div>
            <div
                class="mt-5 bg-gray-200 shadow-xl rounded-lg grid md:grid-cols-2 gap-1"
            >
                <div class="m-3 p-3 bg-white rounded-lg">
                    <h3 class="border-b text-lg font-semibold">Teams</h3>
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
                                                    class="text-sm font-semibold text-gray-900 px-6 py-1 text-left"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="text-sm font-semibold text-gray-900 px-6 py-1 text-left"
                                                >
                                                    Users
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="text-sm font-semibold text-gray-900 px-6 py-1 text-left"
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
                                                class="bg-white border-b"
                                            >
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                >
                                                    {{ team.name }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                                >
                                                    {{ team.users_count }}
                                                </td>
                                                <td
                                                    class="text-sm px-6 py-4 whitespace-nowrap"
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
                <div class="m-3 p-3 bg-white rounded-lg">
                    <h3 class="mb-2 border-b text-lg font-semibold">
                        Permissions
                    </h3>
                    <assign-permissions
                        :user-object="userObject"
                        :permissions="permissions"
                    />
                </div>
                <div class="m-3 p-3 bg-white rounded-lg">
                    <h3 class="mb-2 border-b text-lg font-semibold">TBD</h3>
                </div>
                <div class="m-3 p-3 bg-white rounded-lg">
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
