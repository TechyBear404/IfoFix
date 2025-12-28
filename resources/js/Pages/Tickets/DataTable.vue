<script setup>
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";

import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { cn, valueUpdater } from "@/lib/utils";
import {
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table";

import { ArrowUpDown, ChevronDown } from "lucide-vue-next";
import { computed, h, ref, watch, watchEffect } from "vue";
import { Badge } from "@/Components/ui/badge";
import { router } from "@inertiajs/vue3";
import Switch from "@/Components/ui/switch/Switch.vue";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Card } from "@/Components/ui/card";

const props = defineProps({
    tickets: Array,
    technicians: Array,
    devices: Array,
    brands: Array,
    types: Array,
    clients: Array,
    pendingTickets: Boolean,
    deliveredTickets: Boolean,
});

const tickets = ref(props.tickets);

watchEffect(() => {
    tickets.value = props.tickets;
});

// refactoring filter btn
const AUCUN_VALUE = "none";

const selectedTechnician = ref(AUCUN_VALUE);
const selectedDevice = ref(AUCUN_VALUE);
const selectedBrand = ref(AUCUN_VALUE);
const selectedType = ref(AUCUN_VALUE);
const selectedClient = ref(AUCUN_VALUE);

// Update switch refs to use localStorage
const pendingTickets = ref(
    JSON.parse(localStorage.getItem("pendingTickets")) || false
);
const deliveredTickets = ref(
    JSON.parse(localStorage.getItem("deliveredTickets")) || false
);

// Add watchers for switches
watch(pendingTickets, (newValue) => {
    localStorage.setItem("pendingTickets", JSON.stringify(newValue));
});

watch(deliveredTickets, (newValue) => {
    localStorage.setItem("deliveredTickets", JSON.stringify(newValue));
});

const filteredTickets = computed(() => {
    let tickets = props.tickets;
    if (pendingTickets.value) {
        tickets = tickets.filter((ticket) => !ticket.isFinished);
    }
    if (deliveredTickets.value) {
        tickets = tickets.filter((ticket) => ticket.isDelivered);
    }
    if (selectedTechnician.value && selectedTechnician.value !== AUCUN_VALUE) {
        tickets = tickets.filter((ticket) => {
            return ticket.interventions.some((intervention) =>
                intervention.users.some(
                    (user) => user.id === parseInt(selectedTechnician.value)
                )
            );
        });
    }
    if (selectedDevice.value && selectedDevice.value !== AUCUN_VALUE) {
        tickets = tickets.filter(
            (ticket) => ticket.device.id === parseInt(selectedDevice.value)
        );
    }
    if (selectedBrand.value && selectedBrand.value !== AUCUN_VALUE) {
        tickets = tickets.filter(
            (ticket) => ticket.device.brandId === parseInt(selectedBrand.value)
        );
    }
    if (selectedType.value && selectedType.value !== AUCUN_VALUE) {
        tickets = tickets.filter(
            (ticket) => ticket.device.typeId === parseInt(selectedType.value)
        );
    }
    if (selectedClient.value && selectedClient.value !== AUCUN_VALUE) {
        tickets = tickets.filter(
            (ticket) => ticket.client.id === parseInt(selectedClient.value)
        );
    }
    return tickets;
});

const getColumnNames = (id) => {
    const names = {
        isFinished: "Status",
        title: "Titre",
        client: "Client",
        device: "Appareil",
        brand: "Marque",
        type: "Type",
        interventions: "Assigné à",
    };
    return names[id];
};

