<template>
    <Card class="flex flex-col">
        <CardContent class="flex-1 p-6">
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold">Liste des techniciens</h1>
                <UserCreate />
            </div>
            <div class="flex items-center gap-2 py-4">
                <div class="flex items-center space-x-2">
                    <div class="relative flex-1 max-w-52">
                        <Input
                            type="search"
                            class="pl-10"
                            placeholder="Rechercher..."
                            :model-value="table.getState().globalFilter"
                            @update:model-value="table.setGlobalFilter"
                        />
                        <font-awesome-icon
                            icon="fa-solid fa-search"
                            class="absolute text-gray-500 -translate-y-1/2 left-3 top-1/2"
                        />
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
                                (value) => column.toggleVisibility(!!value)
                            "
                        >
                            {{ getColumnNames(column.id) }}
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <div class="">
                <Table class="z-0">
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
                            <TableRow
                                v-for="row in table.getRowModel().rows"
                                :key="row.id"
                                @click="selectUser(row.original)"
                                class="cursor-pointer hover:bg-blue-100"
                            >
                                <TableCell
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </TableCell>
                            </TableRow>
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
                    {{ table.getFilteredRowModel().rows.length }} technicien(s)
                    affichés(s) / Total : {{ users.length }} technicien(s)
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
        </CardContent>
    </Card>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/Components/ui//dropdown-menu";
import { Input } from "@/Components/ui//input";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui//table";
import { valueUpdater } from "@/lib/utils";
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table";
import { ArrowUpDown, ChevronDown } from "lucide-vue-next";
import { h, ref, watch, onMounted } from "vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import UserCreate from "@/Pages/Administration/Users/Partials/Create.vue";

const emit = defineEmits(["user-selected"]);

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const columns = [
    {
        accessorKey: "firstName",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Prénom", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) =>
            h("div", { class: "capitalize" }, row.original.firstName),
    },
    {
        accessorKey: "lastName",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Nom", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) =>
            h("div", { class: "capitalize" }, row.original.lastName),
    },
    {
        accessorKey: "email",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Email", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
    },
    {
        accessorKey: "role",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Rôle", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
    },
    {
        accessorKey: "isActive",
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Statut", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            );
        },
        cell: ({ row }) =>
            h(
                Badge,
                {
                    // class: `px-2 py-1 text-xs text-white ${
                    //     row.original.isActive ? "bg-green-500" : "bg-red-500"
                    // }`,
                    variant: row.original.isActive ? "success" : "danger",
                },
                () => (row.original.isActive ? "Actif" : "Inactif")
            ),
    },
];

const sorting = ref([]);
const columnFilters = ref([]);
const columnVisibility = ref({});
const globalFilter = ref("");

// Load saved column visibility on mount
onMounted(() => {
    const saved = localStorage.getItem("users-column-visibility");
    if (saved) {
        columnVisibility.value = JSON.parse(saved);
    }
});

// Save column visibility changes
watch(
    columnVisibility,
    (newValue) => {
        localStorage.setItem(
            "users-column-visibility",
            JSON.stringify(newValue)
        );
    },
    { deep: true }
);
const table = useVueTable({
    get data() {
        return props.users;
    },
    get columns() {
        return columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnVisibility),
    onGlobalFilterChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, globalFilter),
    // globalFilterFn: (row, columnId, value) => {
    //     const search = value.toLowerCase();
    //     return Object.values(row.original).some((cellValue) =>
    //         String(cellValue).toLowerCase().includes(search)
    //     );
    // },

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
        get globalFilter() {
            return globalFilter.value;
        },
    },
});

const getColumnNames = (id) => {
    const names = {
        firstName: "Prénom",
        lastName: "Nom",
        email: "Email",
        role: "Rôle",
        isActive: "Statut",
    };
    return names[id];
};

const selectUser = (user) => {
    emit("user-selected", user);
};
</script>
