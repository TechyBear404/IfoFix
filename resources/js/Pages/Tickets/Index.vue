<template>
    <AppLayout>
        <div class="flex m-4 grow" :class="showCreateForm ? 'gap-4' : ''">
            <DataTable
                :tickets="props.tickets"
                :clients="clients"
                :technicians="technicians"
                :devices="props.devices"
                :brands="props.brands"
                :types="props.types"
                class="flex-1 min-w-0 transition-all duration-300 ease-in-out"
                :class="showCreateForm ? 'hidden lg:block' : ''"
                @updateDeliveredTickets="deliveredTickets = $event"
                @updatePendingTickets="pendingTickets = $event"
                @button-click="buttonClick"
            />
            <CreateTicket
                v-if="showCreateForm"
                :devices="devices"
                :technicians="technicians"
                :clients="clients"
                :brands="brands"
                :types="types"
                @close="showCreateForm = false"
                class="transition-all duration-300 ease-in-out"
                :class="
                    showCreateForm
                        ? 'translate-x-0 w-full lg:w-80 xl:w-96'
                        : 'translate-x-full opacity-0 w-0'
                "
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import DataTable from "./DataTable.vue";
import CreateTicket from "./CreateTicket.vue";

const props = defineProps({
    tickets: Array,
    technicians: Array,
    devices: Array,
    brands: Array,
    types: Array,
    clients: Array,
    messages: String,
});

const showCreateForm = ref(false);

const buttonClick = () => {
    showCreateForm.value = true;
};
</script>
