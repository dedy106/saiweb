// agar navbar hide dan show ketika scroll
var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "0";
    } else {
        document.getElementById("navbar").style.top = "-55px";
    }
    prevScrollpos = currentScrollPos;
}

// agar navbar muncul dari kiri, ketika di klik
function openNav() {
    document.getElementById("mySidenav").style.width = "80%";
    $('.nutuPin').show();
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    $('.nutuPin').hide();
}

var al = document.getElementById("tempat").getAttribute('value');

if (al == "home") {
    document.getElementById('home').style.background = '#ebebeb';
} else if (al == "art") {
    document.getElementById('art').style.background = '#ebebeb';
} else if (al == "vide") {
    document.getElementById('vide').style.background = '#ebebeb';
}