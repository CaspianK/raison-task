<template>
    <div class="search">
        <input type="text" placeholder="Поиск" v-model="query" />
        <div v-if="stores" class="search__results">
            <div v-for="store in stores" :key="store.id" @click="openStore(store.id)">
                {{ store.name }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useRouter } from "vue-router";
import requestService from "../services/requestService";

const router = useRouter();
const query = ref("");
const stores = ref(null);
const error = ref(null);

const search = async () => {
    try {
        const response = await requestService.get("/search", {
            params: {
                q: query.value,
            },
        });
        if (response.data) {
            stores.value = response.data;
        }
    } catch (err) {
        if (err.request.response) {
            alert(JSON.parse(err.request.response).message);
        } else {
            error.value = 'Failed to submit form';
        }
    }
};

const openStore = (storeId) => {
    router.push({
        name: "purchasesWithStoreId",
        params: {
            store_id: storeId,
        },
    });
};

watch(query, (newQuery) => {
    query.value = newQuery;

    if (newQuery.length >= 2) {
        search();
    } else if (newQuery.length === 0) {
        stores.value = null;
    }
});
</script>
