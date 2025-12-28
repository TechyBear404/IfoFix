<template>
    <AppLayout>
        <Head>
            <title>Administration / Clients</title>
        </Head>
        <div class="flex m-4 grow" :class="selectedClient ? 'gap-4' : ''">
            <ClientsList
                :clients="clientsList"
                class="w-full transition-all duration-300 ease-in-out"
                @client-selected="setSelectedClient"
            />
            <ClientProfile
                :client="selectedClient"
                @client-selected="setSelectedClient"
                class="transition-all duration-300 ease-in-out"
                :class="
                    selectedClient
                        ? 'translate-x-0 w-full lg:w-96'
                        : 'translate-x-full opacity-0 w-0'
                "
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ClientsList from "@/Pages/Administration/Clients/Partials/List.vue";
import ClientProfile from "@/Pages/Administration/Clients/Partials/Show.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const { clients } = defineProps({
    clients: {
        type: Array,
    },
});

const clientsList = ref(clients);

const selectedClient = ref(null);

const setSelectedClient = (client) => {
    selectedClient.value = client;
};

watch(
    () => clients,
    (newClients) => {
        clientsList.value = newClients;
    },
    { deep: true }
);
</script>
