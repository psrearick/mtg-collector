<template>
    <nav class="bg-gray-800">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex items-center px-2 lg:px-0">
                    <inertia-link href="/">
                        <Logo
                            class="text-gray-300 fill-current"
                            :show-text="false"
                        />
                    </inertia-link>
                    <div class="hidden lg:block lg:ml-6">
                        <div class="flex space-x-4">
                            <nav-link
                                :href="route('dashboard')"
                                :active="isRoute('dashboard')"
                            >
                                Dashboard
                            </nav-link>
                            <nav-link
                                :href="route('collections.index')"
                                :active="isRoute('collections.index')"
                            >
                                Collections
                            </nav-link>
                            <nav-link
                                :href="route('cards.index')"
                                :active="isRoute('cards.index')"
                            >
                                Cards
                            </nav-link>
                            <nav-link
                                :href="route('shared.index')"
                                :active="isRoute('shared.index')"
                            >
                                Shared
                            </nav-link>
                        </div>
                    </div>
                </div>
                <div
                    class="
                        flex-1 flex
                        justify-center
                        px-2
                        lg:ml-6
                        lg:justify-end
                    "
                ></div>
                <div class="flex lg:hidden">
                    <!-- Mobile menu button -->
                    <mobile-menu-button
                        :show="mobileMenuOpen"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    />
                </div>
                <div class="lg:block lg:ml-4">
                    <div class="flex items-center">
                        <!-- Profile dropdown -->
                        <div class="ml-4 relative flex-shrink-0">
                            <div>
                                <profile-button
                                    class="hidden lg:block"
                                    @click="profileMenuOpen = !profileMenuOpen"
                                />
                            </div>

                            <!--
                                Dropdown menu, show/hide based on menu state.
                            -->
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <div v-if="profileMenuOpen">
                                    <div
                                        class="
                                            fixed
                                            left-0
                                            top-0
                                            h-full
                                            w-full
                                            bg-transparent
                                            z-10
                                        "
                                        @click="
                                            profileMenuOpen = !profileMenuOpen
                                        "
                                    />
                                    <menu-items
                                        class="
                                            origin-top-right
                                            absolute
                                            right-0
                                            mt-2
                                            z-20
                                        "
                                    >
                                        <!-- <menu-item
                                            id="user-menu-item-0"
                                            href="profile"
                                            :current="
                                                route().current('profile')
                                            "
                                        >
                                            Your Profile
                                        </menu-item> -->
                                        <menu-item
                                            id="user-menu-item-1"
                                            href="/user/settings"
                                            :current="
                                                route().current('settings')
                                            "
                                        >
                                            Settings
                                        </menu-item>
                                        <menu-item
                                            id="user-menu-item-2"
                                            href="/logout"
                                            method="post"
                                        >
                                            Logout
                                        </menu-item>
                                    </menu-items>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile menu, show/hide based on menu state. -->
        <mobile-menu v-if="mobileMenuOpen" id="mobile-menu" class="lg:hidden">
            <mobile-menu-items>
                <mobile-menu-item
                    :href="route('dashboard')"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    Dashboard
                </mobile-menu-item>
                <mobile-menu-item
                    :href="route('collections.index')"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    Collections
                </mobile-menu-item>
                <mobile-menu-item
                    :href="route('cards.index')"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    Cards
                </mobile-menu-item>
                <mobile-menu-item
                    :href="route('shared.index')"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    Shared
                </mobile-menu-item>
            </mobile-menu-items>
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex justify-between px-5">
                    <div class="flex-shrink-0 flex">
                        <!-- <img
                            class="h-10 w-10 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixqx=zHlOFbGo5k&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt=""
                        /> -->
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                    </div>
                    <!--<notification-button />-->
                </div>
            </div>
            <mobile-menu-items>
                <!-- <mobile-menu-item href="profile">
                    Your Profile
                </mobile-menu-item> -->
                <mobile-menu-item
                    href="/user/settings"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    Settings
                </mobile-menu-item>
                <mobile-menu-item href="logout" method="post">
                    Logout
                </mobile-menu-item>
            </mobile-menu-items>
        </mobile-menu>
    </nav>
</template>

<script>
import Logo from "@/Components/Logo";
import NavLink from "@/Components/NavLink";
import Icon from "@/Components/Icon";
import Search from "@/Components/Form/Search";
import MobileMenuButton from "@/Components/Buttons/MobileMenuButton";
import NotificationButton from "@/Components/Buttons/NotificationButton";
import ProfileButton from "@/Components/Buttons/ProfileButton";
import MenuItems from "@/Components/Menus/MenuItems";
import MenuItem from "@/Components/Menus/MenuItem";
import MobileMenu from "@/Components/Menus/MobileMenu";
import MobileMenuItems from "@/Components/Menus/MobileMenuItems";
import MobileMenuItem from "@/Components/Menus/MobileMenuItem";

export default {
    name: "Navbar",

    components: {
        MobileMenuItem,
        MobileMenuItems,
        MobileMenu,
        MenuItem,
        MenuItems,
        ProfileButton,
        NotificationButton,
        MobileMenuButton,
        Logo,
        NavLink,
        Icon,
        Search,
    },

    data() {
        return {
            mobileMenuOpen: false,
            profileMenuOpen: false,
        };
    },
    computed: {
        currentRoute() {
            return this.$store.getters.currentRoute;
        },
    },
    methods: {
        logout() {
            axios.post("/logout");
        },
        isRoute(checkRoute) {
            const current = this.currentRoute.substr(
                0,
                this.currentRoute.indexOf(".")
            );
            const check = checkRoute.substr(0, checkRoute.indexOf("."));
            return current === check;
        },
    },
};
</script>
