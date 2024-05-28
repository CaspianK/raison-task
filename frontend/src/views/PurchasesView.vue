<template>
    <div class="purchases">
        <div class="error" v-if="error">{{ error }}</div>
        <div class="purchases__filters">
            <SearchComponent />
            <div class="purchases__currencies" v-if="currencies">
                <div class="link" @click="updateCurrency(null)">
                    all
                </div>
                <div class="link" v-for="currency in currencies" :key="currency.code"
                    @click="updateCurrency(currency.code)">
                    {{ currency.code }}
                </div>
            </div>
        </div>
        <PurchasesTable v-if="purchases" :purchases="purchases" :currencies="currencies" :currentStore="currentStore"
            :currentCurrency="currentCurrency" @edit="editPurchase" @update="fetchPurchases()" />
        <button class="button" @click="showForm('add')">Добавить</button>
        <PurchaseFormComponent v-if="isFormVisible" @close="isFormVisible = false" :action="action"
            @update="fetchPurchases()" :purchaseId="purchaseId" />
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import requestService from '../services/requestService';
import PurchasesTable from '../components/PurchasesComponent.vue';
import SearchComponent from '../components/SearchComponent.vue';
import PurchaseFormComponent from '../components/PurchaseFormComponent.vue';

const route = useRoute();
const router = useRouter();

const purchases = ref(null);
const currencies = ref(null);
const currentCurrency = ref(null)
const currentStore = ref(route.params.store_id);
const error = ref('');
const isFormVisible = ref(false);
const action = ref('add');
const purchaseId = ref(null);

const fetchCurrencies = async () => {
    try {
        const response = await requestService.get('/currencies/exchange', {
            params: {
                currency: currentCurrency.value
            }
        });
        if (response.data) {
            currencies.value = response.data;
        }
    } catch (err) {
        if (err.request.response) {
            error.value = JSON.parse(err.request.response).message;
        } else {
            error.value = 'Failed to submit form';
        }
    }
}

const fetchPurchases = async () => {
    try {
        const response = await requestService.get('/purchases', {
            params: {
                store_id: currentStore.value
            }
        });
        if (response.data) {
            purchases.value = response.data;
        }
    } catch (err) {
        if (err.request.response) {
            error.value = JSON.parse(err.request.response).message;
        } else {
            error.value = 'Failed to submit form';
        }
    }
}

const updateCurrency = (currencyCode) => {
    currentCurrency.value = currencyCode;
    fetchCurrencies();
}

const updateStore = (storeId) => {
    console.log('storeId', storeId);
    if (storeId) {
        router.push({
            name: 'purchasesWithStoreId',
            params: {
                store_id: storeId
            }
        });
    } else {
        router.push('/purchases');
    }
}

const showForm = (newAction, newPurchaseId = null) => {
    isFormVisible.value = true;
    action.value = newAction;
    purchaseId.value = newPurchaseId;
}

const editPurchase = (purchaseId) => {
    showForm('edit', purchaseId);
}

onMounted(() => {
    fetchCurrencies();
    fetchPurchases();
});

watch(() => route.params.store_id, (newStoreId) => {
    currentStore.value = newStoreId;
    fetchPurchases();
}, { immediate: true });
</script>
