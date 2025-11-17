<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div id="modalIcon" class="flex-shrink-0 mr-3"></div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            </div>

            <p id="modalMessage" class="text-gray-600 mb-6"></p>

            <div class="flex justify-end space-x-3">
                <button id="modalCancel"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>

                <button id="modalConfirm"
                    class="px-4 py-2 text-white rounded-lg transition-colors font-medium min-w-[80px]" type="button">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("confirmModal");
        const modalTitle = document.getElementById("modalTitle");
        const modalMessage = document.getElementById("modalMessage");
        const modalIcon = document.getElementById("modalIcon");
        const modalConfirm = document.getElementById("modalConfirm");
        const modalCancel = document.getElementById("modalCancel");

        let activeForm = null;

        document.querySelectorAll("[data-confirm]").forEach(button => {
            button.addEventListener("click", (e) => {
                e.preventDefault();

                activeForm = button.closest("form");

                modalTitle.textContent = button.dataset.confirmTitle || "Confirm Action";
                modalMessage.textContent = button.dataset.confirm || "Are you sure?";

                const type = button.dataset.confirmType || "danger";

                let iconHtml = "";
                let confirmClass = "";

                if (type === "danger" || type === "delete") {
                    iconHtml = `
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4
                          c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>`;
                    confirmClass = "bg-red-600 hover:bg-red-700";
                } else {
                    confirmClass = "bg-blue-600 hover:bg-blue-700";
                }

                modalIcon.innerHTML = iconHtml;

                modalConfirm.textContent = button.dataset.confirmText || "Confirm";
                modalConfirm.className =
                    "px-4 py-2 text-white rounded-lg transition-colors font-medium min-w-[80px] " +
                    confirmClass;

                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        });

        modalConfirm.addEventListener("click", () => {
            if (!activeForm) return;

            modal.classList.add("hidden");
            modal.classList.remove("flex");

            activeForm.requestSubmit();
            activeForm = null;
        });

        modalCancel.addEventListener("click", () => {
            modal.classList.add("hidden");
            activeForm = null;
        });

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
                activeForm = null;
            }
        });
    });
</script>
