<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

var listText = ""
var listName = ""
function saveList(input) {
    console.log("saving...")
}

const form = useForm({
    name: '',
    text: '',
    open: false,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <form @submit.prevent="submit">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <!-- <li v-for="item in items">
                            {{ item.message }}
                            </li> -->
                            <textarea v-model="listText" placeholder="List contents"></textarea>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <TextInput
                        id="list-name"
                        class="mt-1 block w-full"
                        v-model="listName"
                        autofocus
                        placeholder="List name"
                    />
                    <PrimaryButton @click="saveList()" class="ms-4">
                        Save
                    </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
