<template>
    <nav class="border-b border-gray-100 bg-white">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link
                            :href="
                                $page.props.user.name
                                    ? route('dashboard')
                                    : route('home')
                            "
                        >
                            <jet-application-mark
                                size="text-4xl"
                                color="text-orange-300"
                                class="rounded-full bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 p-2 text-center"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <jet-nav-link
                            :href="route('laws.index')"
                            :active="route().current('laws.*')"
                        >
                            Laws
                        </jet-nav-link>
                        <jet-nav-link
                            v-if="$page.props.user.name"
                            :href="route('documents.index')"
                            :active="route().current('documents.*')"
                        >
                            Documentation
                        </jet-nav-link>
                    </div>
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="relative ml-3">
                        <!-- Teams Dropdown -->
                        <jet-dropdown
                            v-if="
                                $page.props.user.name &&
                                $page.props.jetstream.hasTeamFeatures
                            "
                            align="right"
                            width="60"
                        >
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700 focus:bg-gray-50 focus:outline-none active:bg-gray-50"
                                    >
                                        {{ $page.props.user.current_team.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <template
                                        v-if="
                                            $page.props.jetstream
                                                .hasTeamFeatures
                                        "
                                    >
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Manage Team
                                        </div>

                                        <!-- Team Settings -->
                                        <jet-dropdown-link
                                            :href="
                                                route(
                                                    'teams.show',
                                                    $page.props.user
                                                        .current_team
                                                )
                                            "
                                        >
                                            Team Settings
                                        </jet-dropdown-link>

                                        <jet-dropdown-link
                                            v-if="
                                                $page.props.jetstream
                                                    .canCreateTeams
                                            "
                                            :href="route('teams.create')"
                                        >
                                            Create New Team
                                        </jet-dropdown-link>

                                        <div
                                            class="border-t border-gray-100"
                                        ></div>

                                        <!-- Team Switcher -->
                                        <div
                                            v-if="
                                                $page.props.user.all_teams
                                                    .length > 1
                                            "
                                        >
                                            <div
                                                class="block px-4 py-2 text-xs text-gray-400"
                                            >
                                                Switch Teams
                                            </div>

                                            <template
                                                v-for="team in $page.props.user
                                                    .all_teams"
                                                :key="team.id"
                                            >
                                                <form
                                                    @submit.prevent="
                                                        switchToTeam(team)
                                                    "
                                                >
                                                    <jet-dropdown-link
                                                        as="button"
                                                    >
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <svg
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
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
                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                        </div>
                                                    </jet-dropdown-link>
                                                </form>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </jet-dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div v-if="$page.props.user.name" class="relative ml-3">
                        <jet-dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                    class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full object-cover"
                                        :src="
                                            $page.props.user.profile_photo_url
                                        "
                                        :alt="$page.props.user.name"
                                    />
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ $page.props.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Administration
                                </div>

                                <jet-dropdown-link :href="route('dashboard')">
                                    Dashboard
                                </jet-dropdown-link>
                                <!-- Account Management -->
                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Manage Account
                                </div>

                                <jet-dropdown-link
                                    :href="route('profile.show')"
                                >
                                    Profile
                                </jet-dropdown-link>

                                <jet-dropdown-link
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                >
                                    API Tokens
                                </jet-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <jet-dropdown-link as="button">
                                        Log Out
                                    </jet-dropdown-link>
                                </form>
                            </template>
                        </jet-dropdown>
                    </div>
                    <div v-else>
                        <Link :href="route('login')"> Log In </Link>
                    </div>
                </div>
                <hamburger-menu
                    :show="showingNavigationDropdown"
                    @toggle-navigation-dropdown="
                        showingNavigationDropdown = $event
                    "
                />
            </div>
        </div>
        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <responsive-primary-nav />
        </div>
    </nav>
</template>

<script>
import { defineComponent } from "vue";
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import ResponsivePrimaryNav from "@/Layouts/Nav/ResponsivePrimaryNav.vue";
import HamburgerMenu from "@/Layouts/Nav/HamburgerMenu.vue";

export default defineComponent({
    components: {
        ResponsivePrimaryNav,
        JetApplicationMark,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        HamburgerMenu,
    },

    data() {
        return {
            showingNavigationDropdown: false,
        };
    },

    computed: {
        roles() {
            return this.$page.props.user.roles;
        },
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

        onToggleDropdown(event) {
            this.showingNavigationDropdown = event;
        },

        logout() {
            this.$inertia.post(route("logout"));
        },
    },
});
</script>