const columns = [
    {
        accessorKey: "id",
        header: "id",
        cell: ({ row }) => {
            return h("p", row.getValue("id"));
        },
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: "isFinished",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Status", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) => {
            const status = row.getValue("isFinished") ? "Terminé" : "En cours";
            return h(
                Badge,
                {
                    variant: status === "Terminé" ? "success" : "warning",
                    // class:
                    //     status === "Terminé"
                    //         ? "px-2 py-1 bg-emerald-100 text-emerald-600"
                    //         : "px-2 py-1 bg-orange-100 text-orange-600",
                },
                () => status
            );
        },
    },
    {
        accessorKey: "client",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Clients", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) => {
            const client = row.getValue("client");

            return h(
                "div",
                { class: "flex flex-col gap-1" },
                client.firstName + " " + client.lastName
            );
        },
    },
    {
        accessorKey: "title",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Titre", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) =>
            h(
                "div",
                {
                    class: "capitalize",
                    // onClick: () =>
                    //     router.visit(`/tickets/${row.getValue("id")}`),
                },
                row.getValue("title")
            ),
    },
    {
        accessorKey: "device",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Appareil", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) => {
            const device = row.getValue("device");
            return h(
                "div",
                { class: "flex flex-col gap-1" },
                device?.name || "Non spécifié"
            );
        },
    },
    {
        accessorKey: "brand",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Marque", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) => {
            const device = row.getValue("device");
            return h(
                "div",
                { class: "flex flex-col gap-1" },
                device?.brand?.name || "Non spécifié"
            );
        },
    },
    {
        accessorKey: "type",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Type", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) => {
            const device = row.getValue("device");
            return h(
                "div",
                { class: "flex flex-col gap-1" },
                device?.type?.name || "Non spécifié"
            );
        },
    },
    {
        accessorKey: "interventions",
        header: "Assigné à",
        cell: ({ row }) => {
            const interventions = row.getValue("interventions");
            // Récupérer tous les utilisateurs de toutes les interventions
            const allUsers = interventions
                .flatMap((intervention) => intervention.users)
                .map((user) => user.firstName);
            // Supprimer les doublons et joindre les noms
            const uniqueUsers = Array.from(new Set(allUsers));

            return h(
                "div",
                { class: "flex flex-col gap-1" },
                uniqueUsers.join(", ") || "Non assigné"
            );
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            return h(
                "div",
                {
                    class: "cursor-pointer text-gray-400 hover:text-red-600",
                    onClick: (event) => {
                        event.stopPropagation();
                        if (
                            confirm(
                                "Êtes-vous sûr de vouloir supprimer ce ticket ?"
                            )
                        ) {
                            router.delete(`/tickets/${row.getValue("id")}`);
                        }
                    },
                },
                [
                    h(FontAwesomeIcon, {
                        icon: "fa-solid fa-trash",
                        class: "h-4 w-4 px-4",
                    }),
                ]
            );
        },
    },
];

const sorting = ref([]);
const columnFilters = ref([]);

// Update columnVisibility to use localStorage
const columnVisibility = ref(
    JSON.parse(localStorage.getItem("columnVisibility")) || {}
);

// Add watcher for columnVisibility
watch(
    columnVisibility,
    (newValue) => {
        localStorage.setItem("columnVisibility", JSON.stringify(newValue));
    },
    { deep: true }
);

const rowSelection = ref({});
const expanded = ref({});
const globalFilter = ref("");

// Add these computed properties
const isClientVisible = computed(() => {
    return table.getColumn("client")?.getIsVisible();
});

const isTechnicianVisible = computed(() => {
    return table.getColumn("interventions")?.getIsVisible();
});

const isDeviceVisible = computed(() => {
    return table.getColumn("device")?.getIsVisible();
});

const isBrandVisible = computed(() => {
    return table.getColumn("brand")?.getIsVisible();
});

const isTypeVisible = computed(() => {
    return table.getColumn("type")?.getIsVisible();
});

const table = useVueTable({
    data: filteredTickets,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) => {
        valueUpdater(updaterOrValue, columnVisibility);
        // Save to localStorage whenever visibility changes
        localStorage.setItem(
            "columnVisibility",
            JSON.stringify(columnVisibility.value)
        );
    },
    onRowSelectionChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, expanded),
    onGlobalFilterChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, globalFilter),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
        get expanded() {
            return expanded.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
        columnPinning: {
            left: ["status"],
        },
    },
    // globalFilterFn: fuzzyFilter,
});

const resetFilters = () => {
    selectedTechnician.value = AUCUN_VALUE;
    selectedDevice.value = AUCUN_VALUE;
    selectedBrand.value = AUCUN_VALUE;
    selectedType.value = AUCUN_VALUE;
    selectedClient.value = AUCUN_VALUE;
    pendingTickets.value = false;
    deliveredTickets.value = false;
    globalFilter.value = "";
};
</script>

