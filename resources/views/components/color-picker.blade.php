<?php

$colors = [
    'red' => '#ef4444',
    'orange' => '#f97316',
    'amber' => '#f59e0b',
    'yellow' => '#eab308',
    'lime' => '#84cc16',
    'green' => '#22c55e',
    'emerald' => '#10b981',
    'teal' => '#14b8a6',
    'cyan' => '#06b6d4',
    'sky' => '#0ea5e9',
    'blue' => '#3b82f6',
    'indigo' => '#6366f1',
    'violet' => '#8b5cf6',
    'purple' => '#a855f7',
    'fuchsia' => '#d946ef',
    'pink' => '#ec4899',
];

$selected = $attributes->get('value', '#3b82f6');
$name = $attributes->get('name', 'color');
$id = $attributes->get('id', $name);
?>

<div 
    x-data="{ 
        open: false,
        color: '{{ $selected }}',
        colors: {{ json_encode($colors) }},
        selectColor(newColor) {
            this.color = newColor;
            this.open = false;
            this.$refs.input.value = newColor;
            this.$refs.input.dispatchEvent(new Event('change'));
        }
    }" 
    class="relative"
>
    <label for="{{ $id }}" class="form-label">
        {{ $slot }}
    </label>
    
    <div class="mt-1 flex items-center gap-2">
        <button 
            type="button"
            @click="open = !open"
            class="w-10 h-10 rounded-lg shadow-sm border border-gray-300"
            :style="{ backgroundColor: color }"
            aria-label="Choose color"
        ></button>
        
        <input 
            type="text" 
            name="{{ $name }}"
            id="{{ $id }}"
            x-ref="input"
            x-model="color"
            class="form-input w-32"
            pattern="^#[0-9A-Fa-f]{6}$"
            placeholder="#000000"
            @error($name) 
                class="border-red-500" 
                aria-invalid="true"
                aria-describedby="{{ $id }}-error"
            @enderror
        >
    </div>
    
    <div 
        x-show="open" 
        @click.away="open = false"
        class="absolute left-0 mt-2 p-3 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
    >
        <div class="grid grid-cols-8 gap-2">
            <template x-for="(hex, name) in colors" :key="name">
                <button
                    type="button"
                    :style="{ backgroundColor: hex }"
                    class="w-6 h-6 rounded-md hover:scale-110 transform transition-transform"
                    :class="{ 'ring-2 ring-offset-2 ring-blue-500': color === hex }"
                    @click="selectColor(hex)"
                    :aria-label="'Choose ' + name"
                ></button>
            </template>
        </div>
    </div>
    
    @error($name)
        <p class="form-error" id="{{ $id }}-error">{{ $message }}</p>
    @enderror
</div>