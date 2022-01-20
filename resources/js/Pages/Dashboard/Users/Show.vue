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
                class="mt-10 bg-gray-200 shadow-xl rounded-lg grid md:grid-cols-2 gap-2"
            >
                <div class="m-3 p-3 bg-white rounded-lg">
                    <h3 class="border-b text-lg font-semibold">Teams</h3>
                    <ul>
                        <li
                            v-for="(team, index) in userObject.teams"
                            :key="index"
                            class="mt-2 flex justify-between"
                        >
                            <span>{{ team.name }}</span>
                            <span
                                class="px-2 rounded bg-yellow-300"
                                :class="
                                    team.membership.role === 'editor'
                                        ? 'bg-yellow-300'
                                        : 'bg-blue-400 text-white'
                                "
                                >{{ team.membership.role }}</span
                            >
                        </li>
                    </ul>
                </div>
                <div class="m-2 p-5 bg-white rounded-lg">
                    <h3 class="mb-3 border-b text-lg font-semibold">
                        Permissions
                    </h3>
                    <assign-permissions
                        :user-object="userObject"
                        :permissions="permissions"
                    />
                </div>
                <div class="m-2 p-5 bg-white rounded-lg">tile</div>
                <div class="m-2 p-5 bg-white rounded-lg">tile</div>
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