<template>
    <Card class="w-full p-6">
        <div class="flex gap-2">
            <div class="w-full">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-semibold">Tickets</h1>
                        <font-awesome-icon
                            icon="fa-solid fa-plus"
                            class="text-xl text-gray-400 cursor-pointer hover:text-indigo-500"
                            @click="$emit('buttonClick')"
                        />
                    </div>
                </div>
                <div class="flex items-center gap-2 py-4">
                    <Input
                        type="search"
                        class="max-w-sm"
                        placeholder="Rechercher..."
                        :model-value="table.getState().globalFilter"
                        @update:model-value="table.setGlobalFilter"
                    />
                    <div class="flex gap-3 shrink-0">
                        <div class="flex items-center space-x-2">
                            <Switch
                                :checked="pendingTickets"
                                @update:checked="
                                    (value) => {
                                        pendingTickets = value;
                                    }
                                "
                                id="pending-mode"
                            />
                            <Label for="pending-mode">en cours</Label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Switch
                                :checked="deliveredTickets"
                                @update:checked="
                                    (value) => {
                                        deliveredTickets = value;
                                    }
                                "
                                id="delivered-mode"
                            />
                            <Label for="delivered-mode">rendu</Label>
                        </div>
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline" class="ml-auto">
                                Colonnes <ChevronDown class="w-4 h-4 ml-2" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuCheckboxItem
                                v-for="column in table
                                    .getAllColumns()
                                    .filter((column) => column.getCanHide())"
                                :key="column.id"
                                class="capitalize"
                                :checked="column.getIsVisible()"
                                @update:checked="
                                    (value) => {
                                        column.toggleVisibility(!!value);
                                    }
                                "
                            >
                                {{ getColumnNames(column.id) }}
                            </DropdownMenuCheckboxItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    <Select v-if="isClientVisible" v-model="selectedClient">
                        <SelectTrigger class="max-w-48 flex-1">
                            <SelectValue placeholder="Client" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="AUCUN_VALUE"
                                    >Clients</SelectItem
                                >
                                <SelectItem
                                    v-for="client in props.clients"
                                    :key="client.id"
                                    :value="String(client.id)"
                                >
                                    {{ client.firstName }} {{ client.lastName }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Select
                        v-if="isTechnicianVisible"
                        v-model="selectedTechnician"
                    >
                        <SelectTrigger class="max-w-48 flex-1">
                            <SelectValue placeholder="Technicien" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="AUCUN_VALUE"
                                    >Techniciens</SelectItem
                                >
                                <SelectItem
                                    v-for="technician in props.technicians"
                                    :key="technician.id"
                                    :value="String(technician.id)"
                                >
                                    {{ technician.firstName }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Select v-if="isDeviceVisible" v-model="selectedDevice">
                        <SelectTrigger class="max-w-48 flex-1">
                            <SelectValue placeholder="Model" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="AUCUN_VALUE"
                                    >Appareils</SelectItem
                                >
                                <SelectItem
                                    v-for="device in props.devices"
                                    :key="device.id"
                                    :value="String(device.id)"
                                >
                                    {{ device.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Select v-if="isBrandVisible" v-model="selectedBrand">
                        <SelectTrigger class="max-w-48 flex-1">
                            <SelectValue placeholder="Marque" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="AUCUN_VALUE"
                                    >Marques</SelectItem
                                >
                                <SelectItem
                                    v-for="brand in props.brands"
                                    :key="brand.id"
                                    :value="String(brand.id)"
                                >
                                    {{ brand.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Select v-if="isTypeVisible" v-model="selectedType">
                        <SelectTrigger class="max-w-48 flex-1">
                            <SelectValue placeholder="Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="AUCUN_VALUE"
                                    >Types</SelectItem
                                >
                                <SelectItem
                                    v-for="type in props.types"
                                    :key="type.id"
                                    :value="String(type.id)"
                                >
                                    {{ type.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Button
                        variant="outline"
                        size="icon"
                        class="w-8 shrink-0"
                        @click="resetFilters"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-rotate"
                            class="w-4 h-4"
                        />
                    </Button>
                </div>

                <div class="">
                    <Table>
                        <TableHeader>
                            <TableRow
                                v-for="headerGroup in table.getHeaderGroups()"
                                :key="headerGroup.id"
                            >
                                <TableHead
                                    v-for="header in headerGroup.headers"
                                    :key="header.id"
                                >
                                    <FlexRender
                                        v-if="!header.isPlaceholder"
                                        :render="header.column.columnDef.header"
                                        :props="header.getContext()"
                                    />
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="table.getRowModel().rows?.length">
                                <template
                                    v-for="row in table.getRowModel().rows"
                                    :key="row.id"
                                >
                                    <TableRow
                                        class="hover:cursor-pointer"
                                        :data-state="
                                            row.getIsSelected() && 'selected'
                                        "
                                        @click="
                                            router.visit(
                                                `/tickets/${row.original.id}`
                                            )
                                        "
                                    >
                                        <TableCell
                                            v-for="cell in row.getVisibleCells()"
                                            :key="cell.id"
                                        >
                                            <FlexRender
                                                :render="
                                                    cell.column.columnDef.cell
                                                "
                                                :props="cell.getContext()"
                                            />
                                        </TableCell>
                                    </TableRow>
                                    <!-- <TableRow v-if="row.getIsExpanded()">
                                        <TableCell
                                            :colspan="row.getAllCells().length"
                                        >
                                            {{ row.original }}
                                        </TableCell>
                                    </TableRow> -->
                                </template>
                            </template>

                            <TableRow v-else>
                                <TableCell
                                    :colspan="columns.length"
                                    class="h-24 text-center"
                                >
                                    No results.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex items-center justify-end py-4 space-x-2">
                    <div class="flex-1 text-sm text-muted-foreground">
                        {{ table.getFilteredRowModel().rows.length }} ticket(s)
                        affichés(s) / Total : {{ tickets.length }} ticket(s)
                    </div>
                    <div class="space-x-2">
                        <Button
                            size="sm"
                            :disabled="!table.getCanPreviousPage()"
                            @click="table.previousPage()"
                        >
                            Précedent
                        </Button>
                        <Button
                            size="sm"
                            :disabled="!table.getCanNextPage()"
                            @click="table.nextPage()"
                        >
                            Suivant
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>
