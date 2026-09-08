<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, AlertCircle, X } from 'lucide-vue-next';

const page = usePage();
const visible = ref(false);
const message = ref('');
const type = ref('success');
let timer = null;

const flash = computed(() => page.props.flash || {});

watch(
    () => flash.value,
    (newVal) => {
        if (newVal?.success) {
            type.value = 'success';
            message.value = newVal.success;
            show();
        } else if (newVal?.error) {
            type.value = 'error';
            message.value = newVal.error;
            show();
        }
    },
    { immediate: true, deep: true }
);

function show() {
    visible.value = true;
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        visible.value = false;
    }, 4500);
}

function dismiss() {
    visible.value = false;
}
</script>

<template>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="visible && message"
            class="fixed bottom-6 right-6 z-50 max-w-md w-full shadow-2xl rounded-2xl p-4 border flex items-start gap-3 backdrop-blur-md"
            :class="[
                type === 'success'
                    ? 'bg-emerald-50/95 border-emerald-200 text-emerald-900'
                    : 'bg-rose-50/95 border-rose-200 text-rose-900',
            ]"
        >
            <div class="shrink-0 mt-0.5">
                <CheckCircle2
                    v-if="type === 'success'"
                    class="w-5 h-5 text-emerald-600"
                />
                <AlertCircle
                    v-else
                    class="w-5 h-5 text-rose-600"
                />
            </div>
            <div class="flex-1 text-sm font-medium leading-relaxed">
                {{ message }}
            </div>
            <button
                type="button"
                @click="dismiss"
                class="shrink-0 rounded-lg p-1 text-slate-400 hover:text-slate-700 hover:bg-black/5 transition"
            >
                <X class="w-4 h-4" />
            </button>
        </div>
    </transition>
</template>
