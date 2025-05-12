<div
    x-data="{
        primaryColor: @js($attributes->get('primary-color', '#2D2B8D')),
        secondaryColor: @js($attributes->get('secondary-color', '#FFC903')),
        logoPath: @js($attributes->get('logo-path')),
        themeName: @js($attributes->get('theme-name', 'default')),
        footerText: @js($attributes->get('footer-text', '© ' . date('Y') . ' SuperDuper Starter. All rights reserved.')),
    }"
    x-init="
        $watch('$store.form.data.primary_color', value => { primaryColor = value || '#2D2B8D' });
        $watch('$store.form.data.secondary_color', value => { secondaryColor = value || '#FFC903' });
        $watch('$store.form.data.logo_path', value => { logoPath = value });
        $watch('$store.form.data.template_theme', value => { themeName = value || 'default' });
        $watch('$store.form.data.footer_text', value => { footerText = value || '© ' + new Date().getFullYear() + ' SuperDuper Starter. All rights reserved.' });
    "
>
    <div class="p-4 overflow-hidden border border-gray-200 rounded-lg bg-gray-50">
        <div class="p-4 text-center text-white rounded-t-lg" x-bind:style="'background-color: ' + primaryColor">
            <div class="mb-0">
                <template x-if="logoPath">
                    <div class="flex justify-center">
                        <img x-bind:src="'{{ asset('storage') }}/' + logoPath" alt="Logo" class="h-8 max-w-[180px]" />
                    </div>
                </template>
                <template x-if="!logoPath">
                    <div class="text-xl font-bold">SuperDuper Starter</div>
                </template>
            </div>
        </div>

        <div class="p-6 bg-white border-gray-200 border-x">
            <div class="mb-4 text-sm text-gray-500">{{ now()->format('F j, Y') }}</div>

            <h2 class="mb-4 text-xl font-semibold" x-bind:style="'color: ' + primaryColor">
                Test Email from SuperDuper Filament Starter
            </h2>

            <p class="mb-4">
                This is a test email to verify your email configuration settings are working correctly.
            </p>

            <div class="my-4 border-t border-gray-100"></div>

            <p class="mb-2">
                Email theme: <strong x-text="themeName.charAt(0).toUpperCase() + themeName.slice(1)"></strong>
            </p>

            <div class="mt-4 mb-2">
                <a href="#" class="inline-block px-4 py-2 text-sm font-medium rounded"
                   x-bind:style="'background-color: ' + secondaryColor + '; color: #000000;'">
                    Example Button
                </a>
            </div>
        </div>

        <div class="p-4 text-sm text-center text-gray-600 border-b border-gray-200 rounded-b-lg bg-gray-50 border-x">
            <span x-text="footerText"></span>
        </div>
    </div>

    <div class="mt-2 text-xs text-gray-500">
        <p>This is a preview of how your emails will appear. The actual email may look slightly different depending on the email client.</p>
    </div>
</div>
