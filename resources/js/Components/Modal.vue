<script setup>
import { watch, onMounted, onUnmounted } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    title: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['close']);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

watch(
    () => props.show,
    (val) => {
        if (val) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);
</script>

<template>
    <teleport to="body">
        <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
            >
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                    @click="close"
                />

                <!-- Dialog Panel -->
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-if="show"
                        :class="[
                            'relative w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 transform transition-all',
                            maxWidth === 'sm' && 'sm:max-w-sm',
                            maxWidth === 'md' && 'sm:max-w-md',
                            maxWidth === 'lg' && 'sm:max-w-lg',
                            maxWidth === 'xl' && 'sm:max-w-xl',
                            maxWidth === '2xl' && 'sm:max-w-2xl',
                            maxWidth === '3xl' && 'sm:max-w-3xl',
                        ]"
                    >
                        <div
                            v-if="title"
                            class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50"
                        >
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ title }}
                            </h3>
                            <button
                                v-if="closeable"
                                @click="close"
                                type="button"
                                class="p-1 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                            >
                                <X class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="p-6">
                            <slot />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
