<template>
    <AppLayout>
        <Head>
            <title>Administration / Techniciens</title>
        </Head>
        <div class="flex m-4 grow" :class="selectedUser ? 'gap-4' : ''">
            <UsersList
                :users="usersList"
                class="w-full transition-all duration-300 ease-in-out"
                @user-selected="setSelectedUser"
            />
            <UserProfile
                :user="selectedUser"
                @user-selected="setSelectedUser"
                class="transition-all duration-300 ease-in-out"
                :class="
                    selectedUser
                        ? 'translate-x-0 w-full lg:w-96'
                        : 'translate-x-full opacity-0 w-0'
                "
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import UsersList from "@/Pages/Administration/Users/Partials/List.vue";
import UserProfile from "@/Pages/Administration/Users/Partials/Show.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const { users } = defineProps({
    users: {
        type: Array,
    },
});

const usersList = ref(users);

const selectedUser = ref(null);

const setSelectedUser = (user) => {
    console.log("setSelectedUser");

    selectedUser.value = user;
};

watch(
    () => users,
    (newUsers) => {
        console.log("watch users");
        usersList.value = newUsers;
    },
    { deep: true }
);
</script>
