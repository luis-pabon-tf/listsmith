<script setup>
import { reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';

const queryString = window.location.href;
const noteId = queryString.split('/').pop()
let editMode = false

const rData = reactive({
    categories: {},
    selectedCat: 0,
    catName: "",
    listText: "",
    listName: "",
    catName: ""
})

const form = useForm({
    listNameError: false,
    listTextError: false,
    catNameError: false
});

function loadNote() {
    axios.get("http://localhost:8000/api/notes/" + noteId).then(response => {
        rData.listText = response.data.text
        rData.listName = response.data.name
        rData.catName = response.data.category.name
    })
}

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

    let savePath = "http://localhost:8000/api/notes"

    if (editMode) {
        axios.put(savePath + "/" + noteId, postBody)
    } else {
        axios.post(savePath, postBody).then(response => {
            rData.categories = response.data.categories
        })
    }
}
if (!isNaN(noteId)) {
    editMode = true
    loadNote()
} else {
    loadCategories();
}
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
                        <div v-if="!editMode" class="p-6 text-gray-900">
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
                        <div v-else-if="rData.catName">Category: {{ rData.catName }}</div>
                    </div>
                    <div v-if="!editMode || rData.listText" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <textarea v-model="rData.listText" placeholder="List contents"></textarea>
                            <InputError v-if="form.listTextError" message="You must enter some text for the list" class="mt-2" />
                        </div>

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
                    <div v-else>Loading...</div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
