<script setup>
import { reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';

const rData = reactive({
    categories: {},
    selectedCat: 0,
    catName: "",
    listText: "",
    listName: ""
})

const form = useForm({
    listNameError: false,
    listTextError: false,
    catNameError: false
});

function loadCategories() {
    axios.get("http://localhost:8000/api/categories").then(response => {
        rData.categories = response.data
    })
}

function validateList() {
    let result = true

    if (rData.listName == '') {
        form.listNameError = true
        result = false
    } else {
        form.listNameError = false
    }

    if (rData.listText == '') {
        form.listTextError = true
        result = false
    } else {
        form.listTextError = false
    }

    if (rData.selectedCat == 0 && rData.catName == '') {
        form.catNameError = true
        result = false
    } else {
        form.catNameError = false
    }

    return result
}

function saveList() {
    if (!validateList()) {
        return;
    }

    let postBody = {
        name: rData.listName,
        text: rData.listText,
        cat_id: rData.selectedCat,
        user_id: usePage().props.auth.user.id
    }
    if (rData.selectedCat == 0) {
        postBody.cat_name = rData.catName
    }

    axios.post("http://localhost:8000/api/notes", postBody).then(response => {
        console.log(response.data.note)
        rData.categories = response.data.categories
    })
}

loadCategories();
</script>

<template>
    <Head title="Note Editor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Note Editor</h2>
        </template>

        <div class="py-12">
            <form @submit.prevent="submit">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <select v-model="rData.selectedCat">
                                <option value=0>New Category</option>
                                <option v-for="cat in rData.categories" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                            <TextInput v-if="rData.selectedCat == '0'"
                                id="cat-name"
                                class="mt-1 block w-full"
                                v-model="rData.catName"
                                placeholder="Category Name"
                            />
                            <InputError v-if="form.catNameError" message="You must select a category or enter a new one" class="mt-2" />
                        </div>

                        <div class="p-6 text-gray-900">
                            <textarea v-model="rData.listText" placeholder="List contents"></textarea>
                            <InputError v-if="form.listTextError" message="You must enter some text for the list" class="mt-2" />
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <TextInput
                            id="list-name"
                            class="mt-1 block w-full"
                            v-model="rData.listName"
                            placeholder="List name"
                        />
                        <InputError v-if="form.listNameError" message="You must enter a name for the list" class="mt-2" />

                        <PrimaryButton @click="saveList()" class="ms-4">
                            Save
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
