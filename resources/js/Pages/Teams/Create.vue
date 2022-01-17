<template>
    <home-layout title="Create Team">
        <template #header>Create Team</template>

        <div v-if="firstTeam">
            <h1 class="mb-10 text-3xl text-gray-700">
                We're glad you have registered!
                <span class="text-gray-500"
                    >Let's figure out where you belong.</span
                >
            </h1>
            <div class="mb-10 text-xl text-gray-700">You're here because:</div>
            <div class="flex justify-around">
                <button
                    :class="leaderClass"
                    class="flex justify-center items-center mr-5 bg-green-100 hover:bg-green-600 focus:bg-green-600 p-3 rounded-xl text-gray-900 focus:text-white hover:text-white uppercase transition"
                    @click="clickLeader"
                >
                    <i class="text-3xl md:mr-3 ri-user-add-fill"></i>
                    I am creating my own team where i am the admin
                </button>
                <button
                    :class="invitedClass"
                    class="flex justify-center items-center bg-blue-100 hover:bg-blue-600 focus:bg-blue-600 p-3 rounded-xl text-gray-900 focus:text-white hover:text-white uppercase transition"
                    @click="clickInvited"
                >
                    <i class="text-3xl md:mr-3 ri-team-fill"></i>
                    I was invited to join an existing team
                </button>
            </div>

            <transition-group
                enter-active-class="transition-all ease-out duration-500 transform"
                leave-active-class="transition-all ease-in duration-200 transform"
                enter-from-class="opacity-0 scale-70"
                leave-from-class="opacity-100 scale-100"
                enter-to-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-70"
            >
                <div v-if="isLeader" class="origin-top-right right-0">
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        <create-team-form />
                    </div>
                </div>
                <div v-if="isInvited" class="origin-top-right right-0">
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        <p class="mb-5 text-lg text-gray-500">
                            If you were sent here from an email inviting you to
                            a team,
                            <span class="text-lg text-gray-900"
                                >go back to that email, and click the "Accept
                                Invitation" button.</span
                            >
                        </p>
                        <p>
                            Didn't get a team invitation email? Ask a member of
                            an existing team to add you to their team, or to
                            send it again.
                        </p>
                    </div>
                </div>
            </transition-group>
        </div>

        <div v-else class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <create-team-form />
        </div>
    </home-layout>
</template>

<script>
import { defineComponent } from "vue";
import HomeLayout from "@/Layouts/HomeLayout.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";

export default defineComponent({
    components: {
        HomeLayout,
        CreateTeamForm,
    },

    props: {
        firstTeam: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            isLeader: false,
            isInvited: false,
        };
    },

    computed: {
        leaderClass() {
            if (!this.isLeader && !this.isInvited) {
                return "w-1/2";
            }
            return this.isLeader
                ? "w-3/4 text-lg font-semibold"
                : "w-1/4 text-sm";
        },
        invitedClass() {
            if (!this.isLeader && !this.isInvited) {
                return "w-1/2";
            }
            return this.isInvited
                ? "w-3/4 text-lg font-semibold"
                : "w-1/4 text-sm";
        },
    },

    methods: {
        clickInvited() {
            this.isInvited = true;
            this.isLeader = false;
        },
        clickLeader() {
            this.isLeader = true;
            this.isInvited = false;
        },
    },
});
</script>
