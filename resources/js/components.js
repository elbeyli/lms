// Register Alpine.js components
document.addEventListener("alpine:init", () => {
    // Color Picker Component
    Alpine.data("colorPicker", ({ initial = "#3b82f6", colors = {} } = {}) => ({
        open: false,
        color: initial,
        colors: colors,
        init() {
            this.$watch("color", (value) => {
                this.$refs.input.value = value;
                this.$refs.input.dispatchEvent(new Event("change"));
            });
        },
        selectColor(newColor) {
            this.color = newColor;
            this.open = false;
        },
    }));

    // Bulk Actions Component
    Alpine.data("bulkActions", () => ({
        selectedItems: [],
        allSelected: false,
        init() {
            this.$watch("selectedItems", (value) => {
                this.$dispatch("selection-changed", value.length > 0);
            });
        },
        toggleAll() {
            this.allSelected = !this.allSelected;
            this.selectedItems = this.allSelected
                ? Array.from(
                      this.$el.querySelectorAll("[data-selectable-id]")
                  ).map((el) => el.dataset.selectableId)
                : [];
        },
        isSelected(id) {
            return this.selectedItems.includes(id.toString());
        },
        toggle(id) {
            const index = this.selectedItems.indexOf(id.toString());
            if (index === -1) {
                this.selectedItems.push(id.toString());
            } else {
                this.selectedItems.splice(index, 1);
            }
            this.allSelected =
                this.selectedItems.length ===
                Array.from(this.$el.querySelectorAll("[data-selectable-id]"))
                    .length;
        },
    }));

    // Progress Tracking Component
    Alpine.data(
        "progressTracker",
        ({ initial = 0, onUpdate = () => {} } = {}) => ({
            progress: initial,
            async updateProgress(newValue) {
                this.progress = newValue;
                await onUpdate(newValue);
            },
        })
    );

    // Topic Completion Component
    Alpine.data(
        "topicCompletion",
        ({ topicId, initial = false, progress = 0 } = {}) => ({
            isCompleted: initial,
            progress: progress,
            async toggleCompletion() {
                try {
                    const response = await fetch(
                        `/topics/${topicId}/toggle-completion`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    "meta[name=csrf-token]"
                                ).content,
                            },
                        }
                    );

                    if (!response.ok)
                        throw new Error("Network response was not ok");

                    const result = await response.json();
                    this.isCompleted = result.is_completed;
                    this.progress = result.progress_percentage;

                    // Dispatch event for parent components
                    this.$dispatch("topic-completion-changed", {
                        topicId,
                        isCompleted: this.isCompleted,
                        progress: this.progress,
                    });
                } catch (error) {
                    console.error("Error:", error);
                    alert("Failed to update completion status");
                }
            },
        })
    );

    // Subject Management Component
    Alpine.data("subjectManagement", () => ({
        async archiveSubjects(event) {
            const ids = event.detail.ids;
            if (!ids.length) return;

            if (
                !confirm(
                    `Are you sure you want to archive ${ids.length} subjects?`
                )
            )
                return;

            try {
                const response = await fetch("/subjects/bulk-archive", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({ ids }),
                });

                if (!response.ok)
                    throw new Error("Network response was not ok");

                window.location.reload();
            } catch (error) {
                console.error("Error:", error);
                alert("Failed to archive subjects. Please try again.");
            }
        },

        async deleteSubjects(event) {
            const ids = event.detail.ids;
            if (!ids.length) return;

            if (
                !confirm(
                    `Are you sure you want to delete ${ids.length} subjects? This action cannot be undone.`
                )
            )
                return;

            try {
                const response = await fetch("/subjects/bulk-delete", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({ ids }),
                });

                if (!response.ok)
                    throw new Error("Network response was not ok");

                window.location.reload();
            } catch (error) {
                console.error("Error:", error);
                alert("Failed to delete subjects. Please try again.");
            }
        },
    }));

    // Prerequisites Management Component
    Alpine.data("prerequisitesManager", ({ initial = [] } = {}) => ({
        prerequisites: initial,
        newPrerequisite: "",

        addPrerequisite() {
            const value = this.newPrerequisite.trim();
            if (value && !this.prerequisites.includes(value)) {
                this.prerequisites.push(value);
                this.newPrerequisite = "";
            }
        },

        removePrerequisite(index) {
            this.prerequisites.splice(index, 1);
        },
    }));
});
