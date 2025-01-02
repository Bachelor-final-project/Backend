import './bootstrap';


$(this).ready(function () {
    console.log("start ready app.js")
    var str=location.href.toLowerCase();
    $(".nav-link").each(function() {
        if (str.indexOf(this.href.toLowerCase()) > -1) {
            $(".nav-link").removeClass("active bg-gradient-primary");
            $(this).parent("nav-link").addClass("active bg-gradient-primary");
            console.log(this.href.toLowerCase());
        }
    });
})