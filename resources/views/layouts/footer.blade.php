<footer class="main-footer">
    <strong>&copy; {{ date('Y') }} {{ __('messages.portal_name') }}.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <a href="https://wa.me/{{ preg_replace('/\D/', '', config('services.whatsapp.number')) }}" class="btn btn-success btn-sm" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
    </div>
</footer>
