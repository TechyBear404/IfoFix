<template>
    <AppLayout>
        <div class="container h-screen max-h-screen p-4 mx-auto">
            <Tabs :default-value="activeTab" class="">
                <TabsList
                    class="grid justify-start w-full grid-cols-1 p-2 text-white rounded-md bg-gradient-to-b from-indigo-500 via-indigo-600 to-indigo-600 sm:grid-cols-2 md:flex md:flex-row"
                >
                    <Link :href="route('administration', { tab: 'stats' })">
                        <TabsTrigger
                            value="stats"
                            class="w-full px-4 py-2 rounded-md hover:bg-indigo-400"
                        >
                            Statistiques
                        </TabsTrigger>
                    </Link>
                    <Link :href="route('administration', { tab: 'users' })">
                        <TabsTrigger
                            value="users"
                            class="w-full px-4 py-2 rounded-md hover:bg-indigo-400"
                        >
                            Techniciens
                        </TabsTrigger>
                    </Link>
                    <Link :href="route('administration', { tab: 'clients' })">
                        <TabsTrigger
                            value="clients"
                            class="w-full px-4 py-2 rounded-md hover:bg-indigo-400"
                        >
                            Clients
                        </TabsTrigger>
                    </Link>
                    <Link :href="route('administration', { tab: 'devices' })">
                        <TabsTrigger
                            value="devices"
                            class="w-full px-4 py-2 rounded-md hover:bg-indigo-400"
                        >
                            Appareils
                        </TabsTrigger>
                    </Link>
                </TabsList>
                <TabsContent value="stats" class=""> Stats page </TabsContent>
                <TabsContent value="users" class="">
                    <UsersPage :users="usersList" />
                </TabsContent>
                <TabsContent value="clients" class="">
                    <ClientsPage :clients="clientsList" />
                </TabsContent>
                <TabsContent value="devices" class="">
                    Devices administration page
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import UsersPage from "@/Pages/Administration/Users/Index.vue";
import ClientsPage from "@/Pages/Administration/Clients/Index.vue";

const props = defineProps({
    users: {
        type: Array,
        default: [],
    },
    clients: {
        type: Array,
        default: [],
    },
    activeTab: {
        type: String,
        default: "stats",
    },
});

import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
const page = usePage();

// const activeTab = ref(props.activeTab);

const usersList = ref(props.users);
const clientsList = ref(props.clients);

// Watch for flash messages
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash && typeof newFlash === "object" && newFlash.refresh) {
            if (newFlash.refresh === "users") {
                fetchUsers();
            } else if (newFlash.refresh === "clients") {
                fetchClients();
            }
        }
    },
    { deep: true }
);

const fetchUsers = () => {
    axios.get("/users").then((response) => {
        usersList.value = response.data;
    });
};

const fetchClients = () => {
    axios.get("/clients").then((response) => {
        clientsList.value = response.data;
    });
};
</script>
