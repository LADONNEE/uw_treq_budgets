<template>
    <transition name="m-modal" v-on:after-leave="modalClose">
        <div v-if="open" class="m-modal__mask" :class="cssClasses" @mousedown.self="close()">
            <div class="m-modal__wrapper" @mousedown.self="close()">
                <div class="m-modal__container" @click.stop>
                    <div class="m-modal__close" v-if="closeButton">
                        <button type="button" aria-label="Close" @click="close()">&times;</button>
                    </div>
                    <div class="m-modal__body">
                        <slot>Default Body</slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import { scrollLock, scrollLockOff } from "../utilities/scroll-lock";

    export default {
        props: {
            open: {
                type: Boolean,
                required: false,
                default: true
            },
            closeButton: {
                type: Boolean,
                required: false,
                default: true
            },
            size: {
                type: String,
                required: false,
                default: ''
            },
            position: {
                type: String,
                required: false,
                default: 'window'
            }
        },
        data() {
            return {
                previousFocus: null
            }
        },
        computed: {
            cssClasses() {
                return {
                    small: this.size === 'small',
                    large: this.size === 'large',
                    left: this.position === 'left',
                    right: this.position === 'right'
                }
            }
        },
        watch: {
            open(val) {
                if (val) {
                    this.modalOpen();
                }
            }
        },
        methods: {
            modalOpen() {
                this.previousFocus = document.activeElement;
            },
            modalClose() {
                scrollLockOff();
                if (this.previousFocus) {
                    this.previousFocus.focus();
                }
            },
            close() {
                this.$emit('close');
            },
            closeOnEscape(e) {
                if (this.open && (e.key === 'Escape')) {
                    e.preventDefault();
                    this.close();
                }
            },
            scrollLockOff: scrollLockOff
        },
        mounted() {
            this.modalOpen();
            document.addEventListener('keydown', this.closeOnEscape);
        },
        destroyed() {
            scrollLockOff();
            document.removeEventListener('keydown', this.closeOnEscape);
        }
    }
</script>
