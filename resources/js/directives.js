let handleOutsideClick;

const closable = {
    beforeMount(el, binding) {
        handleOutsideClick = (e) => {
            e.stopPropagation();
            const { handler, exclude } = binding.value;
            let clickedOnExcludedEl = false;
            exclude.forEach((refName) => {
                if (!clickedOnExcludedEl) {
                    const excludedEl = binding.instance.$refs[refName];
                    if (excludedEl) {
                        clickedOnExcludedEl = excludedEl.contains(e.target);
                    }
                }
            });
            if (!el.contains(e.target) && !clickedOnExcludedEl) {
                binding.instance[handler]();
            }
        };
        document.addEventListener("click", handleOutsideClick);
        document.addEventListener("touchstart", handleOutsideClick);
    },
    unmounted() {
        document.removeEventListener("click", handleOutsideClick);
        document.removeEventListener("touchstart", handleOutsideClick);
    },
};

export { closable };
