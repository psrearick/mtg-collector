<template>
    <div>
        <svg
            v-if="icon === 'logo'"
            :height="size"
            viewBox="0 0 512 512"
            :width="size"
            :class="classes"
            xmlns="http://www.w3.org/2000/svg"
        >
            <g id="Layer_3" data-name="Layer 3">
                <path d="m144 400h16v16h-16z" />
                <path d="m416 24h16v16h-16z" />
                <path d="m32 144h16v16h-16z" />
                <path
                    d="m56 236a28 28 0 1 0 -28 28 28.031 28.031 0 0 0 28-28z"
                />
                <path
                    d="m404 360a28 28 0 1 0 28 28 28.031 28.031 0 0 0 -28-28z"
                />
                <path
                    d="m507.839 234.851-23.8-13.019-13.019-23.8a8 8 0 0 0 -14.037 0l-13.019 23.8-23.8 13.019a8 8 0 0 0 0 14.037l23.8 13.018 13.019 23.8a8 8 0 0 0 14.037 0l13.019-23.8 23.8-13.018a8 8 0 0 0 0-14.037z"
                />
                <path
                    d="m131.839 344.981-23.8-13.019-13.019-23.8a8 8 0 0 0 -14.037 0l-13.019 23.8-23.8 13.019a8 8 0 0 0 0 14.037l23.8 13.019 13.019 23.8a8 8 0 0 0 14.037 0l13.019-23.8 23.8-13.019a8 8 0 0 0 0-14.037z"
                />
                <path
                    d="m380.06 316.077a48.192 48.192 0 0 0 -14.051-63.8l-.441-.305 97.058-143.489a8 8 0 0 0 -2.569-11.383l-136-80a8 8 0 0 0 -10.672 2.4l-9.385 13.8v-25.3a8 8 0 0 0 -8-8h-144a8 8 0 0 0 -8 8v2.584l-114.971 45.988a8 8 0 0 0 -4.278 10.811l112 240a7.993 7.993 0 0 0 10.326 4l18.249-7.6 19.269 25.763c.052.069.105.138.159.206a33.18 33.18 0 0 1 7.246 20.653v161.595h16v-113.056c.658-10.925 3.98-30.546 18.335-46.265 14.954-16.379 38.394-24.679 69.665-24.679a8 8 0 0 0 6.247-3l32-40a8 8 0 0 0 -2.669-12.153 80.78 80.78 0 0 1 -20.678-16.253c-9.456-10.315-14.687-21.652-15.639-33.839l61.639 42.676a32.132 32.132 0 0 1 9.512 42.287l-60.981 87.716a8 8 0 0 0 -1.431 4.566v112h16v-109.492l59.732-85.92q.173-.249.328-.511zm-268.06-236.077v24a8 8 0 0 1 -8 8h-24a8 8 0 0 1 -8-8v-24a8 8 0 0 1 8-8h24a8 8 0 0 1 8 8zm240.284 103.716a8 8 0 0 1 -8 8h-40a8 8 0 0 1 -8-8v-40a8 8 0 0 1 8-8h40a8 8 0 0 1 8 8zm-40.284-87.716v-24a8 8 0 0 1 8-8h24a8 8 0 0 1 8 8v24a8 8 0 0 1 -8 8h-24a8 8 0 0 1 -8-8zm-152-80h128v40.833l-32.437 47.7-22.19-22.19a8 8 0 0 0 -11.314 0l-28.284 28.284a8 8 0 0 0 0 11.314l28.284 28.284a8 8 0 0 0 1.6 1.228l-46.274 68.047a8 8 0 0 0 2.259 11.208l38.946 25.292h-58.59zm132.123 296.04c-34.053.7-60.147 10.735-77.6 29.85a79.871 79.871 0 0 0 -6.527 8.176 49.239 49.239 0 0 0 -10.66-30.2l-16.774-22.427 62.035-25.848 53.895 35z"
                />
                <path
                    d="m192.971 72a7.978 7.978 0 0 0 5.657-2.343l16.972-16.97a8 8 0 0 0 0-11.314l-16.972-16.973a8 8 0 0 0 -11.314 0l-16.971 16.972a8 8 0 0 0 0 11.314l16.971 16.971a7.978 7.978 0 0 0 5.657 2.343z"
                />
            </g>
        </svg>
        <svg
            v-if="icon === 'search'"
            class="h-5 w-5 text-gray-400"
            :class="classes"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"
            />
        </svg>
        <svg
            v-if="icon === 'outline-menu'"
            class="h-6 w-6"
            :class="classes"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
        <svg
            v-if="icon === 'outline-x'"
            class="h-6 w-6"
            :class="classes"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
            />
        </svg>
        <svg
            v-if="icon === 'outline-bell'"
            class="h-6 w-6"
            :class="classes"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
            />
        </svg>
        <svg
            v-if="icon === 'chevron-right'"
            class="h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"
            />
        </svg>
        <svg
            v-if="icon === 'chevron-left'"
            class="h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clip-rule="evenodd"
            />
        </svg>
        <svg
            v-if="icon === 'selector'"
            class="h-5 w-5 text-gray-400"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd"
            />
        </svg>
        <svg
            v-if="icon === 'check'"
            class="h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd"
            />
        </svg>
    </div>
</template>

<script>
export default {
    name: "Icon",
    props: {
        icon: {
            type: String,
            default: "",
        },
        classes: {
            type: String,
            default: "",
        },
        size: {
            type: String,
            default: "2rem",
        },
    },
};
</script>
