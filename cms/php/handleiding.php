<?php // checken of men wel is ingelogd
// ====================================
login_check_v2(); ?>

<script>
    function adjustIframe(obj) {
        function resize() {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }
        resize(); // Adjust height initially
        setTimeout(resize, 1500); // Recheck height after 1 second
    }
</script>

<div class="box-container">
    <div class="box box-full md-box-full title">
        <h3><span class="icon fas fa-sitework"></span>docs.sitework.nl</h3>
        <a href="https://docs.sitework.nl" target="_blank" rel="noopener" rel="noreferrer" class="btn fl-right">Bezoek website</a>
    </div>
    <div class="box box-full md-box-full">
        <div style="height: 65vh;">
            <iframe src="https://docs.sitework.nl" frameborder="0" onload="adjustIframe(this)" height="100%" width="100%"></iframe>
        </div>
    </div>
</div>

