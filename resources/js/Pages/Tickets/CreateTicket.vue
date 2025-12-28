<template>
    <form @submit.prevent="submit" class="space-y-4">
        <Card>
            <CardHeader>
                <CardTitle class="flex justify-between">
                    <!-- <div class="p-4 bg-white border rounded-lg shadow"> -->
                    <div class="flex items-center justify-between mb-4">
                        <BackButton v-if="params === '/tickets/create'" />
                        <h2 class="text-lg font-semibold">Nouveau Ticket</h2>
                        <Button
                            variant="ghost"
                            @click="$emit('close')"
                            v-if="params !== '/tickets/create'"
                        >
                            <XIcon class="w-4 h-4" />
                        </Button>
                    </div>
                </CardTitle>
            </CardHeader>
            <CardContent>
                <div>
                    <Label for="title">Titre</Label>
                    <Input id="title" v-model="form.title" />
                </div>
                <div>
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        class="resize-none"
                    />
                </div>
                <div>
                    <Label for="device" class="flex items-center gap-2">
                        Appareil
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            @click="openNewDeviceModal"
                        >
                            <font-awesome-icon icon="fa-solid fa-plus" />
                        </Button>
                    </Label>
                    <div class="flex flex-col gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="openDeviceModal"
                            class="justify-between w-full"
                        >
                            <span>{{
                                selectedDeviceName || "Sélectionner un appareil"
                            }}</span>
                            <font-awesome-icon icon="fa-solid fa-laptop" />
                        </Button>
                    </div>
                </div>
                <div>
                    <Label for="client" class="flex items-center gap-2">
                        Client
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            @click="openNewClientModal"
                        >
                            <font-awesome-icon icon="fa-solid fa-plus" />
                        </Button>
                    </Label>
                    <div class="flex flex-col gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="openClientModal"
                            class="justify-between w-full"
                        >
                            <span>{{
                                selectedClientName || "Sélectionner un client"
                            }}</span>
                            <font-awesome-icon icon="fa-solid fa-user" />
                        </Button>
                    </div>
                </div>
                <div>
                    <Label>Techniciens</Label>
                    <Button
                        type="button"
                        variant="outline"
                        @click="openModal"
                        class="justify-between w-full"
                    >
                        Sélectionner des techniciens
                        <font-awesome-icon icon="fa-solid fa-users" />
                    </Button>

                    <!-- Liste des techniciens sélectionnés -->
                    <div
                        v-if="selectedTechnicians.length"
                        class="flex flex-wrap gap-2 mt-2"
                    >
                        <div
                            v-for="tech in selectedTechnicians"
                            :key="tech.id"
                            class="inline-flex items-center gap-1 rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground border-input bg-background hover:bg-accent hover:text-accent-foreground"
                        >
                            <span
                                >{{ tech.firstName }} {{ tech.lastName }}</span
                            >
                            <button
                                type="button"
                                @click="toggleTechnician(tech.id)"
                                class="ml-1 rounded-full outline-none hover:bg-muted-foreground/20 focus:bg-muted-foreground/20"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-times"
                                    class="w-3 h-3"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <Label for="images">Images</Label>
                    <div
                        class="mt-2 mb-4"
                        @dragenter.prevent="handleDragEnter"
                        @dragleave.prevent="handleDragLeave"
                        @dragover.prevent
                        @drop.prevent="handleFileDrop"
                    >
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col w-full h-32 transition-colors duration-200 border-2 border-dashed rounded-lg cursor-pointer"
                                :class="{
                                    'border-primary bg-primary/10': isDragging,
                                    'hover:bg-gray-50 border-gray-300':
                                        !isDragging,
                                }"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-cloud-arrow-up"
                                        class="w-8 h-8 mb-3 text-gray-400"
                                        :class="{ 'text-primary': isDragging }"
                                    />
                                    <p class="text-sm text-gray-500">
                                        {{
                                            isDragging
                                                ? "Déposez les images ici"
                                                : "Cliquez ou glissez des images ici"
                                        }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        (JPG, PNG, GIF jusqu'à 2MB)
                                    </p>
                                </div>
                                <input
                                    type="file"
                                    class="hidden"
                                    multiple
                                    accept="image/*"
                                    @change="handleImageUpload"
                                />
                            </label>
                        </div>
                        <!-- Prévisualisation des images -->
                        <div
                            v-if="imagePreviewUrls.length"
                            class="grid grid-cols-4 gap-4 mt-4"
                        >
                            <div
                                v-for="(url, index) in imagePreviewUrls"
                                :key="index"
                                class="relative"
                            >
                                <img
                                    :src="url"
                                    class="object-cover w-full h-24 rounded-lg"
                                />
                                <button
                                    type="button"
                                    @click="removeImage(index)"
                                    class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-times"
                                        class="w-3 h-3"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <Button type="submit" class="w-full">Créer</Button>
            </CardContent>
        </Card>

        <CreateClientModal v-model="isNewClientModalOpen" />

        <CreateDeviceModal
            v-model="isNewDeviceModalOpen"
            :brands="brands"
            :types="types"
        />

        <TechniciansModal
            v-model="isModalOpen"
            :form="form"
            :technicians="technicians"
            @update:selectedTechnicians="updateSelectedTechnicians"
        />

        <ClientsModal
            v-model="isClientModalOpen"
            :form="form"
            :clients="clients"
            @update:selectedClientName="selectedClientName = $event"
        />

        <DeviceModal
            v-model="isDeviceModalOpen"
            :form="form"
            :devices="devices"
            @update:selectedDeviceName="selectedDeviceName = $event"
        />
    </form>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { XIcon } from "lucide-vue-next";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import TechniciansModal from "./partials/TechniciansModal.vue";
import ClientsModal from "./partials/ClientsModal.vue";
import DeviceModal from "./partials/DevicesModal.vue";
import CreateClientModal from "./partials/CreateClientModal.vue";
import CreateDeviceModal from "./partials/CreateDeviceModal.vue";
import BackButton from "@/Components/BackButton.vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

const props = defineProps({
    devices: Array,
    technicians: Array,
    clients: Array, // Nouvelle prop
    brands: Array, // Ajouter cette prop
    types: Array, // Ajouter cette prop
});

const form = useForm({
    title: "",
    description: "",
    deviceId: null,
    clientId: null, // Modifié de client à clientId
    technicianIds: [], // Modifié pour supporter plusieurs techniciens
    images: [], // Ajout du champ images
});

const emit = defineEmits(["close"]);

const createTicketOpen = ref(false);

const submit = () => {
    form.post(route("tickets.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("close");
        },
        onError: (errors) => {
            console.error("Erreur lors de la soumission:", errors);
        },
    });
};

const isModalOpen = ref(false);

const openModal = () => {
    isModalOpen.value = true;
};
const selectedTechnicians = computed(() => {
    return props.technicians.filter((tech) =>
        form.technicianIds.includes(tech.id)
    );
});

const isClientModalOpen = ref(false);

const openClientModal = () => {
    isClientModalOpen.value = true;
};

const selectedClientName = ref("");

const isNewClientModalOpen = ref(false);

const openNewClientModal = () => {
    isNewClientModalOpen.value = true;
};

const isNewDeviceModalOpen = ref(false);

const openNewDeviceModal = () => {
    isNewDeviceModalOpen.value = true;
};

const isDeviceModalOpen = ref(false);

const selectedDeviceName = ref("");

const openDeviceModal = () => {
    isDeviceModalOpen.value = true;
};

const imagePreviewUrls = ref([]);
const selectedImages = ref([]);

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);
    files.forEach((file) => {
        if (file.size > 2 * 1024 * 1024) {
            alert("Chaque image ne doit pas dépasser 2MB");
            return;
        }
        selectedImages.value.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewUrls.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
    form.images = selectedImages.value;
};

const removeImage = (index) => {
    imagePreviewUrls.value.splice(index, 1);
    selectedImages.value.splice(index, 1);
    form.images = selectedImages.value;
};
const params = window.location.pathname;

const updateSelectedTechnicians = (techs) => {
    selectedTechnicians.value = techs;
};

const isDragging = ref(false);
let dragCounter = ref(0);

const handleDragEnter = (e) => {
    e.preventDefault();
    dragCounter.value++;
    if (dragCounter.value === 1) {
        isDragging.value = true;
    }
};

const handleDragLeave = (e) => {
    e.preventDefault();
    dragCounter.value--;
    if (dragCounter.value === 0) {
        isDragging.value = false;
    }
};

const handleFileDrop = (event) => {
    isDragging.value = false;
    dragCounter.value = 0;
    const files = Array.from(event.dataTransfer.files).filter((file) =>
        file.type.startsWith("image/")
    );

    files.forEach((file) => {
        if (file.size > 2 * 1024 * 1024) {
            alert("Chaque image ne doit pas dépasser 2MB");
            return;
        }
        selectedImages.value.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewUrls.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
    form.images = selectedImages.value;
};
</script>
