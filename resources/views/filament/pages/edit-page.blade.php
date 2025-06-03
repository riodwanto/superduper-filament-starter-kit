<x-filament::page>
    <div class="space-y-6">
        {{-- TODO: Add security notice --}}
        <!-- Security Notice -->
        {{-- <x-filament::section>
            <x-slot name="heading">
                Security Information
            </x-slot>

            <x-slot name="description">
                For security reasons, the following content is not allowed: JavaScript code, PHP code, script tags,
                iframe elements, and event handlers (onclick, onload, etc.).
            </x-slot>
        </x-filament::section> --}}

        <!-- Main Form -->
        <div wire:key="form-{{ $this->formKey }}" class="space-y-6">
            {{ $this->form }}

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-information-circle class="w-4 h-4" />
                    <span>Changes are saved immediately when you click Save.</span>
                </div>

                <div class="flex space-x-3">
                    {{-- TODO: Add refresh button --}}
                    {{-- <x-filament::button
                        wire:click="$refresh"
                        color="gray"
                        outlined
                        icon="heroicon-o-arrow-path"
                    >
                        Refresh
                    </x-filament::button> --}}

                    <x-filament::button
                        wire:click="save"
                        color="primary"
                        icon="heroicon-o-document-check"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- File Information -->
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                File Information
            </x-slot>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="font-medium">File Path:</span>
                    <code class="px-2 py-1 text-xs bg-gray-100 rounded dark:bg-gray-800">
                        {{ $this->getDisplayFilePath() }}
                    </code>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Last Modified:</span>
                    <span>
                        @php
                            $filePath = $this->getMainFilePath();
                            $lastModified = file_exists($filePath) ? \Carbon\Carbon::createFromTimestamp(filemtime($filePath))->diffForHumans() : 'Unknown';
                        @endphp
                        {{ $lastModified }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">File Size:</span>
                    <span>{{ $this->formatBytes(strlen($this->fileContent)) }}</span>
                </div>
            </div>
        </x-filament::section>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="save"
         class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50">
        <div class="flex items-center p-6 space-x-3 bg-white rounded-lg dark:bg-gray-800">
            <x-filament::loading-indicator class="w-5 h-5" />
            <span>Saving your changes...</span>
        </div>
    </div>
</x-filament::page>