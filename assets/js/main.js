document.addEventListener('DOMContentLoaded', function() {
    var anchors = document.querySelectorAll('a[href^="#"]');
    for (var i = 0; i < anchors.length; i++) {
        anchors[i].addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href && href.length > 1) {
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    }

    var phoneInputs = document.querySelectorAll('input[type="tel"]');
    for (var p = 0; p < phoneInputs.length; p++) {
        phoneInputs[p].addEventListener('input', function(e) {
            var val = e.target.value.replace(/\D/g, '');
            if (val.indexOf('8') === 0) val = '7' + val.substring(1);
            if (val.length > 0 && val.indexOf('7') !== 0) val = '7' + val;
            var formatted = '+7';
            if (val.length > 1) formatted += ' (' + val.substring(1, 4);
            if (val.length >= 5) formatted += ') ' + val.substring(4, 7);
            if (val.length >= 8) formatted += '-' + val.substring(7, 9);
            if (val.length >= 10) formatted += '-' + val.substring(9, 11);
            e.target.value = formatted;
        });
    }

    var dateInputs = document.querySelectorAll('input[type="datetime-local"]');
    for (var d = 0; d < dateInputs.length; d++) {
        var now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dateInputs[d].min = now.toISOString().slice(0, 16);
    }
});
