<template>
    <div class="space-y-1 pt-2 pb-3">
        <jet-responsive-nav-link
            :href="route('laws.index')"
            :active="route().current('laws.*')"
        >
            Laws
        </jet-responsive-nav-link>

        <jet-responsive-nav-link
            v-if="$page.props.user.name"
            :href="route('documents.index')"
            :active="route().current('documents.*')"
        >
            Documentation
        </jet-responsive-nav-link>
    </div>

    <!-- Responsive Settings Options -->
    <div class="border-t border-gray-200 pt-4 pb-1">
        <div class=".namepx-4 flex items-center">
            <div
                v-if="
                    $page.props.user.name &&
                    $page.props.jetstream.managesProfilePhotos
                "
                class="mr-3 shrink-0"
            >
                <img
                    class="h-10 w-10 rounded-full object-cover"
                    :src="$page.props.user.profile_photo_url"
                    :alt="$page.props.user.name"
                />
            </div>

            <div v-if="$page.props.user.name">
                <div class="text-base font-medium text-gray-800">
                    {{ $page.props.user.name }}
                </div>
                <div class="text-sm font-medium text-gray-500">
                    {{ $page.props.user.email }}
                </div>
            </div>
        </div>

        <div v-if="$page.props.user.name" class="mt-3 space-y-1">
            <jet-responsive-nav-link
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                Dashboard
            </jet-responsive-nav-link>
            <jet-responsive-nav-link
                :href="route('profile.show')"
                :active="route().current('profile.show')"
            >
                Profile
            </jet-responsive-nav-link>

            <jet-responsive-nav-link
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')"
                :active="route().current('api-tokens.index')"
            >
                API Tokens
            </jet-responsive-nav-link>

            <!-- Authentication -->
            <form method="POST" @submit.prevent="logout">
                <jet-responsive-nav-link as="button">
                    Log Out
                </jet-responsive-nav-link>
            </form>

            <!-- Team Management -->
            <template v-if="$page.props.jetstream.hasTeamFeatures">
                <div class="border-t border-gray-200"></div>

                <div class="block px-4 py-2 text-xs text-gray-400">
                    Manage Team
                </div>

                <!-- Team Settings -->
                <jet-responsive-nav-link
                    :href="route('teams.show', $page.props.user.current_team)"
                    :active="route().current('teams.show')"
                >
                    Team Settings
                </jet-responsive-nav-link>

                <jet-responsive-nav-link
                    v-if="$page.props.jetstream.canCreateTeams"
                    :href="route('teams.create')"
                    :active="route().current('teams.create')"
                >
                    Create New Team
                </jet-responsive-nav-link>

                <!-- Team Switcher -->
                <div v-if="$page.props.user.all_teams.length > 1">
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Switch Teams
                    </div>

                    <template
                        v-for="team in $page.props.user.all_teams"
                        :key="team.id"
                    >
                        <form @submit.prevent="switchToTeam(team)">
                            <jet-responsive-nav-link as="button">
                                <div class="flex items-center">
                                    <svg
                                        v-if="
                                            team.id ===
                                            $page.props.user.current_team_id
                                        "
                                        class="mr-2 h-5 w-5 text-green-400"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    <div>{{ team.name }}</div>
                                </div>
                            </jet-responsive-nav-link>
                        </form>
                    </template>
                </div>
            </template>
        </div>
        <jet-responsive-nav-link v-else :href="route('login')">
            Log In
        </jet-responsive-nav-link>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";

export default defineComponent({
    components: {
        JetResponsiveNavLink,
    },

    methods: {
        switchToTeam(team) {
            this.$inertia.put(
                route("current-team.update"),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                }
            );
        },

        logout() {
            this.$inertia.post(route("logout"));
        },
    },
});
</script>
