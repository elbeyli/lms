@php
$actions = $attributes->get('actions', []);
@endphp

<div x-data="{
    selectedItems: [],
    allSelected: false,
    actions: {{ json_encode($actions) }},
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selectedItems = this.allSelected ? this.$el.querySelectorAll('[data-selectable-id]').map(el => el.dataset.selectableId) : [];
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
        this.allSelected = this.selectedItems.length === this.$el.querySelectorAll('[data-selectable-id]').length;
    }
}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    class="form-checkbox"
                    x-model="allSelected"
                    @click="toggleAll()"
                >
                <span class="ml-2">Select All</span>
            </label>

            <template x-if="selectedItems.length > 0">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600" x-text="selectedItems.length + ' selected'"></span>
                    <div class="border-l border-gray-300 h-4"></div>
                    <div class="flex items-center space-x-2">
                        <template x-for="(action, index) in actions" :key="index">
                            <button
                                type="button"
                                class="text-sm font-medium"
                                :class="action.class || 'text-blue-600 hover:text-blue-800'"
                                @click="$dispatch(action.event, { ids: selectedItems })"
                                x-text="action.label"
                            ></button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{ $slot }}
</div>