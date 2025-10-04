@if($siteSettings->enable_cookie_consent ?? false)
    <div id="cookieConsentBanner" class="cookie-consent-banner" style="display:none;">
        <p>
            We use cookies to improve your experience. 
            By continuing to use this site, you accept our use of cookies.
        </p>
        <button id="acceptCookies">Accept</button>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const banner = document.getElementById('cookieConsentBanner');
            if (!localStorage.getItem('cookiesAccepted')) {
                banner.style.display = 'block';
            }
            document.getElementById('acceptCookies')?.addEventListener('click', function () {
                localStorage.setItem('cookiesAccepted', 'true');
                banner.style.display = 'none';
            });
        });
    </script>
    @endpush
@endif
