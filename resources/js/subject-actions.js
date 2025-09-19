// Subject management functionality
const subjectActions = () => ({
    init() {
        this.$watch("selectedItems", (value) => {
            // Enable/disable bulk actions based on selection
            this.$dispatch("selection-changed", value.length > 0);
        });

        // Listen for bulk action events
        this.$el.addEventListener(
            "archive-subjects",
            this.archiveSubjects.bind(this)
        );
        this.$el.addEventListener(
            "delete-subjects",
            this.deleteSubjects.bind(this)
        );
    },

    async archiveSubjects(event) {
        const ids = event.detail.ids;
        if (!ids.length) return;

        if (
            !confirm(`Are you sure you want to archive ${ids.length} subjects?`)
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

            if (!response.ok) throw new Error("Network response was not ok");

            await response.json();

            // Refresh the page
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

            if (!response.ok) throw new Error("Network response was not ok");

            await response.json();

            // Refresh the page
            window.location.reload();
        } catch (error) {
            console.error("Error:", error);
            alert("Failed to delete subjects. Please try again.");
        }
    },
});
